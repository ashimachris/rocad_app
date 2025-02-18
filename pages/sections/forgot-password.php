<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" href="/rocad_admin/images/favicon.png" type="image/gif" sizes="32x32">
  <title>Rocad | Construction</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../dist/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../../dist/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="../../plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
<style type="text/css">
    .login-box-body{
      min-height: 320px; position: relative; top: 100px;
    }
  </style>
</head>
<body class="hold-transition login-page">
  <div class="login-box">
  
  <!-- /.login-logo --> 
  <div class="login-box-body">
    <p class="login-box-msg"><img src="../../images/rocad-logo.png" width="152.5px" height="52.2px"></p>
<p class="login-box-msg"><?php echo "<font color='red'></font>" ?></p>
    <form action="" method="post">
      <div class="form-group has-feedback">
        <input type="email" class="form-control" placeholder="Email" required name="usermail">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-md-12">
          <button type="submit" class="btn btn-primary btn-block btn-flat w-100" name="login">Submit</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

    <center>
      <br>
      <a href="login.php">Back to Login</a>
      <br>
    </center>
    
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
  <!-- jQuery 2.2.3 -->
  <script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
  <!-- Bootstrap 3.3.6 -->
  <script src="../../bootstrap/js/bootstrap.min.js"></script>
  <!-- iCheck -->
  <script src="../../plugins/iCheck/icheck.min.js"></script>
  <script src="login/script.js"></script>
</body>
</html>