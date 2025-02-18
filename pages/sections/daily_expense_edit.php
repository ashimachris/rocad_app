<?php
if(session_status()===PHP_SESSION_NONE){
session_start();
}
  $active_menu = "data_tables";
  include_once "../layout/header.php";
 require_once('../../db_con/config.php');
  
$preby =$_SESSION['admin_rocad'];
$msg="";


$site = "SELECT * FROM `rocad_site` where sitename!='' and status=1 order by sitename Asc";
$as_site=mysqli_query($config,$site) or die(mysqli_error($config));
$as_site2=mysqli_query($config,$site) or die(mysqli_error($config));
$timeDate=date('Y-m-d H:i:s');
$qryasset="SELECT * FROM assets where status=1";
$asset=mysqli_query($config,$qryasset) or die(mysqli_error($config));
/////////////////////////


$tenDgt = rand(1000000000,9999999999);

if (isset($_GET['edit_id'])) {
$qry_details = "SELECT * FROM `daily_expenses_reports` WHERE id=". $_GET['edit_id'];

$details=mysqli_query($config,$qry_details) or die(mysqli_error($config));

$row_details = mysqli_fetch_assoc($details);

}

if(isset($_POST["sbt"])){

  $plant_no = mysqli_real_escape_string($config,$_POST["plantno"]);
	$fromsite = mysqli_real_escape_string($config,$_POST["fromsite"]);
	$description = mysqli_real_escape_string($config,$_POST["rem"]);
	$amount = mysqli_real_escape_string($config,$_POST["amount"]);
	$wstatus = mysqli_real_escape_string($config,$_POST["wstatus"]);
	$bank_name = mysqli_real_escape_string($config,$_POST["bank_name"]);
	$pay_to = mysqli_real_escape_string($config,$_POST["pay_to"]);
	$supl = mysqli_real_escape_string($config,$_POST["supl"]);
	$edit_id = mysqli_real_escape_string($config,$_POST["edit_id"]);
	$reference = mysqli_real_escape_string($config,$_POST["reference"]);
  
	$sql = "UPDATE daily_expenses_reports SET plant_no='$plant_no', site='$fromsite',description='$description', 
                                amount='$amount', fromsite='$fromsite', bank_name='$bank_name',pay_to='$pay_to'
                                WHERE id='$edit_id' ";

	$sql_store = "UPDATE storeloadingdetails SET bank_name='$bank_name',pay_to='$pay_to',supl='$supl'
                                WHERE reference='$reference' ";

     	$update=mysqli_query($config,$sql) or die(mysqli_error($config));


     	$update_store=mysqli_query($config,$sql_store) or die(mysqli_error($config));
    
    if($update){

	
      $msg="<font color='green'>Data updated successfully.</font>";
  echo    "<script>setTimeout(function(){window.location='daily_report.php';},4200);</script>";
  }else{
	echo"Error, Failed to update the record";
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
          padding-right:20px;
        }
      }
</style>
<body class="hold-transition skin-blue sidebar-mini">
  <!-- Put Page-level css and javascript libraries here -->

  <!-- DataTables -->
 <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

  <!-- ================================================ -->

  <div class="wrapper">

    <?php include_once "../layout/topmenu.php"; ?>

    <?php include_once "../layout/left-sidebar.php"; ?>
    

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        ROCAD
        <small>Generate Report</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="/rocad_admin/pages/dashboard/"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Administration</a></li>
        <li class="active"><a href="requisition_report.php">Report</a></li>
        <li class="active" ><span style="cursor: pointer;" onclick="history.back()">Back</span></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header"> 
              <h3 class="box-title"><a href="#"><a href="daily_report.php"><?php echo "Daily Expense Register" ?></a></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <h3 class="box-title"><?php  echo $msg; ?></h3>
              <div class="form-group">
                
                  <img src="pace/expense.png" style="height:25%; padding-top:50px;" class="hidden-mobile">
               
                <div class="form-wrapper">
                  
                  <form name="add_name" id="add_name" action="" method="post"  class="form-style-9"><center><div id="msg" style="color:red"></div></center>           

		<ul>

                   <?php 
                      $reference = $row_details['reference'];

                      $qry_store = mysqli_query($config, "SELECT * FROM `storeloadingdetails` where reference='$reference'");
                      $r_store =  mysqli_fetch_assoc($qry_store);
                      $pay_to = $r_store['pay_to'];
                      $bank_name = $r_store['bank_name'];
                      $supl = $r_store['supl'];

                    ?>
 
		<li>
   
   
              <label for="">Time & Date:</label>
              <input type="text" class="form-control" required value="<?php echo $timeDate; ?>"  name="make" disabled>
              
   
    
             
              <label for="">Site:</label>
               <select class="form-control" id="fromsite" required name="fromsite">
                        
                          <option value="" selected>Select Location(Site)</option>
                          <?php while($row_site=mysqli_fetch_assoc($as_site)){?>
			  <?php $site = $row_details['site']; ?>
                          <option value="<?php echo $row_site['id']; ?>" <?php echo isset($site) && $site==$row_site['id']  ? 'selected' : '' ?>>

			  <?php echo $row_site['site_state']."---".$row_site['site_lga']."---".$row_site['site_loc']; ?></option>
                          <?php }?>
                        </select>
			
				<div class="form-wrapper">
				<label for="">Plant No:</label> 
				<select name="plantno" required class="form-control"><option value="0">::Select Plant No::</option>
				<option value="0">Nil</option>
				<?php while($row_asset=mysqli_fetch_assoc($asset)){?>
				<?php $plant_no = $row_details['plant_no']; ?>
				<option value="<?php echo $row_asset['sortno']; ?>" <?php echo isset($plant_no) && $plant_no==$row_asset['sortno']  ? 'selected' : '' ?>><?php echo $row_asset['sortno']; ?>
				</option><?php }?>
				</select>
			
			  <div class="form-wrapper">
				<label for="">Status:</label>
				<select name="wstatus" id="wstatus" class="form-control">
				<?php $wstatus = $row_details['wstatus']; ?>

				<option value='Urgent' <?php echo isset($wstatus) && $wstatus== 'Urgent' ? 'selected' : '' ?>>Urgent</option>
				<option value='Not Urgent' <?php echo isset($wstatus) && $wstatus== 'Not Urgent' ? 'selected' : '' ?>>Not Urgent</option>
				</select>
				</div>
			</div>
			<label for="">Description:</label>
			<textarea class="form-control" name="rem" required>
				<?php echo $row_details['description']; ?>
			</textarea>

			</li><li><label for="">Amount</label>
			<input class="form-control" name="amount" type="number" min="0" step="0.01" value="<?php echo $row_details['amount'] ?>"/>

			<input class="form-control" name="edit_id" type="hidden" value="<?php echo $_GET['edit_id'] ?>"/>

			<input class="form-control" name="reference" type="hidden" value="<?php echo $reference ?>"/>
    
        </li><li><label for="">Supplier/Account Name:</label>
			<input class="form-control" name="supl" type="text" value="<?php echo $supl ?>"/>

     </li><li><label for="">Bank Name</label>
			<select class="form-control" name="bank_name" type="text" value="<?php echo $bank_name ?>">
      <option>Nil</option>
                <option>Access Bank Plc</option>
                <option>Ecobank Nigeria</option>
                <option>Heritage Bank</option>
                <option>Jaiz Bank</option>
                <option>Fidelity Bank Plc</option>
                <option>First Bank of Nigeria Limited</option>
                <option>First City Monument Bank Limited</option>
                <option>Guaranty Trust Bank Plc</option>
                <option>Keystone Bank Limited</option>
                <option>Providus Bank</option>
                <option>MoniePoint</option>
                <option>Opay</option>
                <option>Polaris Bank Limited</option>
                <option>Sterling Bank Plc</option>
                <option>Stanbic IBTC Bank</option>
                <option>Standard Chattered Bank Nigeria</option>
                <option>Union/Titan Trust Bank Limited</option>
                <option>United Bank for Africa Plc</option>
                <option>Wema Bank</option>
                <option>Zenith Bank Plc</option>
      </select>

     </li><li><label for="">Account Number</label>
			<input class="form-control" name="pay_to" type="text" value="<?php echo $pay_to ?>"/>

     </li>
                    </ul>
                     <div>
                     <div align="right"> 
                     <input type="submit" name="sbt" id="submit" class="btn btn-info" value="Submit"/>
                     </div> 
                     </div> 
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