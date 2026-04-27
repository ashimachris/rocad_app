<?php
// Start session if not already started
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

$active_menu = "data_tables";
include_once "../layout/header.php";
require_once('../../db_con/config.php');
$msg="";

// Query to fetch all beneficiaries
$benefit = "SELECT * FROM `beneficiaries` WHERE id IS NOT NULL ORDER BY id DESC";
$as_benefit = mysqli_query($config, $benefit) or die(mysqli_error($config));
$checkbenefit = mysqli_num_rows($as_benefit);

$bankQuery = "SELECT bank_name FROM beneficiaries WHERE id IS NOT NULL";
$bankResult = mysqli_query($config, $bankQuery) or die(mysqli_error($config));

$uniqueBanks = []; // key = normalized name, value = ['bank_name' => original name, 'count' => total]

while ($row = mysqli_fetch_assoc($bankResult)) {
    $originalName = $row['bank_name'];
    // Normalize: uppercase, trim, remove punctuation
    $normalized = strtoupper(trim(preg_replace("/[^A-Z0-9 ]/", "", $originalName)));

    if(isset($uniqueBanks[$normalized])) {
        $uniqueBanks[$normalized]['count'] += 1; // add to count
    } else {
        $uniqueBanks[$normalized] = [
            'bank_name' => strtoupper(trim($originalName)), // display nicely
            'count' => 1
        ];
    }
}

// Convert associative array to indexed array for slicing
$allBanks = array_values($uniqueBanks);

// Split evenly for 4 cards
$totalBanks = count($allBanks);
$perCard = ceil($totalBanks / 4);
$card1Banks = array_slice($allBanks, 0, $perCard);
$card2Banks = array_slice($allBanks, $perCard, $perCard);
$card3Banks = array_slice($allBanks, $perCard*2, $perCard);
$card4Banks = array_slice($allBanks, $perCard*3, $perCard);
?>

<?php
if(isset($_GET["delete_id"])){
    $delete_id = $_GET['delete_id'];
   
   $as_benefit=mysqli_query($config,"SELECT * FROM `beneficiaries` WHERE id=$delete_id") or die(mysqli_error($config));

    if(mysqli_num_rows($as_benefit)==1){
        $as_benefit=mysqli_query($config,"DELETE FROM `beneficiaries` WHERE id=$delete_id") or die(mysqli_error($config));
        
        $msg="<font color='green'>Beneficiary deleted succesfully</font>";
       echo    "<script>setTimeout(function(){window.location='beneficiary.php';},3000);</script>";
    }else{
        $msg="<font color='red'>Failed to delete the record, please try again later</font>";
       echo    "<script>setTimeout(function(){window.location='beneficiary.php';},3000);</script>";
    }
}
?>

<style>
/* Styling for dropdown button */
.dropbtn { background-color: #fff; color: #337ab7; padding: 3px; font-size: 13.5px; border: none; cursor: pointer; }
.dropdown { position: relative; display: inline-block; }
.dropdown-content { display: none; position: absolute; background-color: #f9f9f9; min-width: 160px; box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2); z-index: 10; }
.dropdown-content a { color: black; padding: 12px 16px; text-decoration: none; display: block; }
.dropdown-content a:hover { background-color: #f1f1f1; }
.dropdown:hover .dropdown-content { display: block; }
.dropdown:hover .dropbtn { background-color: #fff; }

/* Bank card styling */
.bank-card { color: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); margin-bottom: 20px; }
.bank-card ul { list-style: none; padding-left: 0; margin: 0; }
.bank-card li { padding:5px 0; font-size:16px; display:flex; justify-content:space-between; }
</style>

<script>
// Confirmation dialog for delete action
function myFunction() {
  let text = "Are you sure to delete?";
  return confirm(text);
}
</script>

<body class="hold-transition skin-blue sidebar-mini">

<link rel="stylesheet" href="../../plugins/datatables/dataTables.bootstrap.css">
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>

<div class="wrapper">

    <?php include_once "../layout/topmenu.php"; ?>
    <?php allow_access_all(1, 1, 0, 0, 1, 0, $usergroup); ?>
    <?php include_once "../layout/left-sidebar.php"; ?>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>ROCAD <small>Beneficiary</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Administration</a></li>
                <li class="active" <?php allow_access(1,1,0,0,1,0,$usergroup); ?>><a href="add_beneficiary.php">New Beneficiary</a></li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-body">
                            <div id="error"><?php echo $msg; ?></div>

                            <!-- 4 Bank Cards -->
                            <div class="row" style="margin-bottom:20px;">
                                <?php 
                                $cardGradients = [
                                    'linear-gradient(135deg, #6a11cb, #2575fc)',
                                    'linear-gradient(135deg, #fc5c7d, #6a82fb)',
                                    'linear-gradient(135deg, #ff9966, #ff5e62)',
                                    'linear-gradient(135deg, #56ab2f, #a8e063)'
                                ];
                                $cards = [$card1Banks, $card2Banks, $card3Banks, $card4Banks];
                                for($i=0; $i<4; $i++): ?>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="bank-card" style="background: <?php echo $cardGradients[$i]; ?>;">
                                            <h4 style="margin-bottom:15px; font-weight:700;">Banks - List <?php echo $i+1; ?></h4>
                                            <ul>
                                                <?php foreach($cards[$i] as $bank): ?>
                                                    <li>
                                                        <span><?php echo $bank['bank_name']; ?></span>
                                                        <span><?php echo $bank['count']; ?></span>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    </div>
                                <?php endfor; ?>
                            </div>

                            <button onclick="window.location='/rocad_admin/pages/sections/add_beneficiary.php'" style="width: 120px; height: 30px; font-size: 12px; border-radius: 10px; margin: 10px; padding: 10px 20px; background-color: #3498db; color: #fff; border: none; float:right; display: flex; justify-content: center; align-items: center;"
                            class="pending">Add Beneficiary</button>

                            <!-- Data Table -->
                            <table id="example1" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Account Name</th>
                                        <th>Account Number</th>
                                        <th>Bank Name</th>
                                        <th <?php allow_access(1, 1, 0, 0, 1, 0, $usergroup); ?>>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $j = 0; while($row_benefit = mysqli_fetch_assoc($as_benefit)) { $j++; ?>
                                    <tr>
                                        <td><?php echo $j; ?></td>
                                        <td><?php echo $row_benefit['account_name']; ?></td>
                                        <td><?php echo $row_benefit['account_number']; ?></td>
                                        <td><?php echo $row_benefit['bank_name']; ?></td>
                                        <td>
                                            <div  <?php allow_access(1, 1, 0, 0, 1, 0, $usergroup); ?> class="dropdown">
                                                <button class="dropbtn"><i class="fa fa-eye" aria-hidden="true"></i></button>
                                                <div class="dropdown-content">
                                                    <a  href="edit_beneficiary.php?edit_id=<?php echo $row_benefit['id']; ?>">Edit</a>
                                                    <a  href="beneficiary.php?delete_id=<?php echo $row_benefit['id']; ?>" onclick="return myFunction()">Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Account Name</th>
                                        <th>Account Number</th>
                                        <th>Bank Name</th>
                                        <th <?php allow_access(1, 1, 0, 0, 1, 0, $usergroup); ?>>Action</th>
                                    </tr>
                                </tfoot>
                            </table>

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
<script src="js/table.js"></script>
