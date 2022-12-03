<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="google" content="notranslate" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-VYQ028XZGT"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-VYQ028XZGT');
  </script>

  <title>Bingo Virtual - Login Host</title>

  <link rel="icon" type="image/png" href="<?= base_url();?>assets/template/img/icon.png">
  <!-- Custom fonts for this template-->
  <link href="<?= base_url() ?>assets/template/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="<?= base_url() ?>assets/template/css/sb-admin-2.css" rel="stylesheet">
  <link href="<?= base_url() ?>assets/template/css/xpell.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <!--div class="col-lg-6 d-lg-block bg-login-image" style="background-color: <?php echo $this->config->item('login_img_bg_color'); ?>;"></div-->
              <div class="col-lg-6 center">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4"><?php echo $this->lang->line('login_tittle_lb'); ?></h1>
                  </div>
                  <?php if($this->session->flashdata("error")):?>
  		              <div class="alert alert-danger">
  		                <p><?php echo $this->session->flashdata("error")?></p>
  		              </div>
  		            <?php endif; ?>
                  <form class="user" action="<?= base_url() ?>login/iniciarsesionhost" method="post">
                    <div class="form-group">
                      <input type="text" class="form-control form-control-user" id="user_name" name="user_name" placeholder="<?php echo $this->lang->line('login_user_tf'); ?>"  <?php if(!empty(form_error('user_name'))){ echo 'value="'.set_value('user_name').'"';}?>>
                    </div>
                    <!--div class="form-group">
                      <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="<?php //echo $this->lang->line('login_pass_tf'); ?>">
                    </div-->
                    <button type="submit" class="btn btn-primary btn-user btn-block" style="background-color: <?php echo $this->config->item('primary_btn_color'); ?>; border-color: <?php echo $this->config->item('primary_btn_color'); ?>; color: <?php echo $this->config->item('primary_btn_font_color'); ?>;">
                      <?php echo $this->lang->line('login_lgnbtn_lb'); ?>
                    </button>
                  </form>
                  <!--hr-->
                  <!--div class="text-center">
                    <a class="small" href="forgot-password.html">Forgot Password?</a>
                  </div-->
                  <!--div class="text-center">
                    <a class="small" href="<?= base_url() ?>register"><?php //echo $this->lang->line('login_createaccount_lb'); ?></a>
                  </div-->
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="<?= base_url() ?>assets/template/vendor/jquery/jquery.min.js"></script>
  <script src="<?= base_url() ?>assets/template/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="<?= base_url() ?>assets/template/vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="<?= base_url() ?>assets/template/js/sb-admin-2.min.js"></script>

</body>

</html>