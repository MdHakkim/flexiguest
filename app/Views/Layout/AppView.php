<!DOCTYPE html>

<html
  lang="en"
  class="light-style layout-navbar-fixed layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="<?=base_url('assets')?>/"
  data-template="vertical-menu-template"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />
    <?=$this->renderSection("titleRender")?>
      <?= $this->include('Layout/HeaderScript') ?>
      <?= helper('pageaccess'); ?>
      <body>
        <!-- Layout wrapper -->
          <div id="loader_flex_bg"><div class="loader_flexi"></div></div>
          <div class="layout-wrapper layout-content-navbar">
              <div class="layout-container">
                <!-- Menu -->
                <?= $this->include('Layout/SidePanel') ?>
                <!-- / Menu -->

                <!-- Layout container -->
                <div class="layout-page">
                  <!-- Navbar -->
                  <?= $this->include('Layout/MenuPanel') ?>

                     <!-- Content wrapper -->
          <div class="content-wrapper">
          <?php 
          $access = '';
          $access  = checkPageAccess();
          if($access ==  0){
          ?>
              <!-- Not Authorized -->
              <div class="container-xxl container-p-y">
                <div class="misc-wrapper text-center">
                  <h1 class="mx-2 mb-2">You are not authorized!</h1>
                  <p class="mx-2 mb-4">You don't have permission to access this page!!</p>
                  
                  <div class="mt-5">
                    <img
                      src='<?php echo base_url('/assets/img/illustrations/girl-hacking-site-light.png');?>'
                      alt="page-misc-error-light"
                      width="450"
                    
                    />
                  </div>
                </div>
              </div>
            <!-- /Not Authorized -->
            
            <?php 
          }
          
          else{
            echo $this->renderSection("contentRender");
          }
            
          
          ?>

          <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->

          <!-- Footer -->

          <?= $this->include('Layout/Footer') ?>



                </div>
            <!-- / Layout page -->
            </div>

            <!-- Overlay -->
            <div class="layout-overlay layout-menu-toggle"></div>
            <!-- Drag Target Area To SlideIn Menu On Small Screens -->
            <div class="drag-target"></div>
          </div>
          <?= $this->include('Layout/FooterScript') ?>

          <?= $this->renderSection("script") ?>
      </body>
    </html>