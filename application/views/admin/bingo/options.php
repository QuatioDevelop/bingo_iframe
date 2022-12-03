<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$roomPatterns = array();

//print_r($winningpatternarray);
foreach($winningpatternarray as $rwp)
{
  array_push($roomPatterns,$rwp->id);
}
//print_r($roomPatterns);

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
  <script language=javascript src="<?php echo base_url() ?>assets/template/js/scripts.js"></script>
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">
    <?php $this->load->view('layouts/sidebar'); ?>    

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column offset-3">

      <!-- Main Content -->
      <div id="content">

        <?php $this->load->view('layouts/navbar'); ?>

         <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800"><?php echo $this->lang->line('admin_bingoroom_title')." ".$this->config->item('room'); ?></h1>
                
            </div>
            <div class="row">

              <div class="col-md-12 text-cent" >
      
      <h2>Escoge los patrones para ganar:</h2>
        
    </div>

      <div class="col-md-12" >
     <form name="configForm" action="<?= base_url() ?>admin/bingo/saveOptions" method="post">

        <div class="form-group">
          <label for="iFrame">Código del video</label>
          <input type="text" class="form-control" name="iFrame" placeholder="iFrame" maxlength="50" value="<?php echo isset($iframe) ? $iframe->iframe : '' ?>">
        </div> 
        <div class="form-group">
          <label for="channel">Canal del video (Twitch)</label>
          <input type="text" class="form-control" name="channel" placeholder="channel" maxlength="50" value="<?php echo isset($channel) ? $channel->winning_patterns : '' ?>">
        </div> 

     <br>
     <table class="opt-table" border="1">
        <tr>
          <td>
            <table border="0">
              <?php if(!empty($winningpatterns)):?>
                <?php foreach($winningpatterns as $wp):?>
                    <tr>
                        <td><img src="<?= base_url() ?>assets/template/img/<?php echo $wp->image;?>" style="width: 60px;"></td>
                        <td width="400" colspan="2">
                          <input type="checkbox" name="winningpatternform<?php echo $wp->id;?>" <?php echo (in_array($wp->id, $roomPatterns))?"checked":""; ?>>
                  &nbsp;&nbsp;&nbsp;<?php echo $wp->name;?>
                        </td>
                    </tr>
                <?php endforeach;?>
              <?php endif;?>              
            </table>
         </td>
      </tr>
    </table>
    <br>        
    <br>
    <div class="col-md-12 text-cent" >
      <button type="submit" name="submit" class="btn btn-primary" style="background-color: <?php echo $this->config->item('primary_btn_color'); ?>; border-color: <?php echo $this->config->item('primary_btn_color'); ?>; color: <?php echo $this->config->item('primary_btn_font_color'); ?>;">Guardar</button>  
      <a href="<?= base_url() ?>admin/bingo" class="btn btn-secondary" >Volver</a>   
    </div>
     </form>
    </div>
            
              
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

</body>

</html>
