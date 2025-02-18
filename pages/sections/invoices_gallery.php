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
 //////////////////////////////
 ?>
 
<style type="text/css">
input{
  text-transform: uppercase;
}
 
div.gallery {
  margin: 0px;
  border: 1px solid #ccc;
  float: left;
  width: 305px;
}

div.gallery:hover {
  border: 1px solid #3c8dbc;
  cursor: pointer;
}

div.gallery img {
   
}

div.desc {
  padding: 1px;
  text-align: left;
  font-weight: bold;
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

    <?php include_once "../layout/topmenu.php"; allow_access_all(1,1,0,0,1,0,$usergroup); ?>
    <?php include_once "../layout/left-sidebar.php"; ?>
    

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <h1>
       <?php if(($row2['status']==2)or($row2['status']==4)){?><h3 class="box-title" <?php allow_access(1,1,0,0,$usergroup); ?>><a class="pr" href="#" onclick="$('.main-footer').hide();$('.pr').hide();javascript:window.print();">PRINT</a></h3><?php }?>
      </h1>
       
      </h1>
       
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">           
             
            <!-- /.box-header -->
            <div class="box-body">
              
              <h3 class="box-title"><a href="#">Invoice Gallery</a></h3>
              <?php
              $query = $config->query("SELECT * FROM invoices ORDER BY uploaded_on DESC");
               if($query->num_rows > 0){
    while($row = $query->fetch_assoc()){
        $imageURL = 'uploads/'.$row["file_name"];
?>
 <div class="gallery">
  <a target="_blank" href='<?php echo $imageURL = 'uploads/'.$row["file_name"]; ?>'>
    <img src="<?php echo $imageURL; ?>" alt="Suppliers Invoice" style="width:300px;height: 400px">
  </a>
  <div class="desc">Uploaded On: <?php echo date($row["uploaded_on"]); ?></div>  
  <div class="desc">L.P.O.No.: <?php echo $row["lpo"]; ?></div>
  <div class="desc">Plant No: <?php if($row["PlantNo"]){echo $row["PlantNo"];}else{echo "N/A";} ?></div>
  <div class="desc">Title: <?php echo $row["title"] ?></div>
</div>
    
<?php }
}else{ ?>
    <p>No Invoice(s) found...</p>
<?php } ?>
              

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