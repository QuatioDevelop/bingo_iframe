<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('admin/clients/meta_clients'); ?>
    <title>Control En Vivo</title>
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
                <h1 class="h3 mb-0 text-gray-800"><?php echo $this->lang->line('winners'); ?></h1>
                <!--a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> <?php echo $this->lang->line('admin_btn_gen_report'); ?></a-->
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <h4 id="connected">Se han conectado <?php echo $total_clients->total_users; ?> Usuarios</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h4 id="last_draw">Última Balota: <?php echo $last_draw; ?></h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h4 id="total_draws">Total Balotas: <?php echo $total_draws; ?></h4>
                </div>
            </div>
            <hr>
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
              <div class="card-body">
                <h3 class="text-cent">Usuarios</h3>
                <div class="table-responsive">
                  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th><?php echo $this->lang->line('admin_clients_table_name'); ?></th>
                        <th><?php echo $this->lang->line('admin_clients_table_username'); ?></th>
                        <th><?php echo $this->lang->line('user_bingotable_title'); ?></th>
                        <th><?php echo $this->lang->line('bingo_pattern_title'); ?></th>
                        <th><?php echo $this->lang->line('user_last_login_title'); ?></th>
                        <th>Última actividad</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody id="winnersbody">
                        <?php if(!empty($winners)):?>
                            <?php foreach($winners as $winner):?>
                                <tr>
                                    <td><?php echo $winner['name'];?></td>
                                    <td><?php echo $winner['user_id'];?></td>
                                    <td><a href="<?php echo base_url() ?>admin/bingo/viewcard/<?php echo $this->outh_model->Encryptor('encrypt',$winner['user_id']) ?>/<?php echo $winner['card'] ?>" target=_blank><?php echo $winner['card'];?></a></td>
                                    <?php 
                                      $patterns = json_decode($winner['patterns'], true); 
                                      $patt = array();
                                      foreach($patterns[$winner['card']] as $pattern)
                                      {
                                        array_push($patt, $pattern['name']);
                                      }
                                    ?>
                                    <td><?php echo implode("<br>",$patt);?></td>
                                    <td><?php echo $winner['last_login'];?></td>
                                    <td><?php echo $winner['last_activity'];?></td>
                                    <td>
                                      <div class="btn-group">
                                        <?php if($u_permissions->update_per): ?>
                                        <a href="<?php echo base_url()?>admin/bingo/dobingo/<?php echo $winner['user_id'];?>/<?php echo $winner['card'];?>" class="btn btn-info"><span class="fa fa-bold"></span></a>
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
    var base_url = "<?php echo base_url(); ?>";
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
                    columns: [ 0, 1, 2, 3, 4, 5 ]
                }
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5 ]
                }
            }
        ],
        columnDefs: [
                {width : "5%", targets: [2]},
                {width : "10%", targets: [1]}
            ],
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
      
      setTimeout('checkwinner()',5000);

      /*$("#datepicker_from").datetimepicker({
        showOn: "button",
        buttonImageOnly: false,
        "onSelect": function(date) {
          minDateFilter = new Date(date).getTime();
          oTable.draw();
        }
      }).keyup(function() {
        minDateFilter = new Date(this.value).getTime();
        oTable.draw();
      });*/

    });

    function checkwinner() 
    { 
      $.ajax({
      async:  true, 
        type: "POST",
        url: base_url+"admin/bingo/checkwinner",
      dataType:"html",
        success: function(data)
        { 
          //console.log("-----------------");
          //console.log(data);

          var json         = JSON.parse(data);
          //console.log(json.winners);
          $('#connected').html('Se han conectado '+json.total_clients.total_users+' Usuarios');
          $('#last_draw').html('Última Balota: '+json.last_draw);
          $('#total_draws').html('Total Balotas: '+json.total_draws);
          $('#winnersbody').html(json.winners);

          //for ($i = 0; i < json.winners.length; $i++) {
          

          setTimeout('checkwinner()',5000);
                
        },
        error: function( jqXHR, textStatus, errorThrown ) {
            console.log(jqXHR.status, textStatus, errorThrown);
            alert("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
            
        }
      }).fail( function( jqXHR, textStatus, errorThrown ) {
          console.log(jqXHR.status, textStatus, errorThrown);
          alert("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
      });   
    }
  </script>
</body>

</html>
