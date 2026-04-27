<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$active_menu = "data_tables";

include_once "../layout/header.php";
require_once('../../db_con/config.php');

/* =========================
   VALIDATE PLANT (FROM SECOND CODE)
========================= */
if (isset($_GET['v']) && !empty($_GET['v'])) {
    $asset_id = $_GET['v'];
} else {
    echo '<script>location.href="/rocad_admin/pages/dashboard/";</script>';
    exit;
}

/* =========================
   GET PLANT NUMBER
========================= */
$q_asset = mysqli_query($config, "SELECT * FROM assets WHERE id = '$asset_id'") or die(mysqli_error($config));
$row_asset = mysqli_fetch_assoc($q_asset);
$plantNo = $row_asset['sortno'];

/* =========================
   DATE + STATUS FILTER (FROM FIRST CODE)
========================= */
$mysql = "";
$title = "";

if (isset($_GET['f']) && !empty($_GET['t'])) {
    $f = $_GET['f'];
    $t = $_GET['t'];

    if (isset($_GET['tlt']) && $_GET['tlt'] !== "") {
        $tlt = $_GET['tlt'];
        $tlts = " AND i.ispaid = $tlt";
    } else {
        $tlts = "";
    }

    $mysql = " AND (DATE(i.uploaded_on) BETWEEN '$f' AND '$t') $tlts";
    $title = "From: ($f) To: ($t)";
} else {
    $mysql = " AND (DATE(i.uploaded_on) >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK))";
    $title = "FOR THE LAST 1 WEEK";
}

/* =========================
   MAIN QUERY (FIRST CODE STYLE + PLANT FILTER)
========================= */
$assets = "
SELECT 
    i.id,
    i.lpo,
    i.reference,
    i.PlantNo,
    i.title,
    i.infoD,
    i.ispaid,
    i.uploaded_on,
    i.uploadby,
    s.invoice AS invoiceL,
    s.reqfor AS description,
    s.totalvalue AS amount
FROM invoices i
LEFT JOIN storeloadingdetails s ON i.reference = s.reference
WHERE i.PlantNo = '$plantNo'
  AND i.title NOT IN ('Aggregate','Sand','Laterite','Boulder','MC1','Asphalt','Blocks','S125','Reinforcement')
  AND i.reference > 0
  $mysql
ORDER BY i.id DESC
";

$as_asset = mysqli_query($config, $assets) or die(mysqli_error($config));

/* =========================
   COUNTS
========================= */
$qry_paid = mysqli_query($config, "SELECT COUNT(*) AS paid_count FROM invoices i WHERE i.PlantNo='$plantNo' AND i.ispaid=1 $mysql");
$qry_unpaid = mysqli_query($config, "SELECT COUNT(*) AS unpaid_count FROM invoices i WHERE i.PlantNo='$plantNo' AND i.ispaid=0 $mysql");
$qry_total = mysqli_query($config, "SELECT COUNT(*) AS total_count FROM invoices i WHERE i.PlantNo='$plantNo' $mysql");

$paid_count = mysqli_fetch_assoc($qry_paid)['paid_count'];
$unpaid_count = mysqli_fetch_assoc($qry_unpaid)['unpaid_count'];
$total_count = mysqli_fetch_assoc($qry_total)['total_count'];
?>

<body class="hold-transition skin-blue sidebar-mini">

<link rel="stylesheet" href="../../plugins/datatables/dataTables.bootstrap.css">
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>

<div class="wrapper">

<?php
include_once "../layout/topmenu.php";
allow_access_all(1,1,0,0,1,0,$usergroup);
include_once "../layout/left-sidebar.php";
?>

<div class="content-wrapper">

<section class="content-header">
    <h1>ROCAD <small>Store</small></h1>
</section>

<section class="content">
<div class="row">
<div class="col-xs-12">
<div class="box">

<div class="box-header">
<h3 class="box-title">Plant Invoices</h3>

<div class="row">
    <div class="col-md-4">
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3><?= number_format($total_count) ?></h3>
                <p>Total Invoices</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="small-box bg-green">
            <div class="inner">
                <h3><?= number_format($paid_count) ?></h3>
                <p>Paid Invoices</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3><?= number_format($unpaid_count) ?></h3>
                <p>Unpaid Invoices</p>
            </div>
        </div>
    </div>
</div>
</div>

<div class="box-body">

<p class="text-right">
<span style="color:darkred; cursor:pointer" id="myBtn">Filter By Date</span>
</p>

<center>
<h3><font color="darkgreen">TOTAL INVOICES <?= $title ?></font></h3>
</center>

<table id="example1" class="table table-bordered table-hover">
<thead>
<tr>
<th>S/N</th>
<th>LPO</th>
<th>Plant No</th>
<th>Title</th>
<th>Amount</th>
<th>Status</th>
<th>Uploaded On</th>
<th>Uploaded By</th>
<th>Preview</th>
</tr>
</thead>

<tbody>
<?php
$j = 0;
while ($row = mysqli_fetch_assoc($as_asset)) {
$j++;

$amount = $row['amount'];
$ref = $row['reference'];
$imageURL = $row['invoiceL'];
?>
<tr>
<td>Item <?= $j ?></td>
<td><?= $row['lpo'] ?></td>
<td><?= $row['PlantNo'] ?></td>
<td>
<?php
if ($row['infoD']) echo $row['infoD']." -- ";
echo ($row['title'] == 'Advance Voucher') ? $row['description'] : $row['title'];
?>
</td>
<td><?= number_format($amount,2) ?></td>
<td align="center">
<?php
if ($row['ispaid'] == 1) {
    echo "<span class='label label-success'>PAID</span>";
} else {
    echo "<span class='label label-warning'>UNPAID</span>";
}
?>
</td>
<td><?= $row['uploaded_on'] ?></td>
<td>
<?php
$prebyID = $row['uploadby'];
require '../layout/preby.php';
echo $row_preby['fullname'];
?>
</td>
<td align="center">
<a href="invoices_more.php?v=<?= $ref ?>" class="btn btn-xs btn-info">View</a>
<a href="<?= $imageURL ?>" target="_blank" class="btn btn-xs btn-primary">Preview</a>
</td>
</tr>
<?php } ?>
</tbody>

</table>

</div>
</div>
</div>
</div>
</section>

</div>

<div id="myModal" class="modal">
<div class="modal-content">
<span class="close">&times;</span>

<center>
<form>
<table>
<tr>
<th>From</th>
<td><input type="date" name="f" required class="form-control"></td>
</tr>
<tr>
<th>To</th>
<td><input type="date" name="t" required class="form-control"></td>
</tr>
<tr>
<th>Status</th>
<td>
<select name="tlt" class="form-control">
<option value="">All</option>
<option value="1">Paid</option>
<option value="0">Unpaid</option>
</select>
</td>
</tr>
<tr>
<td colspan="2" align="center">
<input type="hidden" name="v" value="<?= $asset_id ?>">
<button class="btn btn-primary">Filter Record</button>
</td>
</tr>
</table>
</form>
</center>

</div>
</div>

<?php
include_once "../layout/footer.php";
?>

<script>
var modal = document.getElementById("myModal");
var btn = document.getElementById("myBtn");
var span = document.getElementsByClassName("close")[0];

btn.onclick = () => modal.style.display = "block";
span.onclick = () => modal.style.display = "none";
window.onclick = e => { if (e.target == modal) modal.style.display = "none"; }
</script>

<script src="js/table.js"></script>
</body>
