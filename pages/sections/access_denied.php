<?php
if(session_status()===PHP_SESSION_NONE){
session_start();
}
$active_menu = "profile";
require_once('../../db_con/config.php');
include_once "../layout/header.php"; 
?>

<body class="hold-transition skin-blue sidebar-mini">
  <!-- Put Page-level css and javascript libraries here -->


  <!-- ================================================ -->

  <div class="wrapper">

    <?php include_once "../layout/topmenu.php"; ?>
    <?php include_once "../layout/left-sidebar.php"; ?>
    

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
 <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
.card {
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
  max-width: 400px;
  margin: auto;
  text-align: center;
  font-family: arial;
}

.title {
  color: grey;
  font-size: 18px;
}

button {
  border: none;
  outline: 0;
  display: inline-block;
  padding: 8px;
  color: white;
  background-color: #000;
  text-align: center;
  cursor: pointer;
  width: 100%;
  font-size: 18px;
}

 

button:hover, a:hover {
  opacity: 0.7;
}
#img,#rst {
  display:none;
}
</style>
<div><?php  echo $statusMsg; ?></div>

<center><span style="font-weight: bold; font-size:18px;"></span></center><br>
<div class="card">
  <img src="access.png" alt="John" style="width:100%">
  <div id="img">
  <form name="add_name" id="add_name" action="" method="post" enctype="multipart/form-data"  class="form-style-9">
 <input type="file" class="form-control" required name="filen" title="Upload Profile Picture">
  <div align="right">
                     <input type="submit" name="sbt" id="submit" class="btn btn-info" value="Submit"/> 
                     </div></form>
                       </div>
                       <div id="rst">
                        <div id="error"></div>
                        <form action="#" method="post" class="form-style-9" onsubmit="return matchPassword()">
             <div class="form-group">
            <div class="form-wrapper">
              <div style="margin-bottom: 10px;" id="status"></div>
              <input type="Password" class="form-control" required name="pass" placeholder="Type new Password" id="pass">
            </div>
            <div class="form-wrapper">
              <label for=""></label>
               
              <input type="hidden" class="form-control" required  name="ids" id="idg" value="<?php echo $id; ?>">
              </div>
              <div align="right"><br>
                     <input type="submit" name="forgot" class="btn btn-info" value="Reset" id="forgot" /> 
                     </div></div></form></div>        

  
 
  
</div>
 

        
    </div><!-- /.content-wrapper -->
    
    <?php include_once "../layout/copyright.php"; ?>
    <?php include_once "../layout/right-sidebar.php"; ?>

    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
  </div><!-- ./wrapper -->

<?php include_once "../layout/footer.php" ?>
<script src="profile/script.js"></script>
<script>  
function matchPassword(){  
  var pw1 = document.getElementById("pass").value;  
  var pw2 = document.getElementById("pass1").value;  
  if(pw1 != pw2)  
  {   
  document.getElementById("error").innerHTML="<font color='red'>Password did not match!</font>"; 
    //alert("Passwords did not match");
    return false; 
  } else {  
    return true; 
  }  
}  
</script>
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<script type="text/javascript">
      $(document).ready(function(){
        $("#forgot").click(function(event){
          event.preventDefault();
          var newpass = $('#pass').val();
          var ids = $('#idg').val();
          $.ajax({
            url: "profile/reset_pass.php",
            method: "post",
            data: {forgot:1, newpass:newpass,ids:ids},

            beforeSend: function(){
                $('#status').html('<center><img src="loader.gif" width="50px" /></center>');
            },
            success: function(data){
                $("#status").html(data);
            }
          })
        });
      });
    </script>  