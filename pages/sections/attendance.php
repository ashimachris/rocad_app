<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$active_menu = "attendance";

include_once "../layout/header.php";
require_once('../../db_con/config.php');

$preby = $_SESSION['admin_rocad'] ?? "system";

// ===== HANDLE SUBMISSION =====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_absent'])) {

    $date = $_POST['date'] ?? date('Y-m-d');
    $staff_ids = $_POST['staff_ids'] ?? [];

    if (empty($staff_ids)) {
        echo "<script>alert('No staff selected');window.location='{$_SERVER['PHP_SELF']}?date={$date}';</script>";
        exit;
    }

    $month = date('n', strtotime($date));
    $year  = date('Y', strtotime($date));
    $created_at = date('Y-m-d H:i:s');

    $absent_staff_list = [];
    foreach ($staff_ids as $staff_id) {

        $staff_id = intval($staff_id);

        // ===== GET STAFF DETAILS =====
        $q = mysqli_query($config, "SELECT s.full_name, s.basic,
            (s.rent_allowance + s.utility_allowance + s.medical_allowance + s.transport_allowance + s.feeding_allowance) AS allowances,
            sc.cat_name
            FROM staff s
            LEFT JOIN staff_cat sc ON s.cat = sc.id
            WHERE s.id = {$staff_id}
        ");

        $staff = mysqli_fetch_assoc($q);

        if (!$staff) continue;

        // collect staff info for email
        $site = '';
        $trade = '';
        $category = $staff['cat_name'] ?? '';

        $q2 = mysqli_query($config,"SELECT s.trade, rs.site_loc 
        FROM staff s 
        LEFT JOIN rocad_site rs ON s.site = rs.id
        WHERE s.id = {$staff_id}");

        if($row2 = mysqli_fetch_assoc($q2)){
            $site = $row2['site_loc'];
            $trade = $row2['trade'];
        }

        $absent_staff_list[] = [
            'name' => $staff['full_name'],
            'site' => $site,
            'trade' => $trade,
            'category' => $category
        ];

        $basic = floatval($staff['basic']);
        $allowances = floatval($staff['allowances']);
        $isContract = (strtolower(trim($staff['cat_name'])) === 'contract staff');

        // ===== CALCULATE ABSENTEEISM =====
        if ($isContract) {
            $amount = ($basic / 27) * 1;
        } else {
            $amount = (($basic + $allowances) / 22) * 1;
        }

        $amount = round($amount, 2);
        $net = 0 - $amount;

        $penalties_json = json_encode([
            ["description" => "Absenteeism", "amount" => $amount]
        ]);

        // ===== PREVENT DUPLICATE =====
        $check = mysqli_query($config, "
            SELECT id FROM payoff_deductions 
            WHERE staff_id = {$staff_id}
            AND description = 'Absenteeism'
            AND DATE(created_at) = '{$date}'
        ");

        if (mysqli_num_rows($check) > 0) {
            continue;
        }

        // ===== INSERT =====
        $sql = "INSERT INTO payoff_deductions 
        (staff_id, description, penalties_json, total_penalty, net_payoff, created_at, month, year, preby)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($config, $sql);

        if ($stmt) {
            $desc = "Absenteeism";

            mysqli_stmt_bind_param($stmt, "issddsiis",
                $staff_id,
                $desc,
                $penalties_json,
                $amount,
                $net,
                $date,
                $month,
                $year,
                $preby
            );

            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    // SEND EMAIL
    if(!empty($absent_staff_list)){

        $to = "chris@rocad.com, ronaldo@rocad.com, rene@rocad.com, umar@rocad.com, deleakintayo@rocad.com, mairo@rocad.com";
        $subject = "Daily Absenteeism Report";

        $message = "<h3>Absent Staff for {$date}</h3>";
        $message .= "<table border='1' cellpadding='8' cellspacing='0'>";
        $message .= "<tr><th>Name</th><th>Site</th><th>Trade</th><th>Category</th></tr>";

        foreach($absent_staff_list as $s){
            $message .= "<tr>
            <td>{$s['name']}</td>
            <td>{$s['site']}</td>
            <td>{$s['trade']}</td>
            <td>{$s['category']}</td>
            </tr>";
        }

        $message .= "</table>";

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: ROCAD Mgt <rocad@56.233.109>" . "\r\n";

        mail($to,$subject,$message,$headers);
    }

    echo "<script>alert('Absenteeism recorded successfully');window.location='{$_SERVER['PHP_SELF']}';</script>";
    exit;
}


// ===== FETCH STAFF =====
$staff = "SELECT s.*, rs.site_loc 
  FROM staff s
  LEFT JOIN rocad_site rs ON s.site = rs.id
  WHERE s.id NOT IN (2) 
  AND s.is_ex_staff = 0
  AND s.is_deleted = 0
  ORDER BY s.id ASC
";

$as_staff = mysqli_query($config, $staff) or die(mysqli_error($config));
?>

<body class="hold-transition skin-blue sidebar-mini">

<link rel="stylesheet" href="../../plugins/datatables/dataTables.bootstrap.css">
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>

<div class="wrapper">

<?php include_once "../layout/topmenu.php"; ?>
<?php allow_access_all(1,1,0,0,1,1,$usergroup); ?>
<?php include_once "../layout/left-sidebar.php"; ?>

<div class="content-wrapper">

<section class="content-header">
  <h1>Attendance <small>Daily Absenteeism</small></h1>
</section>

<section class="content">

<form method="post" id="attendanceForm">

<div class="box">

    <div class="box-header with-border">
        <div style="display:flex; gap:10px; align-items:center;">

            <input type="date" name="date" id="datePicker" 
                value="<?php echo $selected_date; ?>" 
                class="form-control" style="width:200px;" required>

            <button type="submit" name="mark_absent" id="markAbsentBtn" 
                class="btn btn-danger" style="display:none;">
                Mark Absent
            </button>

             <button class="btn btn-info"
                    onclick="window.location.href='penalty.php'">
                View Report
            </button>

        </div>
    </div>

    <div class="box-body">
        <table id="example1" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>S/N</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Site</th>
                    <th>Trade</th>
                    <th>Category</th>
                </tr>
            </thead>

            <tbody>
            <?php 
            $j=0;
            while($row = mysqli_fetch_assoc($as_staff)){
            $j++;
            ?>

            <?php
              $selectedDate = $_POST['date'] ?? date('Y-m-d');

              $checkAbsent = mysqli_query($config, "
                  SELECT id FROM payoff_deductions 
                  WHERE staff_id = {$row['id']}
                  AND description = 'Absenteeism'
                  AND DATE(created_at) = '{$selectedDate}'
              ");

              $isAbsentToday = (mysqli_num_rows($checkAbsent) > 0);
              ?>
            <tr style="<?php echo $isAbsentToday ? 'background-color:#f8d7da;' : ''; ?>">
                <td>
                    <input type="checkbox" class="staff-checkbox" name="staff_ids[]" value="<?php echo $row['id']; ?>"
            <?php echo $isAbsentToday ? 'disabled' : ''; ?>
>
                </td>
                <td><?php echo $j; ?></td>
                <td><?php echo $row['full_name']; ?></td>
                <td><?php echo $row['phone']; ?></td>
                <td><?php echo $row['site_loc'] ?? '—'; ?></td>
                <td><?php echo $row['trade']; ?></td>
                <td>
                    <?php 
                    $catID = $row['cat'];
                    require '../layout/cat.php';
                    echo $row_cats['cat_name']; 
                    ?>
                </td>
            </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

</div>

</form>

</section>
</div>

<?php include_once "../layout/copyright.php"; ?>
<?php include_once "../layout/right-sidebar.php"; ?>
<div class="control-sidebar-bg"></div>

</div>

<?php include_once "../layout/footer.php"; ?>

<script>
$(document).ready(function(){

    $('#example1').DataTable();

    function toggleButton(){
        let checked = $('.staff-checkbox:checked').length;
        if(checked > 0){
            $('#markAbsentBtn').show();
        } else {
            $('#markAbsentBtn').hide();
        }
    }

    $(document).on('change', '.staff-checkbox', toggleButton);

    $('#selectAll').on('change', function(){
        $('.staff-checkbox').prop('checked', this.checked);
        toggleButton();
    });

    // CONFIRM BEFORE SUBMIT
    $('#attendanceForm').on('submit', function(e){
        let confirmAction = confirm("Are you sure you want to mark selected staff as absent?");
        if(!confirmAction){
            e.preventDefault();
        }
    });

});
</script>

</body>
</html>