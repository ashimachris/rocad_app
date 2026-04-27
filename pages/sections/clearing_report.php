<?php
require_once('../../db_con/config.php');
if(session_status()===PHP_SESSION_NONE){
    session_start();
}

$active_menu = "blank";
include_once "../layout/header.php";

// Fetch all sites
$siteQry = mysqli_query($config, "SELECT id, sitename FROM rocad_site WHERE sitename!='' ORDER BY sitename ASC");
$sites = mysqli_fetch_all($siteQry, MYSQLI_ASSOC);

// Filters
$filter_sql = "WHERE 1";
$title = "All Records";

if(isset($_GET['f'], $_GET['t'])){
    $f = $_GET['f'];
    $t = $_GET['t'];
    $filter_sql .= " AND DATE(date) BETWEEN '$f' AND '$t'";
    $title = "From ($f) To ($t)";
}

if(isset($_GET['fromsite']) && $_GET['fromsite'] != 0){
    $fromsite = intval($_GET['fromsite']);
    $filter_sql .= " AND site_id='$fromsite'";

    $siteNameForTitle = '';
    foreach($sites as $s){
        if($s['id'] == $fromsite){
            $siteNameForTitle = $s['sitename'];
            break;
        }
    }
    if($siteNameForTitle) $title .= " | Site: $siteNameForTitle";
}

// Fetch clearing data
$qry = "SELECT c.*, r.sitename 
        FROM clearing_stock c
        LEFT JOIN rocad_site r ON c.site_id = r.id
        $filter_sql
        ORDER BY c.id ASC";

$result = mysqli_query($config,$qry) or die(mysqli_error($config));

// Totals
$totalsQry = "SELECT 
SUM(length) AS total_length,
SUM(m2) AS total_m2,
SUM(diesel) AS total_diesel
FROM clearing_stock
$filter_sql";

$totalsRes = mysqli_query($config,$totalsQry);
$totals = mysqli_fetch_assoc($totalsRes);
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
#filterModal{
display:none;
position:fixed;
z-index:1000;
left:50%;
top:50%;
transform:translate(-50%,-50%);
width:50%;
max-width:600px;
background-color:rgba(0,0,0,0.4);
}
#filterModal .modal-content{
background:#fff;
padding:20px;
border-radius:6px;
box-shadow:0 0 10px rgba(0,0,0,0.25);
}
.export-area{
background:#fff;
padding:10px;
}
.export-totals{
margin-bottom:15px;
}
.export-totals button{
margin-right:5px;
}
</style>

<div class="content-wrapper">

<section class="content-header">
<h1>
Clearing Progress Report
<small><?php echo $title; ?></small>
</h1>
</section>

<section class="content">

<div class="box">

  <div class="box-header text-center" style="margin-bottom:10px;">
    <button class="btn btn-danger" id="myBtn">
      <i class="fa fa-filter"></i> Filter
    </button>

    <button id="sharePdfBtn" class="btn btn-primary">
      <i class="fa fa-file-pdf-o"></i> Share PDF
    </button>

    <button id="shareExcelBtn" class="btn btn-success">
      <i class="fa fa-file-excel-o"></i> Download Excel
    </button>
  </div>

  <div id="exportArea" class="export-area">

    <?php
    // Calculate number of unique days in the filtered clearing_stock table
    $daysRes = mysqli_query($config, "
        SELECT COUNT(DISTINCT DATE(date)) AS no_of_days
        FROM clearing_stock
        $filter_sql
    ");
    $daysRow = mysqli_fetch_assoc($daysRes);
    $no_of_days = $daysRow['no_of_days'] ?? 0;
    ?>

    <div class="export-totals text-center">

      <button class="btn btn-warning" style="pointer-events:none;">
        TOTAL DIESEL:
        <strong id="totalDiesel"><?php echo number_format($totals['total_diesel'],2); ?></strong>
      </button>

      <button class="btn btn-success" style="pointer-events:none;">
        TOTAL LENGTH:
        <strong id="totalLength"><?php echo number_format($totals['total_length'],2); ?></strong>
      </button>

      <button class="btn btn-primary" style="pointer-events:none;">
        TOTAL M<sup>2</sup>:
        <strong id="totalM2"><?php echo number_format($totals['total_m2'],2); ?></strong>
      </button>

      <button class="btn btn-info" style="pointer-events:none;">
        NO OF DAYS:
        <strong id="totalDays"><?php echo $no_of_days; ?></strong>
      </button>

    </div>

<div class="box-body table-responsive">
<table id="clearingTable" class="table table-bordered table-hover" style="text-transform: uppercase; font-size:13px;">
<thead>
<tr>
<th>S/N</th>
<th>DATE</th>
<th>SITE</th>
<th>PLANT</th>
<th>ITEM</th>
<th>LENGTH</th>
<th>WIDTH</th>
<th>M<sup>2</sup></th>
<th>DIESEL</th> 
<th>TOTAL LENGTH</th>
<th>TOTAL M2</th>
<th>CHAINAGE</th>
<th>PREPARED BY</th>
</tr>
</thead>
<tbody>
<?php
$i = 0;
$total_length = 0;
$total_m2 = 0;
while($row = mysqli_fetch_assoc($result)){
    $i++;
    $total_length += $row['length'];
    $total_m2 += $row['m2'];

    $prebyID = $row['entered_by'];
    require '../layout/preby.php';
?>
<tr>
<td><?php echo $i; ?></td>
<td><?php echo $row['date']; ?></td>
<td><?php echo strtoupper($row['sitename'] ?? 'N/A'); ?></td>
<td><?php echo strtoupper($row['plant']); ?></td>
<td><?php echo strtoupper($row['item']); ?></td>
<td><?php echo number_format($row['length'],2); ?></td>
<td><?php echo number_format($row['width'],2); ?></td>
<td><?php echo $row['m2']; ?></td>
<td><?php echo number_format($row['diesel'],2); ?></td>
<td><?php echo number_format($total_length,2); ?></td>
<td><?php echo number_format($total_m2,2); ?></td>
<td><?php echo strtoupper($row['chainage']); ?></td>
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

<!-- FILTER MODAL -->
<div id="filterModal">
<div class="modal-content">
<span class="close">&times;</span>
<center>
<form method="GET">
<table>
<tr>
<th>From:</th>
<td><input type="date" name="f" required class="form-control"></td>
</tr>
<tr>
<th>To:</th>
<td><input type="date" name="t" required class="form-control"></td>
</tr>
<tr>
<th>Site:</th>
<td>
<select class="form-control" name="fromsite">
<option value="0">ALL</option>
<?php foreach($sites as $s){ ?>
<option value="<?php echo $s['id']; ?>">
<?php echo $s['sitename']; ?>
</option>
<?php } ?>
</select>
</td>
</tr>
<tr>
<td colspan="2">
<button type="submit" class="btn btn-success">Filter Records</button>
</td>
</tr>
</table>
</form>
</center>
</div>
</div>

<?php include_once "../layout/copyright.php"; ?>
<?php include_once "../layout/right-sidebar.php"; ?>
<div class="control-sidebar-bg"></div>
</div>

<?php include_once "../layout/footer.php" ?>
<?php include_once "js/datatable_footer.php" ?>

<script>
// Modal
var modal=document.getElementById("filterModal");
var btn=document.getElementById("myBtn");
var span=document.getElementsByClassName("close")[0];
btn.onclick=()=>{modal.style.display="block";}
span.onclick=()=>{modal.style.display="none";}
window.onclick=(e)=>{if(e.target==modal) modal.style.display="none";}

/// Initialize DataTable and calculate dynamic totals
    $(function() {
        const table = $("#clearingTable").DataTable({
            pageLength: 50,
            lengthMenu: [
                [10, 25, 50, 100, 200, 500, 1000, -1],
                [10, 25, 50, 100, 200, 500, 1000, "All"]
            ],
            order: [[0, "asc"]],
            responsive: true
        });

        // Function to calculate totals from visible rows
        const updateTotals = () => {
            const data = table.rows({ search: "applied" }).data();
            let totalDiesel = 0,
                totalLength = 0,
                totalM2 = 0;
            const uniqueDates = new Set();

            for (let i = 0; i < data.length; i++) {
                const length = parseFloat(data[i][5]) || 0;
                const m2 = parseFloat(String(data[i][7]).replace(/,/g, "")) || 0;
                const diesel = parseFloat(data[i][8]) || 0;
                const dateTime = data[i][1];

                totalLength += length;
                totalM2 += m2;
                totalDiesel += diesel;

                if (dateTime) {
                    uniqueDates.add(dateTime.split(" ")[0]);
                }
            }

            // Update header buttons dynamically
            $("#totalDiesel").text(totalDiesel.toFixed(2));
            $("#totalLength").text(totalLength.toFixed(2));
            $("#totalM2").text(totalM2.toFixed(2));
            $("#totalDays").text(uniqueDates.size);
        };

        // Initial calculation
        updateTotals();

        // Recalculate totals on every table draw (pagination, filter, search)
        table.on("draw", updateTotals);
    });

const newFileName="Clearing_Progress_Report_"+new Date().toISOString().slice(0,10);

// PDF EXPORT
document.getElementById("sharePdfBtn").addEventListener("click", async () => {

const element = document.getElementById("exportArea");

// Get DataTable instance
var table = $('#clearingTable').DataTable();

// Destroy DataTable so ALL rows appear in DOM
table.destroy();

// Hide DataTable controls if any remain
document.querySelectorAll('.dataTables_length, .dataTables_filter, .dataTables_paginate, .dataTables_info')
.forEach(el => el.style.display = 'none');

const opt = {
margin:0.3,
filename:newFileName+'.pdf',
image:{type:'jpeg',quality:0.98},
html2canvas:{
scale:2,
scrollY:0
},
jsPDF:{
unit:'in',
format:'a4',
orientation:'landscape'
}
};

// Generate PDF
await html2pdf().set(opt).from(element).save();

// Re-initialize DataTable after export
$('#clearingTable').DataTable({
pageLength:50,
lengthMenu:[[10,25,50,100,200,500,1000,-1],[10,25,50,100,200,500,1000,"All"]],
order:[[0,"asc"]],
responsive:true
});

});

// EXCEL EXPORT with totals on top
document.addEventListener("DOMContentLoaded", function() {
document.getElementById("shareExcelBtn").addEventListener("click", () => {
    const element = document.getElementById("exportArea");
    if (!element) return;

    const wb = XLSX.utils.book_new();
    const ws = XLSX.utils.table_to_sheet(element.querySelector("table"));

    // Add totals row at the very top
    XLSX.utils.sheet_add_aoa(ws, [[
        "TOTAL DIESEL", <?php echo $totals['total_diesel']; ?>,
        "TOTAL LENGTH", <?php echo $totals['total_length']; ?>,
        "TOTAL M2", <?php echo $totals['total_m2']; ?>
    ]], {origin:0});

    XLSX.utils.book_append_sheet(wb, ws, "Clearing Report");
    XLSX.writeFile(wb, newFileName + ".xlsx");
});
});
</script>