<?php
defined('BASEPATH') OR exit('No direct script access allowed');

    //$permissions = $this->session->userdata('user_data')['permissions'];
    $role = $this->session->userdata('user_data')['user_role'];
    $setid = get_set_id();
    //$showAdmin = (!empty($permissions) && ($permissions['2']['read'] || $permissions['3']['read']));
    
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('dashboard/meta_dashboard'); ?>
    <style type="text/css">
      .number {
          background-color: <?php echo $this->config->item('card_number_bg_color'); ?>;
      }
      .checked .number {
          background-color: <?php echo $this->config->item('card_selected_bg_color'); ?>;
          color: <?php echo $this->config->item('card_selected_font_color'); ?>;
          border-color: <?php echo $this->config->item('card_selected_border_color'); ?>;
      }
    </style>
    <script>
    var base_url = "<?php echo base_url(); ?>";
    var sid = <?php echo $setid; ?>;
    

    function printCard() {
      var printButton = document.getElementById("buttonsHide");
      printButton.style.visibility = 'hidden';
        window.print();
        printButton.style.visibility = 'visible';
    }
    function back(){
      window.location.href="index.php";
    }
    function doBingo(uid, cid){
      //$('#sres').html("id="+uid+"&sid="+sid+"&card="+cid);
      $('#sres').html("");
      $.ajax({
          async:  true, 
          type: "POST",
          url: base_url+"dashboard/dobingo",
          data: "id="+uid+"&sid="+sid+"&card="+cid,
          dataType:"html",
          success: function(data)
          { 
            console.log(data);
            //$('#div'+tipo).html(data);
            var json = JSON.parse(data);
            console.log(json);
            if(json.response != "Already")
            {
              if(Object.keys(json.wins).length > 0 && Object.keys(json.wins[cid]).length > 0)
              {
                $('#sres').html('El Bingo ha sido enviado exitosamente.');
              }
              else
              {
                $('#sres').html('Parece que no cumple con los criterios de bingo, por favor verifica tu tabla.');
              }
            }else{
              $('#sres').html('El Bingo ya se había enviado.');
            }
          },
          error: function( jqXHR, textStatus, errorThrown ) {
              console.log(jqXHR.status, textStatus, errorThrown);
              alert("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
          }
      }).fail( function( jqXHR, textStatus, errorThrown ) {
          console.log(jqXHR.status, textStatus, errorThrown);
          alert("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
      });
    }

  </script>
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">
    <?php //$this->load->view('layouts/sidebar'); ?>    

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column" style="margin-left: 0;">

      <!-- Main Content -->
      <div id="content">

        <?php //$this->load->view('layouts/navbar'); ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Content Row -->
          <div class="row mt-4">
            <!-- Content Col -->
            <div class="col">
              <div class = "card-container" style="display: inherit;">
                <?php 
                echo '<p style="text-align: center;"><span class="nameCard">';
                echo $clampName;
                echo "</span></p>";
                //echo '<div style="text-align: center; margin: 15px;"><button onclick="back()" id= "backBtn" class="btn btn-primary">Regresar</button></div>';
                //print_r($this->bingo_model->getCard($setid,$card));
                $c = (object)[];
                $c->card = $this->bingo_model->getCard($setid,$card);
                $c->table_index = $card;
                display_pretty_card(array($c), false);
                //get_table_id($this->session->userdata('user_data')['user_uname']);
                // echo "<br>".$viewfooter;
                ?>
                <div id="buttonsHide" style="text-align: center; margin: 15px;">
                  <button onclick="doBingo('<?php echo $user_id; ?>','<?php echo $card; ?>')" id= "checkWinnerBtn" class="btn btn-primary">Bingo</button>
                </div>
                <div id="sres" style="text-align: center; margin: 15px;"></div>
              </div>
            </div>
            </div>
          </div>
        </div>
        <!-- /.container-fluid -->
      </div>
      <!-- End of Main Content -->

        <?php //$this->load->view('layouts/footer'); ?>

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

  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content" style="color: <?php echo $this->config->item('main_font_color'); ?>;">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Advertencia</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Este Bingo ha terminado, si deseas seguir jugando debes asignar una tabla nuevamente.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Aceptar</button>
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
  <script src="<?= base_url() ?>assets/template/js/sb-admin-2.js"></script>

</body>

</html>
