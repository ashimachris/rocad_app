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


// ================= SAVE CLEARING =================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_clearing'])) {

    $dates       = $_POST['date'] ?? [];
    $plants      = $_POST['plant'] ?? [];
    $items       = $_POST['item'] ?? [];
    $lengths     = $_POST['length'] ?? [];
    $widths      = $_POST['width'] ?? [];
    $m2s         = $_POST['m2'] ?? [];
    $diesels     = $_POST['diesel'] ?? [];
    $chainages   = $_POST['chainage'] ?? [];

    $success = false;

        if (count($dates) > 0) {

            $stmt = $config->prepare("INSERT INTO clearing_stock
            (date, plant, item, length, width, m2, diesel, chainage, entered_by, site_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            foreach ($dates as $i => $date) {

                // length and width must be provided
                if(empty($lengths[$i]) || empty($widths[$i])){
                    continue;
                }

                $stmt->bind_param(
                    "sssddddssi",
                    $dates[$i],
                    $plants[$i],
                    $items[$i],
                    $lengths[$i],
                    $widths[$i],
                    $m2s[$i],
                    $diesels[$i],
                    $chainages[$i],
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
        alert('Clearing saved successfully!');
        window.location.href='clearing_report.php?site_id={$site_id}&sitename={$sitename}';
        </script>";
        exit;
    } else {
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
<h1>ROCAD <small>Site Progress - Clearing</small></h1>
</section>

<section class="content">

<div class="box">

<div class="box-header text-center">
<h3>Site Progress - <?= strtoupper($sitename) ?></h3>
</div>

<div class="box-body">


<div style="margin-bottom:15px;">

<div class="btn btn-success" style="pointer-events:none">
TOTAL LENGTH :
<strong id="totalLength">0</strong>
</div>

<div class="btn btn-info" style="pointer-events:none">
TOTAL WIDTH :
<strong id="totalWidth">0</strong>
</div>

<div class="btn btn-primary" style="pointer-events:none">
TOTAL DIESEL :
<strong id="totalDiesel">0</strong>
</div>

<div class="btn btn-warning" style="pointer-events:none">
TOTAL M<sup>2</sup>:
<strong id="totalM2">0</strong>
</div>

<button 
class="btn btn-info"
onclick="window.location.href='clearing_report.php?site_id=<?php echo $site_id; ?>&sitename=<?php echo $sitename; ?>'">
View Report
</button>

</div>



<form method="POST">

<div class="table-responsive">

<table class="table table-bordered">

<thead>

<tr>
<th>S/N</th>
<th>DATE</th>
<th>PLANT</th>
<th>ITEM</th>
<th>LENGTH</th>
<th>WIDTH</th>
<th>M<sup>2</sup></th>
<th>DIESEL</th>
<th>CHAINAGE</th>
<th></th>
</tr>

</thead>

<tbody id="tableBody"></tbody>

</table>

</div>



<div style="margin-top:15px">

<button type="button" class="btn btn-primary" id="addRowBtn">Add Row</button>

<button type="submit" name="submit_clearing" class="btn btn-success pull-right">
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
$qryasset = mysqli_query($config,
"SELECT sortno FROM assets 
WHERE status=1 
AND (sortno LIKE 'MG%' OR sortno LIKE 'BD%' OR sortno LIKE 'DB%')");

while($a = mysqli_fetch_assoc($qryasset)){
    echo "<option value='{$a['sortno']}'>{$a['sortno']}</option>";
}
?>`;



// ================= ITEM OPTIONS =================
const itemOptions = `
<option value="CLEARING">CLEARING</option>
`;



// ================= UPDATE TOTALS =================
function updateTotals(){

    const lengths = document.querySelectorAll('[name="length[]"]');
    const widths = document.querySelectorAll('[name="width[]"]');
    const m2s = document.querySelectorAll('[name="m2[]"]');
    const diesels = document.querySelectorAll('[name="diesel[]"]');

    let totalLength = 0;
    let totalWidth = 0;
    let totalM2 = 0;
    let totalDiesel = 0;

    lengths.forEach(l => totalLength += parseFloat(l.value || 0));
    widths.forEach(w => totalWidth += parseFloat(w.value || 0));
    m2s.forEach(m => totalM2 += parseFloat(m.value || 0));
    diesels.forEach(d => totalDiesel += parseFloat(d.value || 0));

    document.getElementById('totalLength').innerText = totalLength.toFixed(2);
    document.getElementById('totalWidth').innerText = totalWidth.toFixed(2);
    document.getElementById('totalM2').innerText = totalM2.toFixed(2);
    document.getElementById('totalDiesel').innerText = totalDiesel.toFixed(2);

}


// ================= CALCULATE M2 =================
function calculateM2(row){

    const length = row.querySelector('[name="length[]"]');
    const width = row.querySelector('[name="width[]"]');
    const m2 = row.querySelector('[name="m2[]"]');

    const l = parseFloat(length.value || 0);
    const w = parseFloat(width.value || 0);

    m2.value = (l * w).toFixed(2);

    updateTotals();
}



// ================= ADD ROW =================
document.getElementById('addRowBtn').onclick = () => {

    rowCount++;

    const tr = document.createElement('tr');

    const currentDate = new Date().toISOString().slice(0,16);

    tr.innerHTML = `

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
<select name="item[]" class="form-control">
${itemOptions}
</select>
</td>

<td>
<input name="length[]" type="number" step="0.01" min="0" value="0" class="form-control">
</td>

<td>
<input name="width[]" type="number" step="0.01" min="0" value="0" class="form-control">
</td>

<td>
<input name="m2[]" type="number" step="0.01" class="form-control" readonly>
</td>

<td>
<input name="diesel[]" type="number" step="0.01" min="0" value="0" class="form-control">
</td>

<td>
<input name="chainage[]" class="form-control" placeholder="CH0+000">
</td>

<td>
<button type="button" class="btn btn-danger">X</button>
</td>

`;



const lengthInput = tr.querySelector('[name="length[]"]');
const widthInput = tr.querySelector('[name="width[]"]');

lengthInput.oninput = () => calculateM2(tr);
widthInput.oninput = () => calculateM2(tr);



tr.querySelector('.btn-danger').onclick = () => {
    tr.remove();
    updateTotals();
};



document.getElementById('tableBody').appendChild(tr);

updateTotals();

};

</script>

</body>
</html>