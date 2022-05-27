<div class="modal fade" id="image-popup" style="z-index: 1100" data-backdrop="static" data-keyboard="false" aria-lableledby="popModalWindowlable">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <div class="modal-header">
                <!-- <h4 class="modal-title" id="popModalWindowlabel">Add Concierge Offer</h4> -->
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
            </div>

            <div class="modal-body">
                <img class="d-block w-100" src="">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function displayImagePopup(src){
        $("#image-popup img").attr('src', src);
        $("#image-popup").modal('show');
    }
</script>