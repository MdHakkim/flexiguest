    
<?= $this->extend("Layout/AppView") ?>
<?= $this->section("contentRender") ?>

<div class="content-wrapper">
  <!-- Content -->

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
<!-- / Content -->
<div class="content-backdrop fade"></div>
</div>
<?=$this->endSection()?>
