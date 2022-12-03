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

  <title>Bingo - Register</title>

  <link rel="icon" type="image/png" href="<?= base_url();?>assets/template/img/icon.png">
  <!-- Custom fonts for this template-->
  <link href="<?= base_url() ?>assets/template/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="<?= base_url() ?>assets/template/css/sb-admin-2.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

  <div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5">
      <div class="card-body p-0">
        <!-- Nested Row within Card Body -->
        <div class="row">
          <!--div class="col-lg-5 d-lg-block bg-register-image" style="background-color: <?php echo $this->config->item('login_img_bg_color'); ?>;"></div-->
          <div class="col-lg-7 m-auto">
            <div class="p-5">
              <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4"><?php echo $this->lang->line('login_createaccount_lb'); ?></h1>
              </div>
              <?php if($this->session->flashdata("error")):?>
                <div class="alert alert-danger">
                  <p><?php echo $this->session->flashdata("error")?></p>
                </div>
              <?php endif; ?>
              <form class="user" action="<?= base_url() ?>register/store" method="post">
                <div class="form-group">
                  <input type="email" class="form-control form-control-user" id="name" name="name" placeholder="<?php echo $this->lang->line('register_name_lb'); ?>" value="<?php echo set_value('name');?>">
                  <?php echo form_error("name","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="text" class="form-control form-control-user" id="user_name" name="user_name" placeholder="<?php echo $this->lang->line('register_username_lb'); ?>" value="<?php echo set_value('user_name');?>">
                    <?php echo form_error("user_name","<span class='help-block'>","</span>");?>
                  </div>
                  <div class="col-sm-6">
                    <input type="email" class="form-control form-control-user" id="email" name="email" placeholder="<?php echo $this->lang->line('register_email_lb'); ?>" value="<?php echo set_value('email');?>">
                    <?php echo form_error("email","<span class='help-block'>","</span>");?>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="text" class="form-control form-control-user" id="phone"  name="phone" placeholder="Teléfono" value="<?php echo set_value('phone');?>">
                    <?php echo form_error("phone","<span class='help-block'>","</span>");?>
                  </div>
                  <div class="col-sm-6">
                    <input type="text" class="form-control form-control-user" id="cellphone"  name="cellphone" placeholder="Celular" value="<?php echo set_value('cellphone');?>">
                    <?php echo form_error("cellphone","<span class='help-block'>","</span>");?>
                  </div>
                </div>
                <div class="form-group">
                  <input type="email" class="form-control form-control-user" id="address" name="address" placeholder="Barrio" value="<?php echo set_value('address');?>">
                  <?php echo form_error("address","<span class='help-block'>","</span>");?>
                </div>
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="<?php echo $this->lang->line('login_pass_tf'); ?>" value="<?php echo set_value('password');?>">
                    <?php echo form_error("password","<span class='help-block'>","</span>");?>
                  </div>
                  <div class="col-sm-6">
                    <input type="password" class="form-control form-control-user" id="repeat_password" name="repeat_password" placeholder="<?php echo $this->lang->line('register_rptpass_lb'); ?>" value="<?php echo set_value('repeat_password');?>">
                    <?php echo form_error("repeat_password","<span class='help-block'>","</span>");?>
                  </div>
                </div>
                <div class="form-check">
                  <!--div class="col-sm-6 row"-->
                    <input class="form-check-input" type="checkbox" id="adult" name="adult" value="<?php echo set_checkbox('adult');?>">
                    <label class="form-check-label" for="adult"> Soy mayor de edad</label><br>
                    <?php echo form_error("adult","<span class='help-block'>","</span>");?>
                  <!--/div-->
                </div>
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" id="tyc" name="tyc" value="<?php echo set_checkbox('tyc');?>">
                    <label class="form-check-label" for="tyc"> Acepto términos y condiciones</label><br>
                    <?php echo form_error("tyc","<span class='help-block'>","</span>");?>
                </div>
                <button type="submit" class="btn btn-primary btn-user btn-block" style="background-color: <?php echo $this->config->item('primary_btn_color'); ?>; border-color: <?php echo $this->config->item('primary_btn_color'); ?>; color: <?php echo $this->config->item('primary_btn_font_color'); ?>;">
                  <?php echo $this->lang->line('register_regbtn_lb'); ?>
                </button>                
              </form>
              <hr>
              <!--div class="text-center">
                <a class="small" href="forgot-password.html">Forgot Password?</a>
              </div-->
              <div class="text-center">
                <a class="small" href="<?= base_url() ?>login" ><?php echo $this->lang->line('register_alreadyregbtn_lb'); ?></a>
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
