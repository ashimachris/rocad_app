<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$active_menu = "data_tables";

include_once "../layout/header.php";
require_once('../../db_con/config.php');

// Get ID from URL
if (isset($_GET['v']) && !empty($_GET['v'])) {
    $ids = $_GET['v'];
} else {
    echo '<script>location.href="/rocad_admin/pages/dashboard/";</script>';
}

// Date filter
$mysql = "";
if (isset($_GET['f']) && !empty($_GET['t'])) {
    $f = $_GET['f'];
    $t = $_GET['t'];
    $mysql = " AND (DATE(time_date) BETWEEN '$f' AND '$t')";
}

// Fetch battery history
$asset = "SELECT * FROM `history` WHERE assetID=$ids AND title='Battery' ORDER BY id DESC";
$assets = "SELECT * FROM `history` WHERE assetID=$ids $mysql AND title='Battery' ORDER BY id DESC";

$as_assets = mysqli_query($config, $assets) or die(mysqli_error($config));
$as_asset = mysqli_query($config, $asset) or die(mysqli_error($config));
$row_admin = mysqli_fetch_assoc($as_asset);
$checkassets = mysqli_num_rows($as_assets);
$plantNo = $row_admin["PlantNo"];

// Sum of all battery amounts
$sum = "SELECT SUM(lprice) AS allsum FROM `history` WHERE title='Battery' AND assetID=$ids $mysql";
$sql_sum = mysqli_query($config, $sum) or die(mysqli_error($config));
$row_sum = mysqli_fetch_assoc($sql_sum);
?>

<style>
.center {
    text-align: center;
    border: 2px solid green;
    margin-bottom: 20px;
}
.right {
    text-align: right;
    margin: 0;
}
.modal {
    display: none;
    padding-top: 10px;
    left: 30%;
    top: 25%;
    width: 50%;
    height: 50%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);
}
.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}
.close {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}
.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}
</style>

<body class="hold-transition skin-blue sidebar-mini">
    <!-- DataTables CSS & JS -->
    <link rel="stylesheet" href="../../plugins/datatables/dataTables.bootstrap.css">
    <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>

    <div class="wrapper">
        <?php include_once "../layout/topmenu.php"; allow_access_all(1,1,0,1,1,1,$usergroup); ?>
        <?php include_once "../layout/left-sidebar.php"; ?>

        <div class="content-wrapper">
            <section class="content-header">
                <h1>
                    ROCAD
                    <small>Battery</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#">Administration</a></li>
                    <li class="active"><a href="equipments.php">Plant</a></li>
                    <li class="active"><span style="cursor: pointer;" onclick="history.back()">Back</span></li>
                </ol>
            </section>

            <section class="content">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box">
                            <div class="box-header">
                                <!-- REPLACED LINK WITH BUTTON -->
                                <button onclick="location.href='battery.php?v=<?php echo $ids; ?>'" class="btn btn-primary">
                                    Insert Battery --- <?php echo $plantNo; ?>
                                </button>
                            </div>
                            <div class="box-body">
                                <?php if ($row_sum['allsum']) { ?>
                                    <div>
                                        <p class="right">
                                            <span style="color:darkred; cursor: pointer;" id="myBtn">Filter By Date</span>
                                        </p>
                                        <p class="center">
                                            <span style="color:#3c8dbc;"><b>Total Amount:</b></span>
                                            <span style="color:darkred;">&#8358;<?php echo number_format($row_sum['allsum'], 2); ?></span>
                                        </p>
                                    </div>
                                <?php } ?>

                                <hr>
                                <table id="example1" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Battery</th>
                                            <th>Product Name:</th>
                                            <th>Amps:</th>
                                            <th>Serial No.:</th>
                                            <th>Amount:</th>
                                            <th>Prepared By:</th>
                                            <th>Description.</th>
                                            <th>Date.</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $j=0; while($row_assets = mysqli_fetch_assoc($as_assets)) { $j++; ?>
                                            <tr style="cursor:pointer" onmouseover="$(this).css('background', '#d4edda');" onmouseout="$(this).css('background', '#FFF');">
                                                <td><?php echo "Battery ".$j; ?></td>
                                                <td><?php echo $row_assets['itemName']; ?></td>
                                                <td><?php echo $row_assets['bamps']; ?></td>
                                                <td><?php echo $row_assets['tsno']; ?></td>
                                                <td>&#8358;<?php echo number_format($row_assets['lprice'],2); ?></td>
                                                <td>
                                                    <?php
                                                    $prebyID = $row_assets['pre_by'];
                                                    require '../layout/preby.php';
                                                    echo $row_preby['fullname'];
                                                    ?>
                                                </td>
                                                <td><?php echo $row_assets['info']; ?></td>
                                                <td><?php echo $row_assets['time_date']; ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Battery</th>
                                            <th>Product Name:</th>
                                            <th>Amps:</th>
                                            <th>Serial No.:</th>
                                            <th>Amount:</th>
                                            <th>Prepared By:</th>
                                            <th>Description.</th>
                                            <th>Date.</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Filter Modal -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <center>
                    <form>
                        <table>
                            <tr>
                                <th>From</th>
                                <td>&nbsp;&nbsp;&nbsp;</td>
                                <td><input type="date" name="f" required></td>
                            </tr>
                            <tr>
                                <th>To</th>
                                <td>&nbsp;&nbsp;&nbsp;</td>
                                <td><input type="date" name="t" required></td>
                            </tr>
                            <tr>
                                <td colspan="3">&nbsp;&nbsp;&nbsp;</td>
                            </tr>
                            <tr>
                                <th><input type="hidden" name="v" value="<?php echo $ids; ?>"></th>
                                <td>&nbsp;&nbsp;&nbsp;</td>
                                <td align="center"><button>Filter Record</button></td>
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

    <?php include_once "../layout/footer.php"; ?>
    <script src="js/table.js"></script>
    <script>
        // Modal JS
        var modal = document.getElementById("myModal");
        var btn = document.getElementById("myBtn");
        var span = document.getElementsByClassName("close")[0];

        btn.onclick = function () {
            modal.style.display = "block";
        }
        span.onclick = function () {
            modal.style.display = "none";
        }
        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
