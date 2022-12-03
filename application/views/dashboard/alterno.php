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
    $(function() {
      $(".number").click(function() {
        if($(this).closest('td').hasClass("checked"))
        {
          $(this).closest('td').addClass("unchecked");
          $(this).closest('td').removeClass("checked");
      }else
      {
          $(this).closest('td').addClass("checked");
          $(this).closest('td').removeClass("unchecked");
      }
      });
      //checkbingo();
      
    });

    function printCard() {
      var printButton = document.getElementById("buttonsHide");
      printButton.style.visibility = 'hidden';
        window.print();
        printButton.style.visibility = 'visible';
    }
    function back(){
      window.location.href="index.php";
    }
    function cleanBingo(id,cid){
      $.ajax({
          async:  true, 
          type: "POST",
          url: base_url+"dashboard/cleanbingo",
          data: "id=<?php echo $this->session->userdata('user_data')['user_uname']; ?>&sid="+sid+"&card="+cid,
          dataType:"html",
          success: function(data)
          { 
            //console.log("-----------------");
            console.log(data);
            //$('#div'+tipo).html(data);
            //var json = JSON.parse(data);
            //var json = JSON.parse(data);
          },
          error: function( jqXHR, textStatus, errorThrown ) {
              console.log(jqXHR.status, textStatus, errorThrown);
              log_e(jqXHR.status,textStatus+"-"+errorThrown,"alterno-cleanBingo");
              $('#myModal .modal-body').html('Se detectó problema en la red, código:'+jqXHR.status+'. Por favor actualiza la página');
              $('#myModal').modal('show');
              //alert("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
          }

      }).fail( function( jqXHR, textStatus, errorThrown ) {
          console.log(jqXHR.status, textStatus, errorThrown);
          log_e(jqXHR.status,textStatus+"-"+errorThrown,"alterno-cleanBingo");
          $('#myModal .modal-body').html('Se detectó problema en la red, código:'+jqXHR.status+'. Por favor actualiza la página');
              $('#myModal').modal('show');
          //alert("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
      });
      $( "#"+id ).find( ".number" ).each(function() {
          $(this).closest('td').addClass("unchecked");
          $(this).closest('td').removeClass("checked");
      });
    }
    function doBingo(id){
      //console.log("doBingo("+id+")  "+sid);
      $.ajax({
          async:  true, 
          type: "POST",
          url: base_url+"dashboard/dobingo",
          data: "id=<?php echo $this->session->userdata('user_data')['user_uname']; ?>&sid="+sid+"&card="+id,
          dataType:"html",
          success: function(data)
          { 
            //console.log("-----------------");
            //console.log(data);
            //$('#div'+tipo).html(data);
            var json = JSON.parse(data);
            if(json.response != "Already")
            {
              if(Object.keys(json.wins).length > 0 && Object.keys(json.wins[id]).length > 0)
              {
                $('#myModal .modal-body').html('Tu Bingo ha sido enviado exitosamente.');
                $('#myModal').modal('show');
              }
              else
              {
                $('#myModal .modal-body').html('Parece que no cumples con los criterios de bingo, por favor verifica tu tabla.');
                $('#myModal').modal('show');
              }
            }else{
              $('#myModal .modal-body').html('Tu Bingo ya se había enviado, por favor espera a su revisión.');
              $('#myModal').modal('show');
            }
            //var json = JSON.parse(data);
          },
          error: function( jqXHR, textStatus, errorThrown ) {
              console.log(jqXHR.status, textStatus, errorThrown);
              log_e(jqXHR.status,textStatus+"-"+errorThrown,"alterno-doBingo");
              //alert("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
              $('#myModal .modal-body').html('No se pudo enviar tu bingo. Se detectó problema en la red, código:'+jqXHR.status+'. Por favor actualiza la página');
              $('#myModal').modal('show');
          }

      }).fail( function( jqXHR, textStatus, errorThrown ) {
          console.log(jqXHR.status, textStatus, errorThrown);
          log_e(jqXHR.status,textStatus+"-"+errorThrown,"alterno-doBingo");
          //alert("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
          $('#myModal .modal-body').html('No se pudo enviar tu bingo. Se detectó problema en la red, código:'+jqXHR.status+'. Por favor actualiza la página');
              $('#myModal').modal('show');
      });
    }
    function checkbingo() 
    { 
      $.ajax({
      async:  true, 
        type: "POST",
        url: base_url+"dashboard/checkbingo",
        data: "sid="+sid,
      dataType:"html",
        success: function(data)
      { 
        //console.log("-----------------");
        //console.log(data);

        var json         = JSON.parse(data);
        //console.log(json.sid+"  "+sid);
        id               = json.sid;
        if(id != sid)
        {
          $('#myModal .modal-body').html('Este Bingo ha terminado, si deseas seguir jugando debes asignar una tabla nuevamente.');
          $('#myModal').modal('show');
          $('#buttonBingo').hide();
        }
        
        //setTimeout('checkbingo()',10000);
              
        },
          error: function( jqXHR, textStatus, errorThrown ) {
              console.log(jqXHR.status, textStatus, errorThrown);
              log_e(jqXHR.status,textStatus+"-"+errorThrown,"alterno-checkBingo");
              //alert("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
              $('#myModal .modal-body').html('Se detectó problema en la red, código:'+jqXHR.status+'. Por favor actualiza la página');
              $('#myModal').modal('show');
          }
      }).fail( function( jqXHR, textStatus, errorThrown ) {
          console.log(jqXHR.status, textStatus, errorThrown);
          log_e(jqXHR.status,textStatus+"-"+errorThrown,"alterno-checkBingo");
          //alert("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
          $('#myModal .modal-body').html('Se detectó problema en la red, código:'+jqXHR.status+'. Por favor actualiza la página');
          $('#myModal').modal('show');
      });  
    }
    function log_e(jqxhr,text,place){
      $.ajax({
          async:  true, 
          type: "POST",
          url: base_url+"dashboard/log_e",
          data: "id=<?php echo $this->session->userdata('user_data')['user_uname']; ?>&jqxhr="+jqxhr+"&text="+text+"&place="+place,
          dataType:"html",
          success: function(data)
          { 
            //console.log("-----------------");
            console.log(data);
          }
      });
    }
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

        <!--?php $this->load->view('layouts/navbar'); ?-->
        <div class="banner-l align-items-center justify-content-between mb-4" style="background-color: <?php echo $this->config->item('banner_bg_color'); ?>;">
            <img src="<?= base_url();?>assets/template/img/Banner_1024x128.png">
        </div>
        <div class="banner-s align-items-center justify-content-between mb-4" style="background-color: <?php echo $this->config->item('banner_bg_color'); ?>;">
            <img src="<?= base_url();?>assets/template/img/Banner_512x128.png">
        </div>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">

                <div class="video-container alterno">
                <iframe  src="https://player.twitch.tv/?<?php echo $channel->winning_patterns; ?>&parent=<?php echo $_SERVER['HTTP_HOST'] ?>&muted=false" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                <!--iframe width="100%" height="450" src="https://www.youtube.com/embed/videoseries?list=PLx0sYbCqOb8TBPRdmBHs5Iftvv9TPboYG" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe-->
                </div>
                
          </div>

          <!-- Content Row -->
          <div class="row">

            <div class = "card-container">
              <?php 
            // echo $viewheader."<br>";
              /*echo '<p style="text-align: center;"><span class="nameCard">';

                $id = $_GET["cardnumber"];//get_table_id($_GET["cardnumber"]);
                $sql = "SELECT * FROM users WHERE user_id='".$id."'";
                $result = $mysqli->query($sql) or die($mysql->error());
                if ($row = mysqli_fetch_array($result)) {
                  $name = utf8_encode($row['name']);
                  $clampName = (strlen($name) > 53) ? substr($name,0,50).'...' : $name;
                  echo $clampName. " - ".$id;
                }
              echo "</span></p>";*/
              //echo '<div style="text-align: center; margin: 15px;"><button onclick="back()" id= "backBtn" class="btn btn-primary">Regresar</button></div>';
              //if(get_table_id($this->session->userdata('user_data')['user_uname']) != "[Error]") print_r(check_table("", get_table_id($this->session->userdata('user_data')['user_uname'])[0]->table_index));
              display_pretty_card(get_table_id($this->session->userdata('user_data')['user_uname']), true, true);
              //get_table_id($this->session->userdata('user_data')['user_uname']);
              // echo "<br>".$viewfooter;
              ?>
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
  <a href="https://api.whatsapp.com/send?phone=<?php echo $this->config->item('supportnumber'); ?>&text=Hola,%20yo%20soy%20el%20usuario%20<?php echo $this->session->userdata('user_data')['user_uname']; ?>,%20estoy%20en%20el%20bingo%20<?php echo get_set_name(); ?>%20y%20necesito%20soporte." class="float" target="_blank"><i class="fab fa-fw fa-whatsapp my-float"></i></a>
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

  <script type="text/javascript">
    $(function(){
        var base_url= "<?php echo base_url();?>";
      });
  </script>
</body>

</html>
