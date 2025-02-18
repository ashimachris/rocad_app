<?php

if(session_status()===PHP_SESSION_NONE){

session_start();

}

$active_menu = "data_tables";

include_once "../layout/header.php";

require_once('../../db_con/config.php');

 

if(isset($_GET['f'])and (!empty($_GET['t']))){

  $f=$_GET['f'];

  $t=$_GET['t'];

  if(isset($_GET['tlt'])){

  $tlt=$_GET['tlt'];

  $tlts=" and ispaid=$tlt";

  $title="From: (".$f.") To: (".$t.")";

}
  $mysql=" and (date(uploaded_on) BETWEEN '$f' and '$t')$tlts";

}
else{

$mysql=" and (date(uploaded_on)>= date_sub(curdate(), interval 1 week))";
$title="FOR LAST 1 WEEKS";

}

//mysql_select_db($database_config, $config);

$assets = "SELECT * FROM `invoices` where `title` not in('Aggregate','Sand','Laterite','Boulder','MC1','Asphalt','Blocks','S125','Reinforcement') and reference>0 $mysql order by id desc";

$as_asset=mysqli_query($config,$assets) or die(mysqli_error($config));


$qry_total_record = mysqli_query($config, "SELECT COUNT(*) AS total_record FROM `invoices` where id is not null AND ispaid=1 $mysql");
$total_record =  mysqli_fetch_assoc($qry_total_record)['total_record'];


 ?>


 <?php

 //All - Approving expenses
if (isset($_GET['approve_all'])) {
  $ids = isset($_POST['ids']) ? $_POST['ids'] : [];

    if(count($ids) > 0){
  
        $sql_query = "UPDATE `invoices` SET ispaid=1 where `id` IN (".(implode(",", $ids)).")";
        $updated = mysqli_query($config, $sql_query);
        if($updated){
            $resp['status'] = 'success';
      
        }else{
            $resp['status'] = 'failed';
            $resp['error'] = mysqli_error($config);
        }
    }else{
        $resp['status'] = 'failed';
        $resp['error'] = 'No Expense ID(s) Data sent to delete.';
    }
  
echo json_encode($resp);
exit;
}

?>

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

  color: red;

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



    <?php include_once "../layout/topmenu.php";

  allow_access_all(1,1,0,0,1,0,$usergroup); ?>

    <?php include_once "../layout/left-sidebar.php"; ?>

    



    <!-- Content Wrapper. Contains page content -->

    <div class="content-wrapper">



        <!-- Content Header (Page header) -->

    <section class="content-header">

      <h1>

        ROCAD

        <small>Store</small>

      </h1>

      <ol class="breadcrumb">

        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>

        <li><a href="#">Administration</a></li>

        <li class="active"><a href="equipments.php">plant</a></li>

        <li class="active"><span style="cursor: pointer;" onclick="history.back()">Back</span></li>

      </ol>

    </section>



    <!-- Main content -->

    <section class="content">

      <div class="row">

        <div class="col-xs-12">

          <div class="box">

            <div class="box-header">

             <h3 class="box-title"><a href="invoices_gallery.php">Invoices Gallery</a></h3>

            <!-- /.box-header -->

            <div class="box-body">

               

              <div>



              <p class="right"><span style="color:darkred; cursor: pointer;" id="myBtn">Filter By Date</p>

                 

                  </div>

                                

               <hr>

               <center><div><h2><span style="color:green;text-decoration: overline;">Invoices</span></h2></div></center>

                <h4 <?php allow_access(1,1,0,0,1,0,$usergroup); ?>><label>Pay All:</label> <input class="form-check-input" type="checkbox" id="selectAll" ></h4>
            <div style="float: right; display:none" id="approve_div">
                <button <?php allow_access(1,1,0,0,1,0,$usergroup); ?> class="btn btn-primary btn-sm btn-flat d-none" id="approve_btn">Pay Selected Invoices</button> 
           </div>
           <br /> <br />

            <center>
                  
                  <h2 style="text-align: center" class="box-title"><font color="darkgreen"> TOTAL INVOICES PAID <?php echo $title ?></font></h2> 
                     <h3 style="text-align: center" class="box-title"><font color="darkgreen">  : <?php echo number_format($total_record,2); ?></font></h3> 
              
              </center>

              <table id="example1" class="table table-bordered table-hover w-100">

                <thead>

                <tr>

                 <th></th>
                <th>S/N</th>

                <!--<th>File Name:</th>-->

                <th>REF NO:</th>                                 

                 <th>Plant No:</th>

                 <th>Title:</th>

                 <th>Amount:</th>

                 <th>Status:</th>

                 <th>Uploaded On:</th>

                <th>Uploaded By:</th>

                <th>Preview:</th>       

                  </tr>

                </thead>

                <tbody>

                <?php $j=0;while($row_asset=mysqli_fetch_assoc($as_asset)){$j++;
                 
                  // $imageURL = $row_asset["file_name"];
               
                  $lpo=$row_asset['lpo'];
                  $ref=$row_asset['reference']; 


                  $assetL = "SELECT * FROM `storeloadingdetails` where reference='$ref'";
                   $as_assetL=mysqli_query($config,$assetL) or die(mysqli_error($config));
                  $row_assetsL=mysqli_fetch_assoc($as_assetL);
                  $invoiceL=$row_assetsL['invoice'];
                  $description=$row_assetsL['reqfor'];
                  $amount=$row_assetsL['totalvalue'];

                   
                      $imageURL = $invoiceL

                  ?>

                  <tr onmouseover="$(this).css('background', '#d4edda');" onMouseOut="$(this).css('background', '#FFF');">

                 
                  <td class="text-center">
                     <input class="form-check-input" type="checkbox" name="invoice_id[]" value="<?= $row_asset['id'] ?>">
                  </td>

                 <td><?php echo "Item ".$j; ?></td>

                 <!--<td><?php if($row_asset['file_name']){echo $row_asset['file_name'];}else{echo "N/A";} ?></td>-->

                 <td><?php echo $row_asset['lpo']; ?></td>

                  <td><?php if($row_asset['PlantNo']){echo $row_asset['PlantNo'];}else{echo "N/A";} ?></td>

                  <td>
                    <?php if($row_asset['infoD']){echo $row_asset['infoD']."--";}
                    if($row_asset['title']=='Advance Voucher'){
                     echo $description;
                    }else{
                      echo $row_asset['title'];
                    }
                   ?>
                    
                  </td>
                  <td><?php echo number_format($amount, 2) ?></td>

                  <td align="center">

    <?php

    switch($row_asset['ispaid']) {

        case 1:

            echo "<div class='dropdown'>

                    <span class='dropbtn label label-success'>PAID</span>

                  </div>";

            break;



        case 0:

            echo "<div class='dropdown'>

                    <span class='dropbtn label label-warning'>UNPAID &#11167;</span>
                    
                  </div>";

            break;

    }

    ?>

</td>

                  <td><?php echo $row_asset['uploaded_on']; ?></td>                   

                 <td><?php $prebyID=$row_asset['uploadby'];require '../layout/preby.php';echo $row_preby['fullname']; ?></td>

                    <td style="cursor:pointer">
                      <div class="dropdown">
                        <button class="dropbtn"><i class="fa fa-eye" aria-hidden="true"></i></button>
                        <div class="dropdown-content">
                              <a onClick="window.location='invoices_more.php?v=<?php echo $ref; ?>'">View</a>
                              <!-- <a onClick="window.open('<?php echo $imageURL; ?>')">Preview</a> -->
                              <a class="text-primary" href="<?php echo $imageURL; ?>" target="_blank">Preview</a>
                       
                      </div>
                    </div>
                  </td> 
                  <?php }?></i></td>

                </tbody>

                <tfoot>

                 <tr>


                <th></th>
                <th>S/N</th>

                <!--<th>File Name:</th>-->

                <th>REF NO:</th>                                 

                 <th>Plant No:</th>

                 <th>Title:</th>

                 <th>Amount:</th>

                 <th>Status:</th>

                <th>Uploaded On:</th>

                <th>Uploaded By:</th>

               <th>Preview:</th> 

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

    </div><!-- /.content-wrapper -->

    <div id="myModal" class="modal">



  <!-- Modal content -->

  <div class="modal-content">

    <span class="close">&times;</span>

    <center><form><table>

                                  <tr>  

                <th>From</th>

                <td>&nbsp;&nbsp;&nbsp;</td>

                <td><input type="date" name="f" required class="form-control"></td>                  

                  </tr>

                  <tr>

                <th>&nbsp;&nbsp;&nbsp;</th>

                <td>&nbsp;&nbsp;&nbsp;</td>

                <td>&nbsp;&nbsp;&nbsp;</td>                   

                  </tr> 

                  <tr>

                <th>To</th>

                <td>&nbsp;&nbsp;&nbsp;</td>

                <td><input type="date" name="t" required class="form-control"></td>                   

                  </tr>

                   <tr>

                    <td colspan="3">&nbsp;&nbsp;&nbsp;</td>

                  </tr>

                  <tr>

                <th>Status</th>

                <td>&nbsp;&nbsp;&nbsp;</td>

                <td><select name="tlt" class="form-control"><option value="0">Select</option><option value="1">Paid</option><option value="0">Unpaid</option></select></td>                   

                  </tr>

                  

                    <td colspan="3">&nbsp;&nbsp;&nbsp;</td>

                  </tr> 

                   <tr>

                <th><input type="hidden" name="v" value="<?php echo $ids;?>"></th>

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

    <!-- /.control-sidebar -->

    <!-- Add the sidebar's background. This div must be placed

         immediately after the control sidebar -->

    <div class="control-sidebar-bg"></div>

  </div><!-- ./wrapper -->


<?php include_once "../layout/footer.php" ?>


<script>
var checked_ids = []
var total_checks = $('input[name="invoice_id[]"]').length;
var total_checks_checked = checked_ids.length

$(document).ready(function(){
    /**
     * Store Checked Invoice ID
     */
    
  $('input[name="invoice_id[]"]').change(function(){
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
            $('input[name="invoice_id[]"]').prop('checked', true).trigger('change')
        }else{
            $('input[name="invoice_id[]"]').prop('checked', false).trigger('change')
        }
    })

  $('#approve_btn').click(function(){
      if (confirm(`Are You Sure You Want To Pay The Selected Invoice?`)) {
      $.post("invoices.php?approve_all", {ids: checked_ids}).done(function (data)
      {
        
        alert("Record updated successfully");
          setTimeout(function(){window.location='invoices.php';},4200);
      });
    }
  })

})

</script>

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
<script src="js/table.js"></script>