<?php
  include 'db_con/config.php';
  ob_start();
  session_start();
  if (isset($_POST['login'])) {
    $email = $_POST['email'];
      $password = mysqli_real_escape_string($config,$_POST['password']);
    if (!empty($email) && !empty($password)) {
      //$admin = "SELECT * FROM `admin` WHERE `user_mail`='$email' AND `passwd`='$password'";
      $admin = "SELECT * FROM `new_users` WHERE `email`='$email'";
      $query = mysql_query($admin, $config);
      $result = mysql_num_rows($query);  
      if ($result > 0) {
        $row = mysql_fetch_assoc($query);
        $account_status = $row['status'];
        if ($account_status == 0) {
          echo    "<div class='alert alert-danger text-center' role='alert' style='margin-bottom: 0px;'>
                  <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                  <strong>Sorry, your account has been deactivated!</strong>
                </div>";
        } else {
          if (password_verify($password, $row['passwd'])){
            $_SESSION['user'] = $row['id'];
            echo'<script>location.href="dashboard";</script>';
          } else {
            echo    "<div class='alert alert-danger text-center' role='alert' style='margin-bottom: 0px;'>
                  <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                  <strong>Invalid Password!</strong>
                </div>";
          }
        }
      } else {
         echo    "<div class='alert alert-danger text-center' role='alert' style='margin-bottom: 0px;'>
                   <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                   <strong>Invalid Email Address!</strong>
                 </div>";
      }
    } else {
      echo    "<div class='alert alert-danger text-center' role='alert' style='margin-bottom: 0px;'>
                  <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                  <strong>Enter Login Credentials!</strong>
                </div>";
    }
  }
?>