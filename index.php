<?php
include_once 'db/connect_db_index.php';
session_start();


////////////////////////////////////////  ESPACE ENTREPRISE \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

if (isset($_POST['btn_login'])) {

  $username = $_POST['username'];
  $password = sha1($_POST['password']);

  $select = $pdo->prepare("select * from tbl_user where username='$username' AND password='$password' ");
  $select->execute();
  $row = $select->fetch(PDO::FETCH_ASSOC);

  if ($row['username'] == $username and $row['password'] == $password and ($row['role'] == "Admin" || $row['role'] == "Responsable" || $row['role'] == "storekeeper") and $row['is_active'] == "1") {
    $_SESSION['user_id'] = $row['user_id'];
    $_SESSION['user_name'] = $row['username'];
    $_SESSION['fullname'] = $row['fullname'];
    $_SESSION['magasin'] = $row['magasin'];
    $_SESSION['role'] = $row['role'];
    $_SESSION['select_shop'] = "";
    $_SESSION['count_alert'] = 0;

    $message = 'success';

    $_SESSION['derniere_action'] = time();

    header('refresh:2;dashboard.php');
  } else if ($row['username'] == $username and $row['password'] == $password and $row['role'] == "Operator"  and $row['is_active'] == "1") {
    $_SESSION['user_id'] = $row['user_id'];
    $_SESSION['user_name'] = $row['username'];
    $_SESSION['fullname'] = $row['fullname'];
    $_SESSION['magasin'] = $row['magasin'];
    $_SESSION['role'] = $row['role'];
    $message = 'success';

    $_SESSION['derniere_action'] = time();

    header('refresh:2;dashboard.php');
  }
  /*else if ($row['username']==$username AND $row['password']==$password AND $row['role']=="customer" AND $row['is_active']=="1"){
        $_SESSION['user_id']=$row['user_id'];
        $_SESSION['username']=$row['username'];
        $_SESSION['fullname']=$row['fullname'];
        $_SESSION['role']=$row['role'];
        $message = 'success';

        header('refresh:2;boutique.php');
    }*/ else {

    $errormsg = 'error';
  }
}


//////////////////////////////////////// FIN ESPACE ENTREPRISE \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

?>


<?php
require_once("header_login.php");
$_SESSION['blockrefresh'] = 0;

?>


<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>IPOS | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/iCheck/square/blue.css">

  <link rel="shortcut icon" href="img/logo1.jpg">

  <!-- jQuery 3 -->
  <script src="bower_components/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap 3.3.7 -->
  <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <!-- iCheck -->
  <script src="plugins/iCheck/icheck.min.js"></script>
  <!--Sweetalert Plugin --->
  <script src="bower_components/sweetalert/sweetalert.js"></script>


  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="hold-transition login-page">


  <section class="u-clearfix u-image u-section-1" id="carousel_345e">
    <div class="u-clearfix u-sheet u-sheet-1">
      <div class="u-align-center u-container-style u-group u-shape-rectangle u-group-1">
        <div class="u-container-layout u-container-layout-1">
          <div class="u-border-6 u-border-palette-1-base u-opacity u-opacity-95 u-shape u-shape-svg u-text-white u-shape-1">

            <div class="login-box">
              <div class="login-logo">
                <!-- <a href="index.php"><b>CASH-SOFT|SOCIETE NGNO-KWE SERVICES SARL</b></a> -->
              </div>
              <!-- /.login-logo -->
              <div class="login-box-body">
                <p class="login-box-msg"></p>

                <form action="" method="post" autocomplete="off">
                  <div class="form-group has-feedback">
                    <input type="text" placeholder="Username" name="username" class="u-border-1 u-border-grey-30 u-input u-input-rectangle" required>
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                  </div>
                  <div class="form-group has-feedback">
                    &nbsp;
                  </div>
                  <div class="form-group has-feedback">
                    <input type="password" placeholder="Password" name="password" class="u-border-1 u-border-grey-30 u-input u-input-rectangle" required>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                  </div>
                  <div class="row">
                    <!-- /.col -->
                    <div class="col-xs-4">
                      <center> <button type="submit" class="u-btn u-btn-submit u-button-style" name="btn_login">Connexion</button></center>
                    </div>
                  </div>
                  <!-- >   <-->


                  <!-- <svg class="u-svg-link" preserveAspectRatio="none" viewBox="0 0 160 160" style=""><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-db41"></use></svg>-->
                  <svg class="u-svg-content" viewBox="-3 -3 166 166" x="0px" y="0px" id="svg-db41" style="enable-background:new 0 0 160 160;">
                    <path d="M80,30c27.6,0,50,22.4,50,50s-22.4,50-50,50s-50-22.4-50-50S52.4,30,80,30 M80,0C35.8,0,0,35.8,0,80s35.8,80,80,80
	s80-35.8,80-80S124.2,0,80,0L80,0z"></path>
                  </svg>
              </div>





            </div>
          </div>
        </div>
  </section>
  <?php
  if (!empty($message)) {
    echo '<script type="text/javascript">
              jQuery(function validation(){
              swal("Login Success", "Welcome ' . $_SESSION['role'] . '", "success", {
              button: "Continue",
                });
              });
              </script>';
  } else {
  }
  if (empty($errormsg)) {
  } else {
    echo '<script type="text/javascript">
              jQuery(function validation(){
              swal("Login Fail", "Username or Password is Wrong!", "error", {
              button: "Continue",
                });
              });
          </script>';
  }
  ?>
  </form>
  </div>
  <!-- /.login-box-body -->
  </div>
  <!-- /.login-box -->

  <script>
    $(function() {
      $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' /* optional */
      });
    });
  </script>


  <?php
  require_once("footer.php");
  ?>