<?php
// Start session if none exists
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set active menu and include header
$active_menu = "data_tables";
include_once "../layout/header.php";
require_once('../../db_con/config.php');

// Retrieve admin session variable
$preby = $_SESSION['admin_rocad'];
$prebyID = $preby;

// Include preby data
require '../layout/preby.php';
$sender = $row_preby['fullname'];

$msg = "";
$timeDate = date('Y-m-d H:i:s');
$dp = false;
$qry = false;

// Get asset ID from URL or redirect to dashboard if missing
if (isset($_GET['id'])) {
    $asset_id = $_GET['id'];
} else {
    echo '<script>location.href="/rocad_admin/pages/dashboard/";</script>';
}

// Query asset details from the database
$qry_assets = "SELECT * FROM `structural_asset` WHERE id='$asset_id'";
$as_assets = mysqli_query($config, $qry_assets) or die(mysqli_error($config));
$r_assets = mysqli_fetch_assoc($as_assets);

// Calculate available quantity to receive
$avl_qty_to_receive = $r_assets['total_qty'] - $r_assets['qty_store'];
$asset_name = $r_assets['asset_name'];

// Handle form submission
if (isset($_POST["sbt"])) {

    // Sanitize and assign form data
    $asset_id = mysqli_real_escape_string($config, $_POST["asset_id"]);
    $qty_store = mysqli_real_escape_string($config, $_POST["qty_store"]);
    $total_qty = mysqli_real_escape_string($config, $_POST["total_qty"]);
    $receive_qty = mysqli_real_escape_string($config, $_POST["receive_qty"]);
    $avl_qty_to_receive = mysqli_real_escape_string($config, $_POST["avl_qty_to_receive"]);

    $avl_qty = $qty_store;
    $check = false;

    // Validate received quantity against available quantity
    if ($receive_qty > $avl_qty_to_receive) {
        $check = true;
    }

    // Error message for invalid quantity input
    if ($check) {
        $msg = "<font color='red'>Invalid input, please verify and try again </font>" .
               "<font color='green'>Available asset qty to receive is($avl_qty_to_receive) and you entered ($receive_qty) </font>";
        echo "<script>setTimeout(function(){history.back();},2500)</script>";
    }

    // Calculate updated quantity in store
    $quantiy_left = $qty_store + $receive_qty;
    $qty_store += $receive_qty;

    // Insert record into asset_activity table if validation passes
    $sql = "INSERT INTO `asset_activity` (asset_id, total_qty, avl_qty, move_qty, date, status, site, store_keeper, preby, site_from, type) 
            VALUES ('$asset_id', '".mysqli_real_escape_string($config, $_POST["total_qty"])."', '$quantiy_left', 
            '".mysqli_real_escape_string($config, $_POST["receive_qty"])."', '".mysqli_real_escape_string($config, $_POST["date"])."', 
            '".mysqli_real_escape_string($config, $_POST["status"])."', '".mysqli_real_escape_string($config, $_POST["site"])."', 
            '".mysqli_real_escape_string($config, $_POST["store_keeper"])."', '$prebyID', 
            '".mysqli_real_escape_string($config, $_POST["site_from"])."', 'receive')";

    if (!$check) {
        $qry = mysqli_query($config, $sql) or die(mysqli_error($config));
    }

    // Update asset's qty_store in structural_asset table if query succeeds
    if ($qry) {
        mysqli_query($config, "UPDATE `structural_asset` SET qty_store = '$quantiy_left' WHERE id = '$asset_id'");

        // Fetch destination site details for email
        $siteID = mysqli_real_escape_string($config, $_POST["site"]);
        require_once '../layout/site.php';
        $site = $row_site['site_state'] . "-" . $row_site['site_lga'];

        // Fetch source site details for email
        $site_from_ID = mysqli_real_escape_string($config, $_POST["site_from"]);
        $qry_site_from = "SELECT sitename, id, site_lga, site_state, site_loc FROM rocad_site WHERE id=$site_from_ID AND status=1";
        $site_from = mysqli_query($config, $qry_site_from) or die(mysqli_error($config));
        $row_site_from = mysqli_fetch_assoc($site_from);
        $site_from_name = $row_site_from['site_state'] . "-" . $row_site_from['site_lga'];

        // Prepare email details
        $to = "abbas@ereg.ng, agbachris555@gmail.com";
        $subject = "Asset $asset_name received By $sender on " . date("d-m-Y");

        // Set email headers
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: app.rocad.com" . "\r\n";

        // Construct email body
        $message = "<html><body>";
        $message .= "<h3>Asset: <i>$asset_name</i></h3>";
        $message .= "<h3>Received from site: $site_from_name on " . date("d-m-Y") . "</h3>";
        $message .= "<h3>Received in site: $site</h3>";
        $message .= "<h3>Quantity Received: <i>$receive_qty</i></h3>";
        $message .= "<h3>Quantity in store: " . $qty_store . "</h3>";
        $message .= "<h3>Received by: " . $sender . "</h3>";
        $message .= "</body></html>";

        // Send the email
        mail($to, $subject, $message, $headers);

        // Success message and redirection to history page
        $msg = "<font color='green'>Data successfully saved</font>";
        echo "<script>setTimeout(function(){window.location='history.php?v=$asset_id';},2500)</script>";
    }
}
?>

<style type="text/css">
input{
  text-transform: uppercase;

}
@media (max-width: 767px) {
        .hidden-mobile {
          display: none;
        }
      }
</style>
<body class="hold-transition skin-blue sidebar-mini">
  <!-- Put Page-level css and javascript libraries here -->

  <!-- DataTables -->
  <link rel="stylesheet" href="../../plugins/datatables/dataTables.bootstrap.css">

  <!-- DataTables -->
  <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>

  <!-- ================================================ -->

  <div class="wrapper">

    <?php include_once "../layout/topmenu.php"; allow_access_all(1,1,0,1,0,0,$usergroup); ?>
    <?php include_once "../layout/left-sidebar.php"; ?>
    

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        ROCAD
        <small>Structural Asset</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="/rocad_admin/pages/dashboard/"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Administration</a></li>
        <li class="active" ><a href="tools.php">Plant</a></li>
        <li class="active"><span style="cursor: pointer;" onclick="history.back()">Back</span></li>
      </ol>
    </section>

     <!-- Main content -->
     <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
               
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <h3 class="box-title"><?php  echo $msg; ?></h3>
               <div class="form-group">
                
                  <img src="pace/plant.png" style="height:25%; padding-top:50px;" class="hidden-mobile">
               
                <div class="form-wrapper">

              <form class="form-style-9" method="POST">

                    <?php echo "<h4>Receiving <i>$asset_name</i></h4>"; ?>

        <ul> 
            <input type="hidden" name="asset_id" value="<?php echo $asset_id ?>">
            <input type="hidden" name="total_qty" value="<?php echo $r_assets['total_qty'] ?>">
            <input type="hidden" name="qty_store" value="<?php echo $r_assets['qty_store'] ?>">
           
              <li>
               <div class="form-group">
               
                <div class="form-wrapper">
                   <label for="">Total Quantity:</label>
                   <input type="number" class="form-control" required name="total_qty" value="<?php echo $r_assets['total_qty']; ?>" readonly>
                </div>

                <div class="form-wrapper">
                   <label for="">Available Quantity To Recieve:</label>
                   <input type="number" class="form-control" required name="avl_qty_to_receive" value="<?php echo $avl_qty_to_receive ?>" readonly>
                </div>
              </div>
              </li>

                <li>
                  <div class="form-group">
                  <div class="form-wrapper">
                       <label for="">Date:</label>
                       <input type="datetime-local" class="form-control" required name="date">
                    </div> 
                    <div class="form-wrapper">
                       <label for="">Quantity receive:</label>
                       <input type="number" class="form-control" required name="receive_qty" min="1">
                    </div>
                
                </li>

                <li>
                  <div class="form-group">
                    <div class="form-wrapper">
                       <label for="">Store Keeper:</label>
                       <input type="text" class="form-control" required name="store_keeper">
                    </div>
                    <div class="form-wrapper">
                      <label for="">Status:</label>
                      <select class="form-control" name="status" required><option selected value="">::Select  Status::</option><option value="100">Good</option><option value="50">Idle</option><option value="0">Bad</option></select>
                    </div>     
                 </div>
               </li>

            <li>
             <div class="form-group">
             <div class="form-wrapper">
                   <label for="">Site (Receive in):</label>
                   <select class="form-control" id="in" required name="site">                          
                      <option selected <?php $siteID=0;require '../layout/site.php'?> value="<?php echo $row_site['id']; ?>"><?php echo $row_site['sitename'];?></option>
                      <?php while($row_sites=mysqli_fetch_assoc($sites)){?>
                      <option value="<?php echo $row_sites['id']; ?>"><?php echo $row_sites['site_state']."---".$row_sites['site_lga']."---".$row_sites['site_loc']; ?></option>
                      <?php }?>
                   </select>
                   </div>
                <div class="form-wrapper">
                   <label for="">Site (Receive from):</label>
                   <select class="form-control" id="from" name="site_from">                          
                      <option selected <?php $siteID=0;require '../layout/site.php'?> value="<?php echo $row_site['id']; ?>"><?php echo $row_site['sitename'];?></option>
                      <?php while($row_sites=mysqli_fetch_assoc($sites)){?>
                      <option value="<?php echo $row_sites['id']; ?>"><?php echo $row_sites['site_state']."---".$row_sites['site_lga']."---".$row_sites['site_loc']; ?></option>
                      <?php }?>
                   </select>
                   </div>
                 </li>
                   <li>
                    <?php if ($avl_qty_to_receive==0){
                      echo"<h2>No Available qty to receive</h2>";
                    }else{
                      echo ' <button name="sbt">SUBMIT</button>';
                    }
                    ?>
                   
                   </li>

</ul>
</form>
</div>
</div>
<style type="text/css">
  .form-group {
  display: flex;
  
}
 
  input, textarea, select, button {
  font-family: "Muli-Regular";
  color: #333;
  font-size: 13px;

}
button {
  border: none;
  width: 152px;
  height: 40px;
  margin: auto;
  margin-top: 29px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0;
  background: #305A72;
  font-size: 13px;
  color: #fff;
  text-transform: uppercase;
  font-family: "Muli-SemiBold";
  border-radius: 20px;
  overflow: hidden;
  -webkit-transform: perspective(1px) translateZ(0);
  transform: perspective(1px) translateZ(0);
  box-shadow: 0 0 1px rgba(0, 0, 0, 0);
  position: relative;
  -webkit-transition-property: color;
  transition-property: color;
  -webkit-transition-duration: 0.5s;
  transition-duration: 0.5s;
  &:before {
    content: "";
    position: absolute;
    z-index: -1;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: #f11a09;
    -webkit-transform: scaleX(0);
    transform: scaleX(0);
    -webkit-transform-origin: 0 50%;
    transform-origin: 0 50%;
    -webkit-transition-property: transform;
    transition-property: transform;
    -webkit-transition-duration: 0.5s;
    transition-duration: 0.5s;
    -webkit-transition-timing-function: ease-out;
    transition-timing-function: ease-out;
  }
  &:hover {
    &:before {
      -webkit-transform: scaleX(1);
      transform: scaleX(1);
      -webkit-transition-timing-function: cubic-bezier(0.52, 1.64, 0.37, 0.66);
      transition-timing-function: cubic-bezier(0.52, 1.64, 0.37, 0.66);
    }
  }
}
   
.form-style-9{
  max-width: 450px;
  background: #FAFAFA;
  padding: 30px;
  margin: 50px auto;
  box-shadow: 1px 1px 25px rgba(0, 0, 0, 0.35);
  border-radius: 10px;
   
}
.form-style-9 ul{
  padding:0;
  margin:0;
  list-style:none;
}
.form-style-9 ul li{
  display: block;
  margin-bottom: 10px;
  min-height: 35px;
}
.form-style-9 ul li  .field-style{
  box-sizing: border-box; 
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box; 
  padding: 8px;
  outline: none;
  border: 1px solid #B0CFE0;
  -webkit-transition: all 0.30s ease-in-out;
  -moz-transition: all 0.30s ease-in-out;
  -ms-transition: all 0.30s ease-in-out;
  -o-transition: all 0.30s ease-in-out;

}.form-style-9 ul li  .field-style:focus{
  box-shadow: 0 0 5px #B0CFE0;
  border:1px solid #B0CFE0;
}
.form-style-9 ul li .field-split{
  width: 49%;
}
.form-style-9 ul li .field-full{
  width: 100%;
}
.form-style-9 ul li input.align-left{
  float:left;
}
.form-style-9 ul li input.align-right{
  float:right;
}
.form-style-9 ul li textarea{
  width: 100%;
  height: 100px;
}
.form-style-9 ul li input[type="button"], 
.form-style-9 ul li input[type="submit"] {
  -moz-box-shadow: inset 0px 1px 0px 0px #3985B1;
  -webkit-box-shadow: inset 0px 1px 0px 0px #3985B1;
  box-shadow: inset 0px 1px 0px 0px #3985B1;
  background-color: #216288;
  border: 1px solid #17445E;
  display: inline-block;
  cursor: pointer;
  color: #FFFFFF;
  padding: 8px 18px;
  text-decoration: none;
  font: 12px Arial, Helvetica, sans-serif;
}
.form-style-9 ul li input[type="button"]:hover, 
.form-style-9 ul li input[type="submit"]:hover {
  background: linear-gradient(to bottom, #2D77A2 5%, #337DA8 100%);
  background-color: #28739E;
}
</style>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

           
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
        
    </div><!-- /.content-wrapper -->
    
    <?php include_once "../layout/copyright.php"; ?>
    <?php include_once "../layout/right-sidebar.php"; ?>

    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
  </div><!-- ./wrapper -->

<?php include_once "../layout/footer.php" ?>
<script src="js/table.js"></script>