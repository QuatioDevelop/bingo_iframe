
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

        <div class="container-fluid">
        <!-- Default box -->
        <div class="box box-solid">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php if($this->session->flashdata("error")):?>
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <p><i class="icon fa fa-ban"></i><?php echo $this->session->flashdata("error"); ?></p>
                                
                             </div>
                        <?php endif;?>
                        <form action="<?php echo base_url();?>admin/clients/store" method="POST">
                            <div class="form-group <?php echo !empty(form_error('user_id')) ? 'has-error':'';?>">
                                <label for="user_id"><?php echo $this->config->item('usenumberid') ? $this->lang->line('register_username_lb') : $this->lang->line('register_email_lb'); ?>:</label>
                                <input <?php if($this->config->item('usenumberid')) echo 'type="text" oninput="this.value = this.value.replace(/[^0-9\-]/, \'\')"'; else echo 'type="email"'; ?> class="form-control" id="user_id" name="user_id" value="<?php echo set_value('user_id');?>">
                                <?php echo form_error("user_id","<span class='help-block'>","</span>");?>
                            </div>
                            <div class="form-group <?php echo !empty(form_error('name')) ? 'has-error':'';?>">
                                <label for="name"><?php echo $this->lang->line('register_name_lb'); ?>:</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo set_value('name');?>">
                                <?php echo form_error("name","<span class='help-block'>","</span>");?>
                            </div>
                            <div class="form-group <?php echo !empty(form_error('email')) ? 'has-error':'';?>">
                                <label for="email"><?php echo $this->lang->line('register_email_lb'); ?>:</label>
                                <input type="text" class="form-control" id="email" name="email" value="<?php echo set_value('email');?>">
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
                                <label for="role"><?php echo $this->lang->line('admin_clients_table_rol'); ?>:</label>
                                <select name="role" id="role" class="form-control">
                                    <?php foreach($roles as $role):?>
                                        <option value="<?php echo $role->role_id?>" <?php echo set_select("role",$role->role_id,(3==$role->role_id));?>><?php echo $role->name;?></option>
                                    <?php endforeach;?>
                                </select>
                                <?php echo form_error("role","<span class='help-block'>","</span>");?>
                            </div>    
                            <div class="form-group <?php echo !empty(form_error('num_cards')) ? 'has-error':'';?>">
                                <label for="num_cards"><?php echo $this->lang->line('register_numcards_lb'); ?>:</label>
                                <input type="number" class="form-control" id="num_cards" name="num_cards" value="<?php echo set_value('num_cards',1);?>">
                                <?php echo form_error("num_cards","<span class='help-block'>","</span>");?>
                            </div>                         
                            <div class="form-group">
                                <button type="submit" class="btn btn-success btn-flat"><?php echo $this->lang->line('admin_permissions_btn_save'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
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

  <!-- Page level custom scripts -->
  <script src="<?= base_url() ?>assets/template/js/demo/datatables-demo.js"></script>

</body>

</html>
