<?php
if(session_status()===PHP_SESSION_NONE){
session_start();
}
  $active_menu = "dashboard";
  include_once "../layout/header.php";
  require_once('../../db_con/config.php'); 
 $error=0;
$insetme=0;
$msg="";
$error=""; 

if(isset($_POST["sbt"])){
 
	  $account_name=mysqli_real_escape_string($config,$_POST['account_name']);
    $account_number=mysqli_real_escape_string($config,$_POST['account_number']);
    $bank_name =mysqli_real_escape_string($config,$_POST['bank_name']);
	
	
$date=date('Y-m-d H:m:s');
$benefit = "SELECT * FROM `beneficiaries` WHERE `account_number`= '$account_number' AND bank_name='$bank_name' ";
$as_benefit=mysqli_query($config,$benefit) or die(mysqli_error($config));
$row_benefi=mysqli_fetch_assoc($as_benefit);
$checkbenefit = mysqli_num_rows($as_benefit);

if($checkbenefit==1){///Avoid Duplicate Staff
$msg="<font color='red'>($account_number) Already Exist! please enter new beneficiary!</font>";
   echo    "<script>setTimeout(function(){window.location='#';},3000);</script>";
}else if($account_name==""|| $account_number=="" || $bank_name==""){
  
$msg="<font color='red'>All fields required</font>";
   echo    "<script>setTimeout(function(){window.location='#';},3000);</script>";
}
else{	 
  $insert = "INSERT INTO beneficiaries(`account_name`, `account_number`, `bank_name`) VALUES ('$account_name', '$account_number', '$bank_name')";
  $insetme=mysqli_query($config,$insert)or die(mysqli_error($config));

  $msg="<font color='green'>Beneficiary added succesfully</font>";
   echo    "<script>setTimeout(function(){window.location='beneficiary.php';},3000);</script>";
  }

}
 
?>
 
<body class="hold-transition skin-blue sidebar-mini">
  <!-- Put Page-level css and javascript libraries here -->
  
  <!-- ChartJS -->
  <script src="../../plugins/chartjs/Chart.min.js"></script>

  <div class="wrapper">

    <?php include_once "../layout/topmenu.php"; ?>
    <?php include_once "../layout/left-sidebar.php";allow_access_all(1,1,0,0,1,0,$usergroup); ?>
    

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
         
        <ol class="breadcrumb">
          <li><a href="/rocad_admin/pages/dashboard/"><i class="fa fa-dashboard"></i> Home</a></li>
           <li><a href="staffs.php">Beneficiary</a></li>
          <li class="active"> New Beneficiary</li>
        </ol>
      </section>
<br>
      <!-- Main content -->
      <section class="content">
      <div class="row">
      <div class="col-md-2"></div>
 <div class="col-md-8">
          <div class="box box-primary">
             
            <div class="box-header with-border">
              <h3 class="box-title"><u>New Beneficiary</u></h3>
              <div id="error"><?php echo $msg; ?></div>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
         
            <form action="" method="post">
              <div class="box-body">

                <div class="form-group">
                  <label for="accountNumber">Account Number</label>
                  <input type="number" class="form-control" id="accountNumber" placeholder="Beneficiary Account Number" name="account_number" required>
                </div>

                <div id='pageloader' style='display:none'>
                  <center><img id='uploadimage2' src='loader.gif' style='width:10%;height:10%;'></center>
                </div>

                <div class='row' id='verifiedAccountResponse' style='width:40rem'></div>

                <div class="form-group">
                  <label for="exampleAccountName">Account Name: </label>
                  <input type="text" class="form-control account_name" id="accountName" placeholder="Beneficiary Name" disabled>
                  <input type="hidden" class="form-control account_name" id="accountNameLoad" name="account_name" required>

                 <font id="invalidAccount" color='red' style="display:none; font-size: 16px;">Invalid Account</font>
                </div>
                
                 <div class="form-group" id="bank-select-section" style="display:none;">
                  <label for="bankSelect">Bank Name</label>
                  <select class="form-control select2" name="bank_name" id="bankSelect" onchange="handleSelectionChange()" required>
                    <option value="" selected>::Select Bank</option>
                        <?php
                        $qry_banks = mysqli_query($config,"SELECT * FROM `bank_codes` ORDER BY name ASC");
                        while($row_bank=mysqli_fetch_assoc($qry_banks)){ ?>
                          <option value="<?php echo $row_bank['name']; ?>" data-bank-code="<?php echo $row_bank['paystack_code']; ?>"><?php echo $row_bank['name']; ?></option>
                          <?php }?>
                  </select>
                   <!-- <select class="form-control" name="bank_name" required id="bankName">

                   </select> -->
                </div>
                  
              </div>
              <!-- /.box-body -->

              <div class="box-footer flex" style="display:none;" id="submit_beneficiary">
                <button type="submit" class="btn btn-primary w-100" name="sbt" style="width:100%;">Submit</button>
              </div>
            </form>
          </div>
          <!-- /.box -->
        </div>
        </div>
        
      </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
    
    <?php include_once "../layout/copyright.php"; ?>
    <?php include_once "../layout/right-sidebar.php"; ?>

    <div class="control-sidebar-bg"></div>
  </div><!-- ./wrapper -->

<?php include_once "../layout/footer.php" ?>

<!-- load logic for beneficiaries   -->
<?php require './beneficiaries_footer.php'; ?>