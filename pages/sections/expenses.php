<?php
// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include necessary files for header and database connection
$active_menu = "data_tables";
include_once "../layout/header.php";
require_once('../../db_con/config.php');

// Initialize query filters based on GET parameters
$mysql = "";
if (isset($_GET['f']) && !empty($_GET['t'])) {
    $f = $_GET['f'];
    $t = $_GET['t'];

    $plants = isset($_GET['p']) && !empty($_GET['p']) ? "and assetID='{$_GET['p']}'" : "";
    $tlts = isset($_GET['tlt']) && !empty($_GET['tlt']) ? "and title='{$_GET['tlt']}'" : "";
    $st = isset($_GET['st']) && !empty($_GET['st']) ? "and site='{$_GET['st']}'" : "";

    $mysql = "and (date(time_date) BETWEEN '$f' and '$t') $plants $tlts $st";
}

// Query to get asset data
$qryasset = "SELECT * FROM assets WHERE status=1 ORDER BY id";
$assetQry = mysqli_query($config, $qryasset) or die(mysqli_error($config));

// Query to get the distinct latest records for each title from the history table
$assets = "
SELECT t1.* FROM history AS t1
JOIN (SELECT title, MIN(id) as id FROM history GROUP BY title) AS t2
ON t1.title = t2.title AND t1.id = t2.id";
$as_assets = mysqli_query($config, $assets) or die(mysqli_error($config));

// Query to calculate total sum of non-returned items
$sumQuery = "
SELECT SUM(
    CASE
        WHEN liter IS NULL THEN lprice
        ELSE lprice * liter
    END
) as allsum
FROM `history`
WHERE isreturn IS NULL $mysql";
$sql_sum = mysqli_query($config, $sumQuery) or die(mysqli_error($config));
$row_sum = mysqli_fetch_assoc($sql_sum);

// Query to calculate total sum of returned items
$sumRQuery = "
SELECT SUM(
    CASE
        WHEN liter IS NULL THEN lprice
        ELSE lprice * liter
    END
) as allsumR
FROM `history`
WHERE isreturn $mysql";
$sql_sumR = mysqli_query($config, $sumRQuery) or die(mysqli_error($config));
$row_sumR = mysqli_fetch_assoc($sql_sumR);

// Query to get site information
$siteQuery = "SELECT * FROM `rocad_site` WHERE sitename != '' ORDER BY sitename ASC";
$as_site = mysqli_query($config, $siteQuery) or die(mysqli_error($config));
?>

<!-- Custom styles for modal and layout -->
<style type="text/css">
.center { text-align: center; border: 2px solid green; margin-bottom: 20px; }
.right { text-align: right; margin: 0; }
.modal { display: none; padding-top: 10px; left: 30%; top: 25%; width: 50%; height: 50%; overflow: auto; background-color: rgba(0,0,0,0.4); }
.modal-content { background-color: #fefefe; margin: auto; padding: 20px; border: 1px solid #888; width: 80%; }
.close { color: #aaaaaa; float: right; font-size: 28px; font-weight: bold; }
.close:hover, .close:focus { color: #000; text-decoration: none; cursor: pointer; }
</style>

<body class="hold-transition skin-blue sidebar-mini">
<!-- DataTables integration -->
<link rel="stylesheet" href="../../plugins/datatables/dataTables.bootstrap.css">
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>

<div class="wrapper">
    <!-- Include navigation components -->
    <?php include_once "../layout/topmenu.php"; allow_access_all(1,1,0,0,1,0,$usergroup); ?>
    <?php include_once "../layout/left-sidebar.php"; ?>

    <div class="content-wrapper">
        <!-- Page header -->
        <section class="content-header">
            <h1>ROCAD <small>Breakdown Expenses</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Administration</a></li>
                <li class="active"><a href="equipments.php">Plant</a></li>
                <li class="active"><span style="cursor: pointer;" onclick="history.back()">Back</span></li>
            </ol>
        </section>

        <!-- Main content section -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title"><a href="expenses.php">Expenses</a></h3>
                        </div>
                        <div class="box-body">
                            <!-- Display total amounts -->
                            <div>
                                <p class="right">
                                    <span style="color:darkred; cursor: pointer;" id="myBtn">Filter By Date</span>
                                </p>
                                <p class="center">
                                    <span style="color:#3c8dbc;"><b>Total Amount:</b></span>
                                    <span style="color:darkred;">&#8358;<?php echo number_format($row_sum['allsum'], 2); ?></span>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <span style="color:#3c8dbc;cursor: pointer;" onclick="window.location='returned_items.php'"><b>Total Returned:</b></span>
                                    <span style="color:darkred;">&#8358;<?php echo number_format($row_sumR['allsumR'], 2); ?></span>
                                </p>
                            </div>

                            <!-- DataTable for displaying history -->
                            <table id="example1" class="table table-bordered table-hover" style="text-transform: uppercase;">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Title</th>
                                        <th>Liters Consumed</th>
                                        <th>Amount</th>
                                        <th>Time & Date</th>
                                        <!--<th>Prepared By</th>-->
                                        <th>Loads</th>
                                        <th>Unit(&#13221;)</th>
                                        <!--<th>Site</th>-->
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $j = 0; while ($row_assets = mysqli_fetch_assoc($as_assets)) { $j++; ?>
                                    <tr style="cursor:pointer" onmouseover="$(this).css('background', '#d4edda');" onMouseOut="$(this).css('background', '#FFF');" onClick="window.location='more_expenses.php?f=2020-01-01&t=2080-01-01&p=&tlt=<?php echo $row_assets['title']; ?>&st=&v='">
                                        <td><?php echo $j; ?></td>
                                        <td><?php echo "<span class='dropbtn label label-success'>{$row_assets['title']}</span>"; ?></td>
                                        <td><?php echo $row_assets['liter'] ? $row_assets['liter'] . "L" : "N/A"; ?></td>
                                        <td><?php echo "&#8358;" . number_format($row_assets['lprice'] * ($row_assets['liter'] ?: 1), 2); ?></td>
                                        <td><?php echo $row_assets['time_date']; ?></td>
                                        <!--<td><?php $prebyID = $row_assets['pre_by']; require '../layout/preby.php'; echo $row_preby['fullname']; ?></td>-->
                                        <td><?php echo $row_assets['loadcarry'] ?: "N/A"; ?></td>
                                        <td><?php echo $row_assets['unit'] ?: "N/A"; ?></td>
                                        <!--<td><?php $siteID = $row_assets['site']; require '../layout/site.php'; echo $row_site['sitename']; ?></td>-->
                                    </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                <tr>
                                        <th>S/N</th>
                                        <th>Title</th>
                                        <th>Liters Consumed</th>
                                        <th>Amount</th>
                                        <th>Time & Date</th>
                                        <!--<th>Prepared By</th>-->
                                        <th>Loads</th>
                                        <th>Unit(&#13221;)</th>
                                        <!--<th>Site</th>-->
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal for date filter -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <center>
                <form>
                    <table>
                        <tr>
                            <th>From</th>
                            <td>&nbsp;&nbsp;&nbsp;</td>
                            <td><input type="date" name="f" class="form-control"></td>
                        </tr>
                        <tr>
                            <th>To</th>
                            <td>&nbsp;&nbsp;&nbsp;</td>
                            <td><input type="date" name="t" class="form-control"></td>
                        </tr>
                        <tr>
                            <th>Plant</th>
                            <td>&nbsp;&nbsp;&nbsp;</td>
                            <td>
                                <select name="p" class="form-control">
                                    <option value="">Select Plant</option>
                                    <?php while ($row_plant = mysqli_fetch_assoc($assetQry)) { ?>
                                    <option value="<?php echo $row_plant['assetID']; ?>"><?php echo $row_plant['assetID']; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">&nbsp;&nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                            <th>Site</th>
                            <td>&nbsp;&nbsp;&nbsp;</td>
                            <td>
                                <select name="st" class="form-control">
                                    <option value="">Select Site</option>
                                    <?php while ($row_site = mysqli_fetch_assoc($as_site)) { ?>
                                    <option value="<?php echo $row_site['id']; ?>"><?php echo $row_site['sitename']; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">&nbsp;&nbsp;&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <button type="submit" class="btn btn-info form-control">Search</button>
                            </td>
                        </tr>
                    </table>
                </form>
            </center>
        </div>
    </div>

    <?php include_once "../layout/footer.php"; ?>

    <!-- Modal script -->
    <script>
        var modal = document.getElementById("myModal");
        var btn = document.getElementById("myBtn");
        var span = document.getElementsByClassName("close")[0];

        // Open modal
        btn.onclick = function() { modal.style.display = "block"; }

        // Close modal on span click
        span.onclick = function() { modal.style.display = "none"; }

        // Close modal when clicking outside
        window.onclick = function(event) { if (event.target == modal) modal.style.display = "none"; }
    </script>
</div>
</body>
</html>
