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
$qryasset="SELECT * FROM assets where status=1";
$asset=mysqli_query($config,$qryasset) or die(mysqli_error($config));
$site = "SELECT * FROM `rocad_site` where sitename!='' order by sitename Asc";
$notiqry1="SELECT * FROM `storeloadingdetails` WHERE dept is not null AND status IN(2,4) AND reference in($reference)";
$as_assets=mysqli_query($config,$notiqry1) or die(mysqli_error($config));
/////////////
$notiqry2="SELECT * FROM `storeloadingdetails` WHERE dept is not null AND status IN(2,4) AND reference in($reference)";
$as_assets2=mysqli_query($config,$notiqry2) or die(mysqli_error($config));
////////////////
$checkassets = mysqli_num_rows($as_assets);
$row2=mysqli_fetch_assoc($as_assets2);
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
        <h1>
       <?php if(($row2['status']==2)or($row2['status']==4)){?><h3 class="box-title" <?php allow_access(1,1,0,0,0,0,$usergroup); ?>><a class="pr" href="#" onclick="$('.main-footer').hide();$('.pr').hide();javascript:window.print();">PRINT</a></h3><?php }?>
      </h1>
       
      </h1>
       
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">           
             
            <!-- /.box-header -->
            <div class="box-body">
               <?php if(($row2['status']==0)or($row2['status']==1)){?>
             <script>alert('Not Approved');history.back();</script>';
          <?php }?>
              <div class="form-group">                               

                  <div style="padding:15px 15px;box-shadow: 1px 1px 25px rgb(0 0 0 / 35%);border-radius: 10px;background: #FAFAFA;margin:-40px auto;">
                  <center>
                  <table border="1" rules="rows" width="100%">
                    <tr style="text-transform: uppercase;font-weight: bold;">
                      <td><img src="/rocad_admin/images/icon.png" width="120px"><br>RC. NO 456130</td>
                      <td colspan="3" valign="top">
                    <center>
                    <h2 style="margin:0"><b>ROCAD CONSTRUCTION LIMITED</b></h2>
                    <h4 style="margin:0">BUILDING AND CIVIL ENGINEERING CONTRACTORS</h4>
                    <h5 style="margin:0">No. 50 sokoto Road, Tarauni GRA, Kano P.O Box 247, Kano Nigeria<hr></h4>
                  </center></td>
                      
                     </tr>
                    <tr valign="top">
                      <td colspan="2"><h2><b><?php if(!empty($_GET['t'])){echo "ADVANCE VOUCHER";$checkassets=10;}else{echo "REQUISITION";} ?></h2><h4>(For Internal Use Only)</h4></b></h2><br></td>
                      <td align="center"><b>NO: <?php echo $row2['reference']; ?></b></td>
                      <td align="center"><b>DATE:<?php echo $row2['time_date']; ?></b></td></tr>
                      <tr>
                       <td align="right" ><b>FROM:</b></td>
                       <td align="center"><b><?php if($row2['status']){?>(<?php $siteID=$row2['fromsite'];require '../layout/site.php'; echo $row_site['site_state']."-".$row_site['site_lga']."-".$row_site['site_loc']  ?>)<?php }?></b></td>
                       <td align="right" ><b><?php if(!isset($_GET['t'])){ ?>DEPARTMENT:<?php }?></b></td>
                       <td align="left"><b> <?php $siteID=$row2['dept'];require '../layout/site.php'; echo $row_dept['dname']; ?></b></td>
                     </tr>
                      <tr>
                       <td colspan="4">&nbsp;</td>
                      </tr>
                  </table> 
                  <table border="2" width="100%" style="text-transform: uppercase;">
                    <?php echo "<tr style='font-weight: bold;'><td>S/N</td><td>QTY</td><td>QTY IN WORDS</td><td>A/C CODE</td><td colspan='6'>DESCRIPTION</td></tr>";?>
                    <?php $j=0;while($row_assets=mysqli_fetch_assoc($as_assets)){$j++; ?>

                      <?php echo "<tr><td>".$j."</td>";?>
                      <?php echo "<td>".$row_assets['qty']."</td>";?>
                      <?php echo "<td>".$row_assets['qtyw']."</td>";?>
                      <?php if($row_assets['PlantNo']){echo "<td>".$row_assets['PlantNo']."</td>";}else{ echo "<td>N/A</td>";}?>
                      <?php echo "<td colspan='6'>".$row_assets['infoD']."</td>";?>
                      <?php echo "</tr>";?>              
                    <?php }?>
 
                       <?php
                    $numrow=(20-$checkassets);

                    for($i=1;$i<=$numrow;$i++){?>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                        <?php }?>
                      <tr>
                        <td colspan="6"><b>TOTAL NUMBER OF ITEMS REQUESTED <font color="red">(<?php echo $checkassets;?>)</font></b></td>
                      </tr>
                      <tr>
                        <td><b>Required for:</b></td>
                        <td colspan="5"><b><font color="red"><?php echo $row2['reqfor']; ?></font></b></td>
                         
                      </tr>
                      <tr>
                        <td colspan="6">&nbsp;</td>                                           
                      </tr>
                      <tr>
                        <td colspan="6">&nbsp;</td>                                           
                      </tr>
                      <tr>
                        <td colspan="6">&nbsp;</td>                                           
                      </tr>
                       
                      <tr>
                        <td colspan="2"><b>Required Office's Name:</b></td>
                        <td colspan="2"><b>Requesting Office's:</b></td>
                        <td colspan="2"><b>Head of Department Sign:</b></td>
                        </tr>
                        <tr>
                        <td colspan="2" align="center"><b><?php if($row2['status']){?><?php $prebyID=$row2['authby'];require '../layout/preby.php';echo $row_preby['fullname']; ?><?php }?></b></td>
                        <td colspan="2" align="center"><b><?php if($row2['status']){?><?php $prebyID=$row2['preby'];require '../layout/preby.php';echo $row_preby['fullname']; ?><?php }?></b></td>
                        <td colspan="2" align="center"><b><?php if($row2['status']){?><?Php }?></b></td>
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