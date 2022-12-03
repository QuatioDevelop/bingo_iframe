<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('admin/clients/meta_clients'); ?>
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

         <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">
                    <?php echo $this->lang->line('admin_clients_title'); ?>
                    <small><?php echo $this->lang->line('admin_permissions_subtitle_edit'); ?></small>
                </h1>
                
            </div>

            <!-- DataTales Example -->
            <div class="card shadow mb-4">
              <div class="card-body">
                        <?php if($this->session->flashdata("error")):?>
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <p><i class="icon fa fa-ban"></i><?php echo $this->session->flashdata("error"); ?></p>
                                
                             </div>
                        <?php endif;?>
                        <form action="<?php echo base_url();?>admin/clients/update" method="POST">
                            <input type="hidden" value="<?php echo $client->user_name;?>" name="user_name">
                            <div class="form-group <?php echo !empty(form_error('user_name')) ? 'has-error':'';?>">
                                <label for="user_name"><?php echo $this->lang->line('register_username_lb'); ?>:</label>
                                <input type="text" class="form-control" id="user_name" name="user_name" value="<?php echo !empty(form_error('user_name')) ? set_value('user_name') : $client->user_name;?>" readonly>
                                <?php echo form_error("user_name","<span class='help-block'>","</span>");?>
                            </div>
                            <div class="form-group <?php echo !empty(form_error('name')) ? 'has-error':'';?>">
                                <label for="name"><?php echo $this->lang->line('register_name_lb'); ?>:</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo !empty(form_error('name')) ? set_value('name') : $client->name;?>">
                                <?php echo form_error("name","<span class='help-block'>","</span>");?>
                            </div>
                            <div class="form-group <?php echo !empty(form_error('email')) ? 'has-error':'';?>">
                                <label for="email"><?php echo $this->lang->line('register_email_lb'); ?>:</label>
                                <input type="text" class="form-control" id="email" name="email" value="<?php echo !empty(form_error('email')) ? set_value('email') : $client->email;?>">
                                <?php echo form_error("email","<span class='help-block'>","</span>");?>
                            </div>
                            <div class="form-group <?php echo !empty(form_error('password')) ? 'has-error':'';?>">
                                <label for="password"><?php echo $this->lang->line('login_pass_tf'); ?>:</label>
                                <input type="password" class="form-control" id="password" name="password" value="<?php echo set_value('password');?>">
                                <?php echo form_error("password","<span class='help-block'>","</span>");?>
                            </div>
                            <div class="form-group <?php echo !empty(form_error('passconf')) ? 'has-error':'';?>">
                                <label for="passconf"><?php echo $this->lang->line('register_rptpass_lb'); ?>:</label>
                                <input type="password" class="form-control" id="passconf" name="passconf" value="<?php echo set_value('passconf');?>">
                                <?php echo form_error("passconf","<span class='help-block'>","</span>");?>
                            </div>
                            <div class="form-group <?php echo !empty(form_error('role')) ? 'has-error':'';?>">
                                <label for="role"><?php echo $this->lang->line('admin_permissions_table_role'); ?>:</label>
                                <select name="role" id="role" class="form-control">
                                    <?php if(form_error("role")!=false || set_value("role") != false): ?>
                                        <?php foreach ($roles as $role) :?>
                                            <option value="<?php echo $role->role_id?>" <?php echo set_select("role",$role->role_id);?> ><?php echo $role->name;?></option>
                                        <?php endforeach;?>
                                    <?php else: ?>
                                        <?php foreach ($roles as $role) :?>
                                            <option value="<?php echo $role->role_id;?>" <?php echo $role->role_id == $client->role ? 'selected':'';?>><?php echo $role->name; ?></option>
                                        <?php endforeach;?>
                                    <?php endif;?>
                                </select>
                                <?php echo form_error("role","<span class='help-block'>","</span>");?>
                            </div>  
                                                  
                            <div class="form-group">
                                <button type="submit" class="btn btn-success btn-flat"><?php echo $this->lang->line('admin_permissions_btn_save'); ?></button>
                            </div>
                        </form>
                <a href="<?php echo base_url()?>admin/clients" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" style="background-color: <?php echo $this->config->item('primary_btn_color'); ?>; border-color: <?php echo $this->config->item('primary_btn_color'); ?>; color: <?php echo $this->config->item('primary_btn_font_color'); ?>;"><i class="fas fa-arrow-left fa-sm text-white-50"></i> <?php echo $this->lang->line('admin_btn_back'); ?></a>
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
  <script src="<?= base_url() ?>assets/template/vendor/jquery-ui-1.12.1/jquery-ui.js"></script>
  <!-- Page level custom scripts -->
  <script src="<?= base_url() ?>assets/template/js/demo/datatables-demo.js"></script>

  <script type="text/javascript">
    $(document).ready(function () {
    var base_url= "<?php echo base_url();?>";

    $( "#campaigns" ).autocomplete({
      source:function(request, response){
            $.ajax({
                url: base_url+"admin/campaigns/getCampaigns",
                type:"POST",
                dataType:"json",
                data:{valor: request.term},
                success:function(data){
                    response(data);
                }
            });
        },
        minLength:1,
        select:function(event, ui){
            data=ui.item.campaign_id;
            $('#btn-agregar').val(data);
        }
    });

    $('#btn-agregar').on('click',function(){
      $data = $(this).val();
      if($data != '')
      {
        $.ajax({
                url: base_url+"admin/campaigns/getCampaign",
                type:"POST",
                dataType:"json",
                data:{campaign_id: $data},
                success:function(data){
                    if($('input[value="'+data.campaign_id+'"]').length == 0)
                    {
                      html = "<div class='d-sm-flex align-items-center justify-content-between mb-1 client-campaign bg-gray-300'><input type='hidden' name='refs[]' value='"+data.campaign_id+"'>"+data.name+"<button type='button' class='btn btn-danger btn-remove-campaign'><span class='fa fa-trash-alt'></span></button></div>";
                        $("#client-campaigns").prepend(html);
                        $('#btn-agregar').val(null);
                        $( "#campaigns" ).val(null);
                        
                    }else
                    {
                        alert("<?php echo $this->lang->line('campaign_already_added'); ?>");
                        $('#btn-agregar').val(null);
                    }
                }
            });
      }else{
        alert("<?php echo $this->lang->line('campaign_not_selected'); ?>");
      }
    });

    $('.btn-remove-campaign').on('click',function(){
      $(this).closest("div").remove();
    });

  });
    
  </script>

</body>

</html>