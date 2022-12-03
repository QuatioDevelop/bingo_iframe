<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('admin/clients/meta_clients'); ?>
    <title>Ganadores</title>
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
                <h1 class="h3 mb-0 text-gray-800"><?php echo $this->lang->line('admin_clients_title'); ?></h1>
                <!--a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> <?php echo $this->lang->line('admin_btn_gen_report'); ?></a-->
            </div>

            
            <div class="row">
                <div class="col-md-12">
                    <h4 id="connected">Se han conectado <?php echo $total_clients->total_users; ?> Usuarios</h4>
                </div>
            </div>
            <hr>
            <?php if(!empty($winners)):?>
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
              <div class="card-body">
                    <h3 class="text-cent"><?php echo $this->lang->line('winners'); ?></h3>
                <div class="table-responsive">
                  <table class="table table-bordered" id="dataTableWinners" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th><?php echo $this->lang->line('admin_clients_table_name'); ?></th>
                        <th><?php echo $this->lang->line('admin_clients_table_username'); ?></th>
                        <th><?php echo $this->lang->line('user_bingotable_title'); ?></th>
                        <th><?php echo $this->lang->line('bingo_pattern_title'); ?></th>
                        <th><?php echo $this->lang->line('reports_table_date'); ?></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach($winners as $winner):?>
                          <tr>
                              <td><?php echo $winner->name;?></td>
                              <td><?php echo $winner->user_id;?></td>
                              <td><a href="<?php echo base_url() ?>admin/bingo/viewcard/<?php echo $this->outh_model->Encryptor('encrypt',$winner->user_id) ?>/<?php echo $winner->card ?>" target=_blank><?php echo $winner->card;?></a></td>
                              <?php 
                                $patterns = json_decode($winner->patterns, true); 
                                $patt = array();
                                if($patterns != null)
                                {
                                  foreach($patterns[$winner->card] as $pattern)
                                  {
                                    array_push($patt, $pattern['name']);
                                  }
                                }
                              ?>
                              <td><?php echo implode("<br>",$patt);?></td>
                              <td><?php echo $winner->timestamp;?></td>
                          </tr>
                      <?php endforeach;?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <?php endif;?>

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

  <div class="modal fade" id="modal-remove" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content" style="color: <?php echo $this->config->item('main_font_color'); ?>;">
        <div class="modal-header">
          <h4 class="modal-title">Advertencia</h4>
          <button type="button" class="close" id="cancel-remove-x" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <p>¿Está seguro que desea eliminar todos los usuarios?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger pull-left" id="cancel-remove" data-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary btn-sure-remove" id="confirm-remove" style="background-color: <?php echo $this->config->item('primary_btn_color'); ?>; border-color: <?php echo $this->config->item('primary_btn_color'); ?>; color: <?php echo $this->config->item('primary_btn_font_color'); ?>;">Aceptar</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content" style="color: <?php echo $this->config->item('main_font_color'); ?>;">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Información</h5>
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
                    columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                }
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                }
            }
        ],
        columnDefs: [
                {width : "10%", targets: [1, 3, 4, 6]},
                {width : "5%", targets: [5]}
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
        coun = 0;
        if(ev.date)
        {
          minDateFilter = ev.date.getTime();
        }else
        {
          minDateFilter = "";
        }
        oTable.draw();
      });

      oTable.on( 'draw', function () {
        //console.log('rows count:', oTable.rows( {search:'applied'} ).count());
        //console.log('page info:', oTable.page.info().recordsDisplay);
        //console.log("ConectadoS "+coun);
        $('#connected').html("Se han conectado "+coun+" Usuarios");
        coun = 0;
      } );
          
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

      $('#dataTable3').DataTable({
        dom: 'Bfrtip',
        responsive: true,
        buttons: [
            {
              extend: 'copy'
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                }
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6]
                }
            }
        ],
        columnDefs: [
                {width : "15%", targets: [0, 2, 7]},
                {width : "10%", targets: [1, 3, 4, 6]},
                {width : "5%", targets: [5]}
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

      $('#dataTableWinners').DataTable({
        dom: 'Bfrtip',
        responsive: true,
        buttons: [
            {
              extend: 'copy'
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4 ]
                }
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4 ]
                }
            }
        ],
        columnDefs: [
                {width : "15%", targets: [0, 3]},
                {width : "10%", targets: [1, 4]},
                {width : "5%", targets: [2]}
            ],
        "language": {
            "lengthMenu": "Mostrar _MENU_ ganadores por pagina",
            "zeroRecords": "No se encontraron resultados en su busqueda",
            "searchPlaceholder": "Buscar ganadores",
            "info": "Mostrando del _START_ al _END_ de un total de  _TOTAL_ ganadores",
            "infoEmpty": "No existen ganadores",
            "infoFiltered": "(filtrado de un total de _MAX_ ganadores)",
            "search": "Buscar:",
            "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": "Siguiente",
                "previous": "Anterior"
            },
        }
      });

      $(".btn-remove-all").on("click", function(e){
        e.preventDefault();
        var ruta = $(this).attr("href");
        $("#confirm-remove").val(ruta);
        $("#modal-remove").modal();

      });

      $("#confirm-remove").on("click", function(e){
          var ruta = $(this).val();
          $.ajax({
              url: ruta,
              type:"POST",
              success:function(resp){
                  window.location.href = base_url + resp.trim();
              }
          });
      });
      
    });

    // Date range filter
    minDateFilter = "";
    maxDateFilter = "";
    var coun = 0;
    $.fn.dataTableExt.afnFiltering.push(
      function(oSettings, aData, iDataIndex) {
        if (typeof aData._date == 'undefined') {
          aData._date = new Date(aData[6]).getTime();
        }

        if (minDateFilter && !isNaN(minDateFilter)) {
          if (aData._date < minDateFilter) {
            return false;
          }
        }
        if(aData[8] == "Sí")
          coun++;
        //console.log("--- "+coun+" "+aData[0]);
        return true;
      }
    );

    function iswinner(uid){
      //console.log("iswinner: "+uid);
      $.ajax({
          async:  true, 
          type: "POST",
          url: base_url+"admin/bingo/iswinner",
          data: "uid="+uid,
          dataType:"html",
          success: function(data)
          { 
            //console.log("-----------------");
            //console.log(data);
            var json = JSON.parse(data);
            //console.log(json);
            var res = '<div class="table-responsive">';
                res += '  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">';
                res += '    <thead>';
                res += '      <tr>';
                res += '        <th><?php echo $this->lang->line('admin_clients_table_name'); ?></th>';
                res += '        <th><?php echo $this->lang->line('admin_clients_table_username'); ?></th>';
                res += '        <th><?php echo $this->lang->line('user_bingotable_title'); ?></th>';
                res += '        <th><?php echo $this->lang->line('bingo_pattern_title'); ?></th>';
                res += '        <th></th>';
                res += '      </tr>';
                res += '    </thead>';
                res += '    <tbody>';
                            for(var i = 0; i < json.length; i++){
                res += '                <tr>';
                res += '                    <td>'+json[i]['name']+'</td>';
                res += '                    <td>'+json[i]['user_id']+'</td>';
                res += '                    <td>'+json[i]['card']+'</td>';
                                      var patterns = JSON.parse(json[i]['patterns']); 
                                      var patt = [];
                                      //foreach(patterns[json[i]['card']] as $pattern)
                                      for(var j = 0; j < patterns[json[i]['card']].length; j++)
                                      {
                                        patt.push(patterns[json[i]['card']][j]['name']);
                                      }
                                    
                res += '                    <td>'+patt.join("<br>")+'</td>';
                res += '                    <td>';
                res += '                      <div class="btn-group">';
                res += '                        <a href="'+base_url+'admin/clients/dobingo/'+json[i]['user_id']+'/'+json[i]['card']+'" class="btn btn-info"><span class="fa fa-bold"></span></a>';
                res += '                      </div>';
                res += '                    </td>';
                res += '                </tr>';
                            }
                res += '    </tbody>';
                res += '  </table>';
                res += '</div>';
                $('#myModal .modal-body').html(res);
                $('#myModal').modal('show');
          },
          error: function( jqXHR, textStatus, errorThrown ) {
              console.log(jqXHR.status, textStatus, errorThrown);
              //log_e(jqXHR.status,textStatus+"-"+errorThrown,"clientlist-iswinner");
              //alert("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
              $('#myModal .modal-body').html("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
              $('#myModal').modal('show');
          }

      }).fail( function( jqXHR, textStatus, errorThrown ) {
          console.log(jqXHR.status, textStatus, errorThrown);
          //log_e(jqXHR.status,textStatus+"-"+errorThrown,"clientlist-iswinner");
          //alert("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
          $('#myModal .modal-body').html("Ha ocurrido un error: "+jqXHR.status+" "+textStatus+" "+errorThrown);
          $('#myModal').modal('show');
      });
    } 
  </script>
</body>

</html>
