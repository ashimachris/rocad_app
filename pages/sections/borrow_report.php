<?php
require_once('../../db_con/config.php');
if(session_status()===PHP_SESSION_NONE){
    session_start();
}

$active_menu = "blank";
include_once "../layout/header.php";

// Fetch sites
$siteQry = mysqli_query($config, "SELECT id, sitename FROM rocad_site WHERE sitename!='' ORDER BY sitename ASC");
$sites = mysqli_fetch_all($siteQry, MYSQLI_ASSOC);

// Filters
$filter_sql = "WHERE 1";
$title = "Current Month";

// DEFAULT = CURRENT MONTH
$currentMonth = date('Y-m');
$filter_sql .= " AND DATE_FORMAT(date, '%Y-%m') = '$currentMonth'";

if(isset($_GET['f'], $_GET['t'])){
    $f = $_GET['f'];
    $t = $_GET['t'];

    $filter_sql = "WHERE DATE(date) BETWEEN '$f' AND '$t'";
    $title = "From ($f) To ($t)";
}

if(isset($_GET['fromsite']) && $_GET['fromsite'] != 0){
    $fromsite = intval($_GET['fromsite']);
    $filter_sql .= " AND site_id='$fromsite'";

    foreach($sites as $s){
        if($s['id'] == $fromsite){
            $title .= " | Site: ".$s['sitename'];
            break;
        }
    }
}

// Fetch data
$qry = "SELECT b.*, r.sitename
FROM borrow_stock b
LEFT JOIN rocad_site r ON b.site_id = r.id
$filter_sql
ORDER BY b.date ASC";

$result = mysqli_query($config,$qry);

// Totals
$totalsQry = "SELECT
SUM(trip) AS total_trip,
SUM(diesel) AS total_diesel,
SUM(
CASE 
WHEN DATE(date) = '2026-03-06' THEN trip*15
WHEN DATE(date) BETWEEN '2026-02-19' AND '2026-03-04' THEN trip*15
ELSE trip*17
END
) AS total_m3
FROM borrow_stock
$filter_sql";

$totals = mysqli_fetch_assoc(mysqli_query($config,$totalsQry));

// NEW CALCULATIONS
$avgDpm3 = ($totals['total_m3'] > 0) ? $totals['total_diesel'] / $totals['total_m3'] : 0;
$avgDptrip = ($totals['total_trip'] > 0) ? $totals['total_diesel'] / $totals['total_trip'] : 0;
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
}
.label-success{background:#28a745;padding:3px 6px;color:#fff;border-radius:3px;}
.label-warning{background:#ffc107;padding:3px 6px;color:#000;border-radius:3px;}
.label-danger{background:#dc3545;padding:3px 6px;color:#fff;border-radius:3px;}
</style>

<div class="content-wrapper">

<section class="content-header">
<h1>Borrow Pit Report <small><?php echo $title; ?></small></h1>
</section>

<section class="content">

<div class="box">

<div class="box-header text-center">
<button class="btn btn-danger" id="myBtn">Filter</button>
<button id="sharePdfBtn" class="btn btn-primary">Share PDF</button>
<button id="shareExcelBtn" class="btn btn-success">Download Excel</button>
<button id="summaryBtn" class="btn btn-warning">Summary</button>
</div>

<div id="exportArea">

<div class="text-center" style="margin-bottom:10px;">
<button class="btn btn-info">TOTAL DIESEL: <span id="totalDiesel"><?php echo number_format($totals['total_diesel'],2); ?></span></button>
<button class="btn btn-warning">TOTAL TRIP: <span id="totalTrip"><?php echo number_format($totals['total_trip'],2); ?></span></button>
<button class="btn btn-success">TOTAL M3: <span id="totalM3"><?php echo number_format($totals['total_m3'],2); ?></span></button>

<!-- NEW BUTTONS -->
<button class="btn" id="avgDpm3Btn">DIESEL/M3: <?php echo number_format($avgDpm3,3); ?></button>
<button class="btn" id="avgDptripBtn">DIESEL/TRIP: <?php echo number_format($avgDptrip,2); ?></button>

<button class="btn btn-primary" id="detailDpm3" style="display:none;"></button>
<button class="btn btn-dark" id="detailDptrip" style="display:none;"></button>
</div>

<table id="borrowTable" class="table table-bordered table-hover">

<thead>
<tr>
<th>S/N</th>
<th>DATE</th>
<th>SITE</th>
<th>PLANT</th>
<th>TRUCK</th>
<th>ITEM</th>
<th>TRIP</th>
<th>M3</th>
<th>DIESEL</th>
<th>DIESEL/M3</th>
<th>DIESEL/TRIP</th>
<th>REMARKS</th>
<th>PREPARED BY</th>
</tr>
</thead>

<tbody>

<?php
$i=0;
while($row=mysqli_fetch_assoc($result)){
$i++;

$prebyID=$row['entered_by'];
require '../layout/preby.php';

$trip=$row['trip'];
$date=strtotime($row['date']);

$multiplier=17;
if(date('Y-m-d',$date)=='2026-03-06' || ($date>=strtotime('2026-02-19') && $date<=strtotime('2026-03-04'))){
    $multiplier=15;
}

$m3=$trip*$multiplier;
$dpm3=$m3>0?$row['diesel']/$m3:0;
$dptrip=$trip>0?$row['diesel']/$trip:0;
?>

<tr>
<td><?php echo $i; ?></td>
<td><?php echo $row['date']; ?></td>
<td><?php echo strtoupper($row['sitename']); ?></td>
<td><?php echo strtoupper($row['plant']); ?></td>
<td><?php echo strtoupper($row['truck']); ?></td>
<td><?php echo strtoupper($row['item']); ?></td>
<td><?php echo number_format($trip,2); ?></td>
<td><?php echo number_format($m3,2); ?></td>
<td><?php echo number_format($row['diesel'],2); ?></td>
<td><?php echo number_format($dpm3,3); ?></td>
<td><?php echo number_format($dptrip,2); ?></td>
<td><?php echo strtoupper($row['remarks']); ?></td>
<td><?php echo strtoupper($row_preby['fullname']); ?></td>
</tr>

<?php } ?>

</tbody>
</table>

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
<input type="date" name="f" required class="form-control"><br>
<input type="date" name="t" required class="form-control"><br>
<select class="form-control" name="fromsite">
<option value="0">ALL</option>
<?php foreach($sites as $s){ ?>
<option value="<?php echo $s['id']; ?>"><?php echo $s['sitename']; ?></option>
<?php } ?>
</select><br>
<button type="submit" class="btn btn-success">Filter Records</button>
</form>
</center>
</div>
</div>

<?php include_once "../layout/footer.php" ?>

<script>

var modal=document.getElementById("filterModal");
document.getElementById("myBtn").onclick=()=>modal.style.display="block";
document.getElementsByClassName("close")[0].onclick=()=>modal.style.display="none";

let table = $("#borrowTable").DataTable({
pageLength: 100,
lengthMenu: [
    [10, 25, 50, 100, 200, 500, 1000, -1],
    [10, 25, 50, 100, 200, 500, 1000, "All"]
],
responsive: true,
});

// KEEP INITIAL COLUMN STATE
table.columns([9,10]).visible(false);

// STORE ORIGINAL DATA
let originalData = table.rows().data().toArray();
let summaryMode=false;

// ================= PDF =================
$("#sharePdfBtn").click(async function(){

let dt = $("#borrowTable").DataTable();
dt.destroy();

await html2pdf().set({
margin:0.3,
filename:"Borrow_Report.pdf",
html2canvas:{scale:2},
jsPDF:{orientation:'landscape'}
}).from(document.getElementById("exportArea")).save();

// RE-INITIALIZE TABLE PROPERLY
table = $("#borrowTable").DataTable({
pageLength: 100,
lengthMenu: [
    [10, 25, 50, 100, 200, 500, 1000, -1],
    [10, 25, 50, 100, 200, 500, 1000, "All"]
],
responsive: true,
});

// RESTORE SETTINGS AFTER PDF
table.columns([9,10]).visible(false);
originalData = table.rows().data().toArray();

});

// ================= EXCEL =================
$("#shareExcelBtn").click(function(){
let wb=XLSX.utils.book_new();
let ws=XLSX.utils.table_to_sheet(document.querySelector("#borrowTable"));
XLSX.utils.book_append_sheet(wb,ws,"Report");
XLSX.writeFile(wb,"Borrow_Report.xlsx");
});

// ================= SUMMARY =================
$("#summaryBtn").click(function(){

summaryMode=!summaryMode;

if(summaryMode){

let g={};

originalData.forEach(r=>{
let d=r[1].split(" ")[0];
let trip=parseFloat(r[6])||0;
let diesel=parseFloat(r[8])||0;

if(!g[d])g[d]={trip:0,diesel:0,m3:0};

let m=(d==='2026-03-06'||(new Date(d)>=new Date('2026-02-19')&&new Date(d)<=new Date('2026-03-04')))?15:17;

g[d].trip+=trip;
g[d].diesel+=diesel;
g[d].m3+=trip*m;
});

table.clear();

let i=0;
for(let d in g){
i++;
let dpm3=g[d].diesel/g[d].m3;
let dptrip=g[d].diesel/g[d].trip;

table.row.add([
i,d,"","","","",
g[d].trip.toFixed(2),
g[d].m3.toFixed(2),
g[d].diesel.toFixed(2),
`<span class="label-${getColor(dpm3,"m3")}">${dpm3.toFixed(3)}</span>`,
`<span class="label-${getColor(dptrip,"trip")}">${dptrip.toFixed(2)}</span>`,
"CLICK ROW",""
]);
}

table.draw();

table.columns([2,3,4,5,11,12]).visible(false);
table.columns([9,10]).visible(true);

$(this).text("Back");

}else{

table.clear().rows.add(originalData);

table.columns().visible(true);
table.columns([9,10]).visible(false);

table.search('');
table.columns().search('');

table.draw();

$(this).text("Summary");

$("#detailDpm3").removeClass("btn-success btn-warning btn-danger").hide();
$("#detailDptrip").removeClass("btn-success btn-warning btn-danger").hide();

}

});

// ================= ROW CLICK =================
$('#borrowTable tbody').on('click','tr',function(){

if(!summaryMode) return;

let selectedDate = table.row(this).data()[1];
$("#avgDpm3Btn, #avgDptripBtn").hide();
let rowData = table.row(this).data();

let dpm3 = parseFloat($('<div>').html(rowData[9]).text()) || 0;
let dptrip = parseFloat($('<div>').html(rowData[10]).text()) || 0;

let m3Color = getColor(dpm3,"m3");
let tripColor = getColor(dptrip,"trip");

$("#detailDpm3")
.removeClass("btn-success btn-warning btn-danger btn-primary btn-dark")
.addClass("btn-" + m3Color)
.text("DIESEL/M3: " + dpm3.toFixed(3))
.show();

$("#detailDptrip")
.removeClass("btn-success btn-warning btn-danger btn-primary btn-dark")
.addClass("btn-" + tripColor)
.text("DIESEL/TRIP: " + dptrip.toFixed(2))
.show();

table.clear().rows.add(originalData);

summaryMode = false;
$("#summaryBtn").text("Summary");
$("#avgDpm3Btn, #avgDptripBtn").hide();

table.columns().visible(true);
table.columns([9,10]).visible(false);

table.column(1).search(selectedDate);
table.draw();

});

// ================= COLOR FUNCTION =================
function getColor(v,t){
if(t==="m3"){if(v<=0.35)return"success";if(v<=0.40)return"warning";return"danger";}
if(t==="trip"){if(v<=5.25)return"success";if(v<=6.00)return"warning";return"danger";}
}

// ================= HEADER BUTTONS =================
function applyHeaderColors(){

let totalDiesel = parseFloat($("#totalDiesel").text().replace(/,/g,'')) || 0;
let totalTrip = parseFloat($("#totalTrip").text().replace(/,/g,'')) || 0;
let totalM3 = parseFloat($("#totalM3").text().replace(/,/g,'')) || 0;

let avgDpm3 = totalM3 ? totalDiesel / totalM3 : 0;
let avgDptrip = totalTrip ? totalDiesel / totalTrip : 0;

$("#avgDpm3Btn")
.removeClass("btn-success btn-warning btn-danger")
.addClass("btn-" + getColor(avgDpm3,"m3"))
.text("DIESEL/M3: " + avgDpm3.toFixed(3));

$("#avgDptripBtn")
.removeClass("btn-success btn-warning btn-danger")
.addClass("btn-" + getColor(avgDptrip,"trip"))
.text("DIESEL/TRIP: " + avgDptrip.toFixed(2));
}

// RUN ON LOAD
applyHeaderColors();

</script>