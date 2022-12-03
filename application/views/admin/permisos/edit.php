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
                    <?php echo $this->lang->line('admin_permissions_title'); ?>
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
                <form action="<?php echo base_url();?>admin/permissions/update" method="POST">
                    <input type="hidden" value="<?php echo $permission->id;?>" name="id">
                    <div class="form-group">
                        <label for="role"><?php echo $this->lang->line('admin_permissions_table_role'); ?>:</label>
                        <select name="role" id="role" class="form-control" disabled="disabled">
                            <?php foreach($roles as $role):?>
                                <option value="<?php echo $role->role_id?>" <?php echo set_select("role",$role->role_id); echo $permission->role_id == $role->role_id ? 'selected' : '';?>><?php echo $role->name;?></option>
                            <?php endforeach;?>
                            <?php echo form_error("role","<span class='help-block'>","</span>");?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="menu"><?php echo $this->lang->line('admin_permissions_table_menu'); ?>:</label>
                        <select name="menu" id="menu" class="form-control" disabled="disabled">
                            <?php foreach($menus as $menu):?>
                                <option value="<?php echo $menu->id?>" <?php echo set_select("role",$role->role_id); echo $permission->menu_id == $menu->id ? 'selected' : '';?>><?php echo $menu->name;?></option>
                            <?php endforeach;?>
                            <?php echo form_error("menu","<span class='help-block'>","</span>");?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="read"><?php echo $this->lang->line('admin_permissions_table_read'); ?>:</label>
                        <label class="radio-inline">
                            <input type="radio" name="read" value="1" <?php echo $permission->read_per == 1 ? 'checked="checked"':'' ?>><?php echo $this->lang->line('admin_permissions_yes'); ?>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="read" value="0" <?php echo $permission->read_per == 0 ? 'checked="checked"':'' ?>><?php echo $this->lang->line('admin_permissions_no'); ?>
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="read"><?php echo $this->lang->line('admin_permissions_table_read_other'); ?>:</label>
                        <label class="radio-inline">
                            <input type="radio" name="read_other" value="1" <?php echo $permission->read_other_per == 1 ? 'checked="checked"':'' ?>><?php echo $this->lang->line('admin_permissions_yes'); ?>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="read_other" value="0" <?php echo $permission->read_other_per == 0 ? 'checked="checked"':'' ?>><?php echo $this->lang->line('admin_permissions_no'); ?>
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="insert"><?php echo $this->lang->line('admin_permissions_table_insert'); ?>:</label>
                        <label class="radio-inline">
                            <input type="radio" name="insert" value="1" <?php echo $permission->insert_per == 1 ? 'checked="checked"':'' ?>><?php echo $this->lang->line('admin_permissions_yes'); ?>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="insert" value="0" <?php echo $permission->insert_per == 0 ? 'checked="checked"':'' ?>><?php echo $this->lang->line('admin_permissions_no'); ?>
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="update"><?php echo $this->lang->line('admin_permissions_table_update'); ?>:</label>
                        <label class="radio-inline">
                            <input type="radio" name="update" value="1" <?php echo $permission->update_per == 1 ? 'checked="checked"':'' ?>><?php echo $this->lang->line('admin_permissions_yes'); ?>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="update" value="0" <?php echo $permission->update_per == 0 ? 'checked="checked"':'' ?>><?php echo $this->lang->line('admin_permissions_no'); ?>
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="delete"><?php echo $this->lang->line('admin_permissions_table_delete'); ?>:</label>
                        <label class="radio-inline">
                            <input type="radio" name="delete" value="1" <?php echo $permission->delete_per == 1 ? 'checked="checked"':'' ?>><?php echo $this->lang->line('admin_permissions_yes'); ?>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="delete" value="0" <?php echo $permission->delete_per == 0 ? 'checked="checked"':'' ?>><?php echo $this->lang->line('admin_permissions_no'); ?>
                        </label>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success btn-flat"><?php echo $this->lang->line('admin_permissions_btn_save'); ?></button>
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