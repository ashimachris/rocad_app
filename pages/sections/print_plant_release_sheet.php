<?php
if(session_status()===PHP_SESSION_NONE){
session_start();
}
  $active_menu = "data_tables";
  include_once "../layout/header.php";
 require_once('../../db_con/config.php');
  
$preby =$_SESSION['admin_rocad'];
$msg="";
$timeDate=date('Y-m-d H:i:s');
$tenDgt = rand(1000000000,9999999999);
$assetname=$_POST["qty"];
$num = count($assetname);
if(isset($_GET["v"])and (!empty($_GET["v"]))){
  $reference=$_GET["v"];
}
  else{
  echo'<script>location.href="/rocad_admin/pages/dashboard/";</script>';
}
 
$notiqry1="SELECT * FROM `plant_release_sheet` WHERE reference in($reference)";
$as_assets=mysqli_query($config,$notiqry1) or die(mysqli_error($config));
$checkassets = mysqli_num_rows($as_assets);
$row2=mysqli_fetch_assoc($as_assets);
//for preview
 
?>
<?php if(($row2['status']==0)or($row2['status']==1)){?>
<script>
  document.onkeydown = function (e) {
        return false;
}
$(document).bind("contextmenu", function(e) {
    return false;
});
</script>
<?php }?>
<style type="text/css">
input{
  text-transform: uppercase;
}
</style>
<body class="hold-transition skin-blue sidebar-mini">
  <!-- Put Page-level css and javascript libraries here -->

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
       <?php if(($row2['status']==2)or($row2['status']==4)){?><h3 class="box-title" <?php allow_access(1,1,0,0,0,0,$usergroup); ?>><a class="pr" href="#" onclick="$('.main-footer').hide();$('.pr').hide();javascript:window.print();">PRINT</a></h3><?php }?>
      </h1>
       
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">           
             
            <!-- /.box-header -->
            <div class="box-body">
               
              <div class="form-group">                               
            
                  <div style="padding:15px 15px;box-shadow: 1px 1px 25px rgb(0 0 0 / 35%);border-radius: 10px;background: #FAFAFA;margin:-40px auto;<?php if(($row2['status']==0)or($row2['status']==1)){?>background-image:url(pace/notap.png); background-repeat:no-repeat; background-position:center;<?php }?>">
                  <center>
                  <table border="1" rules="rows" width="100%">
                    <tr>
                      <td><img src="/rocad_admin/images/icon.png" width="120px"> </td>
                      <td >
                    <center>
                    <h2 style="margin:0"><b>ROCAD CONSTRUCTION LIMITED</b></h2>
                    <h4 style="margin:0">BUILDING AND CIVIL ENGINEERING CONTRACTORS</h4>
                    <h5 style="margin:0">No. 50 sokoto Road, Tarauni GRA, Kano P.O Box 247, Kano Nigeria</h5><div><h3><b>PLANT RELEASE SHEET</b></h3></div>
                  </center></td>
                   

                     </tr>
                   </table>
                   <table border="1" rules="all" width="100%">
                    <tr valign="top">
                     <td align="center"><b>PLANT NO.:</b></td>
                     <td align="center"><b><?php echo $row2['PlantNo']; ?></b></td>
                <td align="center"><b>SHEET NO: <?php echo $row2['reference']; ?></b></td>
              <td align="center"><b>DATE:<?php echo $row2['time_date']; ?></b></td></tr>
                      <tr>
                       <td align="right" >&nbsp;</td>
                       <td align="center"><b>&nbsp;</b></td>
                       <td align="center"><b>&nbsp;</b></td>
                       <td align="center" ><b>SELF OR LOW BED S.NO.:(<?php echo $row2['Self_low'];?>)</b></td>
                       
                     </tr>
                  </table> 
                   <table border="2" width="100%" rules="all" style="text-transform: uppercase;font-weight: bold;">
                    <tr style='font-weight: bold;'>
                    <td align="center">PLACE OF DISCHARGE:</td>
                     <td colspan="3"><?php $siteID=$row2['fromsite'];require '../layout/site.php'; echo $row_site['site_state']."-".$row_site['site_lga']."-".$row_site['site_loc']  ?></td>                  
                  </tr>                    
                   <tr>
                        <td align="center">PLACE OF ARRIVAL:</td>
                        <td colspan="3"><?php $siteID=$row2['tosite'];require '../layout/site.php'; echo $row_site['site_state']."-".$row_site['site_lga']."-".$row_site['site_loc']  ?></td>
                         </tr>                     
                    </table>
                    <p><div><b>PRELIMINARY CHECK LIST</b></div></p>

                    <table border="2" width="100%" rules="all" style="text-transform: uppercase;font-weight: bold;">
                    <tr>
                    <td width="25%">BATTERIES:</td>
                    <td align="left" width="10%"><?php echo $row2['batt'];?></td>
                    <td align="center">REMARK:</td>
                    <td colspan="2" width="50%"><?php echo $row2['bremark'];?></td>
                  </tr>
                  <tr>
                    <td>JACK:</td>
                    <td><?php echo $row2['jack'];?></td>
                    <td align="center">REMARK:</td>
                    <td colspan="2"><?php echo $row2['Jremark'];?></td>
                  </tr>
                  <tr>
                    <td>FIRE EXTIN:</td>
                    <td><?php echo $row2['fire_ex'];?></td>
                    <td align="center">REMARK:</td>
                    <td colspan="2"><?php echo $row2['Fremark'];?></td>
                  </tr>
                  <tr>
                    <td>SPARE TYRE:</td>
                    <td><?php echo $row2['spare_ty'];?></td>
                    <td align="center">REMARK:</td>
                    <td colspan="2"><?php echo $row2['Sremark'];?></td>
                  </tr>
                  <tr>
                    <td>DIESEL OR PERTOL:</td>
                    <td><?php echo $row2['diesel_p'];?></td>
                    <td align="center">REMARK:</td>
                    <td colspan="2"><?php echo $row2['Dremark'];?></td>
                  </tr>
                  <tr>
                    <td>HYDRAULIC OIL:</td>
                    <td><?php echo $row2['hydro'];?></td>
                    <td align="center">REMARK:</td>
                    <td colspan="2"><?php echo $row2['Hremark'];?></td>
                  </tr>
                  <tr>
                  <td colspan="4">&nbsp;</td>
                
                </tr>
                  <tr>
                  <td colspan="4">DRIVER/PLANT OPTR__________________ SECURITY CHECKER______________________</td>
                
                </tr>
                    </table>
                     <p><div><b>MECHANICAL DISCHARGE</b></div></p>
                 <table border="2" width="100%" rules="all" style="text-transform: uppercase;font-weight: bold;">
                    <tr>
                    <td width="25%">WORKING STATUS:</td>
                    <td align="left" width="10%"><?php echo $row2['work_status'];?></td>
                    <td align="center">REMARK:</td>
                    <td colspan="2" width="50%"><?php echo $row2['Wremark'];?></td>
                  </tr>
                </table>
               

                    <table border="2" width="100%" rules="all" style="text-transform: uppercase;font-weight: bold;">
                      <tr>
                    <td width="25%"colspan="2">SERVICE TO BE CARRIED OUT (<?php echo $row2['service_to_be_carry'];?>)</td>
                     
                    <td align="left" width="10%">MISSING PARTS</td>                          
                    <td align="center">REPAIRS TO BE CARRY OUT (<?php echo $row2['repair_to_be_carry'];?>)</td>                    
                  </tr>
                    <tr>
                    <td width="25%">ENGINE OIL:</td>
                    <td align="left" width="10%"><?php echo $row2['engine_oil'];?></td>
                    <td rowspan="9"><textarea disabled class="form-control" style="resize:none;height:187px;width:199px;"><?php echo $row2['missing_part'];?></textarea></td>
                    <td rowspan="9"><textarea disabled class="form-control" style="resize:none;height:187px;width:199px;"><?php echo $row2['Repair_details'];?></textarea></td>                  
                  </tr>
                 <tr>
                    <td width="25%">OIL FILTER:</td>
                    <td align="left" width="10%"><?php echo $row2['oil_filter'];?></td>
                      
                     
                   </td>
                  </tr>
                    <tr>
                    <td width="25%">FUEL FILTER:</td>
                    <td align="left" width="10%"><?php echo $row2['fuel_filter'];?></td>
                    
                  </tr>
                    <tr>
                    <td width="25%">HYDRAULIC FILTER:</td>
                    <td align="left" width="10%"><?php echo $row2['hydr_filter'];?></td>
                     
                  </tr>
                  <tr>
                    <td width="25%">PRY AIR FILTER:</td>
                    <td align="left" width="10%"><?php echo $row2['pry_filter'];?></td>
                    
                  </tr>
                  <tr>
                    <td width="25%">2NDRY AIR FILTER:</td>
                    <td align="left" width="10%"><?php echo $row2['nry_air_filter'];?></td>
                    
                  </tr>
                  <tr>
                    <td width="25%">GREASING:</td>
                    <td align="left" width="10%"><?php echo $row2['greasing'];?></td>
                      
                  </tr>
                  <tr>
                    <td width="25%">HYDRAULIC TOP-UP:</td>
                    <td align="left" width="10%"><?php echo $row2['hydro_top'];?></td>
                   
                  </tr>
                  <tr>
                    <td width="25%">GEAR OIL:</td>
                    <td align="left" width="10%"><?php echo $row2['gear_oil'];?></td>
                     
                  </tr>
                  <tr>
                    <td>MECHANIC INVOLVED:</td>
                    <td><?php echo $row2['Mechanic'];?></td>
                    <td>ELECTRICIAN INVOLVED:</td>
                    <td><?php echo $row2['electrician'];?></td>
                  </tr>
                  <tr>
                    <td colspan="2" align="left"><font size="0.9px">NAME DATE & SIGNATURE</font></td>
                    <td colspan="2" align="left" width="13%"><font size="0.9px">NAME DATE & SIGNATURE</font></td>
                  </tr>
                  <tr>
                    <td colspan="4"><br>CROSS CHECKED BY: <?php $prebyID=$row2['pre_by'];require '../layout/preby.php';echo $row_preby['fullname']; ?></td>
                  </tr>
                  <tr>
                    <td colspan="2" align="left"><font size="0.9px">NAME DATE & SIGNATURE</font></td>
                    <td colspan="2" align="right" width="13%"><font size="0.9px"></font></td>
                  </tr>
                  <tr>
                    <td colspan="4" align="center"><br>AUTHORIZED BY: <?php $prebyID=$row2['authby'];require '../layout/preby.php';echo $row_preby['fullname']; ?></td>
                  </tr>
                  <tr>
                    <td colspan="2" align="right"> <td colspan="2" align="center"><font size="0.9px">NAME DATE & SIGNATURE</font></td>                    
                  </tr>
                  </table>
                  <table border="0" width="100%" style="font-weight: bold;">
                  <tr>
                    <td colspan="2" align="left">&nbsp;</td>
                    <td colspan="2" align="right"><br><font size="0.9">No Office or Site to accept a Plant without this sheet Accompanied</font></td>
                  </tr>
                    </table>
                  </center>       
  </div> 
 
<style type="text/css">
  .form-group {
  display: flex;
  
}
 
.form-wrapper {
    width: 50%;
    &:first-child {
      margin-right: 20px;
    }
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
@media print {
    @page {
        margin-top: 0;
        margin-bottom: 0;
    }
    body {
        padding-top: 72px;
        padding-bottom: 72px ;
    }
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