<?php
  $modalTypes = array('success', 'warning', 'info');
?>

<style>
<?php echo '#'.implode("Modal, #", $modalTypes).'Modal'?> {
    display: none;
    position: fixed;
    top: 10px;
    right: 22px;
    z-index: 10000;
    width: 500px;
}
</style>

<?php
      foreach($modalTypes as $modalType) 
      {
        if(isset($session) && null !== $session->getFlashdata($modalType)) { 
?>
        <div class="alert alert-solid-<?=$modalType?> alert-dismissible d-flex align-items-center" role="alert">
            <i class="bx bx-xs bx-store me-2"></i>
            <?php echo $session->getFlashdata($modalType); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div><br />

<?php   }
?>
        <div id="<?=$modalType?>Modal" class="alertModal">
            <div class="alert alert-<?=$modalType?>" role="alert">
                <button type="button" style="float: right;" class="btn-close btn-close-<?=$modalType?> btn-modal-close" aria-label="Close"></button>
                <h6 class="alert-heading"><?=$modalType == 'success' ? ucfirst($modalType).'!' : ''?></h6>
                <div id="form<?=ucfirst($modalType)?>Message"></div>
            </div>
        </div>
<?php
      }
?>

<script>
$(document).on('click', '.btn-modal-close', function() {
    $(this).parents('.alertModal').hide();
});
</script>