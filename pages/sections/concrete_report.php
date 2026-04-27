<?php
require_once('../../db_con/config.php');
if(session_status()===PHP_SESSION_NONE){
    session_start();
}

$active_menu = "blank";
include_once "../layout/header.php";

// Fetch sites
$siteQry = mysqli_query($config,"SELECT id,sitename FROM rocad_site WHERE sitename!='' ORDER BY sitename ASC");
$sites = mysqli_fetch_all($siteQry,MYSQLI_ASSOC);

// Correct Items & Sub-Items
$items_map = [
    "DRAIN" => ["BLIND", "BASE", "WALL", "TOP"],
    "FALLOUT DRAIN" => ["BLIND", "BASE", "TOP"],
    "CULVERT" => ["BLIND", "BASE", "WALL", "TOP", "WING WALL", "APRON", "HEADWALL", "TOE BEAM"],
    "ACCESS SLAB" => ["ACCESS SLAB"],
    "ACCESS STREET" => ["ACCESS STREET", "HEADWALL"],
    "RETAINING WALL" => ["BLINDING", "BASE", "WALL"],
    "KERB" => ["KERB"],
    "GENERAL" => ["GENERAL"]
];

// Prepare filters
$filter_sql="WHERE 1";
$title="All Records";
$selectedSite = 0;
$selectedItem = "";
$selectedSubItem = "";
$f = ""; $t = "";

if(isset($_GET['f'],$_GET['t'])){
    $f=$_GET['f'];
    $t=$_GET['t'];
    $filter_sql.=" AND DATE(date) BETWEEN '$f' AND '$t'";
    $title="From ($f) To ($t)";
}

if(isset($_GET['site']) && $_GET['site']!=0){
    $selectedSite=intval($_GET['site']);
    $filter_sql.=" AND site_id='$selectedSite'";
    foreach($sites as $s){
        if($s['id']==$selectedSite){
            $title.=" | Site: ".$s['sitename'];
            break;
        }
    }
}

if(isset($_GET['item']) && $_GET['item']!=""){
    $selectedItem=$_GET['item'];
    $filter_sql.=" AND item='$selectedItem'";
}

if(isset($_GET['sub_item']) && $_GET['sub_item']!=""){
    $selectedSubItem=$_GET['sub_item'];
    $filter_sql.=" AND sub_item='$selectedSubItem'";
}

// Fetch concrete data
$qry = "SELECT c.*, r.sitename
FROM concrete_stock c
LEFT JOIN rocad_site r ON c.site_id = r.id
$filter_sql
ORDER BY c.date ASC, c.id ASC"; // sort by date, then by id within the same date
$result=mysqli_query($config,$qry) or die(mysqli_error($config));

// Totals
$totalsQry="SELECT
SUM(diesel) AS total_diesel,
SUM(bags) AS total_bags,
SUM(cubic_m3) AS total_cubic,
SUM(qty) AS total_qty,
SUM(length) AS total_length
FROM concrete_stock
$filter_sql";
$totalsRes=mysqli_query($config,$totalsQry);
$totals=mysqli_fetch_assoc($totalsRes);
?>

<body class="hold-transition skin-blue sidebar-mini">
<link rel="stylesheet" href="../../plugins/datatables/dataTables.bootstrap.css">
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/html2pdf.js@0.10.1/dist/html2pdf.bundle.min.js"></script>
<script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>

<div class="wrapper">
<?php include_once "../layout/topmenu.php"; ?>
<?php include_once "../layout/left-sidebar.php"; ?>

<style>
.filter-panel {
    background:#f5f5f5;
    padding:10px;
    margin-bottom:15px;
    border-radius:6px;
    display:flex;
    flex-wrap:wrap;
    gap:10px;
    align-items:center;
}
.filter-panel select,
.filter-panel input[type="date"] {
    padding:5px 8px;
    border-radius:4px;
    border:1px solid #ccc;
}
.filter-panel button {
    padding:6px 12px;
    border:none;
    border-radius:4px;
    cursor:pointer;
}
.export-totals button {
    margin:3px;
    pointer-events:none;
}
</style>

<div class="content-wrapper">
<section class="content-header">
<h1>Concrete Progress Report <small><?php echo $title; ?></small></h1>
</section>

<section class="content">
<div class="box">

<div class="box-header text-center">
<!-- Filter Panel -->
<form id="filterForm" method="GET" class="filter-panel text-center" style="margin: 0 auto; display: inline-block;">
<input type="date" name="f" value="<?php echo $f; ?>" required>
<input type="date" name="t" value="<?php echo $t; ?>" required>
<select name="site">
<option value="0">All Sites</option>
<?php foreach($sites as $s){ ?>
<option value="<?php echo $s['id']; ?>" <?php echo ($selectedSite==$s['id'])?"selected":""; ?>><?php echo $s['sitename']; ?></option>
<?php } ?>
</select>
<select name="item" id="itemSelect">
<option value="">All Items</option>
<?php foreach($items_map as $item=>$sub){ ?>
<option value="<?php echo $item; ?>" <?php echo ($selectedItem==$item)?"selected":""; ?>><?php echo $item; ?></option>
<?php } ?>
</select>
<select name="sub_item" id="subItemSelect">
<option value="">All Sub-Items</option>
<?php 
if($selectedItem && isset($items_map[$selectedItem])){
    foreach($items_map[$selectedItem] as $sub){
        $sel = ($selectedSubItem==$sub) ? "selected" : "";
        echo "<option value='$sub' $sel>$sub</option>";
    }
}
?>
</select>
<button type="submit" class="btn btn-success">Search Filter</button>
<!-- <button type="button" class="btn btn-success" id="resetFiltersBtn">Reset Filters</button> -->
</form>

<!-- PDF & Excel -->
<button id="sharePdfBtn" class="btn btn-primary">
<i class="fa fa-file-pdf-o"></i> Share PDF
</button>
<button id="shareExcelBtn" class="btn btn-success">
<i class="fa fa-file-excel-o"></i> Download Excel
</button>
</div>

<div id="exportArea" class="export-area">

<!-- Totals -->
<?php
$isAccess = ($selectedItem=="ACCESS SLAB" || $selectedItem=="ACCESS STREET");

// Calculate number of unique days in filtered concrete_stock
$daysRes = mysqli_query($config, "
    SELECT COUNT(DISTINCT DATE(date)) AS no_of_days
    FROM concrete_stock
    $filter_sql
");
$daysRow = mysqli_fetch_assoc($daysRes);
$no_of_days = $daysRow['no_of_days'] ?? 0;
?>

<div class="export-totals text-center">

<?php if($isAccess){ ?>
    <button class="btn btn-warning" style="pointer-events:none;">
        TOTAL DIESEL: <strong id="totalDiesel"><?php echo number_format($totals['total_diesel'],2); ?></strong>
    </button>
    <button class="btn btn-info" style="pointer-events:none;">
        TOTAL BAGS OF CEMENT: <strong id="totalBags"><?php echo number_format($totals['total_bags'],2); ?></strong>
    </button>
    <button class="btn btn-success" style="pointer-events:none;">
        TOTAL QTY: <strong id="totalQty"><?php echo number_format($totals['total_qty'],2); ?></strong>
    </button>
    <button class="btn btn-primary" style="pointer-events:none;">
        TOTAL CUBIC M<sup>3</sup>: <strong id="totalCubic"><?php echo number_format($totals['total_cubic'],2); ?></strong>
    </button>
<?php } else { ?>
    <button class="btn btn-warning" style="pointer-events:none;">
        TOTAL DIESEL: <strong id="totalDiesel"><?php echo number_format($totals['total_diesel'],2); ?></strong>
    </button>

    <!-- NEW: TOTAL WALL -->
    <button class="btn btn-primary" style="pointer-events:none;">
        TOTAL WALL: <strong id="totalWall">
            <?php 
            $wallLength = mysqli_fetch_assoc(mysqli_query($config,"
                SELECT SUM(length) AS total_wall FROM concrete_stock
                $filter_sql AND sub_item='WALL'
            "))['total_wall'] ?? 0;
            echo number_format($wallLength,2);
            ?>
        </strong>
    </button>

    <!-- NEW: TOTAL BASE -->
    <button class="btn btn-info" style="pointer-events:none;">
        TOTAL BASE: <strong id="totalBase">
            <?php 
            $baseLength = mysqli_fetch_assoc(mysqli_query($config,"
                SELECT SUM(length) AS total_base FROM concrete_stock
                $filter_sql AND sub_item='BASE'
            "))['total_base'] ?? 0;
            echo number_format($baseLength,2);
            ?>
        </strong>
    </button>

    <button class="btn btn-info" style="pointer-events:none;">
        TOTAL BAGS OF CEMENT: <strong id="totalBags"><?php echo number_format($totals['total_bags'],2); ?></strong>
    </button>
    <button class="btn btn-success" style="pointer-events:none;">
        TOTAL CUBIC M<sup>3</sup>: <strong id="totalCubic"><?php echo number_format($totals['total_cubic'],2); ?></strong>
    </button>
<?php } ?>

<!-- NEW FIXED BUTTONS: AGGREGATE & SAND -->
<button class="btn btn-secondary" style="pointer-events:none;">
    AGGREGATE: <strong id="totalAggregate"><?php echo number_format($totals['total_cubic']*0.88,2); ?></strong>
</button>
<button class="btn btn-secondary" style="pointer-events:none;">
    SAND: <strong id="totalSand"><?php echo number_format($totals['total_cubic']*0.44,2); ?></strong>
</button>

<!-- NO OF DAYS button always shown -->
<button class="btn btn-info" style="pointer-events:none;">
    NO OF DAYS: <strong id="totalDays"><?php echo $no_of_days; ?></strong>
</button>

</div>

<div class="box-body table-responsive">
<table id="concreteTable" class="table table-bordered table-hover" style="text-transform:uppercase;font-size:13px;">
<thead>
<tr>
<th>S/N</th>
<th>DATE</th>
<th>SITE</th>
<th>PLANT</th>
<th>ITEM</th>
<th>SUB ITEM</th>
<th>LENGTH</th>
<th>WIDTH</th>
<th>DEPTH</th>
<th>QTY</th>
<th>CUBIC M<sup>3</sup></th>
<th>BAGS</th>
<th>DIESEL</th>
<th>TOTAL M<sup>3</sup></th>
<th>CHAINAGE</th>
<th>DESCRIPTION</th>
<th>PREPARED BY</th>
</tr>
</thead>
<tbody>
<?php
$i=0; $running_m3=0;
while($row=mysqli_fetch_assoc($result)){
    $i++;
    $running_m3 += $row['cubic_m3'];
    $prebyID=$row['entered_by'];
    require '../layout/preby.php';
?>
<tr>
<td><?php echo $i; ?></td>
<td><?php echo $row['date']; ?></td>
<td><?php echo strtoupper($row['sitename'] ?? 'N/A'); ?></td>
<td><?php echo strtoupper($row['plant']); ?></td>
<td><?php echo strtoupper($row['item']); ?></td>
<td><?php echo strtoupper($row['sub_item']); ?></td>
<td><?php echo number_format($row['length'],2); ?></td>
<td><?php echo number_format($row['width'],2); ?></td>
<td><?php echo number_format($row['depth'],2); ?></td>
<td><?php echo number_format($row['qty'],2); ?></td>
<td><?php echo number_format($row['cubic_m3'],2); ?></td>
<td><?php echo number_format($row['bags'],2); ?></td>
<td><?php echo number_format($row['diesel'],2); ?></td>
<td><?php echo number_format($running_m3,2); ?></td>
<td><?php echo strtoupper($row['chainage']); ?></td>
<td><?php echo strtoupper($row['description']); ?></td>
<td><?php echo strtoupper($row_preby['fullname'] ?? 'N/A'); ?></td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
</div>
</div>
</section>
</div>

<?php include_once "../layout/footer.php"; ?>

<script>
$(function(){
    var itemsMap = <?php echo json_encode($items_map); ?>;
    var table = $("#concreteTable").DataTable({pageLength:50,order:[[0,"asc"]],responsive:true});

    // Dynamically update sub-item dropdown
    $("#itemSelect").on("change", function(){
        var item = $(this).val();
        var subSelect = $("#subItemSelect");
        subSelect.empty().append('<option value="">All Sub-Items</option>');
        if(itemsMap[item]){
            itemsMap[item].forEach(function(sub){ subSelect.append('<option value="'+sub+'">'+sub+'</option>'); });
        }
    });

    // Reset filter
    $("#resetFiltersBtn").on("click", function(){
        // Reset the form fields (dropdowns and dates)
        $("#filterForm")[0].reset();

        // Clear DataTable search and column search
        table.search('').columns().search('').draw();
    });

  // Update totals dynamically on table draw
      table.on('draw', function () {

          var data = table.rows({ search: 'applied' }).data();

          var totalDiesel = 0,
              totalBags = 0,
              totalCubic = 0,
              totalWall = 0,
              totalBase = 0;

          // Track unique dates
          var uniqueDates = new Set();

          for (var i = 0; i < data.length; i++) {

              totalDiesel += parseFloat(data[i][12]) || 0; // DIESEL
              totalBags   += parseFloat(data[i][11]) || 0; // BAGS
              totalCubic  += parseFloat(data[i][10]) || 0; // CUBIC M³

              // Sub-item totals
              var subItem = (data[i][5] || '').toUpperCase();
              var length  = parseFloat(data[i][6]) || 0;

              if (subItem === "WALL") totalWall += length;
              if (subItem === "BASE") totalBase += length;

              // Collect unique dates (ignore time)
              var dateTime = data[i][1]; // DATE column

              if (dateTime) {
                  var dateOnly = dateTime.split(' ')[0]; // remove time part
                  uniqueDates.add(dateOnly);
              }
          }

          // Update totals in UI
          $("#totalDiesel").text(totalDiesel.toFixed(2));
          $("#totalBags").text(totalBags.toFixed(2));
          $("#totalCubic").text(totalCubic.toFixed(2));
          $("#totalWall").text(totalWall.toFixed(2));
          $("#totalBase").text(totalBase.toFixed(2));

          // Derived values
          $("#totalAggregate").text((totalCubic * 0.88).toFixed(2));
          $("#totalSand").text((totalCubic * 0.44).toFixed(2));

          // ? Dynamic NO OF DAYS
          $("#totalDays").text(uniqueDates.size);
      });

    // PDF Export
    const newFileName="Concrete_Progress_Report_"+new Date().toISOString().slice(0,10);
    $("#sharePdfBtn").on("click", async function(){
        var element=$("#exportArea")[0];
        table.destroy();
        await html2pdf().set({margin:0.3,filename:newFileName+'.pdf',image:{type:'jpeg',quality:0.98},html2canvas:{scale:2,scrollY:0},jsPDF:{unit:'in',format:'a4',orientation:'landscape'}}).from(element).save();
        table = $("#concreteTable").DataTable({pageLength:50,order:[[0,"asc"]],responsive:true});
    });

    // Excel Export
    $("#shareExcelBtn").on("click", function(){
        const element=document.getElementById("exportArea");
        const wb=XLSX.utils.book_new();
        const ws=XLSX.utils.table_to_sheet(element.querySelector("table"));
        XLSX.utils.sheet_add_aoa(ws,[
            ["TOTAL DIESEL",<?php echo $totals['total_diesel']; ?>,"TOTAL BAGS",<?php echo $totals['total_bags']; ?>,"TOTAL CUBIC",<?php echo $totals['total_cubic']; ?>]
        ],{origin:0});
        XLSX.utils.book_append_sheet(wb,ws,"Concrete Report");
        XLSX.writeFile(wb,newFileName+".xlsx");
    });
});
</script>

</body>
</html>