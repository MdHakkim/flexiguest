<!DOCTYPE html>

<html
  lang="en"
  class="light-style layout-navbar-fixed layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="assets/"
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
                  
                  <?=$this->renderSection("contentRender")?>

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
      </body>
    </html>