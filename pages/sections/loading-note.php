<?php
if(session_status()===PHP_SESSION_NONE){
session_start();
}
$active_menu = "data_tables";
include_once "../layout/header.php";
$insert=0;
$msg="";
$mail=false;
require_once('../../db_con/config.php');
if(isset($_POST["descrip"])and (!empty($_POST["descrip"]))){
$preby =$_SESSION['admin_rocad'];
$number = count($_POST["descrip"]);
$date=date("Y-m-d h:i:sa", time());
$num = str_pad(mt_rand(1,99999999),10,'0',STR_PAD_LEFT);
//$_SESSION['reference']=$num;
 if (isset($_POST['assetsAdd'])) {
 
	for($i=0; $i<$number; $i++)
	{
		if(trim($_POST["descrip"][$i] != ''))
		{		 
			 //mysql_select_db($database_config, $config);
			$sql="insert into `storeloadingdetails`(descrip,partno,unit,qty,unitprice,totalvalue,conditions,preby,fromsite,tosite,method,reference,status,note,PlantNo,title)values('".mysqli_real_escape_string($config,$_POST["descrip"][$i])."','".mysqli_real_escape_string($config,$_POST["partno"][$i])."','".mysqli_real_escape_string($config,$_POST["unit"][$i])."','".mysqli_real_escape_string($config,$_POST["qty"][$i])."','".mysqli_real_escape_string($config,$_POST["unitprice"][$i])."','".mysqli_real_escape_string($config,$_POST["ttlv"][$i])."','".mysqli_real_escape_string($config,$_POST["conditions"][$i])."','$preby','".mysqli_real_escape_string($config,$_POST["from"])."','".mysqli_real_escape_string($config,$_POST["to"])."','".mysqli_real_escape_string($config,$_POST["method"])."','$num',0,'Store Loading Note','".mysqli_real_escape_string($config,$_POST["plantno"][$i])."','Loading Note')";
			 
			$insert=mysqli_query($config,$sql) or die(mysqli_error($config));
		$siteID=$_POST["from"];require '../layout/site.php';
    $froms=$row_site['site_state']."---".$row_site['site_lga']."---".$row_site['site_loc'];
		if($insert==1){
      $mail=true;
	$msg="<font color='green'>Request sent successfully.<br> <small>Please wait for Approval</small></font>"; 
   echo "<script>setTimeout(function(){window.location='loading-note.php';},7200);</script>";
  }
	}
	else{
		 
echo "<script>alert('Please Input the values!');</script>";
 
 }
	 
}}}
//mysql_select_db($database_config, $config);
$site = "SELECT * FROM `rocad_site` where sitename!='' and status=1 order by sitename Asc";
$as_site=mysqli_query($config,$site) or die(mysqli_error($config));
$as_site2=mysqli_query($config,$site) or die(mysqli_error($config));
//$row_admin=mysql_fetch_assoc($as_admin);
//mysql_select_db($database_config, $config);
$qryassets="SELECT * FROM assets where status=1";
$assets=mysqli_query($config,$qryassets) or die(mysqli_error($config));
$qryasset="SELECT * FROM assets where status=1 order by sortno";
$asset=mysqli_query($config,$qryasset) or die(mysqli_error($config));
if($mail){
   $prebyID=$preby;require '../layout/preby.php';
  $siteID=$_POST["to"];require '../layout/site.php';
  $tos=$row_site['site_state']."---".$row_site['site_lga']."---".$row_site['site_loc'];
  $msgT="Prepared By:".$row_preby['fullname']."\nFrom:".$froms."\nTo:".$tos."\nTime & Date:".$date."\nStatus: Pending."."\nLogin to website:\nhttps://app.rocad.com";
  $msgMail = wordwrap($msgT,70);

// send email
mail("deleakintayo@rocad.com,ronaldo@rocad.com,umar@rocad.com","Loading Note (".($row_preby['fullname']).")",$msgMail);

}
?> 
 
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
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
 
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <!-- ================================================ -->

  <div class="wrapper">

    <?php include_once "../layout/topmenu.php";
	 allow_access_all(1,0,0,1,0,0,$usergroup); ?>
    <?php include_once "../layout/left-sidebar.php"; ?>
    

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
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"><?php  echo $msg; ?></h3>
             
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <form name="add_name" id="add_name" action="" method="post" onSubmit="return validate()" >
                <div style="width: 100%; min-height: 500px; overflow: auto; padding: 20px;">
              <div class="row">
                <div class="col-2">
                  <center>
                    <img src="/rocad_admin/images/icon.png" width="120px">
                    <br>
                    <b>RC No. 456130</b>
                  </center>
                </div>
                <div class="col-8 hidden-mobile">
                  <center>
                    <h1 style="margin:0"><b><i>ROCAD CONSTRUCTION LIMITED</i></b></h1>
                    <h4 style="margin:0">No. 4 Audu Bako Way, P.O.Box 247, Kano - Nigeria</h4>
                    <h4 style="margin:0">Tel: 080 80 90 09 08 Fax: (064) 647040</h4>
                  </center>
                </div>
                  <div class="col-2">
                  </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <center><h3><b>STORES LOADING</b></h3></center>
                </div>
              </div>
					 <table class="normal-table table-bordered" width="70%" border="0" style="background-color:#ecf0f5;">
                    <tr >
                      <td>
                        <b>From: </b>
                        <select class="form-control" style="width: 60%; float: right;" id="from" required name="from">
                        
                          <option value="" selected>Select Location(Site)</option>
                          <?php while($row_site=mysqli_fetch_assoc($as_site)){?>
                          <option value="<?php echo $row_site['id']; ?>"><?php echo $row_site['site_state']."---".$row_site['site_lga']."---".$row_site['site_loc']; ?></option>
                          <?php }?>
                        </select>
                      </td>
                    </tr>
                    <tr >
                      <td>
                        <b>To: </b>
                        <select class="form-control" style="width: 60%; float: right;" id="to" onChange="return validate()" required name="to">
                          <option value="" selected>Select Location(Site)</option>
                          <?php while($row_siteto=mysqli_fetch_assoc($as_site2)){?>
                          <option value="<?php echo $row_siteto['id']; ?>"><?php echo $row_siteto['site_state']."---".$row_siteto['site_lga']."---".$row_siteto['site_loc']; ?></option>
                          <?php }?>
                        </select>
                      </td>
                    </tr> 
                    <tr >
                      <td>
                        <b>Method of Despatch: </b>
                        <input type="text" name="method" style="width: 60%; float: right;" required autocomplete="off">
                      </td>
                    </tr>
                  </table>
                  <div id="msg" style="color:red"></div>
					  <table class="table table-bordered txt" id="dynamic_field" border="0" >
                      <tr>
                      <td align="right" colspan="7" >  
						      						      <button type="button" name="add" id="add" class="btn btn-success" onClick="return validate()">Add Items</button></td>
				        </tr>
			    </table>
                <div align="right">
                     <input type="submit" name="assetsAdd" id="submit" class="btn btn-info" value="Submit" onClick="return validate()"/> 
                     </div> 
				</form>
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
<script>

$(document).ready(function(){
	$('#submit').hide();
	var i=1;
	$('#add').click(function(){
		$('#submit').show();
		i++;
		$('#dynamic_field').append('<tr id="row'+i+'"><td><div class="form-group"><label for="exampleInputEmail1">Description: </label><input type="text" name="descrip[]" placeholder="Description" class="form-control"></div></td><td><div class="form-group"><label for="exampleInputEmail1">Plant No: </label><select name="plantno[]" required class="form-control"><option value="" selected>::Select Plant No::</option><option value="">N/A</option><?php while($row_asset=mysqli_fetch_assoc($asset)){?><option value="<?php echo $row_asset['sortno']; ?>"><?php echo $row_asset['sortno']; ?></option><?php }?></select></div></td><td><div class="form-group"><label for="exampleInputEmail1">Part N.: </label><input type="text" class="form-control" id="exampleInputEmail1" placeholder="Part Number" name="partno[]" required></div></td><td><div class="form-group"><label for="exampleInputEmail1">Unit: </label><input type="Number" min="0" name="unit[]" placeholder="Unit" class="form-control name_list" required onkeypress="return isNumber(event)"  /></div></td><td><div class="form-group"><label for="exampleInputEmail1">Quantity: </label><input type="Number" min="0" name="qty[]" placeholder="Quantity" class="form-control name_list" required onkeypress="return isNumber(event)"  /></div></td><td><div class="form-group"><label for="exampleInputEmail1">Unit Price: </label><input type="Number" min="0" name="unitprice[]" placeholder="Unit Price" class="form-control name_list" required onkeypress="return isNumber(event)"  /></div></td><td><div class="form-group"><label for="exampleInputEmail1">Value</label><input type="Number" min="0" name="ttlv[]" placeholder="Total Value" class="form-control name_list" required onkeypress="return isNumber(event)" /></div></td><td><div class="form-group"><label for="exampleInputEmail1">Condition: &nbsp;&nbsp;&nbsp;&nbsp;<label for="" name="remove" id="'+i+'" class="btn_remove" style="float:right;color:red;cursor:pointer">X</label></label><select class="form-control" name="conditions[]" required><option selected value="">::Select Asset Category::</option><option>New</option><option>Old</option></select></div></td></tr>');
	});
	
	$(document).on('click', '.btn_remove', function(){
		var button_id = $(this).attr("id"); 
		$('#row'+button_id+'').remove();
		 
	});
	 
	});
 
</script>
<script>
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        alert('Only Number is Allow');
		return false;
		
    }
    return true;
	}
	 
    </script>

<?php include_once "../layout/footer.php" ?>
<script src="js/table.js"></script>
<script language="javascript">
function validate(){
var from=document.getElementById('from').value;
var to=document.getElementById('to').value;
if(from==to){
	document.getElementById('msg').innerHTML="Source and Destination can not be the same!";
	$('#add').hide();
	$('#submit').hide();
	 
	return false;
}
else{
	
$('#add').show();
$('#submit').show();
 document.getElementById('msg').innerHTML="";
	return true;
}
}
</script>