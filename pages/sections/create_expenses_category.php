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


$tenDgt = rand(1000000000,9999999999);

// Query to get asset data
$qry_categories = "SELECT * FROM expenses_category ORDER BY id";
$expenses_category = mysqli_query($config, $qry_categories) or die(mysqli_error($config));

if (isset($_GET['edit_id'])) {
$qry_details = "SELECT * FROM `daily_expenses_reports` WHERE id=". $_GET['edit_id'];

$details=mysqli_query($config,$qry_details) or die(mysqli_error($config));

$row_details = mysqli_fetch_assoc($details);

}


if(isset($_POST["submit_category"])){

  $insert="insert into `expenses_category`(`name`,  `description`)
          values('".mysqli_real_escape_string($config,$_POST["name"])."','".mysqli_real_escape_string($config,$_POST["description"])."')";

     	$save_category=mysqli_query($config,$insert) or die(mysqli_error($config));
    
    if($save_category){
      $msg="<font color='green'>Data Saved successfully.</font>";
        echo  "<script>setTimeout(function(){window.location='category_list.php';},4200);</script>";
      }else{
      	echo"Error, Failed to save the record";
      	}
   
}


if(isset($_POST["submit_sub_category"])){

  $category_id = $_POST['category_id'];
  $name = mysqli_real_escape_string($config,$_POST["name"]);
  $description = mysqli_real_escape_string($config,$_POST["description"]);

  if($category_id=="" || $name =="" || $description==""){

       echo "Error, Failed to save the record, All fields required";
       exit;

  }else{

      $insert="insert into `expenses_sub_category`(`name`,  `description`, category_id)
              values('$name','$description','$category_id')";

          $save_category=mysqli_query($config,$insert) or die(mysqli_error($config));
        
        if($save_category){

          $msg="<font color='green'>Data Saved successfully.</font>";
            echo  "<script>setTimeout(function(){window.location='category_list.php';},4200);</script>";
          }else{
            echo"Error, Failed to save the record";
            }
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
        <b>Category</b>
      </h1>
      <ol class="breadcrumb">
        <li><a href="/rocad_admin/pages/dashboard/"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Administration</a></li>
        <li class="active"><a href="expense_category.php">Approved Expenses</a></li>
        <li class="active" ><span style="cursor: pointer;" onclick="history.back()">Back</span></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
              <!-- Display the message dynamically -->
              <h3 class="box-title"><?php  echo $msg; ?></h3>
              <div class="form-group">
                
                  <!-- Display an image, hidden on mobile devices -->
                  <img src="pace/cat.jpg" style="height:350px; padding-top:50px;" class="hidden-mobile">
               
                <div class="form-wrapper">
                <div style="display: flex; gap:20px;">
                    <!-- First Form -->
                    <form action="" method="post"  class="form-style-9" style="flex: 1; border: 1px solid #ccc; padding: 20px;">         
                        <ul>
                         
                          
                          <li>
                              <label for="">Time & Date:</label>
                              <!-- Disabled field showing current time and date -->
                              <input type="text" class="form-control" required value="<?php echo $timeDate; ?>"  name="make" disabled>
                          </li>
                          <li><label for="">Main Category</label>
                          <!-- Input field for main category -->
                          <input class="form-control" name="name" type="text" value="" />
                          </li>
                          <li><label for="">Description</label>
                          <!-- Textarea for description -->
                          <textarea class="form-control" name="description" type="text" value=""> </textarea>
                         </li>
                      </ul>
                      <div>
                          <div align="right"> 
                              <!-- Submit button for the first form -->
                              <input type="submit" name="submit_category" class="btn btn-info" value="Saved Category"/>
                          </div> 
                      </div> 
                    </form>
                    <!-- Second Form -->
                    <form action="" method="post"  class="form-style-9" style="flex: 1; border: 1px solid #ccc; padding: 20px;">  
                      <ul>
                      <li>
                        <label for="">Time & Date:</label>
                        <!-- Disabled field showing current time and date -->
                        <input type="text" class="form-control" required value="<?php echo $timeDate; ?>"  name="make" disabled></li>
                        <li><label for="">Main Category</label>
                        <!-- Input field for main category -->
                          <select name="category_id" class="form-control">
                              <option value="">Select Main Category</option>
                              <?php while ($row_category = mysqli_fetch_assoc($expenses_category)) { ?>
                              <option value="<?php echo $row_category['id']; ?>"><?php echo $row_category['name']; ?></option>
                              <?php } ?>
                          </select>
                        </li>
                        <li><label for="">Sub Category Name</label>
                            <input class="form-control" name="name" type="text" value="" />
                        </li>
                        <li><label for="">Description</label>
                          <!-- Textarea for description -->
                          <textarea class="form-control" name="description" type="text" value=""> </textarea>
                         </li>
                    </ul>

                      <div>
                           <div align="right"> 
                              <!-- Submit button for the second form -->
                              <input type="submit" name="submit_sub_category" class="btn btn-info" value="Save sub category"/>
                          </div> 
                      </div> 
                    </form>

                    <!-- Second Form -->
                    <form action="" method="post"  class="form-style-9" style="flex: 1; border: 1px solid #ccc; padding: 20px;">  
                      <ul>
                      <li>
                        <label for="">Time & Date:</label>
                        <!-- Disabled field showing current time and date -->
                        <input type="text" class="form-control" required value="<?php echo $timeDate; ?>"  name="make" disabled></li>
                        <li><label for="">Sub Category</label>
                        <!-- Input field for main category -->
                          <select name="" class="form-control">
                              <option value="">Select sub category</option>
                             <!-- <?php while ($row_category = mysqli_fetch_assoc($expenses_category)) { ?>
                              <option value="<?php echo $row_category['id']; ?>"><?php echo $row_category['name']; ?></option>
                              <?php } ?> -->
                          </select>
                        </li>
                        <li><label for="">Sub_sub_Category Name</label>
                            <input class="form-control" name="name" type="text" value="" />
                        </li>
                        <li><label for="">Description</label>
                          <!-- Textarea for description -->
                          <textarea class="form-control" name="description" type="text" value=""> </textarea>
                         </li>
                    </ul>

                      <div>
                           <div align="right"> 
                              <!-- Submit button for the second form -->
                              <input type="submit" name="submit_sub_sub_category" class="btn btn-info" value="Save sub_sub category"/>
                          </div> 
                      </div> 
                    </form>
                </div>

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