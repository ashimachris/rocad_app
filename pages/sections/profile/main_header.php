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
  max-width: 300px;
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

<center><span style="font-weight: bold; font-size:18px;">User Profile</span></center><br>
<div class="card">
  <img src="/rocad_admin/user_img/<?php echo $row_staff['images'];?>.jpg" alt="John" style="width:100%">
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
              

  <h1><?php echo $row_staff['fullname']; ?></h1>
<span class="title" style="color:green;"><?php $usergroupID=$row_staff['usergroup'];require '../layout/usergroup.php';echo $row_group['usergroups']; ?></span>
<p class="title"><?php echo $row_staff['phone']; ?></p>
  <p>Rocad Construction Company.</p>
   
  <p><button onclick="document.getElementById('rst').style.display = 'block';document.getElementById('img').style.display = 'none'">Reset Password</button></p>
  <p><button onclick="document.getElementById('img').style.display = 'block';document.getElementById('rst').style.display = 'none'">Change Profile Picture</button></p>
</div>
 
