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
                <h1 class="h3 mb-0 text-gray-800"><?php echo $this->lang->line('admin_permissions_title'); ?></h1>
            </div>

            <!-- DataTales Example -->
            <div class="card shadow mb-4">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th><?php echo $this->lang->line('admin_permissions_table_role'); ?></th>
                        <th><?php echo $this->lang->line('admin_permissions_table_menu'); ?></th>
                        <th><?php echo $this->lang->line('admin_permissions_table_read'); ?></th>
                        <th><?php echo $this->lang->line('admin_permissions_table_read_other'); ?></th>
                        <th><?php echo $this->lang->line('admin_permissions_table_insert'); ?></th>
                        <th><?php echo $this->lang->line('admin_permissions_table_update'); ?></th>
                        <th><?php echo $this->lang->line('admin_permissions_table_delete'); ?></th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($permissions)):?>
                            <?php foreach($permissions as $permission):?>
                                <tr>
                                            <td><?php echo $permission->role;?></td>
                                            <td><?php echo $permission->menu;?></td>
                                            <td><?php if($permission->read_per == 0):?>
                                                    <span class="fa fa-times"></span>
                                                <?php else:?>
                                                    <span class="fa fa-check"></span>
                                                <?php endif;?>
                                            </td>
                                            <td><?php if($permission->read_other_per == 0):?>
                                                    <span class="fa fa-times"></span>
                                                <?php else:?>
                                                    <span class="fa fa-check"></span>
                                                <?php endif;?>
                                            </td>
                                            <td><?php if($permission->insert_per == 0):?>
                                                    <span class="fa fa-times"></span>
                                                <?php else:?>
                                                    <span class="fa fa-check"></span>
                                                <?php endif;?>
                                            </td>
                                            <td><?php if($permission->update_per == 0):?>
                                                    <span class="fa fa-times"></span>
                                                <?php else:?>
                                                    <span class="fa fa-check"></span>
                                                <?php endif;?>
                                            </td>
                                            <td><?php if($permission->delete_per == 0):?>
                                                    <span class="fa fa-times"></span>
                                                <?php else:?>
                                                    <span class="fa fa-check"></span>
                                                <?php endif;?>
                                            </td>
                                            
                                            <td>
                                                <div class="btn-group">

                                                    <?php if($u_permissions->update_per): ?>
                                                    <a href="<?php echo base_url()?>admin/permissions/edit/<?php echo $permission->id;?>" class="btn btn-warning"><span class="fa fa-pencil-alt"></span></a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                            <?php endforeach;?>
                        <?php endif;?>
                    </tbody>
                  </table>
                </div>
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