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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('dashboard/meta_dashboard'); ?>
    <script language=javascript src="<?php echo base_url() ?>assets/template/js/scripts.js"></script>
    <script>
    var base_url = "<?php echo base_url(); ?>";
    var posicionActual = <?php echo $currentRoomPattern; ?>;
    var IMAGENES = ["<?php echo implode('","', $roomPatternsImg); ?>"];
    var patterns = <?php echo json_encode($roomPatterns); ?>;
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
      cargar_push();
    }); 
    var timestamp = null;
    function cargar_push() 
    { 
      /*$.ajax({
      async:  true, 
        type: "POST",
        url: base_url+"admin/bingo/httpush",
        data: "&timestamp="+timestamp,
      dataType:"html",
        success: function(data)
      { */
        //console.log("-----------------");
        //console.log(data);

        /*var json         = JSON.parse(data);//eval("(" + data + ")");
        //console.log(json.timestamp);
        timestamp        = json.timestamp;
        user             = json.user;
        id               = json.id;
        status           = json.status;       
        if(timestamp == null)
        {
        
        }
        else
        {*/
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
                var json = JSON.parse(data);
                //console.log(json.length);
                var str='<tr>';
                var c=0;
                var font_size="3";
                for (var i = json.length - 1; i >= 0; i--) {
                  if(json[i]["room"] == sid)
                  {
                    c++;
                    if (c<100) font_size="6";
                    else if (c<1000) font_size="5";
                    else if (c<10000) font_size="4";
                    else font_size="3";

                    //str += '<td align=center width="23" height="25" background="<?php echo base_url() ?>assets/template/img/ubb2.gif"><a href="<?php echo base_url() ?>admin/bingo/checktable/'+(json[i]['user_id'])+'/'+(json[i]['id'])+'/'+(json[i]['card'])+'" target=_blank><font size="'+font_size+'" color="#000000">'+(c)+'</font></a></td>';
                    str += '<td align=center width="70" height="78" style="background-repeat: no-repeat;background-size: contain;" background="<?php echo base_url() ?>assets/template/img/bingo.svg"><a href="<?php echo base_url() ?>admin/bingo/checktable/'+(json[i]['user_id'])+'/'+(json[i]['id'])+'/'+(json[i]['card'])+'" target=_blank><font size="'+font_size+'" color="#000000">'+(json[i]['card'])+'</font></a></td>';
                    if ((c)%3==0) str += '</tr><tr>';
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
              } catch (error) {
                  console.log(data);
                  console.error(error);
                }
              //var json = JSON.parse(data);
              //console.log(str);
            }
            setTimeout('cargar_push()',1000);
          },
          error: function( jqXHR, textStatus, errorThrown ) {
              console.log(jqXHR.status, textStatus, errorThrown);
              //alert("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
              $('#myModal .modal-body').html("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
              $('#myModal').modal('show');
          }

      }).fail( function( jqXHR, textStatus, errorThrown ) {
          console.log(jqXHR.status, textStatus, errorThrown);
          //alert("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
          $('#myModal .modal-body').html("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
          $('#myModal').modal('show');
      }); 
        //}
              
        /*}
      });*/   
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
            
          },
          error: function( jqXHR, textStatus, errorThrown ) {
              console.log(jqXHR.status, textStatus, errorThrown);
              //alert("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
              $('#myModal .modal-body').html("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
              $('#myModal').modal('show');
          }

      }).fail( function( jqXHR, textStatus, errorThrown ) {
          console.log(jqXHR.status, textStatus, errorThrown);
          //alert("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
          $('#myModal .modal-body').html("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
          $('#myModal').modal('show');
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
        console.log(json);
        $('#newDrawModal .modal-body').html('<div style="margin: 5px 15%; position: relative; text-align: center;" class="myriad"><img src="<?php echo base_url() ?>assets/template/img/last-ball.png" alt="" style="width: 80%;"><h1 id = "title-letter-modal">'+json.letter+'</h1><h1 id = "title-number-modal">'+json.number+'</h1></div>');
        $('#newDrawModal').modal('show');
        $("#title-letter").html(json.letter);
        $("#title-number").html(json.number);
        $('#b-'+json.letter+json.number).addClass('drawn');
        $("#b-"+json.letter+json.number+" img").attr("src","<?php echo base_url() ?>assets/template/img/ball2.png");
      },
          error: function( jqXHR, textStatus, errorThrown ) {
              console.log(jqXHR.status, textStatus, errorThrown);
              //alert("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
              $('#myModal .modal-body').html("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
              $('#myModal').modal('show');
          }

      }).fail( function( jqXHR, textStatus, errorThrown ) {
          console.log(jqXHR.status, textStatus, errorThrown);
          //alert("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
          $('#myModal .modal-body').html("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
          $('#myModal').modal('show');
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
              //alert("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
              $('#myModal .modal-body').html("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
              $('#myModal').modal('show');
          }

      }).fail( function( jqXHR, textStatus, errorThrown ) {
          console.log(jqXHR.status, textStatus, errorThrown);
          //alert("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
          $('#myModal .modal-body').html("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
          $('#myModal').modal('show');
      });
    }
  </script>
  <script language=javascript src="<?php echo base_url() ?>assets/template/js/carousel.js"></script>
</head>

<body class="bingo-view-p">

        
    <table width="100%">
    <tr>
      <td width="25%" class="left-panel">
        <img src="<?php echo base_url() ?>assets/template/img/bingo-side.png" alt="" style="margin: 10% 3%;">
        <br>
        <br>
        <h1 class = "myriad" >Última Balota</h1>
        <div style="margin: 5px 15%; position: relative;" class="myriad">
          <img src="<?php echo base_url() ?>assets/template/img/last-ball.png" alt="" style="width: 80%;">
          <h1 id = "title-letter"><?php if(isset($sendnumber) && !$clearText){ echo substr($sendnumber, 0, 1); }?></h1>
          <h1 id = "title-number"><?php if(isset($sendnumber) && !$clearText){ echo substr($sendnumber, 1, 2); }?></h1>
        </div>

        <!--button class="but-img-play" onclick="clearInfo()">Limpiar</button-->
        <form name="random" method="post" action="#" onSubmit="return validate_number(<?php echo $this->config->item('maxColumnNumber'); ?>)">

        <input name="restart" type="button" value="Reiniciar Juego" onClick="RestartConfirmation()" class="but-img-play">

        </form>

        <a class="but-img-play" style="color: rgb(51, 51, 51); text-decoration: none;" href="<?php echo base_url() ?>admin/bingo">Regresar</a>
        <button onclick="getDraw()" id= "getDrawBtn" class="but-img-play">Balota</button>
        <!--a class="but-img-play" style="color: rgb(51, 51, 51); text-decoration: none;" href="verify.php" target=_blank>Verificar</a-->
        
        <div class="win-pat">
        <button id="retroceder" class="but-img-play"><</button>
        <!--div class="carousel"-->
            <div id="imagen" ></div>
        <!--/div-->
        <button id="avanzar" class="but-img-play">></button>
        </div>

        <div id="bingo-calls-table" style="display: none;">
          <br><h3><b>Bingo:</b></h3>
          <table id="bingo-calls" cols=12 style="margin-left: auto; margin-right: auto;"></table>
        </div>
        <div id="new-draw-alert"></div>
            <?php //echo '<br><h3><b>Tarjetas ganadoras:</b><br> (Nuevas en rojo)</h3><br>';
            //winners_table(); ?>
      </td>
      <td width="75%">
      <?php 

    echo '<table width="100%" border=0 cols=5 class="myriad" ><tr> ';

      //$number = count($draws);
      $draws = load_draws();
      //echo print_r($draws);
      for ($i=1; $i < 6; $i++) { 

        echo '<th class="bingo-title myriad">'.find_letter($i * 15).'</th>';
      }
      for ($i = 1; $i<=15; $i+=2) {
        echo "<tr>";
        for ($j=0; $j < 5; $j++) { 
            echo '<td align=center width="20%" class="ball-cell myriad">';
       //               echo '<table border="0" cellpadding="0" cellspacing="0" width="90" height="90"><tr>';
            // echo '<td align=center">';

            $num = $i + ($j * 15);
            // echo "drawn ". array_search($num, $draws);
            printImg($num, $draws);
            $num++;
            if ($num <= 75 && find_letter($num) == find_letter($num - 1)) {
              printImg($num, $draws);
            }

            echo '</td>';
                      // echo "</tr>";
                      // echo '</td>';
        }
        echo "</tr>";

        if ($i%5==0) ;
      }
    echo '</tr></table>';
       ?>

<?php 
  function printImg($number, $drws){
    $classDrawn = "";
    $imgDrawn = "";
    if (!empty($drws) && array_search($number, $drws) !== FALSE) {
      $classDrawn = "drawn";
      $imgDrawn = "2";
    }
    //echo '<div id="b-'.find_letter($number).$number.'" class="ball-img '.$classDrawn.'"><p><a href= "'.base_url().'admin/bingo/sendnumber/'.find_letter($number).$number.'"><img src="'.base_url().'assets/template/img/ball'.$imgDrawn.'.png" alt=""><b class="b-number">'.$number.'</b></img></a></p></div>';
    echo '<div id="b-'.find_letter($number).$number.'" class="ball-img '.$classDrawn.'"><p><a onclick="submitNumber(\''.find_letter($number).$number.'\')"" ><img src="'.base_url().'assets/template/img/ball'.$imgDrawn.'.png" alt=""><b class="b-number">'.$number.'</b></img></a></p></div>';

  }
 ?>
     </form>
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
          <a class="btn btn-primary" href="<?php echo base_url() ?>login/logout"><?php echo $this->lang->line('common_modal_close_btn_close'); ?></a>
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
