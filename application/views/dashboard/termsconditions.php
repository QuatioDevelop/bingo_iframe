<?php
defined('BASEPATH') OR exit('No direct script access allowed');

    //$permissions = $this->session->userdata('user_data')['permissions'];
    $role = $this->session->userdata('user_data')['user_role'];

    //$showAdmin = (!empty($permissions) && ($permissions['2']['read'] || $permissions['3']['read']));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('dashboard/meta_dashboard'); ?>
    <script>
    var base_url = "<?php echo base_url(); ?>";
    var sid = <?php echo get_set_id(); ?>;
     
    </script>
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">
    <?php $this->load->view('layouts/sidebar'); ?>    

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <?php //$this->load->view('layouts/navbar'); ?>
        <!--?php $this->load->view('layouts/navbar'); ?-->
        <div class="banner-l align-items-center justify-content-between mb-4" style="background-color: <?php echo $this->config->item('banner_bg_color'); ?>;">
            <img src="<?= base_url();?>assets/template/img/Banner_1024x128.png">
        </div>
        <div class="banner-s align-items-center justify-content-between mb-4" style="background-color: <?php echo $this->config->item('banner_bg_color'); ?>;">
            <img src="<?= base_url();?>assets/template/img/Banner_512x128.png">
        </div>

        <!-- Begin Page Content -->
        <div class="container-fluid">
            <div class="row-fluid" style="font-size: 20px;">
            
              <br>
              <p style="text-align: center;">
              <strong>
                <span style="text-align: start; color: <?php echo $this->config->item('primary_btn_color'); ?>;font-size: 30px;">Términos y condiciones de la actividad<br>BINGO <?php echo strtoupper(get_set_name()); ?></span>
              </strong>
              </p>
              <div class="text-cent">
                <a class="btn btn-primary" href="<?= base_url() ?>termsconditions/accepttyc" style="background-color: <?php echo $this->config->item('primary_btn_color'); ?>; border-color: <?php echo $this->config->item('primary_btn_color'); ?>; color: <?php echo $this->config->item('primary_btn_font_color'); ?>;">Aceptar</a>
              </div>
              <br>
              <p>Este bingo es un evento privado organizado por la empresa <?php echo get_set_name(); ?>.</p>
              <br>
              <ul>
              <li>Se jugarán binguitos que podrán ser los clásicos con la fila de las letras o con alguna forma reconocida y que se identificará claramente en la pantalla durante el juego. Luego de ellos se jugará un bingo para llenar la tabla completa. Si la empresa lo define, podrán jugarse binguitos adicionales.</li>
              <li>Cada participante puede recibir máximo 1 premio durante el evento, por lo tanto, cuando alguien resulte ganador de alguno de los premios no podrá participar con ninguna de sus tablas para ganar en los siguientes bingos o binguitos.</li>
              </ul>
              <br>
              <p>Plataforma:</p>
              <ul>
              <li>Para cantar bingo, una vez se completen los números de la figura que se está jugando, el usuario debe presionar el botón que dice “bingo” ubicado en la parte inferior de cada tabla, una vez se presione este botón aparecerá el siguiente mensaje de confirmación si se tienen todas las balotas: “Su bingo ha sido enviado exitosamente” o el mensaje: “Parece que no cumples con los criterios de bingo, por favor verifica tu tabla” si no se tienen todas las balotas para cantar. 
              <ul>
              <li>Si no sale ninguno de esos mensajes es posible que se tenga un problema con la conexión a internet del dispositivo, se debe confirmar dicha conexión y actualizar la página, o comunicarse con el soporte del bingo a través del icono de WhatsApp.</li>
              </ul></li>
              <li>Se debe cantar bingo desde la tabla específica donde se completó el bingo, no son válidas otras formas para cantar bingo.</li>
              <li>Para el adecuado funcionamiento de la plataforma es necesario que el usuario cuente con una conexión de internet estable, además de un equipo (computador, tableta o celular) que se encuentre en un estado de funcionamiento óptimo y así evitar dificultades técnicas. La empresa no se hace responsable por fallos o restricciones técnicas particulares de los equipos con los que la persona se conecte.</li>
              <li>La empresa no se hace responsable por restricciones técnicas particulares que se puedan presentar para las personas que ingresan al bingo.</li>
              <li>La transmisión de video de la plataforma es un evento en tiempo real, como todas las plataformas de streaming presenta un retraso que puede variar, pero se encuentra aproximadamente alrededor de los 15 segundos para garantizar la mejor calidad a los espectadores, es importante tener en cuenta que este retraso es igual para todos los usuarios y por tanto no presentando ninguna desventaja para ninguno de los jugadores.</li>
              </ul>

              <p>Los demás términos y condiciones se determinarán durante la transmisión.</p>
              <br>
              <div class="text-cent">
                <a class="btn btn-primary" href="<?= base_url() ?>termsconditions/accepttyc" style="background-color: <?php echo $this->config->item('primary_btn_color'); ?>; border-color: <?php echo $this->config->item('primary_btn_color'); ?>; color: <?php echo $this->config->item('primary_btn_font_color'); ?>;">Aceptar</a>
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
  <a href="https://api.whatsapp.com/send?phone=<?php echo $this->config->item('supportnumber'); ?>&text=Hola,%20yo%20soy%20el%20usuario%20<?php echo $this->session->userdata('user_data')['user_uname']; ?>,%20estoy%20en%20los%20términos%20y%20condiciones%20y%20necesito%20soporte%20en%20el%20bingo%20<?php echo get_set_name(); ?>." class="float" target="_blank"><i class="fab fa-fw fa-whatsapp my-float"></i></a>
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

  <script type="text/javascript">
    $(function(){
        var base_url= "<?php echo base_url();?>";
      });
  </script>
</body>

</html>
