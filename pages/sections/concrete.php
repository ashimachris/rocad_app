<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$active_menu = "data_tables";
include_once "../layout/header.php";
require_once('../../db_con/config.php');

if(!isset($_SESSION['admin_rocad'])){
    die("Session expired. Please login again.");
}
$preby = $_SESSION['admin_rocad'];
$site_id  = intval($_GET['site_id'] ?? 0);
$sitename = $_GET['sitename'] ?? '';


// ================= SAVE CONCRETE =================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_concrete'])) {

$dates        = $_POST['date'] ?? [];
$plants       = $_POST['plant'] ?? [];
$items        = $_POST['item'] ?? [];
$sub_items    = $_POST['sub_item'] ?? [];
$lengths      = $_POST['length'] ?? [];
$widths       = $_POST['width'] ?? [];
$depths       = $_POST['depth'] ?? [];
$qtys         = $_POST['qty'] ?? [];
$m3s          = $_POST['cubic_m3'] ?? [];
$bags         = $_POST['bags'] ?? [];
$diesels      = $_POST['diesel'] ?? [];
$chainages    = $_POST['chainage'] ?? [];
$descriptions = $_POST['description'] ?? [];

$success = false;

if (count($dates) > 0) {

$stmt = $config->prepare("INSERT INTO concrete_stock
(date,plant,item,sub_item,length,width,depth,qty,cubic_m3,bags,diesel,chainage,description,entered_by,site_id)
VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

foreach ($dates as $i => $date) {

if(empty($lengths[$i]) || empty($widths[$i]) || empty($depths[$i])){
continue;
}

$stmt->bind_param(
"ssssdddddddsssi",
$dates[$i],
$plants[$i],
$items[$i],
$sub_items[$i],
$lengths[$i],
$widths[$i],
$depths[$i],
$qtys[$i],
$m3s[$i],
$bags[$i],
$diesels[$i],
$chainages[$i],
$descriptions[$i],
$preby,
$site_id
);

if($stmt->execute()){
$success = true;
}

}

$stmt->close();

}

if($success){

echo "<script>
alert('Concrete saved successfully!');
window.location.href='concrete_report.php?site_id={$site_id}&sitename={$sitename}';
</script>";
exit;

}else{

echo "<script>alert('No valid rows to save!');</script>";

}

}
?>

<body class="hold-transition skin-blue sidebar-mini">

<div class="wrapper">

<?php include_once "../layout/topmenu.php"; ?>
<?php include_once "../layout/left-sidebar.php"; ?>

<div class="content-wrapper">

<section class="content-header">
<h1>ROCAD <small>Site Progress - Concrete</small></h1>
</section>

<section class="content">

<div class="box">

<div class="box-header text-center">
<h3>Site Progress - <?= strtoupper($sitename) ?></h3>
</div>

<div class="box-body">


<div style="margin-bottom:15px;">

<div class="btn btn-success" style="pointer-events:none">
TOTAL M<sup>3</sup> :
<strong id="totalM3">0</strong>
</div>

<div class="btn btn-info" style="pointer-events:none">
TOTAL BAGS :
<strong id="totalBags">0</strong>
</div>

<div class="btn btn-warning" style="pointer-events:none">
TOTAL DIESEL :
<strong id="totalDiesel">0</strong>
</div>

<button class="btn btn-info"
onclick="window.location.href='concrete_report.php?site_id=<?php echo $site_id; ?>&sitename=<?php echo $sitename; ?>'">
View Report
</button>

</div>


<form method="POST">

<div class="table-responsive" style="overflow-x:auto; max-width:100%;">
  <table class="table table-bordered" style="min-width:1200px; table-layout:auto;">
    <thead>
      <tr>
        <th>S/N</th>
        <th>DATE</th>
        <th>PLANT</th>
        <th>ITEM</th>
        <th>SUB ITEM</th>
        <th>LENGTH</th>
        <th>WIDTH</th>
        <th>DEPTH</th>
        <th>QTY</th>
        <th>M<sup>3</sup></th>
        <th>BAGS</th>
        <th>DIESEL</th>
        <th>CHAINAGE</th>
        <th>DESCRIPTION</th>
        <th></th>
      </tr>
    </thead>
    <tbody id="tableBody"></tbody>
  </table>
</div>

<style>
.table-responsive {
  overflow-x: auto;
  padding-bottom: 10px;
}

.table-responsive table input,
.table-responsive table select {
  width: 100%;
  min-width: 100px;
  box-sizing: border-box;
}
</style>


<div style="margin-top:15px">

<button type="button" class="btn btn-primary" id="addRowBtn">Add Row</button>

<button type="submit" name="submit_concrete" class="btn btn-success pull-right">
Submit
</button>

</div>

</form>

</div>
</div>
</section>

</div>

<?php include_once "../layout/footer.php"; ?>

</div>


<script>

let rowCount = 0;


// ================= PLANT OPTIONS =================
const plantOptions = `<?php
echo "<option value='BY HAND'>BY HAND</option>";

$qryasset = mysqli_query($config,
"SELECT sortno FROM assets 
WHERE status=1 
AND (sortno LIKE 'TM%' OR sortno LIKE 'CM%')");

while($a = mysqli_fetch_assoc($qryasset)){
echo "<option value='{$a['sortno']}'>{$a['sortno']}</option>";
}
?>`;


// ================= ITEM LIST =================
const itemOptions = `
<option value="DRAIN">DRAIN</option>
<option value="FALLOUT DRAIN">FALLOUT DRAIN</option>
<option value="CULVERT">CULVERT</option>
<option value="ACCESS SLAB">ACCESS SLAB</option>
<option value="ACCESS STREET">ACCESS STREET</option>
<option value="RETAINING WALL">RETAINING WALL</option>
<option value="KERB">KERB</option>
<option value="GENERAL">GENERAL</option>
`;



// ================= SUB ITEMS =================
const subItems = {

"DRAIN":["BLIND","BASE","WALL","TOP"],
"FALLOUT DRAIN":["BLIND","BASE","WALL","TOP"],
"CULVERT":["BLIND","BASE","WALL","DECK","WING WALL","B/WING WALL","B/APRON","APRON","HEADWALL","TOE BEAM"],
"ACCESS SLAB":["ACCESS SLAB"],
"ACCESS STREET":["ACCESS STREET","HEADWALL"],
"RETAINING WALL":["BLINDING","BASE","WALL"],
"KERB":["KERB"],
"GENERAL":["GENERAL"]

};



// ================= TOTALS =================
function updateTotals(){

const m3s=document.querySelectorAll('[name="cubic_m3[]"]');
const bags=document.querySelectorAll('[name="bags[]"]');
const diesels=document.querySelectorAll('[name="diesel[]"]');

let totalM3=0;
let totalBags=0;
let totalDiesel=0;

m3s.forEach(m=>totalM3+=parseFloat(m.value||0));
bags.forEach(b=>totalBags+=parseFloat(b.value||0));
diesels.forEach(d=>totalDiesel+=parseFloat(d.value||0));

document.getElementById('totalM3').innerText=totalM3.toFixed(2);
document.getElementById('totalBags').innerText=totalBags.toFixed(2);
document.getElementById('totalDiesel').innerText=totalDiesel.toFixed(2);

}



// ================= CALCULATE M3 =================
function calculateM3(row){

const l=parseFloat(row.querySelector('[name="length[]"]').value||0);
const w=parseFloat(row.querySelector('[name="width[]"]').value||0);
const d=parseFloat(row.querySelector('[name="depth[]"]').value||0);
const q=parseFloat(row.querySelector('[name="qty[]"]').value||0);

const m3=row.querySelector('[name="cubic_m3[]"]');

let volume = l * w * d;

if(q > 0){
volume = volume * q;
}

m3.value = volume.toFixed(3);

updateTotals();

}


// ================= SUB ITEM =================
function updateSubItem(row,item){

const subSelect=row.querySelector('[name="sub_item[]"]');
subSelect.innerHTML="";

if(subItems[item]){

subItems[item].forEach(sub=>{
let opt=document.createElement("option");
opt.value=sub;
opt.text=sub;
subSelect.appendChild(opt);
});

}

}



// ================= ADD ROW =================
document.getElementById('addRowBtn').onclick=()=>{

rowCount++;

const tr=document.createElement('tr');

const currentDate=new Date().toISOString().slice(0,16);

tr.innerHTML=`

<td>${rowCount}</td>

<td>
<input name="date[]" type="datetime-local" class="form-control" value="${currentDate}" readonly>
</td>

<td>
<select name="plant[]" class="form-control" required>
<option value="">Select Plant</option>
${plantOptions}
</select>
</td>

<td>
<select name="item[]" class="form-control itemSelect">
<option value="">Select</option>
${itemOptions}
</select>
</td>

<td>
<select name="sub_item[]" class="form-control"></select>
</td>

<td><input name="length[]" type="number" step="0.01" value="0" class="form-control"></td>

<td><input name="width[]" type="number" step="0.01" value="0" class="form-control"></td>

<td><input name="depth[]" type="number" step="0.01" value="0" class="form-control"></td>

<td><input name="qty[]" type="number" step="0.01" value="0" class="form-control"></td>

<td><input name="cubic_m3[]" type="number" step="0.001" class="form-control" readonly></td>

<td><input name="bags[]" type="number" step="0.01" value="0" class="form-control"></td>

<td><input name="diesel[]" type="number" step="0.01" value="0" class="form-control"></td>

<td><input name="chainage[]" class="form-control"></td>

<td><input name="description[]" class="form-control"></td>

<td><button type="button" class="btn btn-danger">X</button></td>

`;



const itemSelect=tr.querySelector('.itemSelect');

itemSelect.onchange=()=>{

updateSubItem(tr,itemSelect.value);

const qtyInput = tr.querySelector('[name="qty[]"]');

// if(itemSelect.value === "ACCESS SLAB" || itemSelect.value === "ACCESS STREET"){
// qtyInput.readOnly = false;
// }else{
// qtyInput.value = 0;
// qtyInput.readOnly = true;
// }
qtyInput.readOnly = false;

};

const lengthInput=tr.querySelector('[name="length[]"]');
const widthInput=tr.querySelector('[name="width[]"]');
const depthInput=tr.querySelector('[name="depth[]"]');
const qtyInput=tr.querySelector('[name="qty[]"]');
const bagsInput=tr.querySelector('[name="bags[]"]');
const dieselInput=tr.querySelector('[name="diesel[]"]');

lengthInput.oninput=()=>calculateM3(tr);
widthInput.oninput=()=>calculateM3(tr);
depthInput.oninput=()=>calculateM3(tr);
qtyInput.oninput=()=>calculateM3(tr);

bagsInput.oninput=updateTotals;
dieselInput.oninput=updateTotals;



tr.querySelector('.btn-danger').onclick=()=>{
tr.remove();
updateTotals();
};



document.getElementById('tableBody').appendChild(tr);

updateTotals();

};

</script>

</body>
</html>