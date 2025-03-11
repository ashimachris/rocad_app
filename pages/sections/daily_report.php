<?php
require_once('../../db_con/config.php');
  if(session_status()===PHP_SESSION_NONE){
session_start();
} 
$preby =$_SESSION['admin_rocad'];
$msg="";
$active_menu = "blank";
  include_once "../layout/header.php";
  $qry_all_asset="SELECT * FROM assets where status=1";
  $asset_all=mysqli_query($config,$qry_all_asset) or die(mysqli_error($config));

  $prebyID=$preby; require '../layout/preby.php';
   
  if(isset($_GET['f'],$_GET['t'],$_GET['p'])){
  $f=$_GET['f'];
  $t=$_GET['t'];
  $p=$_GET['p'];
  if($p=='0'){
    $psql="and id not in(0)";
  }
  else{
$psql="and plant_no='$p'";
  }
  $mysql=" and (date(time_date) BETWEEN '$f' and '$t') $psql";
  //$title=$psql;
$title="From: (".$f.") To: (".$t.")";
} 
else{
$mysql=" and (date(time_date)=curdate())"; 
$title="REAL TIME REPORTS";
}
// $qryasset="SELECT * FROM `daily_expenses_reports` where id is not null $mysql order by id desc";
$qryasset="SELECT * FROM `daily_expenses_reports` where id is not null $mysql AND status='1' order by id desc";
$assets=mysqli_query($config,$qryasset) or die(mysqli_error($config));

$qry_amount = mysqli_query($config, "SELECT SUM(amount) AS total_amount FROM `daily_expenses_reports` WHERE status = '2' $mysql");
$total_amount =  mysqli_fetch_assoc($qry_amount)['total_amount'];



$qry_pending = mysqli_query($config, "SELECT SUM(amount) AS total_amount FROM `daily_expenses_reports` where id is not null AND status='1' $mysql");
$total_pending =  mysqli_fetch_assoc($qry_pending)['total_amount'];

?>


<?php

//Approving expenses
if (isset($_GET['approve_id'])) {
	$sql_query = "UPDATE daily_expenses_reports SET status=2 WHERE id=" . $_GET['approve_id'];
	$approved = mysqli_query($config, $sql_query);
	if($approved){ 
      /*
       $mail=true;
        $sender = $row_preby['fullname'];
        require 'mail_sender.php';
        */
		$msg="<font color='green'>Expense approved successfully</font>";
   		echo "<script>setTimeout(function(){window.location='daily_report.php';},4200);</script>";
	}else{
		$msg="<font color='red'>Failed to approve the expense</font>";
		echo"Failed to approve the expenses";
	}
}

//Deleting record
if (isset($_GET['delete_id'])) {
	$sql_query = "DELETE FROM daily_expenses_reports WHERE id=" . $_GET['delete_id'];
	$deleted = mysqli_query($config, $sql_query);
	if($deleted){
		$msg="<font color='green'>Record deleted successfully</font>";
   		echo "<script>setTimeout(function(){window.location='daily_report.php';},4200);</script>";
	}else{
		echo"Failed to delete the record";
	}
}

//All - Approving expenses
if (isset($_GET['approve_all'])) {
  
	$ids = isset($_POST['ids']) ? $_POST['ids'] : [];
    $banks=$_POST['banks'];
    $comment=$_POST['comment'];
    

    if(count($ids) > 0){

//Sending mail	
$sql_query = "UPDATE `daily_expenses_reports` SET status=2,`bank_mail`='$banks', `comment_mail`='$comment' where `id` IN (".(implode(",", $ids)).")";
        $updated = mysqli_query($config, $sql_query);
        if($updated){ 
            $resp['status'] = 'success';
	    
        }else{
            $resp['status'] = 'failed';
            $resp['error'] = mysqli_error($config);
        }
            $mail=true;
            $sender = $row_preby['fullname'];
            require 'mail_sender.php';
    }else{
        $resp['status'] = 'failed';
        $resp['error'] = 'No Expense ID(s) Data sent to delete.';
    }
	
echo json_encode($resp);
exit;
}

?>


<style type="text/css">
  .treeview-menu li {
    padding-left: 15px;
  }
  .box-body {
    min-height: 500px;
  }
</style>
<style type="text/css">
  .center {
  text-align: center;
  border: 2px solid green;
  margin-bottom: 20px;
}
.right{

  text-align: right;
   
  margin:0;
}
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
  z-index: 10;
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
  background-color: #d4edda;
}

</style>

<body class="hold-transition skin-blue sidebar-mini">
  <!-- Put Page-level css and javascript libraries here -->
   <!-- DataTables -->

  <link rel="stylesheet" href="../../plugins/datatables/dataTables.bootstrap.css">

  <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>

  <script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>
  <!-- ================================================ -->
	

  <div class="wrapper">

    <?php include_once "../layout/topmenu.php"; allow_access_all(1,1,0,0,1,0,$usergroup); ?> 
    <?php include_once "../layout/left-sidebar.php"; ?>
    

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
    <section class="content-header">
      <h3>
        Daily Expense Reports
        <!-- <button style="width: 100px; height: 30px; font-size: 12px; border-radius: 10px; padding: 10px 20px; background-color: #3498db; color: #fff; border: none;" class="download">Download</button>
        <button style="width: 70px; height: 30px; font-size: 12px; border-radius: 10px; padding: 10px 20px; background-color: #3498db; color: #fff; border: none;" class="email">Email</button> -->
      </h3>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Daily Expense Reports</li>
      </ol>
    </section>
 
    <!-- Main content -->
    <section class="content">
	
      <!-- Default box -->
      <div class="box">
	     <center><h3 class="box-title"><?php  echo $msg; ?></h3></center>
        <div class="box-header">
                 <h3 <?php allow_access(1,1,0,0,0,0,$usergroup); ?> class="box-title" style="float: right;cursor: pointer;"><font color="darkred" id="myBtn">Filter By Date</font></h3> 
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <button onclick="window.location='/rocad_admin/pages/sections/daily_pending.php'" style="width: 100px; height: 30px; font-size: 12px; border-radius: 10px; margin: 10px; padding: 10px 20px; background-color: #3498db; color: #fff; border: none; float:right; display: flex; justify-content: center; align-items: center;"
                class="pending"> All pending</button>
                                                                                          
                <button onclick="window.location='/rocad_admin/pages/sections/daily_approve.php'" style="width: 120px; height: 30px; font-size: 12px; border-radius: 10px; margin: 10px; padding: 10px 20px; background-color: #3498db; color: #fff; border: none; float:right; display: flex; justify-content: center; align-items: center;"
                class="pending"> All approved</button>

              <div>
              <h2 style="text-align: center" class="box-title"><font color="darkgreen"> Total Approved : <?php echo number_format($total_amount, 2); ?>
              </h2> 
              <h2 style="text-align: center" class="box-title">  <font color="darkgreen"> Total Pending : <?php echo number_format($total_pending,2); ?>
              </h2>
               
              <center><h2 style="text-align: center" class="box-title"><div id="status" >
                  
              </div></h2></center>
            </div>

        	   <h4 <?php allow_access(1,0,0,0,0,0,$usergroup); ?>><label>Approve All:</label> <input class="form-check-input" type="checkbox" id="selectAll" ></h4>
            <div style="float: right; display:none" id="approve_div">
        		    <button <?php allow_access(1,0,0,0,0,0,$usergroup); ?> class="btn btn-primary btn-sm btn-flat d-none" id="approve_btn">Approve Selected Expenses(s)</button> 
           </div>
           
           <br /> <br />
           <div class="table-responsive">
           <table id="example1" class="table table-bordered table-hover w-100">
                <thead>
                  
                <tr>
		                <th></th>
                    <th>S/N:</th>
                    <th>PLANT/CODE:</th>
                    <th>REF NO:</th>
                    <th>SITE:</th>
                    <th>DESC:</th>
                    <th>AMOUNT:</th>
                    <th>SUPPLIER/ACCT NAME:</th>
                    <th>BANK:</th>
                    <th>ACCT NO:</th>
                    <th> DATE:</th>
                    <th>PREPARED BY:</th>
                    <th>SIGN BY:</th>
                    <th>INVOICE:</th>
                    <th>ACTION</th>
                </tr>
                
                </thead>
                <tbody>
                   <?php $j=0;while($row_assets=mysqli_fetch_assoc($assets)){$j++; ?>
                    <?php 
                      $reference = $row_assets['reference'];

                      $qry_store = mysqli_query($config, "SELECT * FROM `storeloadingdetails` where reference='$reference'");
                      $r_store =  mysqli_fetch_assoc($qry_store);
                      $supplier = $r_store['supl'];
                      $pay_to = $r_store['pay_to'];
                      $bank_name = $r_store['bank_name'];

                    ?>
                    <!-- when approved colour changes to dark red and sets record to disabled -->
                <tr style="text-transform: uppercase;<?php if($row_assets['status']!=1){?>color:darkred <?php }?>">
		              <td class="text-center">
                     <input class="form-check-input" type="checkbox" name="expense_id[]" value="<?= $row_assets['id'] ?>" id="List1" <?php if($row_assets['status']!=1){?>checked disabled <?php }?> >
                  </td>
                  <td> <?php echo $j; ?></td>
                  <td>	
                		<?php
                		  if($row_assets['plant_no']=='0'){
                		  echo "Nil";
                		  }else{
                		  	echo $row_assets['plant_no']; 
                		   }?> 
                	</td>
                  <td><?php echo $row_assets['reference']; ?></td>
                  <td>
                    <?php $siteID=$row_assets['fromsite'];require '../layout/site.php'; echo $row_site['site_state']."-".$row_site['site_lga']."-".$row_site['site_loc']  ?>
                    
                  </td>
                  <td><?php echo $row_assets['description']; ?></td>
                  <td><?php echo number_format($row_assets['amount'],2); ?></td>
                   <td><?php echo $supplier ?></td>
                   <td><?php echo $bank_name ?></td>
                  <td><?php echo $pay_to ?></td>
                  <td><?php echo $row_assets['time_date']; ?></td>
                  <!-- <td><?php $prebyID=$row_assets['preby'];require '../layout/preby.php';echo $row_preby['fullname']; ?></td> -->
                  <!--<td><?php if(!empty($row_assets['authby'])){$authbyID=$row_assets['authby'];}else{$authbyID=0;echo "<span class='label label-danger'>Waiting...</span>";}require '../layout/authby.php'; echo $row_authby['fullname']; ?></td> -->

                  <td><?php if(!empty($row_assets['authby'])){$authbyID=$row_assets['authby'];}else{$authbyID=0;echo "<span class='label label-danger'>Waiting...</span>";}require '../layout/authby.php';echo $row_preby['fullname']; ?></td>
                  
              		 <!-- <td>
              			<?php
              			switch ($row_assets['status']){
              				case 1:
              				echo '<span class="rounded-pill badge badge-dark bg-gradient-dark px-3 text-light">Not Approve</span>';	break;
              				case 2:
              				echo '<span class="rounded-pill badge text-primary bg-gradient-primary px-3"  style="background:blue">Approved</span>';
              				break;
              				}
              				?>
              		 </td> -->
                    <td><?php echo $row_assets['sign_by']; ?></td>

                    <td>
                     <!-- <?php 
                      $invoice_link = "uploads/".$row_assets['reference'].".jpg";
                        if(!empty($row_assets['invoice'])){ 
                          $invoice_link = $row_assets['invoice'];
                        }
                        ?>
                      <center> -->
                        <?php 
                            // Default link with .jpg extension
                            $invoice_link = "uploads/" . $row_assets['reference'] . ".jpg";

                            // Check if the .jpeg version exists
                            $jpeg_link = "uploads/" . $row_assets['reference'] . ".jpeg";
                            if (file_exists($jpeg_link)) {
                                $invoice_link = $jpeg_link;
                            }

                            // Override with a specific invoice link if set
                            if (!empty($row_assets['invoice'])) { 
                                $invoice_link = $row_assets['invoice'];
                            }
                        ?>

                      <!-- <a class="text-primary" href="<?php echo $row_assets['invoice']; ?>" target="_blank"> -->
                      <a class="text-primary" href="<?php echo $invoice_link; ?>" target="_blank">
                        <i class="fa fa-download" aria-hidden="true"></i>
                      </a>
                      </center>
                    </td>
                   
                    <td>
                      <div class="dropdown">
                  		  <button class="dropbtn"><i class="fa fa-eye" aria-hidden="true" <?php allow_access(1,1,1,0,0,0,$usergroup); ?>></i></button>
                     		<div class="dropdown-content">
                           
                     		<?php 
                          $id = $row_assets['id'];
                          if($row_assets['status']!=2){ 
                            echo "
                             <a href='daily_expense_edit.php?edit_id=$id'>Edit</a>
                             <a href='javascript:delete_id($id)'>Delete</a>
                            ";
                          }else{
                            echo "<h5>Approved</h5>";
                          }
                        ?>
                  		</div>
                		</div>
              		</td> 
       
                 </tr>
                  <?php }?>
              
                </tbody>
                <tfoot>
                <tr>
		                <th></th>
                    <th>S/N:</th>
                    <th>PLANT/CODE:</th>
                    <th>REF N0:</th>
                    <th>SITE:</th>
                    <th>DESC:</th>
                    <th>AMOUNT:</th>
                    <th>SUPPLIER/ACCT NAME:</th>
                    <th>BANK:</th>
                    <th>ACCT NO:</th>
                    <th> DATE:</th>
                    <th>PREPARED BY:</th>
                    <th>SIGNED BY:</th>
                    <th>INVOICE:</th>
                    <th>ACTION</th>
                </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
         
        <!-- /.box-body -->
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
    </div><!-- /.content-wrapper -->
    <div id="myModal" class="modal">
 
  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <center><form><table>
                                  <tr>  
                <th>From:</th>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td><input type="date" name="f" required class="form-control"></td>                  
                  </tr>
                  <tr>
                <th>&nbsp;&nbsp;&nbsp;</th>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td>&nbsp;&nbsp;&nbsp;</td>                   
                  </tr> 
                  <tr>
                <th>To:</th>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td><input type="date" name="t" required class="form-control"></td>                   
                  </tr>
                   <tr>
                    <td colspan="3">&nbsp;&nbsp;&nbsp;</td>
                  </tr> 
                  <tr>
                <th>Plant No.</th>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td><select name="p" class="form-control" required><option value="" selected>::Select Plant No::</option><option value="0">ALL</option><?php while($row_asset=mysqli_fetch_assoc($asset_all)){?><option value="<?php echo $row_asset['sortno']; ?>"><?php echo $row_asset['sortno']; ?></option><?php }?></select></td>                   
                  </tr>
                   <tr>
                    <td colspan="3">&nbsp;&nbsp;&nbsp;</td>
                  </tr>  <tr>
                <th><input type="hidden" name="v" value="<?php echo $ids;?>"></th>
                <td>&nbsp;&nbsp;&nbsp;</td>
                <td align="center"><button>Filter Record</button></td>
                                   
                  </tr>                
              </table>
              </form>
              </center>
  </div>

</div>

<!--///////////////////////////////////////////// -->
<!-- Modal -->

<div id="modalInvoice" class="modal" role="dialog">

  <div class="modal-dialog">



    <!-- Modal content-->

    <div class="modal-content">

      <div class="modal-header">

        <button type="button" class="close" id="close2">&times;</button>

        <h4 class="modal-title"><center>Daily Expense Reports</center></h4>

      </div>

      <div class="modal-body">

        <center class="row">

            <div class="navbar-form navbar-center" method="post">

              <div class="form-group full-center">

               <select name="banks" id="banks" required> 
 
<option>Access Bank Plc</option>
<option>Fidelity Bank Plc</option>
<option>First Bank of Nigeria Limited</option>
<option>First City Monument Bank Limited</option>
<option>Guaranty Trust Holding Company Plc</option>
<option>Keystone Bank Limited</option>
<option>Polaris Bank Limited</option>
<option>Sterling Bank Plc</option>
<option>Union/Titan Trust Bank Limited</option>
<option>United Bank for Africa Plc</option>
<option>Zenith Bank Plc</option>
</select>
<br><br>
  <textarea placeholder="Enter Comment" name="comment" id="comment"></textarea>
<br><br>
         
 
 
              <button type="submit" onclick="submitForm2()" class="btn btn-default" id="approve_btn2">Approve</button>

            </div>

        </center>

      </div>

       

    </div>



  </div>

</div>


<!--///////////////////////////////////////////////// -->
    <?php include_once "../layout/copyright.php"; ?>
    
    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
  </div><!-- ./wrapper -->

<?php include_once "../layout/footer.php" ?>
<script>
	function delete_id(id) {
		if (confirm(`Are You Sure You Want To Remove This Record?`)) {
			window.location.href = 'daily_report.php?delete_id=' + id;
		}
	}
	function approve_id(id) {
		if (confirm(`Are You Sure You Want To Approve This Expense?`)) {
			window.location.href = 'daily_report.php?approve_id=' + id;
		}
	}


</script>

<script>
var checked_ids = []
var total_checks = $('input[name="expense_id[]"]').length;
var total_checks_checked = checked_ids.length

$(document).ready(function(){
    /**
     * Store Checked Expense ID
     */
    
    $('input[name="expense_id[]"]').change(function(){
	$('#approve_div').show();
        var id = $(this).val()
        if($(this).is(':checked') === true){
            if(($.inArray(id, checked_ids)) < 0)
            checked_ids.push(id)
        }else{
            checked_ids = checked_ids.filter(e => e != id)
        }
        total_checks_checked = checked_ids.length
        if(total_checks_checked == total_checks){
            $('#selectAll').prop('checked',true)
        }else{
            $('#selectAll').prop('checked',false)
        }
        
	if(total_checks_checked > 0){
            $('#approve_div').show();
        }else{
            $('#approve_div').hide();
        }
    })

    /**
     * Select All Function
     */

    $('#selectAll').change(function(e){
        e.preventDefault()
        var _this = $(this)
        if(_this.is(':checked') === true){
            $('input[name="expense_id[]"]').prop('checked', true).trigger('change')
        }else{
            $('input[name="expense_id[]"]').prop('checked', false).trigger('change')
        }
    })

	$('#approve_btn2').click(function(){
              if (confirm(`Are You Sure You Want To Approve The Selected Expenses?`)) {
                  var banks = $('#banks').val();
                  var comment = $('#comment').val();
                   
$.post("daily_report.php?approve_all", {ids: checked_ids,banks:banks, comment:comment}).done(function (data)
			{
				
				alert("Record updated successfully");
   				setTimeout(function(){window.location='daily_report.php';},4200);
			});
		}
        })

})
 </script>

<script src="blank/script.js"></script>
<script>
// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

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
<script>
// Get the modal
var modal2 = document.getElementById("modalInvoice");

// Get the button that opens the modal
var btn2 = document.getElementById("approve_btn");

// Get the <span> element that closes the modal
var span2 = document.getElementById("close2");

// When the user clicks the button, open the modal 
btn2.onclick = function() {
  modal2.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span2.onclick = function() {
  modal2.style.display = "none";
   }

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal2) {
    modal2.style.display = "none";
  }
}
</script>
<script src="js/table.js"></script>

<script type="text/javascript">
 //////////////////////////
              
       $("input[name='expense_id[]']").change(function(){
            var favorite = [];
            $.each($("input[name='expense_id[]']:checked"), function(){            
                favorite.push($(this).val());
                
            });
           //alert("My favourite sports are: " + favorite.join("+"));
            var value1 = favorite.join(",");
          $.ajax({

                    url: "actions/cal_daily_exp.php",

                    method: "post",

                    data: {displayAmount:1, value1:value1},



                    beforeSend: function(){

                        $('#status').html('Please wait...');

                    },

                    success: function(data){

                        $("#status").html(data);

                    }



                })
 
            }); 
              </script>
