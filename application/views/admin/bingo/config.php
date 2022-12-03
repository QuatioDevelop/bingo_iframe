<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("America/Bogota");
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

  <title>Bingo - <?php echo $this->lang->line('admin_bingo_title'); ?></title>

  <link rel="icon" type="image/png" href="<?= base_url();?>assets/template/img/icon.png">
  <!-- Custom fonts for this template -->
  <link href="<?= base_url() ?>assets/template/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <!-- JQuery-UI -->
  <link rel="stylesheet" href="<?= base_url() ?>assets/template/vendor/jquery-ui-1.12.1/jquery-ui.min.css">
  <!-- Custom styles for this template -->
  <link href="<?= base_url() ?>assets/template/css/sb-admin-2.css" rel="stylesheet">

  <!-- Custom styles for this page -->
  <link href="<?= base_url() ?>assets/template/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href="<?php echo base_url() ?>assets/template/css/xpell.css" rel="stylesheet">
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">
    <?php $this->load->view('layouts/sidebar'); ?>    

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <?php $this->load->view('layouts/navbar'); ?>

         <!-- Begin Page Content -->
        <div class="container-fluid">

             <?php echo form_error("room_name","<span class='help-block'>","</span>");?>
             <?php echo form_error("time","<span class='help-block'>","</span>");?>
             <?php echo form_error("date","<span class='help-block'>","</span>");?>
            <div class="col-md-12" id="infoDiv" >

            <h2>Nombre de sala <?php if(isset($id) && isset($name)) echo $id.": ".$name; ?></h2>
            <h2>Fecha: <?php if(isset($date)) echo $date; ?></h2>
            <h2>Hora: <?php if(isset($date) && !empty($time)){echo mb_strtolower(strftime("%r", strtotime($time))); }else{ echo "";}?></h2>

              <?php 
                if (isset($messageGenerate)) {
                  echo $messageGenerate;
                }
               ?> 
            <button class="btn btn-primary" onclick="showEditDiv()" style="background-color: <?php echo $this->config->item('primary_btn_color'); ?>; border-color: <?php echo $this->config->item('primary_btn_color'); ?>; color: <?php echo $this->config->item('primary_btn_font_color'); ?>;">Nuevo Bingo</button>
          <a href="<?= base_url() ?>admin/bingo" class="btn btn-primary">Volver</a>   
                
      </div>
      <div class="col-md-6" id="editDiv" style="display:none;">
        <form action="<?= base_url() ?>admin/bingo/saveConfig"  method="post">
          <div class="form-group">
            <label for="room_name">Nombre de la sala</label>
            <input type="text" class="form-control" name="room_name" placeholder="Nombre de la Sala" maxlength="50" value="<?php echo get_set_name(); ?>">

          </div>    

          <div class="form-group">
            <label for="date">Fecha del Bingo</label>
            <input type="text" class="form-control" name="date" placeholder="aaaa-mm-dd" value="<?php echo date('Y-m-d')?>">
            <?php 
              if (isset($dateErr)) {
                echo "<p class=\"alert alert-danger\">".$dateErr."</p>";
              }
            ?>  
          </div>    

          <div class="form-group">
            <label style="display: block;" for="time">Hora del bingo</label>
            <input style="display: inline; width: 80%;" class="form-control col-sd-4" name="time" placeholder="hh:mm:ss" value="<?php echo date('H:i:s') ?>" type="text">
            <?php 
              if (isset($timeErr)) {
                echo "<p class=\"alert alert-danger\">".$timeErr."</p>";
              }
            ?>  
          </div>    
          <div class="form-group">
            <button type="submit" class="btn btn-primary" style="background-color: <?php echo $this->config->item('primary_btn_color'); ?>; border-color: <?php echo $this->config->item('primary_btn_color'); ?>; color: <?php echo $this->config->item('primary_btn_font_color'); ?>;">Aceptar</button>
            <a class="btn btn-danger" onclick="hideEditDiv()">Cancelar</a>
          </div>  
        </form>

  </div>
    </div>
        <!-- /.container-fluid -->

</div>
      <!-- End of Main Content -->

        <?php $this->load->view('layouts/footer'); ?>

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content" style="color: <?php echo $this->config->item('main_font_color'); ?>;">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"><?php echo $this->lang->line('common_modal_close_title'); ?></h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body"><?php echo $this->lang->line('common_modal_close_body'); ?></div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal"><?php echo $this->lang->line('common_modal_close_btn_cancel'); ?></button>
          <a class="btn btn-primary" href="<?= base_url() ?>login/logout" style="background-color: <?php echo $this->config->item('primary_btn_color'); ?>; border-color: <?php echo $this->config->item('primary_btn_color'); ?>; color: <?php echo $this->config->item('primary_btn_font_color'); ?>;"><?php echo $this->lang->line('common_modal_close_btn_close'); ?></a>
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
 
  <!-- Page level plugins -->
  <script src="<?= base_url() ?>assets/template/vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="<?= base_url() ?>assets/template/vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="<?= base_url() ?>assets/template/js/demo/datatables-demo.js"></script>
  <script type="text/javascript">
    function showEditDiv(){
      var infoDiv = document.getElementById("infoDiv"); 
      infoDiv.style.display = 'none';

      var infoDiv = document.getElementById("editDiv"); 
      infoDiv.style.display = 'inline-block';
    }
    function hideEditDiv(){
      var infoDiv = document.getElementById("editDiv"); 
      infoDiv.style.display = 'none';

      var infoDiv = document.getElementById("infoDiv"); 
      infoDiv.style.display = 'inline-block';


    }

    // $(function () {
    //  $('#datetimepicker1').datetimepicker();
    // });

  </script>
</body>

</html>
