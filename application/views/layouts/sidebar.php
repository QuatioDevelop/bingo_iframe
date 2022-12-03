<?php
 $this->load->model('backend_model');
 $this->load->model('clientes_model');
    //$client = $this->clientes_model->getClient($id);
  /*
    $permissions[index] 
    index => 1 - Inicio - dashboard
    index => 2 - Usuarios - administrador/usuarios
    index => 3 - Permisos - administrador/permisos
    */
    $permissions = $this->session->userdata('user_data')['permissions'];

    $showAdmin = (!empty($permissions) && ($permissions['2']['update'] || $permissions['3']['update']));
    $showAdminBingo = (!empty($permissions) && ($permissions['5']['read_other']));

?>
<!-- Sidebar -->
    <ul class="navbar-nav sidebar sidebar-light accordion position-fixed" id="accordionSidebar" style="background-color: <?php echo $this->config->item('sidebar_bg_color'); ?>;">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url();?>">
        <!--div class="sidebar-brand-icon rotate-n-15">
          <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Credifinanciera</div-->
          <img src="<?= base_url();?>assets/template/img/bingo-side-logo.png" class="side-logo">
          <img src="<?= base_url();?>assets/template/img/bingo-side-logo-s.png" class="side-logo-s">
      </a>

      <!-- Divider -->
      <!--<hr class="sidebar-divider my-0" style="border-top: 1px solid <?php echo $this->config->item('sidebar_font_color'); ?>;">-->

      <!-- Nav Item - Dashboard -->
      <li class="nav-item active" style="color: <?php echo $this->config->item('sidebar_font_color'); ?>;">
        <a class="nav-link" href="<?= base_url();?>">
          <i class="fas fa-fw fa-tachometer-alt" style="color: <?php echo $this->config->item('sidebar_font_color'); ?>;"></i>
          <span style="color: <?php echo $this->config->item('sidebar_font_color'); ?>;">Bingo</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider" style="border-top: 1px solid <?php echo $this->config->item('sidebar_font_color'); ?>;">

      <!-- Heading -->
      <!--div class="sidebar-heading">
        <?php echo $this->lang->line('sidebar_statistics_title'); ?>
      </div-->

      <li class="nav-item">
        <a class="nav-link" href="<?= base_url();?>termsconditions">
          <i class="fas fa-fw fa-clipboard-check" style="color: <?php echo $this->config->item('sidebar_font_color'); ?>;"></i>
          <span style="color: <?php echo $this->config->item('sidebar_font_color'); ?>;"><?php echo $this->lang->line('termcondic_title'); ?></span></a>
      </li>

      <!--li class="nav-item">
        <a class="nav-link" href="<?= base_url();?>">
          <i class="fas fa-fw fa-dice"></i>
          <span><?php echo $this->lang->line('user_bingotable_title'); ?></span></a>
      </li-->

      <?php if($showAdminBingo): ?>
      <li class="nav-item">
        <a class="nav-link" href="<?= base_url();?>support">
          <i class="fas fa-fw fa-dice" style="color: <?php echo $this->config->item('sidebar_font_color'); ?>;"></i>
          <span style="color: <?php echo $this->config->item('sidebar_font_color'); ?>;">Soporte</span></a>
      </li>


     <!-- Nav Item - Charts -->
      <li class="nav-item">
        <a class="nav-link" href="<?= base_url();?>admin/bingo/colorview">
          <i class="fas fa-fw fa-magic" style="color: <?php echo $this->config->item('sidebar_font_color'); ?>;"></i>
          <span style="color: <?php echo $this->config->item('sidebar_font_color'); ?>;">Tablas color</span></a>
      </li>

      <?php endif; ?>

      <!-- Nav Item - Charts -->
      <!--li class="nav-item">
        <a class="nav-link" href="<?//= base_url();?>reports">
          <i class="fas fa-fw fa-chart-area"></i>
          <span><?php //echo $this->lang->line('admin_reports_title'); ?></span></a>
      </li-->

      <!-- Nav Item - Charts -->
      <!--li class="nav-item">
        <a class="nav-link" href="<?//= base_url();?>users">
          <i class="fas fa-fw fa-user"></i>
          <span><?php //echo $this->lang->line('users_title'); ?></span></a>
      </li-->

      <!-- Divider -->
      <hr class="sidebar-divider" style="border-top: 1px solid <?php echo $this->config->item('sidebar_font_color'); ?>;">

      <?php if($showAdminBingo): ?>

      <!-- Heading -->
      <div class="sidebar-heading" style="color: <?php echo $this->config->item('sidebar_font_color'); ?>;">
        <?php echo $this->lang->line('sidebar_admin_title'); ?>
      </div>

      <!-- Nav Item - Charts -->
      <li class="nav-item">
        <a class="nav-link" href="<?= base_url();?>admin/bingo">
          <i class="fas fa-fw fa-cog" style="color: <?php echo $this->config->item('sidebar_font_color'); ?>;"></i>
          <span style="color: <?php echo $this->config->item('sidebar_font_color'); ?>;"><?php echo $this->lang->line('admin_bingo_title'); ?></span></a>
      </li>

      <hr class="sidebar-divider d-none d-md-block" style="border-top: 1px solid <?php echo $this->config->item('sidebar_font_color'); ?>;">

      <?php endif; ?>

      <?php if($showAdmin): ?>

      <!-- Nav Item - Charts -->
      <li class="nav-item">
        <a class="nav-link" href="<?= base_url();?>admin/clients">
          <i class="fas fa-fw fa-address-book" style="color: <?php echo $this->config->item('sidebar_font_color'); ?>;"></i>
          <span style="color: <?php echo $this->config->item('sidebar_font_color'); ?>;"><?php echo $this->lang->line('sidebar_clients_title'); ?></span></a>
      </li>

      <!-- Nav Item - Charts -->
      <li class="nav-item">
        <a class="nav-link" href="<?= base_url();?>admin/bingo/winnerslite">
          <i class="fas fa-fw fa-trophy" style="color: <?php echo $this->config->item('sidebar_font_color'); ?>;"></i>
          <span style="color: <?php echo $this->config->item('sidebar_font_color'); ?>;">Control en vivo</span></a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="<?= base_url();?>admin/bingo/winners">
          <i class="fas fa-fw fa-trophy" style="color: <?php echo $this->config->item('sidebar_font_color'); ?>;"></i>
          <span style="color: <?php echo $this->config->item('sidebar_font_color'); ?>;"><?php echo $this->lang->line('winners'); ?></span></a>
      </li>

      <!-- Nav Item - Charts -->
      <li class="nav-item">
        <a class="nav-link" href="<?= base_url();?>admin/bingo/audit">
          <i class="fas fa-fw fa-archive" style="color: <?php echo $this->config->item('sidebar_font_color'); ?>;"></i>
          <span style="color: <?php echo $this->config->item('sidebar_font_color'); ?>;"><?php echo $this->lang->line('audit_title'); ?></span></a>
      </li>

      <!-- Nav Item - Charts -->
      <li class="nav-item">
        <a class="nav-link" href="<?= base_url();?>admin/bingo/loginfails">
          <i class="fas fa-fw fa-window-close" style="color: <?php echo $this->config->item('sidebar_font_color'); ?>;"></i>
          <span style="color: <?php echo $this->config->item('sidebar_font_color'); ?>;"><?php echo $this->lang->line('no_login'); ?></span></a>
      </li>
      
      <!-- Nav Item - Tables -->
      <li class="nav-item">
        <a class="nav-link" href="<?= base_url();?>admin/permissions">
          <i class="fas fa-fw fa-table" style="color: <?php echo $this->config->item('sidebar_font_color'); ?>;"></i>
          <span style="color: <?php echo $this->config->item('sidebar_font_color'); ?>;"><?php echo $this->lang->line('admin_permissions_title'); ?></span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block" style="border-top: 1px solid <?php echo $this->config->item('sidebar_font_color'); ?>;">

      <?php endif; ?>

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->