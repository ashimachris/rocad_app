<?php
// reports_hub.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$active_menu = "data_tables";
include_once "../layout/header.php";
require_once('../../db_con/config.php'); // expects $config (mysqli connection)

// Optional date filter (applies to aggregation queries if provided)
$start_date = !empty($_GET['start_date']) ? $_GET['start_date'] : null;
$end_date   = !empty($_GET['end_date'])   ? $_GET['end_date']   : null;

// Prepare date SQL fragment if both provided and valid
$date_sql = "";
if ($start_date && $end_date) {
    // Basic sanitization (we assume client will pass YYYY-MM-DD)
    $s = mysqli_real_escape_string($config, $start_date);
    $e = mysqli_real_escape_string($config, $end_date);
    $date_sql = " AND (DATE(date) BETWEEN '{$s}' AND '{$e}') ";
}

// -------------------------
// SUMMARY COUNTS
// -------------------------
$plantsCount = 0;
$res = mysqli_query($config, "SELECT COUNT(*) AS total FROM assets WHERE status = 1") or die(mysqli_error($config));
if ($r = mysqli_fetch_assoc($res)) $plantsCount = (int)$r['total'];

$staffCount = 0;
$res = mysqli_query($config, "SELECT COUNT(*) AS total FROM staff") or die(mysqli_error($config));
if ($r = mysqli_fetch_assoc($res)) $staffCount = (int)$r['total'];

$sitesCount = 0;
$res = mysqli_query($config, "SELECT COUNT(*) AS total FROM rocad_site WHERE status = 1") or die(mysqli_error($config));
if ($r = mysqli_fetch_assoc($res)) $sitesCount = (int)$r['total'];


// -------------------------
// STOCK ITEMS: compute totals across ALL sites
// Must list all items requested
// -------------------------
$stock_list = [
    'Diesel'                 => ['icon' => 'fa-tint',         'color' => '#00c0ef'],
    'Petrol'                 => ['icon' => 'fa-gas-pump',     'color' => '#00a65a'],
    'Engine Oil'             => ['icon' => 'fa-oil-can',      'color' => '#f39c12'],
    'Hydraulic Oil'          => ['icon' => 'fa-gears',        'color' => '#6c757d'],
    '1/2 Aggregate'          => ['icon' => 'fa-layer-group',  'color' => '#8B6D5C'],
    '3/4 Aggregate'          => ['icon' => 'fa-layer-group',  'color' => '#7f6a53'],
    '3/8 Aggregate'          => ['icon' => 'fa-layer-group',  'color' => '#6b5847'],
    '8mm Reinforcement'      => ['icon' => 'fa-hashtag',      'color' => '#4a6fa5'],
    '10mm Reinforcement'     => ['icon' => 'fa-hashtag',      'color' => '#4a84a5'],
    '12mm Reinforcement'     => ['icon' => 'fa-hashtag',      'color' => '#4aa5a5'],
    '16mm Reinforcement'     => ['icon' => 'fa-hashtag',      'color' => '#4aa56f'],
    '20mm Reinforcement'     => ['icon' => 'fa-hashtag',      'color' => '#7aa54a'],
    'Cement'                 => ['icon' => 'fa-box',          'color' => '#adb5bd'],
    'Laterite'               => ['icon' => 'fa-truck',        'color' => '#bf8b5b'],
];

$stock_items = [];

foreach ($stock_list as $itemName => $meta) {
    $itemEsc = mysqli_real_escape_string($config, $itemName);

    // Total quantity in (from items_supply_history)
    $sql_in = "SELECT COALESCE(SUM(quantity_in),0) AS total_in FROM items_supply_history WHERE item = '{$itemEsc}' {$date_sql}";
    $qr_in = mysqli_query($config, $sql_in) or die(mysqli_error($config));
    $row_in = mysqli_fetch_assoc($qr_in);
    $total_in = (float)($row_in['total_in'] ?? 0);

    // Total quantity out (from stock_card -> qty_used)
    $sql_out = "SELECT COALESCE(SUM(qty_used),0) AS total_out FROM stock_card WHERE item = '{$itemEsc}' {$date_sql}";
    $qr_out = mysqli_query($config, $sql_out) or die(mysqli_error($config));
    $row_out = mysqli_fetch_assoc($qr_out);
    $total_out = (float)($row_out['total_out'] ?? 0);

    // balance
    $balance = $total_in - $total_out;

    // percent (balance relative to total_in). If total_in==0 -> percent 0
    $percent = 0;
    if ($total_in > 0) {
        $percent = round(($balance / $total_in) * 100, 2);
        if ($percent < 0) $percent = 0;
        if ($percent > 100) $percent = 100;
    }

    // Try to detect a unit from items_supply table (any site)
    $unit = '';
    $unit_q = mysqli_query($config, "SELECT unit FROM items_supply WHERE item = '{$itemEsc}' LIMIT 1");
    if ($unit_q && mysqli_num_rows($unit_q) > 0) {
        $urow = mysqli_fetch_assoc($unit_q);
        $unit = $urow['unit'] ?? '';
    }

    $stock_items[$itemName] = [
        'icon'    => $meta['icon'],
        'color'   => $meta['color'],
        'qty_in'  => $total_in,
        'qty_out' => $total_out,
        'balance' => $balance,
        'percent' => $percent,
        'unit'    => $unit
    ];
}

// Page title/date info
$title_extra = "";
if ($start_date && $end_date) {
    $title_extra = "From: ({$start_date}) To: ({$end_date})";
} else {
    $title_extra = "ALL SITES (no date filter)";
}

// Page title/date info
$title_extra = "";
if ($start_date && $end_date) {
    $title_extra = "From: ({$start_date}) To: ({$end_date})";
} else {
    $title_extra = "ALL SITES (no date filter)";
}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Reports Hub</title>
<link rel="stylesheet" href="../../plugins/datatables/dataTables.bootstrap.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<style>
/* small-box summary */
.small-box {
  border-radius: 6px;
  color: #fff;
  position: relative;
  overflow: hidden;
}
.small-box .inner { padding: 15px; }
.small-box h3 { font-size: 28px; margin: 0 0 5px 0; font-weight: 600; }
.small-box p { font-size: 14px; }
.bg-aqua { background:#00c0ef; }
.bg-green { background:#00a65a; }
.bg-yellow { background:#f39c12; }
.small-box .icon { position: absolute; top: -10px; right: 10px; z-index: 0; font-size: 60px; opacity: .2; }
.small-box-footer {
  display: block;
  background: rgba(0,0,0,0.05);
  color: rgba(255,255,255,0.9);
  padding: 8px 12px;
  text-decoration: none;
  position: relative;
  z-index: 10;
}
.small-box-footer:hover { color: #fff; text-decoration: none; }

/* report / card styles */
.report-section { margin-top: 20px; margin-bottom: 30px; }
.section-header {
  background: #f4f4f4;
  font-weight: bold;
  text-align: left;
  font-size: 15px;
  padding: 10px;
  border-left: 5px solid #007bff;
  margin-bottom: 15px;
  border-radius: 4px;
}
.report-card {
  border: 1px solid #e6e6e6;
  border-radius: 8px;
  padding: 18px;
  margin-bottom: 16px;
  background: #fff;
  transition: transform .18s ease, box-shadow .18s ease;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}
.report-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 8px 20px rgba(7, 22, 45, 0.08);
}

/* Collapsible header */
.collapse-header {
  display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:12px;
}
.collapse-toggle {
  background: #007bff; color:#fff; padding:8px 12px; border-radius:6px; text-decoration:none; cursor:pointer;
  border: none;
}
.collapse-toggle:focus { outline: none; box-shadow: none; }

/* Stock overview inside a single large card */
.stock-card {
  padding: 12px;
  border-radius: 8px;
  background: #fff;
  border: 1px solid #e9eef2;
}
.stock-row {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 6px;
  border-bottom: 1px solid #f1f3f5;
}
.stock-row:last-child { border-bottom: none; }
.stock-meta {
  width: 32%;
  min-width: 180px;
  display: flex;
  align-items: center;
  gap: 12px;
}
.stock-meta .icon {
  width: 44px;
  height: 44px;
  border-radius: 8px;
  display:flex;
  align-items:center;
  justify-content:center;
  color:#fff;
  font-size:18px;
}
.stock-name { font-weight:700; font-size:15px; color:#222; }

/* progress */
.stock-progress {
  flex: 1;
  min-width: 220px;
}
.progress {
  height: 12px;
  background: #f0f0f0;
  border-radius: 8px;
  overflow: hidden;
  margin-bottom: 6px;
}
.progress-bar {
  height: 100%;
  line-height:12px;
  color:#fff;
  text-align:center;
  font-size:11px;
  white-space: nowrap;
  transition: width .8s ease;
  display: flex;
  justify-content: center;
  align-items: center;
}

/* qty badges */
.stock-stats {
  width: 260px;
  min-width: 200px;
  display:flex;
  gap:8px;
  justify-content: flex-end;
  align-items: center;
}
.stat-badge {
  background:#f5f7fa;
  border:1px solid #e8edf2;
  padding:6px 8px;
  border-radius:6px;
  font-size:13px;
  color:#333;
}
.stat-label { display:block; font-size:10px; color:#888; }

/* small screens */
@media (max-width: 991px) {
  .stock-meta { width: 35%; }
  .stock-stats { width: 200px; }
}
@media (max-width: 575px) {
  .stock-row { flex-direction: column; align-items: stretch; gap:8px; }
  .stock-meta, .stock-progress, .stock-stats { width:100%; min-width:0; justify-content: space-between; }
  .stock-stats { flex-wrap: wrap; justify-content:flex-start; gap:6px; }
}

/* button style */
.btn-primary {
  background:#007bff; color:#fff; padding:8px 12px; border-radius:6px; text-decoration:none; display:inline-block;
  border: none;
}
.btn-primary:hover { background:#0069d9; color:#fff; }

/* small helper */
.muted { color:#777; font-size:13px; }
</style>
</head>
<body class="hold-transition skin-blue sidebar-mini">

<!-- JS libs -->
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>

<div class="wrapper">
  <?php include_once "../layout/topmenu.php"; ?>
  <?php include_once "../layout/left-sidebar.php"; ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Reports Hub <small>Overview</small></h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Reports Hub</li>
      </ol>
    </section>

    <section class="content container-fluid">
      <!-- summary boxes row -->
      <div class="row">
        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3 id="plantsCount"><?php echo (int)$plantsCount; ?></h3>
              <p>Plants (Machineries)</p>
            </div>
            <div class="icon"><i class="fa fa-cogs"></i></div>
            <a href="equipments.php" class="small-box-footer">Open list <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="small-box bg-green">
            <div class="inner">
              <h3 id="staffCount"><?php echo (int)$staffCount; ?></h3>
              <p>Staffs</p>
            </div>
            <div class="icon"><i class="fa fa-users"></i></div>
            <a href="staffs.php" class="small-box-footer">Open list <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3 id="sitesCount"><?php echo (int)$sitesCount; ?></h3>
              <p>Sites / Projects</p>
            </div>
            <div class="icon"><i class="fa fa-building"></i></div>
            <a href="sites.php" class="small-box-footer">Open list <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
      </div>

      <!-- Stock Overview large collapsible card (collapsed by default) -->
      <div class="row">
        <div class="col-md-12">
          <div class="report-section">
            <div class="section-header">
              STOCK OVERVIEW
              &nbsp; <small class="muted"><?php echo htmlspecialchars($title_extra); ?></small>
            </div>

            <div class="report-card">
              <div class="collapse-header">
                <div style="font-weight:700; font-size:16px;">General overview report for stocking across all sites</div>
                <div>
                  <button id="toggleStockOverview" class="collapse-toggle" aria-expanded="false" aria-controls="stockOverviewContent">
                    <i id="toggleIcon" class="fa fa-chevron-down"></i> Show stock overview
                  </button>
                </div>
              </div>

              <div id="stockOverviewContent" style="display:none;">
                <div class="stock-card" role="list" aria-label="Stock overview list">
                  <?php foreach ($stock_items as $name => $it):
                      $elemId = 'progress-' . strtolower(str_replace([' ', '/', '.'], ['-','-',''], $name));
                      // ensure id chars are safe
                      $elemId = preg_replace('/[^a-z0-9\-_]/', '', $elemId);
                  ?>
                    <div class="stock-row" role="listitem" aria-label="<?php echo htmlspecialchars($name); ?>">
                      <div class="stock-meta">
                        <div class="icon" style="background: <?php echo htmlspecialchars($it['color']); ?>;">
                          <i class="fa <?php echo htmlspecialchars($it['icon']); ?>" aria-hidden="true"></i>
                        </div>
                        <div>
                          <div class="stock-name"><?php echo htmlspecialchars($name); ?></div>
                          <div style="color:#666; font-size:13px;"><?php echo number_format($it['balance'],2) . ' ' . ($it['unit'] ?: ''); ?> balance</div>
                        </div>
                      </div>

                      <div class="stock-progress" aria-hidden="false">
                        <div class="progress" role="progressbar" aria-valuenow="<?php echo $it['percent']; ?>" aria-valuemin="0" aria-valuemax="100">
                          <div id="<?php echo $elemId; ?>" class="progress-bar" style="width: <?php echo $it['percent']; ?>%; background: <?php echo htmlspecialchars($it['color']); ?>;">
                            <?php echo $it['percent']; ?>%
                          </div>
                        </div>
                        <div style="font-size:12px; color:#666;"><?php echo number_format($it['percent'],2); ?>% of total in</div>
                      </div>

                      <div class="stock-stats" aria-hidden="false">
                        <div class="stat-badge">
                          <span class="stat-label">QTY IN</span>
                          <strong><?php echo number_format($it['qty_in'], 2); ?> <?php echo $it['unit']; ?></strong>
                        </div>
                        <div class="stat-badge">
                          <span class="stat-label">QTY OUT</span>
                          <strong><?php echo number_format($it['qty_out'], 2); ?> <?php echo $it['unit']; ?></strong>
                        </div>
                        <div class="stat-badge" style="background:#e9f7ef;border-color:#d6f0df;">
                          <span class="stat-label">BALANCE</span>
                          <strong><?php echo number_format($it['balance'], 2); ?> <?php echo $it['unit']; ?></strong>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div> <!-- /.stock-card -->
              </div> <!-- /.stockOverviewContent -->

              <div style="margin-top:12px; text-align:right;">
                <a class="btn-primary" href="stock_site.php">Open Stock Manager</a>
                <div style="margin-top:6px;">
                  <small class="muted">Totals are aggregated across all sites<?php echo $start_date && $end_date ? " between {$start_date} and {$end_date}" : ""; ?>.</small>
                </div>
              </div>
            </div> <!-- /.report-card -->
          </div> <!-- /.report-section -->
        </div>
      </div>

      <!-- The rest of the report cards grid remains unchanged -->
      <div class="report-section">
        <div class="section-header">PLANT SECTION</div>
        <div class="row">
          <div class="col-md-4 col-sm-6">
            <div class="report-card">
              <div>
                <div class="report-title"><i class="fa fa-industry"></i> Plant Report</div>
                <div class="report-desc">View all time plant historical report</div>
              </div>
              <div>
                <a href="plant-reports.php" class="btn-primary"><i class="fa fa-eye"></i> View</a>
              </div>
            </div>
          </div>

          <div class="col-md-4 col-sm-6">
            <div class="report-card">
              <div>
                <div class="report-title"><i class="fa fa-wrench"></i> Plant Repair Report</div>
                <div class="report-desc">View all time plant repair reports</div>
              </div>
              <div>
                <a href="repair_report.php" class="btn-primary"><i class="fa fa-eye"></i> View</a>
              </div>
            </div>
          </div>

          <div class="col-md-4 col-sm-6">
            <div class="report-card">
              <div>
                <div class="report-title"><i class="fa fa-truck"></i> Plant Release Report</div>
                <div class="report-desc">View plant release reports</div>
              </div>
              <div>
                <a href="plant_release.php" class="btn-primary"><i class="fa fa-eye"></i> View</a>
              </div>
            </div>
          </div>

          <div class="col-md-4 col-sm-6">
            <div class="report-card">
              <div>
                <div class="report-title"><i class="fa fa-circle-notch"></i> Tyre Report</div>
                <div class="report-desc">View detailed tyre stock history</div>
              </div>
              <div>
                <a href="tyre_track.php" class="btn-primary"><i class="fa fa-eye"></i> View</a>
              </div>
            </div>
          </div>

          <div class="col-md-4 col-sm-6">
            <div class="report-card">
              <div>
                <div class="report-title"><i class="fa fa-battery-half"></i> Battery Report</div>
                <div class="report-desc">Track all battery stock records</div>
              </div>
              <div>
                <a href="battery_track.php" class="btn-primary"><i class="fa fa-eye"></i> View</a>
              </div>
            </div>
          </div>
        </div> <!-- /.row -->
      </div>

      <!-- Administration -->
      <div class="report-section">
        <div class="section-header">ADMINISTRATION</div>
        <div class="row">
          <div class="col-md-4 col-sm-6">
            <div class="report-card">
              <div>
                <div class="report-title"><i class="fa fa-file-alt"></i> Requisition Report</div>
                <div class="report-desc">Monitor all requisition and quotations</div>
              </div>
              <div>
                <a href="requisition_report.php" class="btn-primary"><i class="fa fa-eye"></i> View</a>
              </div>
            </div>
          </div>

          <div class="col-md-4 col-sm-6">
            <div class="report-card">
              <div>
                <div class="report-title"><i class="fa fa-wallet"></i> Advance Voucher Report</div>
                <div class="report-desc">Monitor all voucher request</div>
              </div>
              <div>
                <a href="ad_voucher_report.php" class="btn-primary"><i class="fa fa-eye"></i> View</a>
              </div>
            </div>
          </div>

          <div class="col-md-4 col-sm-6">
            <div class="report-card">
              <div>
                <div class="report-title"><i class="fa fa-box"></i> Store Loading Report</div>
                <div class="report-desc">All store loading report summary</div>
              </div>
              <div>
                <a href="loading_report.php" class="btn-primary"><i class="fa fa-eye"></i> View</a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Finance -->
      <div class="report-section">
        <div class="section-header">FINANCE</div>
        <div class="row">
          <div class="col-md-4 col-sm-6">
            <div class="report-card">
              <div>
                <div class="report-title"><i class="fa fa-file-invoice-dollar"></i> General Expenses Report</div>
                <div class="report-desc">View all time expenses report</div>
              </div>
              <div>
                <a href="general_expense_report.php" class="btn-primary"><i class="fa fa-eye"></i> View</a>
              </div>
            </div>
          </div>

          <div class="col-md-4 col-sm-6">
            <div class="report-card">
              <div>
                <div class="report-title"><i class="fa fa-wallet"></i> Daily Expenses</div>
                <div class="report-desc"> Real-Time - Follow up on approved and pending expenses</div>
              </div>
              <div>
                <a href="daily_report.php" class="btn-primary"><i class="fa fa-eye"></i> View</a>
              </div>
            </div>
          </div>
        </div>
      </div>
          <div class="col-md-4 col-sm-6">
            <div class="report-card">
              <div>
                <div class="report-title"><i class="fa fa-wallet"></i> All Bulk Transfer Reports</div>
                <div class="report-desc">Check all bulk transfer payment via the available banks </div>
              </div>
              <div>
                <a href="general_bulk_report.php" class="btn-primary"><i class="fa fa-eye"></i> View</a>
              </div>
            </div>
          </div>
        </div>
      </div>

    </section>
  </div>

  <?php include_once "../layout/copyright.php"; ?>
  <?php include_once "../layout/right-sidebar.php"; ?>
  <div class="control-sidebar-bg"></div>
</div>

<?php include_once "../layout/footer.php"; ?>

<!-- Inline scripts -->
<script>
  // DataTable init placeholder
  $(function () {
    if ( $('#example1').length ) {
      $('#example1').DataTable({ responsive: true, ordering: false, paging: true });
    }
  });

  // animate summary counts
  (function animateCounts(){
    function animateCount(id, start, end, duration) {
      var el = document.getElementById(id);
      if(!el) return;
      var range = Math.abs(end - start);
      if(range === 0) { el.innerText = end; return; }
      var stepTime = Math.max(25, Math.floor(duration / range));
      var current = start;
      var step = (end > start) ? 1 : -1;
      var timer = setInterval(function() {
        current += step;
        el.innerText = current;
        if (current == end) clearInterval(timer);
      }, stepTime);
    }
    animateCount('plantsCount', 0, parseInt(document.getElementById('plantsCount').innerText||0), 700);
    animateCount('staffCount', 0, parseInt(document.getElementById('staffCount').innerText||0), 700);
    animateCount('sitesCount', 0, parseInt(document.getElementById('sitesCount').innerText||0), 700);
  })();

  // Collapsible stock overview (collapsed by default)
  (function(){
    var toggleBtn = document.getElementById('toggleStockOverview');
    var content = document.getElementById('stockOverviewContent');
    var icon = document.getElementById('toggleIcon');
    if (!toggleBtn || !content) return;
    toggleBtn.addEventListener('click', function(){
      var isOpen = content.style.display !== 'none';
      if (isOpen) {
        content.style.display = 'none';
        toggleBtn.setAttribute('aria-expanded','false');
        icon.className = 'fa fa-chevron-down';
        toggleBtn.innerHTML = '<i id="toggleIcon" class="fa fa-chevron-down"></i> Show stock overview';
      } else {
        content.style.display = 'block';
        toggleBtn.setAttribute('aria-expanded','true');
        icon.className = 'fa fa-chevron-up';
        toggleBtn.innerHTML = '<i id="toggleIcon" class="fa fa-chevron-up"></i> Hide stock overview';
      }
    });

    (function(){
  var toggleBtn = document.getElementById('toggleInvoiceOverview');
  var content = document.getElementById('invoiceOverviewContent');
  var icon = document.getElementById('invoiceToggleIcon');
  if (!toggleBtn || !content) return;
  toggleBtn.addEventListener('click', function(){
    var isOpen = content.style.display !== 'none';
    if (isOpen) {
      content.style.display = 'none';
      toggleBtn.setAttribute('aria-expanded','false');
      icon.className = 'fa fa-chevron-down';

    } else {
      content.style.display = 'block';
      toggleBtn.setAttribute('aria-expanded','true');
      icon.className = 'fa fa-chevron-up';

    }
  });
  // default collapsed
  content.style.display = 'none';
  toggleBtn.setAttribute('aria-expanded','false');
  icon.className = 'fa fa-chevron-down';
})();

    // Ensure default collapsed
    content.style.display = 'none';
    toggleBtn.setAttribute('aria-expanded','false');
    icon.className = 'fa fa-chevron-down';
  })();

  // helper to set progress bars (already set server-side but keep for AJAX/future)
  function setProgressBar(id, percent, color) {
    var el = document.getElementById(id);
    if(!el) return;
    percent = Math.max(0, Math.min(100, parseFloat(percent) || 0));
    el.style.width = percent + '%';
    el.style.background = color || el.style.background;
    el.innerText = percent + '%';
  }

  window.addEventListener('load', function() {
    <?php foreach ($stock_items as $name => $it) {
        $elemId = 'progress-' . strtolower(str_replace([' ', '/', '.'], ['-','-',''], $name));
        $elemId = preg_replace('/[^a-z0-9\-_]/', '', $elemId);
        $p = (float)$it['percent'];
        $c = $it['color'];
        echo "setProgressBar('{$elemId}', {$p}, '{$c}');\n";
    } ?>
  });
</script>

<script src="js/table.js"></script>
</body>
</html>
