<?php
// ================= SESSION (24 HOURS) =================
ini_set('session.gc_maxlifetime', 86400); // 24 hours
session_set_cookie_params(86400);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$active_menu = "data_tables";
include_once "../layout/header.php";
require_once('../../db_con/config.php');

if (!isset($_SESSION['admin_rocad'])) {
    die("Session expired. Please login again.");
}

$preby = $_SESSION['admin_rocad'];
$site_id  = intval($_GET['site_id'] ?? 0);
$sitename = $_GET['sitename'] ?? '';

// ================= SAVE BORROW STOCK =================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_borrow'])) {

    $dates   = $_POST['date'] ?? [];
    $plants  = $_POST['plant'] ?? [];
    $trucks  = $_POST['truck'] ?? [];
    $items   = $_POST['item'] ?? [];
    $trips   = $_POST['trip'] ?? [];
    $diesels = $_POST['diesel'] ?? [];
    $remarks = $_POST['remarks'] ?? [];

    $success = false;

    if (count($dates) > 0) {

        $stmt = $config->prepare("INSERT INTO borrow_stock 
        (date, plant, truck, item, trip, diesel, remarks, entered_by, site_id) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        foreach ($dates as $i => $date) {

            if (
                  trim($trips[$i]) === '' &&
                  trim($diesels[$i]) === ''
              ) {
                  continue;
              }

            $stmt->bind_param(
                "ssssddssi",
                $dates[$i],
                $plants[$i],
                $trucks[$i],
                $items[$i],
                $trips[$i],
                $diesels[$i],
                $remarks[$i],
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
        alert('Borrow Pit record saved successfully!');
        window.location.href='borrow_report.php?site_id={$site_id}&sitename={$sitename}';
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
<h1>ROCAD <small>Site Progress - Borrow Pit</small></h1>
</section>

<section class="content">

<div class="box">

<div class="box-header text-center">
<h3>Borrow Pit - <?= strtoupper($sitename) ?></h3>
</div>

<div class="box-body">

<div style="margin-bottom:15px;">

<div class="btn btn-success" style="pointer-events:none">
TOTAL TRIP :
<strong id="totalTrip">0</strong>
</div>

<button 
class="btn btn-info"
onclick="window.location.href='borrow_report.php?site_id=<?php echo $site_id; ?>&sitename=<?php echo $sitename; ?>'">
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
<th>TRUCK</th>
<th>ITEM</th>
<th>TRIP</th>
<th>DIESEL</th>
<th>REMARKS</th>
<th></th>
</tr>

</thead>

<tbody id="tableBody"></tbody>

</table>

</div>

<div style="margin-top:15px">

<button type="button" class="btn btn-primary" id="addRowBtn">Add Row</button>

<button type="submit" name="submit_borrow" class="btn btn-success pull-right">
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

// ================= EXCAVATOR (EX) =================
const plantOptions = `<?php
echo "<option value=''>Select Plant</option>";

$qryasset = mysqli_query($config, "SELECT sortno FROM assets WHERE status = 1 AND (sortno LIKE 'EX%' OR sortno LIKE 'BD%' OR sortno LIKE 'WL%')");

while($a = mysqli_fetch_assoc($qryasset)){
    echo "<option value='{$a['sortno']}'>{$a['sortno']}</option>";
}
?>`;

// ================= TRUCK (TP) =================
const truckOptions = `<?php
$qrytruck = mysqli_query($config, "SELECT sortno FROM assets WHERE status=1 AND sortno LIKE 'TP%'");
while($t = mysqli_fetch_assoc($qrytruck)){
echo "<option value='{$t['sortno']}'>{$t['sortno']}</option>";
}
?>`;

// ================= ITEM =================
const itemOptions = `
<option value="">Select Item</option>
<option value="FILLING">FILLING</option>
<option value="PILING">PILING</option>
`;

// ================= UPDATE TOTAL TRIP =================
function updateTrip(){

const trips = document.querySelectorAll('[name="trip[]"]');

let total = 0;

trips.forEach(t => total += parseFloat(t.value || 0));

document.getElementById('totalTrip').innerText = total.toFixed(2);

}

// ================= ADD ROW =================
document.getElementById('addRowBtn').onclick = () => {

rowCount++;

const tr = document.createElement('tr');

const currentDate = new Date().toISOString().slice(0,16);

tr.innerHTML = `

<td>${rowCount}</td>

<td>
<input name="date[]" type="datetime-local" class="form-control" value="${currentDate}">
</td>

<td>
<select name="plant[]" class="form-control" required>
${plantOptions}
</select>
</td>

<td>
<select name="truck[]" class="form-control" required>
<option value="">Select Truck</option>
${truckOptions}
</select>
</td>

<td>
<select name="item[]" class="form-control" required>
${itemOptions}
</select>
</td>

<td>
<input name="trip[]" type="number" step="0.01" min="0" value="0" class="form-control" required>
</td>

<td>
<input name="diesel[]" type="number" step="0.01" min="0" value="0" class="form-control">
</td>

<td>
<input name="remarks[]" class="form-control" required>
</td>

<td>
<button type="button" class="btn btn-danger">X</button>
</td>

`;

tr.querySelector('.btn-danger').onclick = () => {
    tr.remove();
    updateTrip();
};

const plantSelect = tr.querySelector('[name="plant[]"]');
const truckSelect = tr.querySelector('[name="truck[]"]');

// ================= PLANT CHANGE LOGIC =================
plantSelect.onchange = function () {

    const tripInput = tr.querySelector('[name="trip[]"]');
    const itemSelect = tr.querySelector('[name="item[]"]');

    if (this.value.startsWith("BD")) {

        // ================= TRUCK =================
        truckSelect.innerHTML = `<option value="N/A">N/A</option>`;
        truckSelect.value = "N/A";
        truckSelect.style.pointerEvents = "none";
        truckSelect.style.backgroundColor = "#eee";

        // ================= TRIP =================
        tripInput.value = 0;
        tripInput.readOnly = true;

        // ================= ITEM =================
        itemSelect.value = "PILING";
        itemSelect.style.pointerEvents = "none";
        itemSelect.style.backgroundColor = "#eee";

    } else {

        // ================= TRUCK =================
        truckSelect.innerHTML = `
            <option value="">Select Truck</option>
            ${truckOptions}
        `;
        truckSelect.style.pointerEvents = "auto";
        truckSelect.style.backgroundColor = "";

        // ================= TRIP =================
        tripInput.readOnly = false;

        // ================= ITEM =================
        itemSelect.value = "FILLING";
        itemSelect.style.pointerEvents = "none";
        itemSelect.style.backgroundColor = "#eee";
    }

    updateTrip();
};

tr.querySelector('[name="trip[]"]').oninput = updateTrip;

document.getElementById('tableBody').appendChild(tr);

updateTrip();

};

</script>

</body>
</html>