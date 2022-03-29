<style>
  #successModal, #warningModal{
    display: none;
  }
  #successModal, #warningModal{
      position: fixed;
      top: 10px;
      right: 22px;
      z-index: 10000;
      width: 500px;
  }
</style>

<div id="successModal">
    <div class="alert alert-success" role="alert">
      <button type="button" style="float: right;" class="btn-close btn-close-success" aria-label="Close"></button>
      <h6 class="alert-heading">Success!</h6>
      <div id="formSuccessMessage"></div>
    </div>
</div>
<div id="warningModal">
    <div class="alert alert-warning" role="alert">
      <button type="button" style="float: right;" class="btn-close btn-close-warning" aria-label="Close"></button>
      <h6 class="alert-heading"></h6>
      <div id="formWarningMessage"></div>
    </div>
</div>
<script>
  $(document).on('click','.btn-close-success',function(){
    $('#successModal').hide();
  });

  $(document).on('click','.btn-close-warning',function(){
    $('#warningModal').hide();
  });
</script>