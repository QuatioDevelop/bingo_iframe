<?php
defined('BASEPATH') OR exit('No direct script access allowed');

    //$permissions = $this->session->userdata('user_data')['permissions'];
    $role = $this->session->userdata('user_data')['user_role'];

    //$showAdmin = (!empty($permissions) && ($permissions['2']['read'] || $permissions['3']['read']));

    //$numbercards = get_number_cards();
    // echo "numbercards ".$numbercards;
    
    if (isset($_GET["restart"])) restart();
    $draws=load_draws(); 
    //print_r($clearText);
    //echo "<br>";
    //print_r($sendnumber);

    $last = $this->session->flashdata('last');
    //print_r($last);
    if (isset($last))
    {
      $clearText = $last["clearText"];
      $sendnumber = $last["sendnumber"];
    }
    
    $roomPatternsImg = array();
    $roomPatterns = array();
    $currentRoomPattern = -1;

    //print_r($currentpattern);
    //print_r("  <br>  ");
    foreach($winningpatternarray as $rwp)
    {
      array_push($roomPatternsImg,"assets/template/img/".$rwp->image);
      array_push($roomPatterns,array('id' => $rwp->id, 'name' => $rwp->name));
      if($currentpattern != null && $currentpattern->id == $rwp->id)
      {
        $currentRoomPattern = sizeof($roomPatterns)-1;
      }
    }
    if($currentRoomPattern == -1 && sizeof($roomPatterns) > 0)
    {
      $currentRoomPattern = 0;
      setCurrentWinningPattern($roomPatterns[0]["id"]);
    }
    //print_r($roomPatterns);
    //print_r("  <br>  ");
    //print_r($currentRoomPattern);
        $draws = load_draws();
    
      $last_draws = getLastDraws(true);
      $ldl_1 = $last_draws['ldl_1'];
      $ldn_1 = $last_draws['ldn_1'];
      $ldl_2 = $last_draws['ldl_2'];
      $ldn_2 = $last_draws['ldn_2'];
      $ldl_3 = $last_draws['ldl_3'];
      $ldn_3 = $last_draws['ldn_3'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('dashboard/meta_dashboard'); ?>
    <script language=javascript src="<?php echo base_url() ?>assets/template/js/scripts.js"></script>
    <style type="text/css">
      .bingo-title {
          color: <?php echo $this->config->item('host_bingo_font_color'); ?>;
          border-top: 1px solid <?php echo $this->config->item('host_bingo_font_color'); ?>;
          border-right: 1px solid <?php echo $this->config->item('host_bingo_font_color'); ?>;
          border-bottom: 1px solid <?php echo $this->config->item('host_bingo_font_color'); ?>;
      }
      .ball-img {
          color: <?php echo $this->config->item('host_ball_font_color'); ?>;
      }
      .ball-cell {
          border-top: 1px solid <?php echo $this->config->item('host_bingo_font_color'); ?>;
          border-bottom: 1px solid <?php echo $this->config->item('host_bingo_font_color'); ?>;
      }
      .ball-img.drawn a {
          color: <?php echo $this->config->item('host_drawn_ball_font_color'); ?>;
      }
    </style>
    <script>
    var base_url = "<?php echo base_url(); ?>";
    var posicionActual = <?php echo $currentRoomPattern; ?>;
    var IMAGENES = ["<?php echo implode('","', $roomPatternsImg); ?>"];
    var patterns = <?php echo json_encode($roomPatterns); ?>;
    var drawModes = ["<?php echo implode('","', $this->config->item('draw_modes')); ?>"];
    var currentMode = <?php echo $config->current_draw_mode; ?>;
    var sid = <?php echo get_set_id(); ?>;
    var b = 0;
    function clearInfo() {
      var titleLetter = document.getElementById("title-letter");
      titleLetter.innerHTML = "";

      var titleNumber = document.getElementById("title-number");
      titleNumber.innerHTML = "";
    }
    $(document).ready(function()
    {
      $("#success-alert").hide();
      cargar_push();
    }); 
    var timestamp = null;
    function cargar_push() 
    { 
          $.ajax({
          async:  true, 
          type: "POST",
          url: base_url+"admin/bingo/pushandler",
          data: "",
          dataType:"html",
          success: function(data)
          { 
            if (data === "")
            {
              $('#bingo-calls-table').hide();
            }else{
              try {
                console.log(data);
                var json = JSON.parse(data);
                //console.log(json.length);
                var str='<tr>';
                var c=0;
                var font_size="3";
                for (var i = json.length - 1; i >= 0; i--) {
                  if(json[i]["room"] == sid)
                  {
                    c++;
                    if (json[i]['card']<100) font_size="55";
                    else if (json[i]['card']<1000) font_size="45";
                    else if (json[i]['card']<10000) font_size="35";
                    else font_size="27";

                    str += '<td align=center width="111" height="116" style="background-repeat: no-repeat;background-size: contain;" background="<?php echo base_url() ?>assets/template/img/bingo.svg"><a style="font-size: '+font_size+'px;" href="<?php echo base_url() ?>admin/bingo/checktable/'+(json[i]['user_id'])+'/'+(json[i]['id'])+'/'+(json[i]['card'])+'" target=_blank>'+(json[i]['card'])+'</a></td>';
                    if ((c)%4==0) str += '</tr><tr>';
                  }
                }
                str += '</tr>';
                if(c > 0)
                {
                  $('#bingo-calls-table').show();
                  $('#bingo-calls').html(str);
                  if(c > b)
                  {
                    playAudio();
                  }
                  b = c;
                }else{
                  $('#bingo-calls-table').hide();
                }
                //var json = JSON.parse(data);
                //console.log(str);
              } catch (error) {
                console.log(data);
                console.error(error);
                $('#btn-reload').show();
              }
            }
            setTimeout('cargar_push()',1000);
          },
          error: function( jqXHR, textStatus, errorThrown ) {
              console.log(jqXHR.status, textStatus, errorThrown);
              log_e(jqXHR.status,textStatus+"-"+errorThrown,"host-bingoCalls");
              $('#btn-reload').show();
              //alert("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
              //$('#myModal .modal-body').html("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
              //$('#myModal').modal('show');
          }

      }).fail( function( jqXHR, textStatus, errorThrown ) {
          console.log(jqXHR.status, textStatus, errorThrown);
          log_e(jqXHR.status,textStatus+"-"+errorThrown,"host-bingoCalls");
              $('#btn-reload').show();
          //alert("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
          //$('#myModal .modal-body').html("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
          //$('#myModal').modal('show');
      });    
    }
    //var x = document.getElementById("myAudio"); 

    function playAudio() { 
      document.getElementById('myAudio').play(); 
      //x.play(); 
    } 

    function pauseAudio() { 
      document.getElementById('myAudio').pause(); 
      //x.pause(); 
    } 
    function submitNumber(number){
      //console.log(number);
      $.ajax({
          async:  true, 
          type: "POST",
          url: base_url+"admin/bingo/senddraw",
          data: "n="+number,
          dataType:"html",
          success: function(data)
          { 
            //console.log("-----------------");
            //console.log(data);
            var json = JSON.parse(data);
            //console.log(json);
            if(!json.clear)
            {
              $("#title-letter").html(json.num.substring(0, 1));
              $("#title-number").html(json.num.substring(1));
              $("#b-"+json.num).addClass("drawn");
              $("#b-"+json.num+" img").attr("src","<?php echo base_url() ?>assets/template/img/ball2.png");
            }
            else
            {
              $("#title-letter").html("");
              $("#title-number").html("");
              $("#b-"+json.num).removeClass("drawn");
              $("#b-"+json.num+" img").attr("src","<?php echo base_url() ?>assets/template/img/ball.png");
            }
            $("#last-draw-letter-1").html(json.last_draws.ldl_1);
            $("#last-draw-number-1").html(json.last_draws.ldn_1);
            $("#last-draw-letter-2").html(json.last_draws.ldl_2);
            $("#last-draw-number-2").html(json.last_draws.ldn_2);
            $("#last-draw-letter-3").html(json.last_draws.ldl_3);
            $("#last-draw-number-3").html(json.last_draws.ldn_3);
            if(json.winners)
            {
              $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
                $("#success-alert").slideUp(500);
                //$("#success-alert").alert('close');
              });
            }
          },
          error: function( jqXHR, textStatus, errorThrown ) {
              console.log(jqXHR.status, textStatus, errorThrown);
              log_e(jqXHR.status,textStatus+"-"+errorThrown,"host-submitNumber");
              $('#btn-reload').show();
              //alert("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
              //$('#myModal .modal-body').html("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
              //$('#myModal').modal('show');
          }

      }).fail( function( jqXHR, textStatus, errorThrown ) {
          console.log(jqXHR.status, textStatus, errorThrown);
          log_e(jqXHR.status,textStatus+"-"+errorThrown,"host-submitNumber");
              $('#btn-reload').show();
          //alert("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
          //$('#myModal .modal-body').html("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
          //$('#myModal').modal('show');
      });
    } 
    function getDraw()
    {
      $.ajax({
      async:  true, 
        type: "POST",
        url: base_url+"admin/bingo/randomnumber",
      dataType:"html",
        success: function(data)
      {
        var json = JSON.parse(data);
        //console.log(json);
        $('#newDrawModal .modal-body').html('<div style="margin: 5px 15%; position: relative; text-align: center;" class="myriad"><img src="<?php echo base_url() ?>assets/template/img/last-ball.png" alt="" style="width: 80%;"><h1 id = "title-letter-modal">'+json.letter+'</h1><h1 id = "title-number-modal">'+json.number+'</h1></div>');
        $('#newDrawModal').modal('show');
        $("#title-letter").html(json.letter);
        $("#title-number").html(json.number);
        $('#b-'+json.letter+json.number).addClass('drawn');
        $("#b-"+json.letter+json.number+" img").attr("src","<?php echo base_url() ?>assets/template/img/ball2.png");
        $("#last-draw-letter-1").html(json.last_draws.ldl_1);
        $("#last-draw-number-1").html(json.last_draws.ldn_1);
        $("#last-draw-letter-2").html(json.last_draws.ldl_2);
        $("#last-draw-number-2").html(json.last_draws.ldn_2);
        $("#last-draw-letter-3").html(json.last_draws.ldl_3);
        $("#last-draw-number-3").html(json.last_draws.ldn_3);
        if(json.winners)
        {
          $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
            $("#success-alert").slideUp(500);
            //$("#success-alert").alert('close');
          });
        }
      },
          error: function( jqXHR, textStatus, errorThrown ) {
              console.log(jqXHR.status, textStatus, errorThrown);
              log_e(jqXHR.status,textStatus+"-"+errorThrown,"host-getDraw");
              $('#btn-reload').show();
              //alert("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
              //$('#myModal .modal-body').html("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
              //$('#myModal').modal('show');
          }

      }).fail( function( jqXHR, textStatus, errorThrown ) {
          console.log(jqXHR.status, textStatus, errorThrown);
          log_e(jqXHR.status,textStatus+"-"+errorThrown,"host-getDraw");
              $('#btn-reload').show();
          //alert("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
          //$('#myModal .modal-body').html("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
          //$('#myModal').modal('show');
      });
    }
    function changePattern(number){
      //console.log(number);
      $.ajax({
          async:  true, 
          type: "POST",
          url: base_url+"admin/bingo/changepattern",
          data: "pid="+patterns[number].id+"&i="+number,
          dataType:"html",
          success: function(data)
          { 
            //console.log("-----------------");
            //console.log(data);
            var json = JSON.parse(data);
            //console.log(json);
            posicionActual = json.index;
            renderizarImagen();
          },
          error: function( jqXHR, textStatus, errorThrown ) {
              console.log(jqXHR.status, textStatus, errorThrown);
              log_e(jqXHR.status,textStatus+"-"+errorThrown,"host-changePattern");
              $('#btn-reload').show();
              //alert("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
              //$('#myModal .modal-body').html("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
              //$('#myModal').modal('show');
          }

      }).fail( function( jqXHR, textStatus, errorThrown ) {
          console.log(jqXHR.status, textStatus, errorThrown);
          log_e(jqXHR.status,textStatus+"-"+errorThrown,"host-changePattern");
              $('#btn-reload').show();
          //alert("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
          //$('#myModal .modal-body').html("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
          //$('#myModal').modal('show');
      });
    }
    function changeDrawMode(number){
      //console.log(number);
      $.ajax({
          async:  true, 
          type: "POST",
          url: base_url+"admin/bingo/changedrawmode",
          data: "i="+number+"&ci="+currentMode,
          dataType:"html",
          success: function(data)
          { 
            //console.log("-----------------");
            //console.log(data);
            var json = JSON.parse(data);
            //console.log(json);
            currentMode = json.index;
            $('#sel-draw-mode').html(drawModes[json.index]);
            //posicionActual = json.index;
            //renderizarImagen();
          },
          error: function( jqXHR, textStatus, errorThrown ) {
              console.log(jqXHR.status, textStatus, errorThrown);
              log_e(jqXHR.status,textStatus+"-"+errorThrown,"host-checkPattern");
              $('#btn-reload').show();
              //alert("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
              //$('#myModal .modal-body').html("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
              //$('#myModal').modal('show');
          }

      }).fail( function( jqXHR, textStatus, errorThrown ) {
          console.log(jqXHR.status, textStatus, errorThrown);
          log_e(jqXHR.status,textStatus+"-"+errorThrown,"host-checkPattern");
          $('#btn-reload').show();
          //alert("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
          //$('#myModal .modal-body').html("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
          //$('#myModal').modal('show');
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
  <script language=javascript src="<?php echo base_url() ?>assets/template/js/carousel.js"></script>
</head>

<body class="bingo-view-p" style="background-color: <?php echo $this->config->item('host_bg_color'); ?>; color: <?php echo $this->config->item('host_font_color'); ?>;">
  <table width="100%">
     <tr>
      <td >
        <table width="100%">
          <tr>
            <td width="25%" style="text-align: center;">
              <img src="<?php echo base_url() ?>assets/template/img/bingo-side.png" alt="" style="margin: 5% auto;">
            </td>
            <td width="50%" style="text-align: center;">
              <table width="100%">
                <tr rowspan="4"><h4 class = "myriad" >Últimas Balotas</h4></tr>
                <tr>
                  <td width="30%">
                    <div style="margin: auto; position: relative;" class="myriad">
                      <img src="<?php echo base_url() ?>assets/template/img/last-ball.png" alt="" style="width: 100%;/*min-width: 95px;*/max-width: 194px;">
                      <h1 id = "title-letter"><?php if(isset($sendnumber) && !$clearText){ echo substr($sendnumber, 0, 1); }?></h1>
                      <h1 id = "title-number"><?php if(isset($sendnumber) && !$clearText){ echo substr($sendnumber, 1, 2); }?></h1>
                    </div>
                  </td>
                  <td width="10%"></td>
                  <td width="20%">
                    <div style="margin: auto; position: relative;" class="myriad">
                      <img src="<?php echo base_url() ?>assets/template/img/last-ball.png" alt="" style="width: 75%;/*min-width: 85px;max-width: 184px;*/">
                      <h1 id = "last-draw-letter-1" class="last-draw-letter"><?php echo $ldl_1 ?></h1>
                      <h1 id = "last-draw-number-1" class="last-draw-number"><?php echo $ldn_1 ?></h1>
                    </div>
                  </td>
                  <td width="20%">
                    <div style="margin: auto; position: relative;" class="myriad">
                      <img src="<?php echo base_url() ?>assets/template/img/last-ball.png" alt="" style="width: 65%;/*min-width: 85px;max-width: 184px;*/">
                      <h1 id = "last-draw-letter-2" class="last-draw-letter"><?php echo $ldl_2 ?></h1>
                      <h1 id = "last-draw-number-2" class="last-draw-number"><?php echo $ldn_2 ?></h1>
                    </div>
                  </td>
                  <td width="20%">
                    <div style="margin: auto; position: relative;" class="myriad">
                      <img src="<?php echo base_url() ?>assets/template/img/last-ball.png" alt="" style="width: 50%;/*min-width: 85px;max-width: 184px;*/">
                      <h1 id = "last-draw-letter-3" class="last-draw-letter"><?php echo $ldl_3 ?></h1>
                      <h1 id = "last-draw-number-3" class="last-draw-number"><?php echo $ldn_3 ?></h1>
                    </div>
                  </td>
                </tr>
              </table>
            </td>
            <td>
              <table>
                <tr>
                  <div class="win-pat">
                    <button id="retroceder" class="but-img-play"><</button>
                    <!--div class="carousel"-->
                        <div id="imagen" ></div>
                    <!--/div-->
                    <button id="avanzar" class="but-img-play">></button>
                  </div>
                </tr>
                <tr>
                  <div class="win-pat">
                    <button id="retroceder-dm" class="but-img-play"><</button>
                    <!--div class="carousel"-->
                        <div id="sel-draw-mode" ><?php echo $this->config->item('draw_modes')[$config->current_draw_mode]; ?></div>
                    <!--/div-->
                    <button id="avanzar-dm" class="but-img-play">></button>
                  </div>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
      
     </tr>
     <tr>
      <td width="100%">
      <?php 

    echo '<table width="80%" border=0 cols=16 class="myriad" style="margin:auto;"> ';

      for ($i = 1; $i<6; $i++) {
        echo "<tr>";
        for ($j=0; $j <= 15; $j++) { 
            if($j == 0)
            {
              echo '<td class="bingo-title myriad">'.find_letter($i * 15);//print_r($i * 15);
            }else
            {
            
            echo '<td align=center class="ball-cell myriad">';
              $num = $j + (($i - 1) * 15);
              //print_r($num);
              printImg($num, $draws);
              $num++;
            }
            echo '</td>';
        }
        echo "</tr>";
      }
      echo '</table>';
      ?>

<?php 
  function printImg($number, $drws){
    $classDrawn = "";
    $imgDrawn = "";
    if (!empty($drws) && array_search($number, $drws) !== FALSE) {
      $classDrawn = "drawn";
      $imgDrawn = "2";
    }
    echo '<div id="b-'.find_letter($number).$number.'" class="ball-img '.$classDrawn.'"><p><a onclick="submitNumber(\''.find_letter($number).$number.'\')"" ><img src="'.base_url().'assets/template/img/ball'.$imgDrawn.'.png" alt=""><b class="b-number">'.$number.'</b></img></a></p></div>';

  }
 ?>
     </form>
     </td>
     </tr>
     <tr>
      <td width="100%">
        <table style="margin: auto;" width="80%">
          <tr>
            <td width="40%">
              <div id="bingo-calls-table" style="display: none;">
              <h3><b>Bingo:</b></h3>
              <div style="height: 116px;overflow: auto;scroll-behavior: auto; text-align: left;">
                <table id="bingo-calls" cols=12 ></table>
              </div>
              </div>
              <!--div id="new-draw-alert"></div-->
            </td>
            <td width="20%" class="center text-cent">
              <button onclick="getDraw()" id="getDrawBtn" class="but-img-play" style="font-size: 24px;padding: 32px;line-height: 4px;">Balota</button>
              <form name="random" method="post" action="#" onSubmit="return validate_number(<?php echo $this->config->item('maxColumnNumber'); ?>)">
                <input style="font-size: 14px" name="restart" type="button" value="Reiniciar Juego" onClick="RestartConfirmation()" class="but-img-play">
              </form>
            </td>
            <td width="40%">
              <div id="btn-reload" style="display: none;" class="text-cent">
                <button class="but-img-play" onClick="window.location.reload();">Actualizar</button>
              </div>
            </td>
          </tr>
          <tr>
            <td colspan="3">
              
            </td>
          </tr>
        </table>
        <div class="alert alert-success text-cent" id="success-alert">
           <!--button type="button" class="close" data-dismiss="alert">x</button-->
           <strong> !Atentos a sus tablas!</strong>
        </div>
      </td>
     </tr>
  </table>

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
          <a class="btn btn-primary" href="<?php echo base_url() ?>login/logout" style="background-color: <?php echo $this->config->item('primary_btn_color'); ?>; border-color: <?php echo $this->config->item('primary_btn_color'); ?>; color: <?php echo $this->config->item('primary_btn_font_color'); ?>;"><?php echo $this->lang->line('common_modal_close_btn_close'); ?></a>
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

  <div class="modal fade" id="newDrawModal" tabindex="-1" role="dialog" aria-labelledby="newDrawModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content-bingo">
        <div class="modal-header-bingo">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          
        </div>
        <div class="modal-footer-bingo">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Aceptar</button>
        </div>
      </div>
    </div>
  </div>
  <audio id="myAudio" src="<?php echo base_url() ?>assets/template/sounds/bell.mp3" ></audio>

  <!-- Bootstrap core JavaScript-->
  <script src="<?php echo base_url() ?>assets/template/vendor/jquery/jquery.min.js"></script>
  <script src="<?php echo base_url() ?>assets/template/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="<?php echo base_url() ?>assets/template/vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="<?php echo base_url() ?>assets/template/js/sb-admin-2.min.js"></script>
  
</body>

</html>
