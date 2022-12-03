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
    function cleanBingo(id){
      $( "#"+id ).find( ".number" ).each(function() {
          $(this).closest('td').addClass("unchecked");
          $(this).closest('td').removeClass("checked");
      });
    }
    function doBingo(id){
      $.ajax({
          async:  true, 
          type: "POST",
          url: base_url+"admin/bingo/dobingo",
          data: "id="+<?php echo $this->session->userdata('user_data')['user_uname']; ?>+"&sid="+sid+"&card="+id,
          dataType:"html",
          success: function(data)
          { 
            console.log("-----------------");
            console.log(data);
            //$('#div'+tipo).html(data);
            var json = JSON.parse(data);
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
            //var json = JSON.parse(data);
          }
      });
    }
    function checkbingo() 
    { 
      $.ajax({
      async:  true, 
        type: "POST",
        url: base_url+"admin/bingo/checkbingo",
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
      <div class="content">
     <?php //$this->load->view('layouts/navbar'); ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <div class="row">
           

            
            <div class="col-md-8" id="chatSection">
              <!-- DIRECT CHAT -->
              <div class="box box-warning direct-chat direct-chat-primary">
                <div class="box-header with-border">
                  <h3 class="box-title" id="ReciverName_txt"><?=$chatTitle;?></h3>

                  <div class="box-tools pull-right">
                    <span data-toggle="tooltip" title="Clear Chat" class="ClearChat"><i class="fa fa-comments"></i></span>
                    <!--<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>-->
                   <!-- <button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="Clear Chat"
                            data-widget="chat-pane-toggle">
                      <i class="fa fa-comments"></i></button>-->
                   <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                    </button>-->
                  </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  <!-- Conversations are loaded here -->
                  <div class="direct-chat-messages" id="content">
                     <!-- /.direct-chat-msg -->

                     <div id="dumppy"></div>

                  </div>
                  <!--/.direct-chat-messages-->
 
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                  <!--<form action="#" method="post">-->
                    <div class="input-group">
                     <?php
            //$obj=&get_instance();
            //$obj->load->model('UserModel');
            $profile_url = base_url()."assets/template/img/avatars/default-user-image.png";
            //$user=$obj->UserModel->GetUserData();
          ?>
                      
                        <input type="hidden" id="Sender_Name" value="<?=$this->session->userdata('user_data')['user_name'];?>">
                        <input type="hidden" id="Sender_ProfilePic" value="<?=$profile_url;?>">
                      
                      <input type="hidden" id="ReciverId_txt">
                        <input type="text" name="message" placeholder="Type Message ..." class="form-control message">
                          <span class="input-group-btn">
                             <button type="button" class="btn btn-success btn-flat btnSend" id="nav_down">Send</button>
                             <div class="fileDiv btn btn-info btn-flat"> <i class="fa fa-upload"></i> 
                             <input type="file" name="file" class="upload_attachmentfile"/></div>
                          </span>
                    </div>
                  <!--</form>-->
                </div>
                <!-- /.box-footer-->
              </div>
              <!--/.direct-chat -->
            </div>




            <div class="col-md-4">
              <!-- USERS LIST -->
              <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title"><?=$strTitle;?></h3>

                  <div class="box-tools pull-right">
                    <span class="label label-danger"><?=count($vendorslist);?> <?=$strsubTitle;?></span>
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                    </button>
                  </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                  <ul class="users-list clearfix">
                  
                    <?php if(!empty($vendorslist)){
            foreach($vendorslist as $v):
            ?>
                        <li class="selectVendor" id="<?=$v['id'];?>" title="<?=$v['name'];?>">
                          <img src="<?=$v['picture_url'];?>" alt="<?=$v['name'];?>" title="<?=$v['name'];?>">
                          <span class="users-list-name" href="#"><?=$v['name'];?></span>
                          <!--<span class="users-list-date">Yesterday</span>-->
                        </li>
                    <?php endforeach;?>
                    
                   <?php }else{?>
                    <li>
                       <a class="users-list-name" href="#">No Vendor's Find...</a>
                     </li>
                    <?php } ?>
                    
                    
                  </ul>
                  <!-- /.users-list -->
                </div>
                <!-- /.box-body -->
               <!-- <div class="box-footer text-center">
                  <a href="javascript:void(0)" class="uppercase">View All Users</a>
                </div>-->
                <!-- /.box-footer -->
              </div>
              <!--/.box -->
            </div>
            <!-- /.col -->            
          </div>
    
    <!-- /.row --> 
    
      </div>
    
    
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
  <script src="<?= base_url() ?>assets/template/js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="<?= base_url() ?>assets/template/vendor/chart.js/Chart.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="<?= base_url() ?>assets/template/js/chart-bar.js"></script>
  <script src="<?=base_url('assets/template/js/chat.js');?>"></script> 
  <script type="text/javascript">
    $(function(){
        var base_url= "<?php echo base_url();?>";

      });

    
  </script>
</body>

</html>
