<?php
if(session_status()===PHP_SESSION_NONE){
session_start();
}
$active_menu = "data_tables";
include_once "../layout/header.php";
?>
<?php require_once('../../db_con/config.php');?>
<style>
.dropbtn {
  background-color: #fff;
  color: #337ab7;
  padding: 3px;
  font-size: 13.5px;
  border: none;
  cursor: pointer;
}

.dropdown {
  position: relative;
  display: inline-block;
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f9f9f9;
  min-width: 160px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}

.dropdown-content a {
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}

.dropdown-content a:hover {background-color: #f1f1f1}

.dropdown:hover .dropdown-content {
  display: block;
}

.dropdown:hover .dropbtn {
  background-color: #fff;
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

    <?php include_once "../layout/topmenu.php"; if(isset($_GET['v'])and (!empty($_GET['v']))){
  $reference=$_GET['v'];
   
}else{
  echo "Invalid URL";
  die;
}
$notiqry1="SELECT * FROM `plant_release_sheet` WHERE status IN($sts) AND reference IN($reference)";
//mysql_select_db($database_config, $config);
$noti1=mysqli_query($config,$notiqry1);
$noti11=mysqli_query($config,$notiqry1);
$row_notii=mysqli_fetch_assoc($noti11);
$total1=mysqli_num_rows($noti1);
$preby =$_SESSION['admin_rocad'];
if($total1==0){
  header("location:/rocad_admin/pages/dashboard/");
}
if (isset($_POST['denied'])) {
  $action=$_POST['denied'];
  $note=$_POST['note'];
  $recby=$_POST['mysqls'];
  $authby=$_SESSION['admin_rocad'];
   $upqdry="update `plant_release_sheet` set status=$action,note='$note',authby='$preby' where reference=$reference";
   $upd=mysqli_query($config,$upqdry);
   if($upd){
switch($action){case 0:$sts="Pending";break;case 1:$sts="Denied";break;case 2:$sts="Approved";break;case 4:$sts="Received<";break;case 5:$sts="Received";break;default:$sts="Unknown";}
    $prebyID=$preby;require '../layout/preby.php';
     
  $msgT="Response By:".$row_preby['fullname']."\nStatus:".$sts."\nLogin to website:\nhttps://app.rocad.com";
  $msgMail = wordwrap($msgT,70);
$dt=(rand(10,100));
// send email
  $prebyID=$row_notii['pre_by'];require '../layout/preby.php';
mail($row_preby['user_mail'],"Plant Release Response ".$dt,$msgMail);
///////////////////////
     echo "<script>alert('Successfully Updated');window.location='/rocad_admin/pages/dashboard/';</script>";
   }
   else{
     echo "<script>alert('Error Updating Data');</script>";
   }
}
 ?>
    <?php include_once "../layout/left-sidebar.php"; ?>
    

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        ROCAD
        <small>Notification</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="/rocad_admin/pages/dashboard/"><i class="fa fa-dashboard"></i> Home</a></li>
         
        <li class="active"><a href="#">Notification</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <table width="402" class="table table-bordered table-hover">
              <tr>
              
              <td width="110"><div align="right">Place of Discharge:</div></td>
              <td colspan="8"><?php $siteID=$row_notii['fromsite'];require '../layout/site.php'; echo $row_site['sitename'];  ?></td>
              <td width="161" rowspan="6" valign="top" <?php allow_access(1,1,0,0,0,0,$usergroup); ?>><font color="blue">Note:</font> <br><form action="" method="post" id="denied"><input type="hidden" name="denied" id="act"><textarea placeholder="<?php echo $row_notii['note']; ?>" required name="note"></textarea><input type="submit" name="ok" id="ok" style="display:none"></form><br><br><center>Action<br>
                <span class="label label-danger" style="cursor:pointer" onClick="$('#act').attr('value','1');$('#ok').click();"> Denied</span> | | <span class="label label-success" style="cursor:pointer" onClick="$('#act').attr('value','2');$('#ok').click();">Approved</span>
              </center><br><br>
              <div align="center"> <strong>No. of Item Requested  <span class="badge bg-yellow"><?php echo $total1; ?></span></strong> </div></td>  <td width="161" rowspan="6" valign="top" <?php allow_access(0,0,1,1,1,1,$usergroup); ?>><font color="blue">Note:</font> <br><form action="" method="post" id="denied"><input type="hidden" name="denied" id="act2"><textarea placeholder="" required name="note" readonly><?php echo $row_notii['note']; ?></textarea><input type="submit" name="ok2" id="ok2" style="display:none"><input type="hidden" name="mysqls" value="<?php echo $recby=',recby='.$preby; ?>"></form><br><br><center><br>
    <span class="label label-success" style="cursor:pointer" onClick="$('#act2').attr('value','4');$('#ok2').click();">Received</span>
              </center><br><br>
              <div align="center"> <strong>No. of Item Requested  <span class="badge bg-yellow"><?php echo $total1; ?></span></strong> </div></td>
              </tr>
              <tr>
               
              <td><div align="right">Place of Arrival:</div></td>
               <td  colspan="8"><?php $siteID=$row_notii['tosite'];require '../layout/site.php'; echo $row_site['sitename'];  ?></td>
              </tr>
              <tr>
               <td><div align="right">Plant No.:</div></td>
               <td  colspan="8"><?php echo $row_notii['PlantNo']; ?></td>
              </tr>
              <tr>
               <td><div align="right">Self or Low Bed:</div></td>
               <td  colspan="8"><?php echo $row_notii['Self_low']; ?></td>
              </tr>
              <td><div align="right">Prepared By:</div></td>
               <td  colspan="8"><?php $prebyID=$row_notii['pre_by'];require '../layout/preby.php'; echo $row_preby['fullname'];  ?></td>
              </tr>
              <tr>
               
              <td><div align="right">Reference:</div></td>
               <td  colspan="8"><?php echo $row_notii['reference']; ?></td>
              </tr>
              <tr>
               
              <td><div align="right">Time &amp; Date:</div></td>
               <td  colspan="8"><?php echo $row_notii['time_date']; ?></td>
              </tr>
               
               <tr>
               
              <td><div align="right">Status:</div></td>
               <td  colspan="8"><?php switch($row_notii['status']){case 0:echo "<font color='blue'>Pending</font>";break;case 1:echo "<font color='red'>Denied</font>";break;case 2:echo "<font color='darkgreen'>Approved</font>";break;case 4:echo "<font color='pink'>Received</font>";break;case 5:echo "<font color='pink'>Received</font>";break;default:echo "<font color='red'>Unknown</font>";} ?></td>
              </tr>
              <?php if($row_notii['modify_by']){?>
              <tr>
                <td><div align="right">Infomation:</div></td>
              <td><div align="left"><?php $modby=$row_notii['modify_by'];require '../layout/preby.php';echo "<font color='red'>Some Item has been modifed by:</font>"." <span class='label label-success'>".$row_preby['fullname']."</span>"?> </div>
              </td>
              </tr>
            <?php }?>
               <tr>
              <td  colspan="9"><div align="right"></div></td>
               
              </tr>
              </table>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <h4 class="box-title"><a class="pr" href="#" onclick="javascript:window.open('print_plant_release_sheet.php?v=<?php echo $reference ?>&w=<?php echo $row_notii['id'] ?>');">PREVIEW</a></h4>
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