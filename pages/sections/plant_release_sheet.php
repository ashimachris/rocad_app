<?php
if(session_status()===PHP_SESSION_NONE){
session_start();
}
  $active_menu = "data_tables";
  include_once "../layout/header.php";
 require_once('../../db_con/config.php');
 
///////////

 ////////////////////////
$qryasset="SELECT * FROM assets where status=1 order by sortno";
$asset=mysqli_query($config,$qryasset) or die(mysqli_error($config));
/////////////////////////
$msg="";
$preby =$_SESSION['admin_rocad'];
$timeDate=date('Y-m-d H:i:s');
$tenDgt = rand(1000000000,9999999999);
if(isset($_POST["sbt"])){
  //vars
  $fromsite=mysqli_real_escape_string($config,$_POST["fromsite"]);
  $tosite=mysqli_real_escape_string($config,$_POST["tosite"]);
  $PlantNo=mysqli_real_escape_string($config,$_POST["plantno"]);
  $self=mysqli_real_escape_string($config,$_POST["selfOrLow"]);
  $jack=mysqli_real_escape_string($config,$_POST["jack"]);
  $jremark=mysqli_real_escape_string($config,$_POST["jremark"]);
  $fireex=mysqli_real_escape_string($config,$_POST["fireex"]);
  $fireremark=mysqli_real_escape_string($config,$_POST["fireremark"]);
  $styre=mysqli_real_escape_string($config,$_POST["styre"]);
  $styreremark=mysqli_real_escape_string($config,$_POST["styreremark"]);
  $dp=mysqli_real_escape_string($config,$_POST["dp"]);
  $dpremark=mysqli_real_escape_string($config,$_POST["dpremark"]);
  $hydr=mysqli_real_escape_string($config,$_POST["hydr"]);
  $hydrremark=mysqli_real_escape_string($config,$_POST["hydrremark"]);
  $wstatus=mysqli_real_escape_string($config,$_POST["wstatus"]);
  $wsremark=mysqli_real_escape_string($config,$_POST["wsremark"]);
  $rcarry=mysqli_real_escape_string($config,$_POST["rcarry"]);
  $Rinfo=mysqli_real_escape_string($config,$_POST["Rinfo"]);
  $Minfo=mysqli_real_escape_string($config,$_POST["Minfo"]);
  $scarry=mysqli_real_escape_string($config,$_POST["scarry"]);
  $engoil=mysqli_real_escape_string($config,$_POST["engoil"]);
  $ofilter=mysqli_real_escape_string($config,$_POST["ofilter"]);
  $ffilter=mysqli_real_escape_string($config,$_POST["ffilter"]);
  $hfilter=mysqli_real_escape_string($config,$_POST["hfilter"]);
  $pfilter=mysqli_real_escape_string($config,$_POST["pfilter"]);
  $nfilter=mysqli_real_escape_string($config,$_POST["nfilter"]);
  $greasing=mysqli_real_escape_string($config,$_POST["greasing"]);
  $htop=mysqli_real_escape_string($config,$_POST["htop"]);
  $goil=mysqli_real_escape_string($config,$_POST["goil"]);
  $mech=mysqli_real_escape_string($config,$_POST["mech"]);
  $mphone=mysqli_real_escape_string($config,$_POST["mphone"]);
  $elec=mysqli_real_escape_string($config,$_POST["elec"]);
  $elecphone=mysqli_real_escape_string($config,$_POST["elecPhone"]);
  $batt=mysqli_real_escape_string($config,$_POST["batt"]);
  $bremark=mysqli_real_escape_string($config,$_POST["bremark"]);
 /////////////////////////////
$sql="INSERT INTO `plant_release_sheet`(`fromsite`, `tosite`, `PlantNo`, `Self_low`, `jack`, `Jremark`, `fire_ex`, `Fremark`, `spare_ty`, `Sremark`, `diesel_p`, `Dremark`, `hydro`, `Hremark`, `work_status`, `Wremark`, `repair_to_be_carry`, `Repair_details`, `missing_part`, `service_to_be_carry`, `engine_oil`, `oil_filter`, `fuel_filter`, `hydr_filter`, `pry_filter`, `nry_air_filter`, `greasing`, `hydro_top`, `gear_oil`, `Mechanic`, `mphone`, `electrician`, `elephone`, `reference`, `time_date`, `pre_by`,`batt`,`bremark`,`note`,`dept`)values('$fromsite','$tosite','$PlantNo','$self','$jack','$jremark','$fireex','$fireremark','$styre','$styreremark','$dp','$dpremark','$hydr','$hydrremark','$wstatus','$wsremark','$rcarry','$Rinfo','$Minfo','$scarry','$engoil','$ofilter','$ffilter','$hfilter','$pfilter','$nfilter','$greasing','$htop','$goil','$mech','$mphone','$elec','$elecphone','$tenDgt','$timeDate','$preby','$batt','$bremark','Plant Release','ex')";
       
      $insert=mysqli_query($config,$sql) or die(mysqli_error($config));
      $siteID=$fromsite;require '../layout/site.php';
      $froms=$row_site['site_state']."---".$row_site['site_lga']."---".$row_site['site_loc'];
        if($insert==1){
      /////////
      $prebyID=$preby;require '../layout/preby.php';
    $siteID=$tosite;require '../layout/site.php';
  $tos=$row_site['site_state']."---".$row_site['site_lga']."---".$row_site['site_loc'];    
     
  $msgT="Prepared By:".$row_preby['fullname']."\nPlace of Discharge:".$froms."\nPlace of Arrival:".$tos."\nTime & Date:".$timeDate."\nStatus: Pending."."\nLogin to website:\nhttps://app.rocad.com";
  $msgMail = wordwrap($msgT,70);

// send email
  mail("deleakintayo@rocad.com,ronaldo@rocad.com,umar@rocad.com,munzali@ereg.ng","Plant Release Sheet",$msgMail);
///////////////////////
     $liter=$_POST['liter'];
  $msg="<font color='green'>Request sent successfully.</font>";
   echo    "<script>setTimeout(function(){window.location='plant_release.php';},4200);</script>";
  }
   
}
?>
<style type="text/css">
input{
  text-transform: uppercase;
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
        <small>Plant Release</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="/rocad_admin/pages/dashboard/"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Administration</a></li>
        <li class="active" ><a href="plant_release.php">Plant Release List</a></li>
        <li class="active"><span style="cursor: pointer;" onclick="history.back()">Back</span></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header"> 
              <h3 class="box-title"><a href="#"><a href="#"><?php echo "Plant Release Sheet";?></a></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <h3 class="box-title"><?php  echo $msg; ?></h3>
              <div class="form-group">
                
                  <img src="pace/release.png" style="height:25%; padding-top:50px;" class="hidden-mobile">
               
                <div class="form-wrapper">
                  <form name="add_name" id="add_name" action="" method="post"  class="form-style-9">           

<ul>
 
<li>
   <div id="msg" style="color:red"></div>
   <div class="form-group">
            <div class="form-wrapper">
              <label for="">Place of Discharge:</label>
               <select class="form-control" id="fromsite" required name="fromsite">
              <option <?php $siteID=0;require '../layout/site.php'?> value="<?php echo $row_sites['id']; ?>"><?php echo $row_sites['sitename'];?></option>
     <?php while($row_sites=mysqli_fetch_assoc($sites)){?>
    <option value="<?php echo $row_sites['id']; ?>"><?php echo $row_sites['site_state']."---".$row_sites['site_lga']."---".$row_sites['site_loc']; ?></option>
                        <?php }?>
                      </select>
            </div>
            <div class="form-wrapper">
              <label for="">Place of Arrival</label>
               <select class="form-control" id="tosite" required name="tosite">
                             <option <?php $siteID=0;require '../layout/site.php'?> value="<?php echo $row_site['id']; ?>"><?php echo $row_site['sitename'];?></option>
     <?php while($row_sites=mysqli_fetch_assoc($sites)){?>
    <option value="<?php echo $row_sites['id']; ?>"><?php echo $row_sites['site_state']."---".$row_sites['site_lga']."---".$row_sites['site_loc']; ?></option>
                        <?php }?>
                      </select>
              </div>
            </div></li>
             <li>
   
   <div class="form-group" onmousemove="return validate()">
            <div class="form-wrapper">
              <label for="">Plant No:</label>
              <select name="plantno" required class="form-control"><option value="" selected>::Select Plant No::</option><?php while($row_asset=mysqli_fetch_assoc($asset)){?><option value="<?php echo $row_asset['sortno']; ?>"><?php echo $row_asset['sortno']; ?></option><?php }?></select>
              </div>
            <div class="form-wrapper">
              <label for="">SELF OR LOW BED S.NO.:</label>
              <input type="text" class="form-control" name="selfOrLow" placeholder="(if Any)">
              </div>
              </div></li>
              <div class="form-style-9" style="margin:10px 0;">
                <fieldset >
                <legend style="font-size: medium;">PRELIMINARY CHECK LIST</legend>
               <table>
                <tr>
      <td colspan="7" style="font-size:x-small;color:red;" align="left">Tick the below Boxes with Yes or No (If no state the reason)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
     <tr>
    <td style="width: 80px;" title="Battery">Batteries:</td>
    <td>Yes&nbsp;</td>
    <td style="width: 50px;"><input type="radio" required class="form-check-input" id="check1" name="batt" value="YES" onclick="$('#bt').attr('disabled', 1);"></td>
    <td>No&nbsp;</td>
    <td style="width: 20px;"><input type="radio" required class="form-check-input" id="check1" name="batt" value="NO" onclick="$('#bt').removeAttr('disabled');"></td>
    <td style="width: 30px;">REMARK:</td>
    <td><input type="text" style="width:100%;" name="bremark" disabled id="bt"></td>
    </tr>
  <tr>
    <td style="width: 80px;" title="Jack">JACK:</td>
    <td>Yes&nbsp;</td>
    <td style="width: 50px;"><input type="radio" required class="form-check-input" id="check1" name="jack" value="YES" onclick="$('#jk').attr('disabled', 1);"></td>
    <td>No&nbsp;</td>
    <td style="width: 20px;"><input type="radio" required class="form-check-input" id="check1" name="jack" value="NO" onclick="$('#jk').removeAttr('disabled');"></td>
    <td style="width: 30px;">REMARK:</td>
    <td><input type="text" style="width:100%;" name="jremark" disabled id="jk"></td>
    </tr>
    <tr>
      <td colspan="7"></td>
    </tr>
    <tr>
    <td style="width: 80px;" title="Fire Extinguisher">FIRE EXT.:</td>
    <td>Yes&nbsp;</td>
    <td style="width: 50px;"><input type="radio" required class="form-check-input" id="check1" name="fireex" value="YES" onclick="$('#fx').attr('disabled', 1);"></td>
    <td>No&nbsp;</td>
    <td style="width: 20px;"><input type="radio" required class="form-check-input" id="check1" name="fireex" value="NO" onclick="$('#fx').removeAttr('disabled');"></td>
    <td style="width: 30px;">REMARK:</td>
    <td><input type="text" style="width:100%;" name="fireremark" disabled id="fx"></td>
     </tr>
     <tr>
      <td colspan="7"></td>
    </tr>
     <tr>
    <td style="width: 80px;" title="Spare Tyre">SPARE TY.:</td>
    <td>Yes&nbsp;</td>
    <td style="width: 50px;"><input type="radio" required class="form-check-input" id="check1" name="styre" value="YES" onclick="$('#spt').attr('disabled', 1);"></td>
    <td>No&nbsp;</td>
    <td style="width: 20px;"><input type="radio" required class="form-check-input" id="check1" name="styre" value="NO" onclick="$('#spt').removeAttr('disabled');"></td>
    <td style="width: 30px;">REMARK:</td>
    <td><input type="text" style="width:100%;" name="styreremark" disabled id="spt"></td>
     
  </tr>
  <tr>
      <td colspan="7"></td>
    </tr>
    
    <tr>
    <td style="width: 80px;" title="Diesel or Pertol">Diesel/Pertol: </td>
    <td> D.&nbsp;</td>
    <td style="width: 50px;"><input type="radio" required class="form-check-input" id="check1" name="dp" value="Diesel"></td>
    <td>P.&nbsp;</td>
    <td style="width: 20px;"><input type="radio" required class="form-check-input" id="check1" name="dp" value="Petrol"></td>
    <td style="width: 30px;">REMARK:</td>
    <td><input type="text" style="width:100%;" name="dpremark"></td>
     
  </tr>
  <tr>
      <td colspan="7" style="font-size:x-small;color:red;" align="right">State the Quantity in Liter</td>
    </tr>  
  <tr>
    <td style="width: 80px;" title="Hydraulic Oil">HYDR. OIL:</td>
    <td>Yes&nbsp;</td>
    <td style="width: 50px;"><input type="radio" required class="form-check-input" id="check1" name="hydr" value="YES"></td>
    <td>No&nbsp;</td>
    <td style="width: 20px;"><input type="radio" required class="form-check-input" id="check1" name="hydr" value="NO"></td>
    <td style="width: 30px;">REMARK:</td>
    <td><input type="text" style="width:100%;" name="hydrremark"></td>
     
  </tr>
  <tr>
      <td colspan="7" style="font-size:x-small;color:red;" align="right">State the Quantity in Liter</td>
    </tr>
</table>        
    
    </fieldset>
              </div>
              <div class="form-style-9" style="margin:10px 0;">
                  <fieldset >
                <legend style="font-size: medium;">MECHANICAL DISCHARGE</legend>
<table>
                <tr>
      <td colspan="7" style="font-size:x-small;color:red;" align="left">Tick the below Boxes with Yes or No (If No state the reason)</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
  <tr>
    <td style="width: 80px;" title="Jack">Working Status:</td>
    <td>Yes&nbsp;</td>
    <td style="width: 50px;"><input type="radio" required class="form-check-input" id="check1" name="wstatus" value="YES" onclick="$('#ws').attr('disabled', 1);"></td>
    <td>No&nbsp;</td>
    <td style="width: 20px;"><input type="radio" required class="form-check-input" id="check1" name="wstatus" value="NO" onclick="$('#ws').removeAttr('disabled');"></td>
    <td style="width: 30px;">REMARK:</td>
    <td><input type="text" style="width:100%;" name="wsremark" disabled id="ws"></td>
    </tr>
    <tr>
      <td colspan="6"></td>
    </tr>
  </table>
  <table >
    <tr>
      <td colspan="7">&nbsp;</td>
    </tr>
    <tr>
    <td title="Fire Extinguisher" colspan="4">REPAIRS TO BE CARRIED OUT</td>
  </tr>

  <tr>
    <td align="right">Yes&nbsp;</td>
    <td align="left"><input type="radio" required class="form-check-input" id="check1" name="rcarry" value="YES" onclick="$('#rinf,#minf').removeAttr('disabled');"></td>
    <td align="right">No&nbsp;</td>
    <td align="left"><input type="radio" required class="form-check-input" id="check1" name="rcarry" value="NO" onclick="$('#rinf,#minf').attr('disabled', 1);"></td>
  </tr>
  <tr>
      <td colspan="7">&nbsp;</td>
    </tr>
    </table>
                    
              <label for="">State the details below</label>
               <textarea class="form-control" name="Rinfo" disabled id="rinf"></textarea>
               <label for="">Missing Parts</label>
               <textarea class="form-control" name="Minfo" disabled id="minf"></textarea>         
         <table>
          <tr>
      <td colspan="4">&nbsp;</td>
    </tr>
           <tr>
    <td title="Fire Extinguisher" colspan="4">SERVICE TO BE CARRIED OUT</td>
  </tr>

  <tr>
    <td align="right">Yes&nbsp;</td>
    <td align="left"><input type="radio" required class="form-check-input" id="check1" name="scarry" value="YES"></td>
    <td align="right">No&nbsp;</td>
    <td align="left"><input type="radio" required class="form-check-input" id="check1" name="scarry" value="NO"></td>
  </tr>
  <tr>
      <td colspan="4" style="font-size:x-small;color:red;" align="left">If Yes Tick Items Done</td>
      <tr>
  <td colspan="4">&nbsp;</td>
</tr>
    </tr>
         </table>
<table>
<tr>
    <td title="Jack">Engine Oil:</td>
    <td>Yes&nbsp;</td>
    <td><input type="radio" required class="form-check-input" id="check1" name="engoil" value="YES"></td>
    <td>No&nbsp;</td>
    <td><input type="radio" required class="form-check-input" id="check1" name="engoil" value="NO"></td>
     </tr>
     
    <tr>
    <td title="Fire Extinguisher">Oil Filter:</td>
    <td>Yes&nbsp;</td>
    <td><input type="radio" required class="form-check-input" id="check1" name="ofilter" value="YES"></td>
    <td>No&nbsp;</td>
    <td><input type="radio" required class="form-check-input" id="check1" name="ofilter" value="NO"></td>
     </tr>
      
     <tr>
    <td title="Spare Tyre">Fuel Filter:</td>
    <td>Yes&nbsp;</td>
    <td><input type="radio" required class="form-check-input" id="check1" name="ffilter" value="YES"></td>
    <td>No&nbsp;</td>
    <td ><input type="radio" required class="form-check-input" id="check1" name="ffilter" value="NO"></td>
    </tr> 
    <tr>
    <td  title="Diesel or Pertol">Hydraulic Filter:</td>
    <td>Yes.&nbsp;</td>
    <td ><input type="radio" required class="form-check-input" id="check1" name="hfilter" value="YES"></td>
    <td>No.&nbsp;</td>
    <td ><input type="radio" required class="form-check-input" id="check1" name="hfilter" value="NO"></td>
    <tr>
      <tr>
    <td title="Diesel or Pertol">Pry Air Filter:</td>
    <td>Yes.&nbsp;</td>
    <td ><input type="radio" required class="form-check-input" id="check1" name="pfilter" value="YES"></td>
    <td>No.&nbsp;</td>
    <td><input type="radio" required class="form-check-input" id="check1" name="pfilter" value="NO"></td>
    </tr>
    <tr>
    <td  title="Diesel or Pertol">2NDRY Air Filter:</td>
    <td>Yes.&nbsp;</td>
    <td ><input type="radio" required class="form-check-input" id="check1" name="nfilter" value="YES"></td>
    <td>No.&nbsp;</td>
    <td ><input type="radio" required class="form-check-input" id="check1" name="nfilter" value="NO"></td>
    <tr>
      <tr>
    <td  title="Diesel or Pertol">Greasing:</td>
    <td>Yes.&nbsp;</td>
    <td ><input type="radio" required class="form-check-input" id="check1" name="greasing" value="YES"></td>
    <td>No.&nbsp;</td>
    <td ><input type="radio" required class="form-check-input" id="check1" name="greasing" value="NO"></td>
    <tr>
      <tr>
    <td s title="Diesel or Pertol">Hydraulic Top-up:</td>
    <td>Yes.&nbsp;</td>
    <td ><input type="radio" required class="form-check-input" id="check1" name="htop" value="YES"></td>
    <td>No.&nbsp;</td>
    <td ><input type="radio" required class="form-check-input" id="check1" name="htop" value="NO"></td>
    <tr>
      <tr>
    <td  title="Diesel or Pertol">Gear Oil:</td>
    <td>Yes.&nbsp;</td>
    <td ><input type="radio" required class="form-check-input" id="check1" name="goil" value="YES"></td>
    <td>No.&nbsp;</td>
    <td><input type="radio" required class="form-check-input" id="check1" name="goil" value="NO"></td>
    <tr>
  </table>
</fieldset>
</div>     
              <li>
   
   <div class="form-group">
            <div class="form-wrapper">
              <label for="">Mechanic Involved:</label>
              <input type="text" class="form-control" required name="mech">
              </div>
             <div class="form-wrapper">
              <label for="">Phone:</label>
              <input type="number" min="0" class="form-control" required name="mphone">
            </div>
              </li>
              <li>
   
   <div class="form-group">
            <div class="form-wrapper">
              <label for="">Electrician Involved:</label>
              <input type="text" class="form-control" required name="elec">
              </div>
             <div class="form-wrapper">
              <label for="">Phone:</label>
              <input type="number" min="0" class="form-control" required name="elecPhone">
            </div>
              </li>
              <li>   
   <div class="form-group">            
               
              <li>
              <button name="sbt" id="submit">Submit</button>
            </li>
             
          </div></li>
             
                    
          
                 
</form>
</div>
</div>
 
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
<script language="javascript">
function validate(){
var from=document.getElementById('fromsite').value;
var to=document.getElementById('tosite').value;
if(from==to){
  document.getElementById('msg').innerHTML="Place of Dispatch/Place of Arrival can not be the same!";
  $('#submit').hide();
   
  return false;
}
else{
$('#submit').show();
 document.getElementById('msg').innerHTML="";
  return true;
}
}
</script>
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