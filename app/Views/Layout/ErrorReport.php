<?php if(isset($session) && null !== $session->getFlashdata('error')) { ?>

<div class="alert alert-solid-danger alert-dismissible d-flex align-items-center" role="alert">
    <i class="bx bx-xs bx-store me-2"></i>
    <?php echo $session->getFlashdata('error'); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div><br/>

<?php } ?>


<div id="errorModal">
    <div class="alert alert-danger" role="alert">
        <button type="button" style="float: right;" class="btn-close btn-close-error" aria-label="Close"></button>
        <h6 class="alert-heading">ERROR !!</h6>
        <div id="formErrorMessage"></div>
    </div>
</div>
<script>
$(document).on('click', '.btn-close-error', function() {
    $('#errorModal').hide();
});
</script>