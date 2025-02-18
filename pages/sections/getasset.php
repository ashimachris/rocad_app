<?php
$active_menu = "data_tables";
include_once "../layout/header.php";
require_once('../../db_con/config.php');
$preby =$_SESSION['admin_rocad'];
$number = count($_POST["assetname"]);
$date=date("Y-m-d h:i:sa", time());
$error=0;
 if (isset($_POST['assetname'])) {
if($number > 0){
	for($i=0; $i<$number; $i++)
	{
		if(trim($_POST["assetname"][$i] != ''))
		{
		 
			 mysql_select_db($database_config, $config);
			$sql="insert into `assets`(assetname,partno,qty,asset_type,pre_by,status)values('".mysql_real_escape_string($_POST["assetname"][$i])."','".mysql_real_escape_string($_POST["partno"][$i])."','".mysql_real_escape_string($_POST["qty"][$i])."','".mysql_real_escape_string($_POST["cat"][$i])."','$preby',1)";
			 
			$insert=mysql_query($sql,$config) or die(mysql_error());
		}
		}
	}
 
else{
echo "<script>alert('Please Input the values!');</script>";
}
 }
?> 
<?php if($insetme==1){?>
<meta http-equiv="refresh" content="3;URL=assets.php">
<?php }?>
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

    <?php include_once "../layout/topmenu.php"; ?>
    <?php include_once "../layout/left-sidebar.php"; ?>
    

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        ROCAD
        <small>Sites</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Assets</a></li>
        <li class="active"><a href="new-site.php">New Asset</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"><?php  if($error>0){echo $error;} ?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <form name="add_name" id="add_name" >
					 
					  <table class="table table-bordered txt" id="dynamic_field" border="0" >
					    <tr>
						    <td align="right" colspan="5">  
						      						      <button type="button" name="add" id="add" class="btn btn-success">Add Items</button></td>
				        </tr>
			    </table>
                <div align="right">
                     <input type="button" name="assetsAdd" id="submit" class="btn btn-info" value="Submit"/> 
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
	//$('#submit').hide();
	var i=1;
	$('#add').click(function(){
		//$('#submit').show();
		i++;
		$('#dynamic_field').append('<tr id="row'+i+'"><td><div class="form-group"><label for="exampleInputEmail1">Asset Name: </label><input type="text" class="form-control" placeholder="Name of the Asset" name="assetname[]" id="assetname"></div></td><td><div class="form-group"><label for="exampleInputEmail1">Part Number: </label><input type="text" class="form-control" id="exampleInputEmail1" placeholder="Part Number" name="partno[]"></div></td><td><div class="form-group"><label for="exampleInputEmail1">Quantity: </label><input type="text" name="qty[]" placeholder="Quantity" class="form-control name_list" required onkeypress="return isNumber(event)"  /></div></td><td><div class="form-group"><label for="exampleInputEmail1">Asset Category: </label><select class="form-control" name="cat[]" required><option selected value="">::Select Asset Category::</option><option value="1">Material</option><option value="2">Equipment</option></select></div></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove" style="margin:20px">X</button></td></tr>');
	});
	
	$(document).on('click', '.btn_remove', function(){
		var button_id = $(this).attr("id"); 
		$('#row'+button_id+'').remove();
	});
	
	$('#submit').click(function(){
		if($('#assetname').val()!=''){	
		$.ajax({
			url:"asset.php",
			method:"POST",
			data:$('#add_name').serialize(),
			success:function(data)
			{
				//window.open('termal.php', '_new', 'toolbar=no,directories=0,scrollbars=1,resizable=no,top=0,left=100,width=980');
				$('#add_name')[0].reset();
				$('#submit').hide();
			}
		});
		}
		else{
			alert('Please Input the values!');
			return false;
			}
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
