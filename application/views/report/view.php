<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//echo "<br>";
//print_r($st);
//echo "<br>";
$keys = array();
$types = array();
if(!empty($stats)){
  $stdata = json_decode ($stats[sizeof($stats)-1]->data, true);
  //print_r($stdata);
  foreach ($stdata[$this->session->userdata('lang')] as $key => $value) {
      array_push($keys, $key);
  }

  $dat = array();
  foreach ($keys as $key) {
      //array_push($types, gettype ($stdata[$this->session->userdata('lang')][$key]));
      $types[$key] = gettype ($stdata[$this->session->userdata('lang')][$key]);
      //$dat[$key] = array();
  }

  //print_r($types);
  foreach($stats as $detail)
  {
      $gamedata = json_decode ($detail->data, true);
      foreach ($keys as $key) 
      {
          if(array_key_exists($key,$gamedata[$this->session->userdata('lang')]))
          {
            switch (gettype($gamedata[$this->session->userdata('lang')][$key])) {
              case 'integer':
                //echo "integer ".$gamedata[$this->session->userdata('lang')][$key]."<br>";
                if(array_key_exists($key,$dat))
                {
                  //$dat[$key]['values'] += $gamedata[$this->session->userdata('lang')][$key];
                  array_push($dat[$key]['values'], $gamedata[$this->session->userdata('lang')][$key]);
                }else
                {
                  $dat[$key]['key'] = $key;
                  $dat[$key]['type'] = 'integer';
                  $dat[$key]['type_lbl'] = 'integer_type_lbl';
                  $dat[$key]['values'] = array();
                  array_push($dat[$key]['values'], $gamedata[$this->session->userdata('lang')][$key]);
                  //$dat[$key]['values'] = $gamedata[$this->session->userdata('lang')][$key];
                  //$dat[$key]['count'] = 1;
                }                
                break;
              case 'string':
                //echo "string ".$gamedata[$this->session->userdata('lang')][$key]." ".array_key_exists($key,$dat)."<br> ";
                if(array_key_exists($key,$dat))
                {
                  if(array_key_exists($gamedata[$this->session->userdata('lang')][$key],$dat[$key]['values']))
                  {
                    $dat[$key]['values'][$gamedata[$this->session->userdata('lang')][$key]]['count']++;
                  }else
                  {
                    $dat[$key]['values'][$gamedata[$this->session->userdata('lang')][$key]]['key'] = $gamedata[$this->session->userdata('lang')][$key];
                    $dat[$key]['values'][$gamedata[$this->session->userdata('lang')][$key]]['count'] = 1;
                  }
                }else
                {
                  $dat[$key] = array();
                  $dat[$key]['key'] = $key;
                  $dat[$key]['type'] = 'string';
                  $dat[$key]['type_lbl'] = 'string_type_lbl';
                  $dat[$key]['values'][$gamedata[$this->session->userdata('lang')][$key]]['key'] = $gamedata[$this->session->userdata('lang')][$key];
                  $dat[$key]['values'][$gamedata[$this->session->userdata('lang')][$key]]['count'] = 1;
                } 
                
                break;
              default:
                # code...
                break;
            }
            //
          }
      }
  //    //if(array_key_exists($key,$gamedata[$this->session->userdata('lang')]))
  //                                          {
  }
  //print_r($dat);
  $div = 0;
  foreach ($dat as $v) {
      //echo "<br>------------------<br>";
      //print_r($v);
      switch ($v['type']) {
              case 'integer':
                  if(count($v['values'])) {
                      $v['summary']['count'] = array_sum($v['values'])/count($v['values']);
                  }
                  $v['summary']['max'] = max($v['values']);
                  $v['summary']['min'] = min($v['values']);
                  $div += 3;
                  //$v['summary']['count'] = $v['value']/$v['count'];       
                break;
              case 'string':
                  $highest = -1;
                  foreach($v['values'] as $detail)
                  {
                      if($detail['count'] > $highest)
                      {
                        $highest = $detail['count'];
                        $v['summary'] = $detail;
                      }
                  }
                  $div ++;
                break;
              default:
                break;
            }
      $dat[$v['key']] = $v;
  }
  //echo "**************<br>";
  //print_r($dat);
  if($div != 0)
    $div = $div >= 12 ? 12 : 12/$div;

}
/*foreach ($stdata as $value) {
    print_r($value[$this->session->userdata('lang')]);echo "<br>";
    array_push($keys, $value[$this->session->userdata('lang')]);
}*/

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
                <h1 class="h3 mb-0 text-gray-800"><?php echo $this->lang->line('admin_reports_title'); ?></h1>
                <a href="<?php echo base_url()?>reports" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-arrow-left fa-sm text-white-50"></i> <?php echo $this->lang->line('admin_btn_back'); ?></a>
            </div>
         

          <!-- Content Row -->


                <?php 
            //print_r($stats);
                //echo $this->session->userdata('lang');
            if(empty($stats)):?>
                <div class="col-xs-6">  
                    <h4><?php echo $this->lang->line('no_stats_data'); ?></h4><br>
                </div> 
        <?php else:?>
                
                   <!-- Page Heading -->
          <h4 class="h4 mb-2 text-gray-800"><?php echo $stats[0]->campaign_name; ?></h4>

            <!-- Content Row -->
          <div class="row">

             <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-6 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1"><?php echo $this->lang->line('reached_users'); ?></div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $users->total_users; ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-user fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-6 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1"><?php echo $this->lang->line('total_data'); ?></div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo sizeof($stats); ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-chart-area fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <?php foreach($dat as $detail):?>
            <!-- Pending Requests Card Example -->
            <div class="col-xl-<?php echo $div; ?> col-md-6 mb-4">
              <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-warning text-uppercase mb-1"><?php echo $this->lang->line($detail['type_lbl'])." ".$detail['key']; ?></div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo array_key_exists('key',$detail['summary']) ? $detail['summary']['key']/*." = ".$detail['summary']['count']*/ : $detail['summary']['count']; ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-<?php echo $detail['type'] == 'integer' ? 'hashtag' : 'pastafarianism'; ?> fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <?php if($detail['type'] == 'integer'):?>
              <div class="col-xl-<?php echo $div; ?> col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1"><?php echo $this->lang->line('min_lbl')." ".$detail['key']; ?></div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $detail['summary']['min']; ?></div>
                      </div>
                      <div class="col-auto">
                        <i class="fas fa-hashtag fa-2x text-gray-300"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-xl-<?php echo $div; ?> col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1"><?php echo $this->lang->line('max_lbl')." ".$detail['key']; ?></div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $detail['summary']['max']; ?></div>
                      </div>
                      <div class="col-auto">
                        <i class="fas fa-hashtag fa-2x text-gray-300"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <?php endif;?>
          <?php endforeach;?>
          </div>

          <div class="row">

            <?php foreach($dat as $detail):?>
              <?php if($detail['type'] != 'integer'):?>
                <!-- Bar Chart -->
                <div class="col-xl-6 col-lg-5">
                  <div class="card shadow mb-4">
                    <div class="card-body">
                      <div class="chart-bar">
                        <canvas id="barChart<?php echo $detail['key']; ?>"></canvas>
                      </div>
                      <hr>
                      
                    </div>
                  </div>
                </div>
              <?php endif;?>
            <?php endforeach;?>
          </div>

                <!-- DataTales Example -->
                <div class="card shadow mb-4">
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                          <tr>
                            <th><?php echo $this->lang->line('reports_table_date'); ?></th>
                            <th><?php echo $this->lang->line('reports_table_user'); ?></th>
                            <?php
                            foreach ($keys as $key) {
                                echo "<th>".$key."</th>";
                            }?>
                          </tr>
                        </thead>
                        <tbody>
                                <?php foreach($stats as $detail):?>
                                    <?php //print_r($detail); ?>
                                    <tr>
                                        <td><?php echo $detail->date;?></td>
                                        <td><?php echo $detail->user;?></td>
                                        <?php
                                        $gamedata = json_decode ($detail->data, true);
                                        foreach ($keys as $key) {
                                            if(array_key_exists($key,$gamedata[$this->session->userdata('lang')]))
                                            {
                                                echo "<td>".$gamedata[$this->session->userdata('lang')][$key]."</td>";
                                            }
                                            else
                                            {
                                                echo "<td>".$this->lang->line('no_stats_data')."</td>";
                                            }
                                        }?>   
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
  <script src="<?= base_url() ?>assets/template/vendor/chart.js/Chart.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="<?= base_url() ?>assets/template/js/demo/datatables-demo.js"></script>
  <script src="<?= base_url() ?>assets/template/js/chart-bar.js"></script>
  <script type="text/javascript">
    $(function(){
        //var base_url= "<?php echo base_url();?>";
        <?php foreach($dat as $v):?>
          <?php if($v['type'] != 'integer'):
            $xData = array();
            $yData = array();
            foreach($v['values'] as $detail)
            {
              array_push($yData, $detail['count']);                      
              array_push($xData, '"'.$detail['key'].'"');
            }
            ?>
            drawChart("barChart<?php echo $v['key']; ?>",<?php echo '"'.$v['key'].'"'; ?>,<?php echo '['.implode(",",$xData)."]"; ?>,"",<?php echo '['.implode(",",$yData)."]"; ?>);
             
          <?php endif;?>
        <?php endforeach;?>
      });

  </script>

</body>

</html>
