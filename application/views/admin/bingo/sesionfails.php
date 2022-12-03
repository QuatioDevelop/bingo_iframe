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

        <?php $this->load->view('layouts/navbar'); ?>

         <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800"><?php echo $this->lang->line('login_fails'); ?></h1>
                <!--a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> <?php echo $this->lang->line('admin_btn_gen_report'); ?></a-->
            </div>

            <!-- DataTales Example -->
            <div class="card shadow mb-4">
              <div class="card-body">
                <h3 class="text-cent"><?php echo $this->lang->line('admin_reports_title'); ?></h3>
                <div class='mb-4'>
                  <div class="form-group">
                    <label for="dtp_input1" class="col-md-2 control-label">Mostrar desde:</label>
                    <div class="input-group date form_datetime col-md-5" data-date="<?php echo date("Y-m-d H:i:s"); ?>" data-date-format="dd-mm-yyyy HH:ii" data-link-field="dtp_input1">
                      <input class="form-control" size="16" type="text" value="" readonly>
                      <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                      <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                    </div>
                    <input type="hidden" id="dtp_input1" value="" /><br/>
                  </div>
                </div>

                <div class="table-responsive">
                  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th><?php echo $this->lang->line('logs_id'); ?></th>
                        <th><?php echo $this->lang->line('logs_level'); ?></th>
                        <th><?php echo $this->lang->line('logs_event'); ?></th>
                        <th><?php echo $this->lang->line('logs_ip_address'); ?></th>
                        <th><?php echo $this->lang->line('logs_browser'); ?></th>
                        <th><?php echo $this->lang->line('logs_browser_version'); ?></th>
                        <th><?php echo $this->lang->line('logs_os'); ?></th>
                        <th><?php echo $this->lang->line('logs_date'); ?></th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($login_fails)):?>
                            <?php foreach($login_fails as $log):?>
                                <tr>
                                    <td><?php echo $log->id;?></td>
                                    <td><?php echo $log->level_name;?></td>
                                    <td><?php echo $log->event;?></td>
                                    <td><?php echo $log->ip_address;?></td>
                                    <td><?php echo $log->browser; ?></td>
                                    <td><?php echo $log->browser_version;?></td>
                                    <td><?php echo $log->os;?></td>
                                    <td><?php echo $log->date;?></td>                                    
                                </tr>
                            <?php endforeach;?>
                        <?php endif;?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <!-- DataTales Example -->
            <div class="card shadow mb-4">
              <div class="card-body">
                <h3 class="text-cent">Registro Fallos</h3>
                <div class='mb-4'>
                  <div class="form-group">
                    <label for="dtp_input2" class="col-md-2 control-label">Mostrar desde:</label>
                    <div class="input-group date form_datetime col-md-5" data-date="<?php echo date("Y-m-d H:i:s"); ?>" data-date-format="dd-mm-yyyy HH:ii" data-link-field="dtp_input2">
                      <input class="form-control" size="16" type="text" value="" readonly>
                      <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                      <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                    </div>
                    <input type="hidden" id="dtp_input2" value="" /><br/>
                  </div>
                </div>

                <div class="table-responsive">
                  <table class="table table-bordered" id="dataTable2" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th><?php echo $this->lang->line('logs_id'); ?></th>
                        <th>Bingo</th>
                        <th><?php echo $this->lang->line('admin_clients_table_username'); ?></th>
                        <th>jqXHR</th>
                        <th>status</th>
                        <th>Lugar</th>
                        <th><?php echo $this->lang->line('logs_date'); ?></th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($error_regs)):?>
                            <?php foreach($error_regs as $reg):?>
                                <tr>
                                    <td><?php echo $reg->id;?></td>
                                    <td><?php echo $reg->bingo;?></td>
                                    <td><?php echo $reg->user_id;?></td>
                                    <td><?php echo $reg->jqxhr_status;?></td>
                                    <td><?php echo $reg->text_status; ?></td>
                                    <td><?php echo $reg->place;?></td>
                                    <td><?php echo $reg->date;?></td>
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

  <!-- Bootstrap core JavaScript-->
  <script src="<?= base_url() ?>assets/template/vendor/jquery/jquery.min.js"></script>
  <script src="<?= base_url() ?>assets/template/vendor/jquery-ui-1.12.1/jquery-ui.min.js"></script>
  <script src="<?= base_url() ?>assets/template/vendor/bootstrap/js/bootstrap.min.js"></script>
  <script src="<?= base_url() ?>assets/template/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="<?= base_url() ?>assets/template/vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="<?= base_url() ?>assets/template/js/sb-admin-2.js"></script>
 
  <!-- Page level plugins -->
  <script src="<?= base_url() ?>assets/template/vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="<?= base_url() ?>assets/template/vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="<?= base_url() ?>assets/template/vendor/datetimepicker/js/bootstrap-datetimepicker.js"></script>
  <!--script src="<?= base_url() ?>assets/template/vendor/datetimepicker/bootstrap/js/bootstrap.js"></script-->
  <script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.bootstrap4.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.colVis.min.js"></script>
  <!-- Page level custom scripts -->
  <!--script src="<?= base_url() ?>assets/template/js/demo/datatables-demo.js"></script-->
  <script type="text/javascript">
    $(document).ready(function() {
      var oTable = $('#dataTable').DataTable({
        dom: 'Bfrtip',
        responsive: true,
        buttons: [
            {
              extend: 'copy'
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
                }
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
                }
            }
        ],
        columnDefs: [
                {width : "3%", targets: [0]},
                {width : "8%", targets: [1, 3, 5, 4, 6]},
                {width : "10%", targets: [7]}
            ],
        "order": [[ 0, "desc" ]],
        "language": {
            "lengthMenu": "Mostrar _MENU_ usuarios por pagina",
            "zeroRecords": "No se encontraron resultados en su busqueda",
            "searchPlaceholder": "Buscar usuarios",
            "info": "Mostrando del _START_ al _END_ de un total de  _TOTAL_ usuarios",
            "infoEmpty": "No existen usuarios",
            "infoFiltered": "(filtrado de un total de _MAX_ usuarios)",
            "search": "Buscar:",
            "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": "Siguiente",
                "previous": "Anterior"
            },
        }
      });

      $('.form_datetime').datetimepicker({
          //language:  'fr',
          format: "yyyy-mm-dd hh:ii",
          todayBtn:  1,
          autoclose: 1,
          todayHighlight: 1,
          startView: 2
          
      }).on('changeDate', function(ev){
        if(ev.date)
        {
          minDateFilter = ev.date.getTime();
        }else
        {
          minDateFilter = "";
        }
        oTable.draw();
      });      
    });

    // Date range filter
    minDateFilter = "";
    maxDateFilter = "";

    $.fn.dataTableExt.afnFiltering.push(
      function(oSettings, aData, iDataIndex) {
        
          if (typeof aData._date == 'undefined') {
            aData._date = new Date(aData[7]).getTime();
          }

          if (minDateFilter && !isNaN(minDateFilter)) {
            if (aData._date < minDateFilter) {
              return false;
            }
          }
          return true;
      }
    );

  </script>
</body>

</html>
