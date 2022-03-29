


  <div id="errorModal">
    <div class="alert alert-danger" role="alert">
      <button type="button" style="float: right;" class="btn-close btn-close-error" aria-label="Close"></button>
      <h6 class="alert-heading">Error report!</h6>
      <div id="formErrorMessage"></div>
    </div>
  </div>
<script>
  $(document).on('click','.btn-close-error',function(){
    $('#errorModal').hide();
  });
</script>