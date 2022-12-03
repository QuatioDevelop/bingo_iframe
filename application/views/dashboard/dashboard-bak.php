<?php
defined('BASEPATH') OR exit('No direct script access allowed');

    //$permissions = $this->session->userdata('user_data')['permissions'];
    //$role = $this->session->userdata('user_data')['user_role'];
    $uid = $uid;
    
    //$showAdmin = (!empty($permissions) && ($permissions['2']['read'] || $permissions['3']['read']));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('dashboard/meta_dashboard'); ?>
    <script>
    var base_url = "<?php echo base_url(); ?>";
    var sid = <?php echo get_set_id(); ?>;
    var activityCount = 0;
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
        activityCount++;
        if(activityCount == 4)
        {
          activityCount = 0;
          $.ajax({
              async:  true, 
              type: "POST",
              url: base_url+"dashboard/logactivity",
              data: "id=<?php echo $uid; ?>",
              dataType:"html",
              success: function(data)
              { 
                console.log("Activity Count");
                console.log(data);
              },
              error: function( jqXHR, textStatus, errorThrown ) {
                  console.log(jqXHR.status, textStatus, errorThrown);
                  log_e(jqXHR.status,textStatus+"-"+errorThrown,"dashboard-cleanBingo");
                  //alert("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
                  $('#myModal .modal-body').html('Se detectó problema en la red, código:'+jqXHR.status+'. Por favor actualiza la página, no perderás las balotas marcadas');
                  $('#myModal').modal('show');
              }
          }).fail( function( jqXHR, textStatus, errorThrown ) {
            console.log(jqXHR.status, textStatus, errorThrown);
            log_e(jqXHR.status,textStatus+"-"+errorThrown,"dashboard-cleanBingo");
            //alert("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
            $('#myModal .modal-body').html('Se detectó problema en la red, código:'+jqXHR.status+'. Por favor actualiza la página, no perderás las balotas marcadas');
            $('#myModal').modal('show');
        });
        }
      });
      //checkbingo();
      
    });

    function printCard() {
      var printButton = document.getElementById("imprimir-btn");
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
          data: "id=<?php echo $uid; ?>&sid="+sid+"&card="+cid,
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
              log_e(jqXHR.status,textStatus+"-"+errorThrown,"dashboard-cleanBingo");
              //alert("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
              $('#myModal .modal-body').html('Se detectó problema en la red, código:'+jqXHR.status+'. Por favor actualiza la página, no perderás las balotas marcadas');
              $('#myModal').modal('show');
          }

      }).fail( function( jqXHR, textStatus, errorThrown ) {
          console.log(jqXHR.status, textStatus, errorThrown);
          log_e(jqXHR.status,textStatus+"-"+errorThrown,"dashboard-cleanBingo");
          //alert("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
          $('#myModal .modal-body').html('Se detectó problema en la red, código:'+jqXHR.status+'. Por favor actualiza la página, no perderás las balotas marcadas');
          $('#myModal').modal('show');
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
          data: "id=<?php echo $uid; ?>&sid="+sid+"&card="+id,
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
              log_e(jqXHR.status,textStatus+"-"+errorThrown,"dashboard-doBingo");
              //alert("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
              $('#myModal .modal-body').html('No se pudo enviar tu bingo. Se detectó problema en la red, código:'+jqXHR.status+'. Por favor actualiza la página, no perderás las balotas marcadas');
              $('#myModal').modal('show');
          }

      }).fail( function( jqXHR, textStatus, errorThrown ) {
          console.log(jqXHR.status, textStatus, errorThrown);
          log_e(jqXHR.status,textStatus+"-"+errorThrown,"dashboard-doBingo");
          //alert("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
          $('#myModal .modal-body').html('No se pudo enviar tu bingo. Se detectó problema en la red, código:'+jqXHR.status+'. Por favor actualiza la página, no perderás las balotas marcadas');
          $('#myModal').modal('show');
      });
    }
    function checkbingo() 
    { 
      $.ajax({
      async:  true, 
        type: "POST",
        url: base_url+"dashboard/checkbingo",
        data: "id=<?php echo $uid; ?>&sid="+sid,
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
              log_e(jqXHR.status,textStatus+"-"+errorThrown,"dashboard-checkBingo");
              //alert("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
              $('#myModal .modal-body').html('No se pudo enviar tu bingo. Se detectó problema en la red, código:'+jqXHR.status+'. Por favor actualiza la página, no perderás las balotas marcadas');
              $('#myModal').modal('show');
          }
      }).fail( function( jqXHR, textStatus, errorThrown ) {
          console.log(jqXHR.status, textStatus, errorThrown);
          log_e(jqXHR.status,textStatus+"-"+errorThrown,"dashboard-checkBingo");
          //alert("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
          $('#myModal .modal-body').html('No se pudo enviar tu bingo. Se detectó problema en la red, código:'+jqXHR.status+'. Por favor actualiza la página, no perderás las balotas marcadas');
          $('#myModal').modal('show');
      });   
    }
    function log_e(jqxhr,text,place){
      $.ajax({
          async:  true, 
          type: "POST",
          url: base_url+"dashboard/log_e",
          data: "id=<?php echo $uid; ?>&jqxhr="+jqxhr+"&text="+text+"&place="+place,
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
        <div class="banner-l align-items-center justify-content-between mb-4">

              <img src="<?= base_url();?>assets/template/img/Banner_1024x128.png">

          </div>
          <div class="banner-s align-items-center justify-content-between mb-4">

              <img src="<?= base_url();?>assets/template/img/Banner_512x128.png">

          </div>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">

                <!--div class="video-container">
                <iframe  src="https://www.youtube.com/embed/<?php echo $iframe->iframe; ?>?autoplay=1&origin=<?php echo $_SERVER['HTTP_HOST'] ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <div class="video-chat">
                <iframe  src="https://www.youtube.com/live_chat?v=<?php echo $iframe->iframe; ?>&embed_domain=<?php echo $_SERVER['HTTP_HOST'] ?>" frameborder="0"></iframe>
                </div-->

                <div class="video-container">
                  <!--<iframe src="https://player.vimeo.com/video/466903243" frameborder="0" allow="autoplay; fullscreen" allowfullscreen ></iframe>-->
                  <iframe src="https://player.vimeo.com/video/480743365" frameborder="0" allow="autoplay; fullscreen" allowfullscreen ></iframe>
                </div>
                <div class="video-chat">
                  <!--<iframe src="https://vimeo.com/live-chat/466903243/043f851b1d"  frameborder="0"></iframe>-->
                  <iframe src="https://vimeo.com/live-chat/480743365/b8e296d64b" frameborder="0"></iframe>  
                </div>
                
          </div>

          <!-- Content Row -->
          <div class="row">

            <div id="tabla" class = "card-container">
              <?php 
              //echo $viewheader."<br>";
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
              //if(get_table_id($uid) != "[Error]") print_r(check_table("", get_table_id($uid)[0]->table_index));
              display_pretty_card(get_table_id($uid), true, true);
              //get_table_id($uid);
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
  <div class="chat-popup" id="myForm">
    <iframe src="https://vimeo.com/live-chat/466903243/043f851b1d" frameborder="0"></iframe>
    <!--iframe src="https://www.youtube.com/live_chat?v=<?php echo $iframe->iframe; ?>&embed_domain=<?php echo $_SERVER['HTTP_HOST'] ?>" frameborder="0"></iframe-->
  </div>
  <!--div class="chat-popup" id="myForm">
    <iframe  src="https://www.youtube.com/live_chat?v=<?php echo $iframe->iframe; ?>&embed_domain=<?php echo $_SERVER['HTTP_HOST'] ?>" frameborder="0"></iframe>
  </div-->
  <!-- End of Page Wrapper -->
  <a id="support-btn" href="https://api.whatsapp.com/send?phone=<?php echo $this->config->item('supportnumber'); ?>&text=Hola,%20yo%20soy%20el%20usuario%20<?php echo $uid; ?>,%20estoy%20en%20el%20bingo%20<?php echo get_set_name(); ?>%20y%20necesito%20soporte." class="float" target="_blank"><i class="fab fa-fw fa-whatsapp my-float"></i></a>

  <button id="chat-movile-btn" class="float-r" onclick="toggleChat()" target="_blank" style="visibility: hidden;"><i class="fas fa-fw fa-comment"></i></button>

  <button id="imprimir-btn" class="float-r-2" onclick="imprimir()" target="_blank"><i class="fas fa-fw fa-print"></i></button>

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>
  

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"><?php echo $this->lang->line('common_modal_close_title'); ?></h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body"><?php echo $this->lang->line('common_modal_close_body'); ?></div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal"><?php echo $this->lang->line('common_modal_close_btn_cancel'); ?></button>
          <a class="btn btn-primary" href="<?= base_url() ?>login/logout"><?php echo $this->lang->line('common_modal_close_btn_close'); ?></a>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
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
    function toggleChat() {
      $("#myForm").toggle();
    }
    function imprimir(){

      let divToPrint = document.getElementById('tabla');

      let newWin = window.open('','Print-Window');

      newWin.document.open();

      newWin.document.write('<script type="text/javascript" src="<?= base_url() ?>assets/template/js/sb-admin-2.js"></scr'+'ipt>');

      newWin.document.write('<script type="text/javascript" src="<?= base_url() ?>assets/template/vendor/jquery/jquery.min.js"></scr'+'ipt>');
      newWin.document.write('<script type="text/javascript" src="<?= base_url() ?>assets/template/vendor/bootstrap/js/bootstrap.bundle.min.js"></scr'+'ipt>');
      newWin.document.write('<script type="text/javascript" src="<?= base_url() ?>assets/template/vendor/jquery-easing/jquery.easing.min.js"></scr'+'ipt>');
      newWin.document.write('<link href="<?php echo base_url() ?>assets/template/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">');
      newWin.document.write('<link href="<?php echo base_url() ?>assets/template/css/sb-admin-2.css" rel="stylesheet">');
      newWin.document.write('<link href="<?php echo base_url() ?>assets/template/css/xpell.css" rel="stylesheet">');
      //newWin.document.write('<html><body onload="setTimeout(function(){window.print();},5000);">'+divToPrint.innerHTML+'</body></html>');
      newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

      newWin.document.close();

      /*setTimeout(function(){
        newWin.close();
      },500000);*/

      //$('#tabla').printThis();
      //printCard();
      
    }
  </script>
</body>

</html>
