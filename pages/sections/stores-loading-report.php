<?php
$active_menu = "data_tables";
include_once "../layout/header.php";
?>
<?php require_once('../../db_con/config.php');
//session_start();
$preby =$_SESSION['admin_rocad'];
$number = count($_POST["descrip"]);
$date=date("Y-m-d h:i:sa", time());
$error="";
$num = str_pad(mt_rand(1,99999999),10,'0',STR_PAD_LEFT);
//$_SESSION['reference']=$num;
 if (isset($_POST['assetsAdd'])) {
 
  for($i=0; $i<$number; $i++)
  {
    if(trim($_POST["descrip"][$i] != ''))
    {
     
       mysql_select_db($database_config, $config);
      $sql="insert into `storeloadingdetails`(descrip,partno,unit,qty,unitprice,totalvalue,conditions,preby,fromsite,tosite,method,reference,status,note)values('".mysql_real_escape_string($_POST["descrip"][$i])."','".mysql_real_escape_string($_POST["partno"][$i])."','".mysql_real_escape_string($_POST["unit"][$i])."','".mysql_real_escape_string($_POST["qty"][$i])."','".mysql_real_escape_string($_POST["unitprice"][$i])."','".mysql_real_escape_string($_POST["ttlv"][$i])."','".mysql_real_escape_string($_POST["conditions"][$i])."','$preby','".mysql_real_escape_string($_POST["from"])."','".mysql_real_escape_string($_POST["to"])."','".mysql_real_escape_string($_POST["method"])."','$num',0,'Request of Moving Asset')";
       
      $insert=mysql_query($sql,$config) or die(mysql_error());
    
    if($insert==1){
  $error="<font color='green'>Data successfully Saved<br> <small>Please wait for Approval</small></font>";
  }
  }
  else{
     
echo "<script>alert('Please Input the values!');</script>";
 
 }
   
}}

$ref = $_GET['ref'];
mysql_select_db($database_config, $config);
$qry_report="select * from storeloadingdetails where reference=$ref";
$report=mysql_query($qry_report,$config) or die(mysql_error());
$row_report=mysql_fetch_assoc($report);
echo print_r($row_report)
?> 
<?php if($insert==1){?>
<meta http-equiv="refresh" content="3;URL=loading-note.php">
<?php }?>
<style type="text/css">
  .col-1 {
    float: left;
    width: 8.3333%;
  }
  .col-2 {
    float: left;
    width: 16.6666%;
  }
  .col-3 {
    float: left;
    width: 25%;
  }
  .col-4 {
    float: left;
    width: 33.3333%;
  }
  .col-5 {
    float: left;
    width: 41.6666%;
  }
  .col-6 {
    float: left;
    width: 50%;
  }
  .col-7 {
    float: left;
    width: 58.3333%;
  }
  .col-8 {
    float: left;
    width: 66.6666%;
  }
  .col-9 {
    float: left;
    width: 75%;
  }
  .col-10 {
    float: left;
    width: 83.3333%;
  }
  .col-11 {
    float: left;
    width: 91.6666%;
  }
  .col-12 {
    float: left;
    width: 100%;
  }
  .normal-table tr td {
    padding: 5px;
  }
  .w-100 {
    width: 100%;
  }
</style>
<body class="hold-transition skin-blue sidebar-mini">
  <!-- Put Page-level css and javascript libraries here -->

  <!-- DataTables -->
  <link rel="stylesheet" href="../../plugins/datatables/dataTables.bootstrap.css">

  <!-- DataTables -->
  <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <!-- ================================================ -->

  <div class="wrapper">

    <?php include_once "../layout/topmenu.php";
  include_once "../layout/left-sidebar.php"; ?>
    

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        ROCAD
        <small>Store Loading</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Assets</a></li>
        <li class="active"><a href="#">Store Loading</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div style="width: 100%; min-height: 500px; padding: 20px;">
              <div class="row">
                <div class="col-2">
                  <center>
                    <img src="/rocad_admin/images/icon.png" width="120px">
                    <br>
                    <b>RC No. 456130</b>
                  </center>
                </div>
                <div class="col-10">
                  <center>
                    <h1 style="margin: 0;"><i><b>ROCAD CONSTRUCTION LIMITED</b></i></h1>
                    <h4 style="margin: 0;">No. 4 Audu Bako Way, P.O.Box 247, Kano - Nigeria</h4>
                    <h4 style="margin: 0;">Tel: 080 80 90 09 08 Fax: (064) 647040</h4>
                  </center>
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <center><h3><b>STORES LOADING NOTE</b></h3></center>
                </div>
              </div>
              <div class="row">
                <div class="col-7">
                  <table class="normal-table" width="100%" border="1" rules="all" cellpadding="20px">
                    <tr class="bg-gray">
                      <td>
                        <b>From: </b>
                        
                      </td>
                    </tr>
                    <tr class="bg-gray">
                      <td>
                        <b>To: </b>
                        
                      </td>
                    </tr> 
                    <tr class="bg-gray">
                      <td>
                        <b>Method of Despatch: </b>
                        
                      </td>
                    </tr>
                  </table>
                </div>
                <div class="col-5">
                  <div class="row">
                    <span style="margin-right: 20px; margin-bottom: 5px; padding: 5px; border: 1px solid black; background: lightgray; float: right;"><b>No.</b> 12301</span>
                  </div>
                  <div class="row">
                    <span style="margin-right: 20px; padding: 5px; border: 1px solid black; background: lightgray; float: right;"><b>Date:</b> <?php echo date('d-m-Y'); ?></span>
                  </div>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-12">
                  <table class="normal-table1" width="100%" border="1" rules="all">
                    <tr class="bg-gray">
                      <th><center><b>No</b></center></th>
                      <th><center><b>Description</b></center></th>
                      <th><center><b>Part No.</b></center></th>
                      <th><center><b>Unit</b></center></th>
                      <th><center><b>Qty.</b></center></th>
                      <th><center><b>Unit Price</b></center></th>
                      <th><center><b>Total Value</b></center></th>
                      <th><center><b>Condition</b></center></th>
                      <th><center><b>Remarks</b></center></th>
                    </tr>
                    <tr>
                      <td><center>1</center></td>
                      <td width="25%"></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td><center>2</center></td>
                      <td width="25%"></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td><center>3</center></td>
                      <td width="25%"></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td><center>4</center></td>
                      <td width="25%"></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td><center>5</center></td>
                      <td width="25%"></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td><center>6</center></td>
                      <td width="25%"></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td><center>7</center></td>
                      <td width="25%"></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td><center>8</center></td>
                      <td width="25%"></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td><center>9</center></td>
                      <td width="25%"></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                    <tr>
                      <td><center>10</center></td>
                      <td width="25%"></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                    </tr>
                  </table>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-9"></div>
                <div class="col-md-3">
                  <button class="btn btn-primary w-100"><i class="fa fa-print"></i> Print Report</button>
                </div>
              </div>
            </div>

           
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
<script>

</script>