<?php
  $active_menu = "data_tables";
  include_once "../layout/header.php";
?>
<?php require_once('../../db_con/config.php');?>
<?php
mysql_select_db($database_config, $config);
$site = "SELECT * FROM `rocad_site` where sitename !='' order by sitename Asc";
$as_site=mysql_query($site, $config) or die(mysql_error());
$as_site2=mysql_query($site, $config) or die(mysql_error());
//$row_admin=mysql_fetch_assoc($as_admin);
mysql_select_db($database_config, $config);
$qryassets="SELECT * FROM assets where status=1";
$assets=mysql_query($qryassets,$config) or die(mysql_error());
$assets2=mysql_query($qryassets,$config) or die(mysql_error());
$assets3=mysql_query($qryassets,$config) or die(mysql_error());
$assets4=mysql_query($qryassets,$config) or die(mysql_error());
$assets5=mysql_query($qryassets,$config) or die(mysql_error());
$assets6=mysql_query($qryassets,$config) or die(mysql_error());
$assets7=mysql_query($qryassets,$config) or die(mysql_error());
$assets8=mysql_query($qryassets,$config) or die(mysql_error());
$assets9=mysql_query($qryassets,$config) or die(mysql_error());
$assets10=mysql_query($qryassets,$config) or die(mysql_error());

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

    <?php include_once "../layout/topmenu.php"; ?>
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
             
            <!-- /.box-header -->
            <div class="box-body">
              <form action="" method="post" onSubmit="return validate()">
              <div style="width: 100%; min-height: 500px; overflow: auto; padding: 20px;">
              <div class="row">
                <div class="col-2">
                  <center>
                    <img src="/rocad_admin/images/icon.png" width="120px">
                    <br>
                    <b>RC No. 456130</b>
                  </center>
                </div>
                <div class="col-8">
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
              <div class="row">
                <div class="col-7">
                  <table class="normal-table" width="100%" border="1" rules="all" cellpadding="20px">
                    <tr class="bg-gray">
                      <td>
                        <b>From: </b>
                        <select style="width: 60%; float: right;" id="from">
                        
                          <option value="" selected>Select Location(Site)</option>
                          <?php while($row_site=mysql_fetch_assoc($as_site)){?>
                          <option value="<?php echo $row_site['id']; ?>"><?php echo $row_site['sitename']; ?></option>
                          <?php }?>
                        </select>
                      </td>
                    </tr>
                    <tr class="bg-gray">
                      <td>
                        <b>To: </b>
                        <select style="width: 60%; float: right;" id="to" onChange="return validate()">
                          <option value="" selected>Select Location(Site)</option>
                          <?php while($row_siteto=mysql_fetch_assoc($as_site2)){?>
                          <option value="<?php echo $row_siteto['id']; ?>"><?php echo $row_siteto['sitename']; ?></option>
                          <?php }?>
                        </select>
                      </td>
                    </tr> 
                    <tr class="bg-gray">
                      <td>
                        <b>Method of Despatch: </b>
                        <input type="text" name="" style="width: 60%; float: right;">
                      </td>
                    </tr>
                  </table>
                </div>
                <div class="col-5">
                   
                  <div class="row">
                    <span style="margin-right: 20px; padding: 5px; border: 1px solid black; background: lightgray; float: right;"><b>Date:</b> <?php echo date('d-m-Y'); ?></span>
                  </div>
                </div>
              </div>
              <br>
              <div class="row">
              <div id="msg" style="color:#FF5F00"></div>
                <div class="col-12">
                
                  <table class="normal-table1" width="100%" border="1" rules="all">
                    <tr class="bg-gray">
                      <th><center><b>No</b></center></th>
                      <th><center><b>Description</b></center></th>
                      <th><center><b>Part No.</b></center></th>
                      <th><center><b>Unit</b></center></th>
                      <th><center><b>Qty.</b></center></th>
                      <th><center><b>Unit Price</b></center></th>
                      <th><center><b>Total Value</b></center></th>
                      <th><center><b>Condition</b></center></th>
                      <th><center><b>Remarks</b></center></th>
                    </tr>
                    <tr>
                      <td><center>1</center></td>
                      <td width="25%"><select style="width: 100%; float: right; border: 0;">
                          <option value="" selected>::Select Description::</option>
                          <?php while($row_assets=mysql_fetch_assoc($assets)){?>
                          <option value="<?php echo $row_assets['id']; ?>"><?php echo $row_assets['assetname']; ?></option>
                          <?php }?>
                        </select></td>
                      <td><input type="number" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="text" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="number" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="number" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="number" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="text" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="text" name="" style="width: 100%; float: right; border: 0;"></td>
                    </tr>
                    <tr>
                      <td><center>2</center></td>
                      <td width="25%"><select style="width: 100%; float: right; border: 0;">
                          <option value="" selected>::Select Description::</option>
                          <?php while($row_assets=mysql_fetch_assoc($assets2)){?>
                          <option value="<?php echo $row_assets['id']; ?>"><?php echo $row_assets['assetname']; ?></option>
                          <?php }?>
                        </select></td>
                      <td><input type="number" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="text" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="number" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="number" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="number" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="text" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="text" name="" style="width: 100%; float: right; border: 0;"></td>
                    </tr>
                    <tr>
                      <td><center>3</center></td>
                      <td width="25%"><select style="width: 100%; float: right; border: 0;">
                          <option value="" selected>::Select Description::</option>
                          <?php while($row_assets=mysql_fetch_assoc($assets3)){?>
                          <option value="<?php echo $row_assets['id']; ?>"><?php echo $row_assets['assetname']; ?></option>
                          <?php }?>
                        </select></td>
                      <td><input type="number" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="text" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="number" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="number" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="number" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="text" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="text" name="" style="width: 100%; float: right; border: 0;"></td>
                    </tr>
                    <tr>
                      <td><center>4</center></td>
                      <td width="25%"><select style="width: 100%; float: right; border: 0;">
                          <option value="" selected>::Select Description::</option>
                          <?php while($row_assets=mysql_fetch_assoc($assets4)){?>
                          <option value="<?php echo $row_assets['id']; ?>"><?php echo $row_assets['assetname']; ?></option>
                          <?php }?>
                        </select></td>
                      <td><input type="number" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="text" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="number" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="number" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="number" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="text" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="text" name="" style="width: 100%; float: right; border: 0;"></td>
                    </tr>
                    <tr>
                      <td><center>5</center></td>
                      <td width="25%"><select style="width: 100%; float: right; border: 0;">
                          <option value="" selected>::Select Description::</option>
                          <?php while($row_assets=mysql_fetch_assoc($assets5)){?>
                          <option value="<?php echo $row_assets['id']; ?>"><?php echo $row_assets['assetname']; ?></option>
                          <?php }?>
                        </select></td>
                      <td><input type="number" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="text" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="number" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="number" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="number" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="text" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="text" name="" style="width: 100%; float: right; border: 0;"></td>
                    </tr>
                    <tr>
                      <td><center>6</center></td>
                      <td width="25%"><select style="width: 100%; float: right; border: 0;">
                          <option value="" selected>::Select Description::</option>
                          <?php while($row_assets=mysql_fetch_assoc($assets6)){?>
                          <option value="<?php echo $row_assets['id']; ?>"><?php echo $row_assets['assetname']; ?></option>
                          <?php }?>
                        </select></td>
                      <td><input type="number" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="text" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="number" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="number" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="number" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="text" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="text" name="" style="width: 100%; float: right; border: 0;"></td>
                    </tr>
                    <tr>
                      <td><center>7</center></td>
                      <td width="25%"><select style="width: 100%; float: right; border: 0;">
                          <option value="" selected>::Select Description::</option>
                          <?php while($row_assets=mysql_fetch_assoc($assets7)){?>
                          <option value="<?php echo $row_assets['id']; ?>"><?php echo $row_assets['assetname']; ?></option>
                          <?php }?>
                        </select></td>
                      <td><input type="number" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="text" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="number" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="number" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="number" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="text" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="text" name="" style="width: 100%; float: right; border: 0;"></td>
                    </tr>
                    <tr>
                      <td><center>8</center></td>
                      <td width="25%"><select style="width: 100%; float: right; border: 0;">
                          <option value="" selected>::Select Description::</option>
                          <?php while($row_assets=mysql_fetch_assoc($assets8)){?>
                          <option value="<?php echo $row_assets['id']; ?>"><?php echo $row_assets['assetname']; ?></option>
                          <?php }?>
                        </select></td>
                      <td><input type="number" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="text" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="number" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="number" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="number" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="text" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="text" name="" style="width: 100%; float: right; border: 0;"></td>
                    </tr>
                    <tr>
                      <td><center>9</center></td>
                      <td width="25%"><select style="width: 100%; float: right; border: 0;">
                          <option value="" selected>::Select Description::</option>
                          <?php while($row_assets=mysql_fetch_assoc($assets9)){?>
                          <option value="<?php echo $row_assets['id']; ?>"><?php echo $row_assets['assetname']; ?></option>
                          <?php }?>
                        </select></td>
                      <td><input type="number" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="text" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="number" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="number" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="number" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="text" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="text" name="" style="width: 100%; float: right; border: 0;"></td>
                    </tr>
                    <tr>
                      <td><center>10</center></td>
                      <td width="25%"><select style="width: 100%; float: right; border: 0;">
                          <option value="" selected>::Select Description::</option>
                          <?php while($row_assets=mysql_fetch_assoc($assets10)){?>
                          <option value="<?php echo $row_assets['id']; ?>"><?php echo $row_assets['assetname']; ?></option>
                          <?php }?>
                        </select></td>
                      <td><input type="number" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="text" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="number" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="number" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="number" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="text" name="" style="width: 100%; float: right; border: 0;"></td>
                      <td><input type="text" name="" style="width: 100%; float: right; border: 0;"></td>
                    </tr>
                  </table>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-9"></div>
                <div class="col-md-3">
                  <button class="btn btn-primary w-100" onClick="validate()">Submit Form</button>
                </div>
              </div>
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

<?php include_once "../layout/footer.php" ?>
<script src="js/table.js"></script>
<script language="javascript">
function validate(){
var from=document.getElementById('from').value;
var to=document.getElementById('to').value;
if(from==to){
	document.getElementById('msg').innerHTML="Source and Destination can not be the same!";
	return false;
}
else{
	return true;
}
}
</script>