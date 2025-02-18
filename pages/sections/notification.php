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

    <?php include_once "../layout/topmenu.php"; if(isset($_GET['id'])and (!empty($_GET['id']))){
	$reference=$_GET['id'];
	 
}else{
	echo "Invalid URL";
	die;
}
$siteID=0;
$notiqry1="select * from `storeloadingdetails` where status in($sts) and reference in($reference)";
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

	 $upqdry="update storeloadingdetails set status=$action,note='$note',authby='$authby' $recby where reference=$reference";
	 $upd=mysqli_query($config,$upqdry);
	 if($upd){

 $prebyID=$preby;require '../layout/preby.php';
    switch($action){case 0:$sts="Pending";break;case 1:$sts="Denied";break;case 2:$sts="Approved";break;case 4:$sts="Received<";break;case 5:$sts="Received";break;default:$sts="Unknown";}
    $note="";
    if($row_notii['modify_by']){
      $note="\nNote: Some Item has been modifed";
    }
  $msgT="Response By:".$row_preby['fullname'].$note."\nStatus:".$sts."\nLogin to website:\nhttps://app.rocad.com";
  $msgMail = wordwrap($msgT,70);

// send email
  $prebyID=$row_notii['preby'];require '../layout/preby.php';
mail($row_preby['user_mail'],"Loading Note Response",$msgMail);
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
              
              <td width="110"><div align="right">From:</div></td>
              <td colspan="8"><?php $siteID=$row_notii['fromsite'];require '../layout/site.php'; echo $row_site['sitename'];  ?></td>
              <td width="161" rowspan="6" valign="top" <?php allow_access(1,1,0,0,0,0,$usergroup); ?>><font color="blue">Note:</font> <br><form action="" method="post" id="denied"><input type="hidden" name="denied" id="act"><textarea placeholder="<?php echo $row_notii['note']; ?>" required name="note"></textarea><input type="submit" name="ok" id="ok" style="display:none"></form><br><br><center>Action<br>
                <span class="label label-danger" style="cursor:pointer" onClick="$('#act').attr('value','1');$('#ok').click();"> Denied</span> | | <span class="label label-success" style="cursor:pointer" onClick="$('#act').attr('value','2');$('#ok').click();">Approved</span>
              </center><br><br>
  <div align="center"> <strong>Assets Requested  <span class="badge bg-yellow"><?php echo $total1; ?></span></strong> </div></td>  <td width="161" rowspan="6" valign="top" <?php allow_access(0,0,1,1,1,1,$usergroup); ?>><font color="blue">Note:</font> <br><form action="" method="post" id="denied"><input type="hidden" name="denied" id="act2"><textarea placeholder="" required name="note" readonly><?php echo $row_notii['note']; ?></textarea><input type="submit" name="ok2" id="ok2" style="display:none"><input type="hidden" name="mysqls" value="<?php echo $recby=',recby='.$preby; ?>"></form><br><br><center><br>
    <span class="label label-success" style="cursor:pointer" onClick="$('#act2').attr('value','5');$('#ok2').click();">Received</span>
              </center><br><br>
              <div align="center"> <strong>Assets Requested  <span class="badge bg-yellow"><?php echo $total1; ?></span></strong> </div></td>
              </tr>
              <tr>
               
              <td><div align="right">To:</div></td>
               <td  colspan="8"><?php $siteID=$row_notii['tosite'];require '../layout/site.php'; echo $row_site['sitename'];  ?></td>
              </tr>
              <tr>
               
              <td><div align="right">Prepared By:</div></td>
               <td  colspan="8"><?php $prebyID=$row_notii['preby'];require '../layout/preby.php'; echo $row_preby['fullname'];  ?></td>
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
              <td  colspan="9"><div align="right"></div></td>
               
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
              <table id="example1" class="table table-bordered table-hover">
              
                <thead>
                <tr>
                <th>S/N</th>
                  <th>Description</th>
                  <th>Plant No</th>
                  <th>Part Number</th>
                  <th>Unit</th>
                   <th>Quantity</th>
                   <th>Unit Price</th>
                    <th>Total Value</th>
                     <th <?php allow_access(1,1,0,0,0,0,$usergroup); ?>>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php $j=0;while($row_noti1=mysqli_fetch_assoc($noti1)){$j++; ?>
                <tr <?php $isReject=0;if($row_noti1['isReject']){$isReject=1;?>style="color:red;text-decoration: line-through" title="Rejected"<?php }?>>
                <td><?php echo $j; ?></td>
                  <td><?php echo $row_noti1['descrip'];  ?></td>
                  <td><?php echo $row_noti1['PlantNo'];  ?></td>
                  <td><?php echo $row_noti1['partno']; ?></td>
                  <td><?php echo $row_noti1['unit']; ?></td>
                   <td><?php echo $row_noti1['qty']; ?></td>
                  <td><?php echo $row_noti1['unitprice']; ?>
                  <td><?php echo $row_noti1['totalvalue']; ?></td>
                    <td style="cursor: pointer;"><div class="dropdown" <?php if($isReject==1){?>style="display:none"<?php }?>><button class="dropbtn" <?php allow_access(1,1,0,0,0,0,$usergroup); ?>>Modify</button>
  <div class="dropdown-content">
  <a href="modify_ld.php?v=<?php echo $row_noti1['id'];?>">Edit</a>
  <?php if($total1>1){?><a href="#" onClick='if (confirm("Are you sure!") == true) {
    window.location="cancelreq.php?v=<?php echo $row_noti1['id'];?>&r=<?php echo $row_noti1['reference'];?>&s=<?php echo "1";?>";
  } else {
  }'>Reject</a><?php }?>
  </div></div></td> 
                  </td>
                  
                  
                </tr>
                <?php }?>
              
                </tbody>
                <tfoot>
                <tr>
                <th>S/N</th>
                  <th>Description</th>
                  <th>Plant No</th>
                  <th>Part Number</th>
                  <th>Unit</th>
                   <th>Quantity</th>
                   <th>Unit Price</th>
                    <th>Total Value</th>
                     <th <?php allow_access(1,1,0,0,0,0,$usergroup); ?>>Action</th>
                </tr>
                </tfoot>
              </table>
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