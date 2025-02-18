<?php

if(session_status()===PHP_SESSION_NONE){

session_start();

}

$active_menu = "data_tables";

include_once "../layout/header.php";

?>

<?php require_once('../../db_con/config.php');?>

<style>



  .modal {

  display: none; /* Hidden by default */

   

  padding-top: 10px; /* Location of the box */

  left: 30%;

  top: 25%;

  width: 50%; /* Full width */

   height: 50%;

  overflow: auto; /* Enable scroll if needed */

  background-color: rgb(0,0,0); /* Fallback color */

  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */

}



/* Modal Content */

.modal-content {

  background-color: #fefefe;

  margin: auto;

  padding: 20px;

  border: 1px solid #888;

  width: 80%;

}



/* The Close Button */

.close {

  color: #000;

  float: right;

  font-size: 50px;

  font-weight: bold;

}



.close:hover,

.close:focus {

  color: #000;

  text-decoration: none;

  cursor: pointer;

}

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

img.attachementPreview{
    height: 80vh;
    width: 80vh;
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



    <?php include_once "../layout/topmenu.php";allow_access_all(1,1,1,1,1,1,$usergroup); if(isset($_GET['v'])and (!empty($_GET['v']))){

  $reference=$_GET['v'];

   

}else{

  echo "Invalid URL";

  die;

}

$notiqry1="SELECT * FROM `storeloadingdetails` WHERE status IN($sts) AND reference IN($reference)";



$noti1=mysqli_query($config,$notiqry1);

$noti11=mysqli_query($config,$notiqry1);

$row_notii=mysqli_fetch_array($noti11);

$total1=mysqli_num_rows($noti1);



/////////vars

$plantno=mysqli_real_escape_string($config,$row_notii['PlantNo']);

$time_date=$row_notii['time_date'];

$totalvalue=mysqli_real_escape_string($config,$row_notii['totalvalue']);

$reqfor=mysqli_real_escape_string($config,$row_notii['reqfor']);

$preby =$_SESSION['admin_rocad'];

$title="Advance Voucher";

$site=mysqli_real_escape_string($config,$row_notii['fromsite']);

$reference=mysqli_real_escape_string($config,$row_notii['reference']);

//////////////////

if($total1==0){

  header("location:/rocad_admin/pages/dashboard/");

}

if (isset($_POST['denied'])) {

  $action=$_POST['denied'];

  $note=$_POST['note'];

  $recby=$_POST['mysqls'];

  $authby=$_POST['mysqls2'];;

   $upqdry="update storeloadingdetails set status=$action,note='$note'$authby$recby where reference=$reference";

   $upd=mysqli_query($config,$upqdry);

   if($action==2){

    $sqls="insert into `history`(`PlantNo`,`time_date`,`lprice`,`info`,`pre_by`,`title`,`site`,`requi`)values('".$plantno."','".$time_date."','".$totalvalue."','".$reqfor."','".$preby."','".$title."','".$site."','".$reference."')";       

      mysqli_query($config,$sqls) or die(mysqli_error($config));
   }

   if($upd){

////////////////////

    $prebyID=$preby;require '../layout/preby.php';

    switch($action){

      case 0:

      $sts="Pending";

      break;

      case 1:

      $sts="Denied";

      break;

      case 2:

      $sts="Approved";       

      break;

      case 4:

      $sts="Received";

      break;

      case 5:

      $sts="Received";

      break;

      default:

      $sts="Unknown";

    }

    $note="";

    if($row_notii['modify_by']){

      $note="\nNote: Some Item has been modifed";

    }

  $msgT="Response By:".$row_preby['fullname'].$note."\nStatus:".$sts."\nLogin to website:\nhttps://app.rocad.com";

  $msgMail = wordwrap($msgT,70);



// send email

  $prebyID=$row_notii['preby'];require '../layout/preby.php';

mail($row_preby['user_mail'],"Requisition Response",$msgMail);

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

        <small>Advance Voucher Notification</small>

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

              
              <td width="110"><div align="right">Site:</div></td>

              <td colspan="8"><?php $siteID=$row_notii['fromsite'];require '../layout/site.php'; echo $row_site['sitename'];  ?></td>

              <td width="161" rowspan="6" valign="top" <?php allow_access(1,1,0,0,0,0,$usergroup); ?>><font color="blue">Note:</font> <br><form action="" method="post" id="denied"><input type="hidden" name="denied" id="act"><input type="hidden" name="mysqls2" value="<?php echo $authby=',authby='.$preby; ?>"><textarea placeholder="<?php echo $row_notii['note']; ?>" required name="note"></textarea><input type="submit" name="ok" id="ok" style="display:none"></form><br><br><center>Action<br>

                <span class="label label-danger" style="cursor:pointer" onClick="$('#act').attr('value','1');$('#ok').click();"> Denied</span> | | <span class="label label-success" <?php allow_access(1,1,0,0,0,0,$usergroup); ?> style="cursor:pointer" id="approveToExpense">Approve to Expense</span>
                
                 
              </center><br><br>
               <input type="hidden" name="reference" value="<?= $reference ?>">

              <div align="center"> <strong>No. of Item Requested  <span class="badge bg-yellow"><?php echo $total1; ?></span></strong> </div></td>  <td width="161" rowspan="6" valign="top" <?php allow_access(0,0,1,1,1,1,$usergroup); ?>><font color="blue">Note:</font> <br><form action="" method="post" id="denied"><input type="hidden" name="denied" id="act2"><textarea placeholder="" required name="note" readonly><?php echo $row_notii['note']; ?></textarea><input type="submit" name="ok2" id="ok2" style="display:none"><input type="hidden" name="mysqls" value="<?php echo $recby=',recby='.$preby; ?>"></form><br><br><center><br>

    <span class="label label-success" style="cursor:pointer" onClick="$('#act2').attr('value','4');$('#ok2').click();">Received</span>

              </center><br><br>

              <div align="center"> <strong>No. of Item Requested  <span class="badge bg-yellow"><?php echo $total1; ?></span></strong> </div></td>

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

               

              <td><div align="right">For What Required:</div></td>

               <td  colspan="8"><?php echo $row_notii['reqfor']; ?></td>

              </tr>

               <tr>

               

              <td><div align="right">Status:</div></td>

               <td  colspan="8"><?php switch($row_notii['status']){case 0:echo "<font color='blue'>Pending</font>";break;case 1:echo "<font color='red'>Denied</font>";break;case 2:echo "<font color='darkgreen'>Approved</font>";break;case 4:echo "<font color='pink'>Received</font>";break;case 5:echo "<font color='pink'>Received</font>";break;default:echo "<font color='red'>Unknown</font>";} ?></td>

              </tr>

            <tr>
              <td><div align="right">Signed By</div></td>
              <td  colspan="8"><?php echo $row_notii['sign_by'];  ?></td>
           </tr>

             <tr>
              <td><div align="right">Total Amount</div></td>
              <td  colspan="8"><?php echo "<b>". number_format($row_notii['totalvalue'],2) . "</b>";  ?></td>
           </tr>

            <tr>
              <td><div align="right">Invoice</div></td>

              <td  colspan="8" id="showInvoiceBtn">
                <div style="cursor: pointer;">
                  <span>View</span>
                </div>
        
              </td>
           </tr>

              <?php if($row_notii['modify_by']){?>

              <tr>

                <td><div align="right">Infomation:</div></td>

              <td><div align="left"><?php $prebyID=$row_notii['modify_by'];require '../layout/preby.php';echo "<font color='red'>Some Item has been modifed by:</font>"." <span class='label label-success'>".$row_preby['fullname']."</span>"?> </div>

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

                  <th>Amount</th>

                  <th>A/C Code</th>

                  <th>Quanity in Word</th>

                   <th>Description</th>

                    <th>L.P.O No:</th>

                   <th <?php allow_access(1,1,0,0,0,0,$usergroup); ?>>Action</th>

                  </tr>

                </thead>

                <tbody>

                <?php $j=0;while($row_noti1=mysqli_fetch_assoc($noti1)){$j++; ?>

                <tr <?php if($row_noti1['isReject']){?>style="color:red;text-decoration: line-through" title="Rejected"<?php }?>>

                <td><?php echo $j; ?></td>

                <td><?php echo "&#8358;".number_format($row_noti1['totalvalue'],2); ?></td>

                  <td><?php if($row_noti1['PlantNo']==null){echo "N/A";}else{echo $row_noti1['PlantNo'];} ?></td>

                   <td><?php echo $row_noti1['qtyw']; ?></td>

                 <td><?php echo $row_noti1['reqfor']; ?></td>

                 <td><?php echo $row_noti1['reference']; ?></td>

                 <td style="cursor: pointer;"><div class="dropdown"><button class="dropbtn" <?php allow_access(1,1,0,0,0,0,$usergroup); ?>>Modify</button>

  <div class="dropdown-content">

  <a href="modify_adv.php?v=<?php echo $row_noti1['id'];?>">Edit</a>
    <a style="cursor: pointer;" target="_blank" href="<?php echo $row_noti1['invoice'];?>">Voucher</a>

  <?php if($total1>1){?><a href="#" onClick='if (confirm("Are you sure!") == true) {

    window.location="cancelreq.php?v=<?php echo $row_noti1['id'];?>&r=<?php echo $row_noti1['reference'];?>&s=<?php echo "2";?>";

  } else {

  }'>Reject</a><?php }?>

  </div></div></td>

                </tr>

                <?php }?>

              

                </tbody>

                <tfoot>

                <tr>

                <th>S/N</th>

                  <th>Amount</th>

                  <th>A/C Code</th>

                  <th>Quanity in Word</th>

                   <th>Description</th>

                   <th>L.P.O No:</th>

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


    <!-- Modal content -->
 <div id="myModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <center>
      <table>
        <tr>  <td>&nbsp;&nbsp;&nbsp;</td></tr>
        <tr>
            <?php
            $attch = parse_url($row_notii['invoice']);
            $name = pathinfo($attch['path'], PATHINFO_FILENAME);
            $ext  = pathinfo($attch['path'], PATHINFO_EXTENSION);
            $attachment = $row_notii['invoice'];
           ?>
            <?php if($ext=="png" || $ext=="jpg" || $ext=="jpeg"){ ?>
                <img src="<?php echo isset($attachment) ? $attachment :'' ?>" alt="Attachement File " width="50" height="80" class="attachementPreview border border-gray img-thumbnail" width="450" height="450">
            <?php }else if($ext=="pdf"){ ?>
                <iframe src="<?php echo isset($attachment) ? $attachment :'' ?>" alt="Attachement File " class="attachementPreview border border-gray" style="border:1px solid black;"></iframe>
            <?php } else{ ?>

                <a href="<?php echo $row_notii['invoice']; ?>">View</a>
                    <?php //echo $row_notii['invoice']; ?>"
               
            <?php } ?>
        
       </tr>
       <tr>  <td>&nbsp;&nbsp;&nbsp;</td></tr>
       </table>
    </center>

  </div>
</div>

    

    <?php include_once "../layout/copyright.php"; ?>

    <?php include_once "../layout/right-sidebar.php"; ?>

 <script>
  var reference = $('input[name="reference"]').val();

  $(document).ready(function(){

  $('#approveToExpense').click(function(){
      if (confirm(`Are You Sure You Want To Approve?`)) {
     
        $.post(`ajax/expense_to_approve.php?approve_to_expenses&reference=${reference}`, { reference: reference }).done(function (data) {
            console.log('data', data);
            alert("Record updated successfully");
            setTimeout(function() {
              window.location = 'daily_report.php';
            }, 1500);
          });

    }
  })

  })
</script>



    <!-- /.control-sidebar -->

    <!-- Add the sidebar's background. This div must be placed

         immediately after the control sidebar -->

    <div class="control-sidebar-bg"></div>

  </div><!-- ./wrapper -->

  <script>
// Get the modal

var modal = document.getElementById("myModal");


// Get the button that opens the modal

var btn = document.getElementById("showInvoiceBtn");


// Get the <span> element that closes the modal

var span = document.getElementsByClassName("close")[0];


// When the user clicks the button, open the modal 

btn.onclick = function() {

  modal.style.display = "block";
}


// When the user clicks on <span> (x), close the modal

span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {

  if (event.target == modal) {

    modal.style.display = "none";

  }

}

</script>



<?php include_once "../layout/footer.php" ?>


<script src="js/table.js"></script>