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
$site=$row_assets["site"];
$tenDgt = rand(1000000000,9999999999);
$description=$_POST["description"];
$number = count((is_countable($description)?$description:[]));
if(isset($_POST["sbt"])){
 
  for($i=0; $i<$number; $i++)
  {
    if(trim($description[$i] != ''))
    {
 
      $sql="insert into `daily_progress`( `site`, `description`, `quantity_used`,time_date, `preby`, `reference`,category,units)
          values('".mysqli_real_escape_string($config,$_POST["site"])."','".mysqli_real_escape_string($config,$_POST["description"][$i])."','".mysqli_real_escape_string($config,$_POST["quantity_used"][$i])."','$timeDate','$preby','$tenDgt','".mysqli_real_escape_string($config,$_POST["category"][$i])."','".mysqli_real_escape_string($config,$_POST["units"][$i])."')";
       
      $insert=mysqli_query($config,$sql) or die(mysqli_error($config));
    
    if($insert==1){
      $msg="<font color='green'>Data successfully Saved.</font>";
   echo    "<script>setTimeout(function(){window.location='progress_report.php';},4200);</script>";
  }
   
}}
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
              <h3 class="box-title"><a href="#"><a href="daily_report.php"><?php echo "Work Progress" ?></a></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <h3 class="box-title"><?php  echo $msg; ?></h3>
              <div class="form-group">
                
                  <img src="pace/work.png" style="height:25%; padding-top:50px;" class="hidden-mobile">
               
                <div class="form-wrapper">
                  
                  <form name="add_name" id="add_name" action="" method="post"  class="form-style-9"><center><div id="msg" style="color:red"></div></center>           

<ul>
 
<li>
   
   
              <label for="">Time & Date:</label>
              <input type="text" class="form-control" required value="<?php echo $timeDate; ?>"  name="make" disabled>
              </li>
             <li>
   
    
             
              <label for="">Site:</label>
               <select class="form-control" id="site" required name="site">
                        
                          <option value="" selected>Select Location(Site)</option>
                          <?php while($row_site=mysqli_fetch_assoc($as_site)){?>
                          <option value="<?php echo $row_site['id']; ?>"><?php echo $row_site['site_state']."---".$row_site['site_lga']."---".$row_site['site_loc']; ?></option>
                          <?php }?>
                        </select>
            </li>
                    </ul>
        <hr><h5 class="box-title"><span style="color:#337ab7;"><?php echo "DETAILS" ?></span></h5>
        <table class="table table-bordered txt" id="dynamic_field" border="0" >
               
          </table>
                <div>
                     <button type="button" name="add" id="add" class="btn btn-success">Create Report</button><hr><hr>
                     <div align="right"> 
                     <input type="submit" name="sbt" id="submit" class="btn btn-info" value="Submit"/>
                     </div> 
                     </div> 
</form>
</div>
</div>
<script>
                                     
          

$(document).ready(function(){
  $('#submit').hide();
  var i=1;
  $('#add').click(function(){
    $('#submit').show();
    i++;
     
    $('#dynamic_field').append('<ul class="row'+i+'"><label for="" name="remove" id="'+i+'" class="btn_remove" style="float:right;color:red;cursor:pointer">X</label><hr><li><div class="form-group"> <label for="">Category:</label><select name="category[]" required class="form-control" id="categoryId"><option value="" selected>::Select Category::</option><option>Aggregate</option><option id="btn2">Laterite</option><option>Sand</option><option>Boulder</option><option>MC1</option><option>Asphalt</option><option>Blocks</option><option>S125</option><option>Reinforcement</option><option>Cement</option></select></div></li><li><div class="categoryResponse"></div></li><li> <label for="">Unit:</label><select name="units[]" required class="form-control"><option value="" selected>::Select Unit::</option><option title="Pieces">Pc</option><option title="Cubic Meter">&#13221;</option><option title="Ton">Tn</option><option title="Liter">LTR</option><option title="8 Milemeter">8mm</option><option title="10 Milemeter">10mm</option><option title="12 Milemeter">12mm</option><option title="16 Milemeter">16mm</option><option title="bag">Bags</option></select></li><li><label for="">Description:</label><textarea class="form-control" name="description[]" required></textarea></li><li><label for="">Qty Used</label><input class="form-control" name="quantity_used[]" type="number" min="0" step="0.01" value="0" /><ul>');
  });
          
  $(document).on('click', '.btn_remove', function(){
    var button_id = $(this).attr("id"); 
    $('.row'+button_id+'').remove();
  });
   
  });
 $(document).ready(function(){
    $(".input").keyup(function(){
          var val1 = +$("#amount").val();
          var val2 = +$("#qty").val();
          $("#ttl").val(val1+val2);
    });

});

 //Selecting category
     $(document).on('change', '#categoryId', function(){
        var category = $(this).val();
        console.log(category)
        $.ajax({
            url: 'ajax/category_request.php',
            type: 'post',
            data: {
                category: category
            },
            dataType: 'json',
            success: function(response) {
               
                let result = response.result;
                $(".categoryResponse").html(`<h4><i>The available quantity for ${category} is ${result}</i></h4`);
            }
        });
    });
</script>
<script language="javascript">
function validate(){
var from=document.getElementById('fromsite').value;
var to=document.getElementById('tosite').value;
if(from==to){
  document.getElementById('msg').innerHTML="ERROR: 'Site and Site Location cant be equel!";
  $('#add').hide();
   
  return false;
}
else{
$('#add').show();
 document.getElementById('msg').innerHTML="";
  return true;
}
}
</script>
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