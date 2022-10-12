<!-- Reservation Attachments  -->
<div class="modal fade" id="resvAttachmentsModal" data-backdrop="static" data-keyboard="false"
    aria-labelledby="popModalWindowlabel">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="resvAttachmentsModallabel">Attachments of Reservation</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <p>Drop files here or click to upload <b>(Image, PDF or Word Documents. Max 5MB each)</b></p>

                <form action="<?= base_url('/uploadResvAttachments')?>" method="POST" class="dropzone needsclick"
                    id="dropzone-multi" enctype="multipart/form-data">
                    <div class="dz-message needsclick">

                    </div>
                    <div class="fallback">
                        <input name="file[]" type="file" multiple />
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" id="closeResvAttchBtn" class="btn btn-secondary" data-bs-dismiss="modal"
                    data-bs-target="#optionWindow" data-bs-toggle="modal">Close</button>
                <button type="button" id="submitResvAttchBtn" class="btn btn-primary">Upload & Save</button>
            </div>
        </div>
    </div>
</div>
<!-- Reservation Attachments  -->

<script>
var mainUrl = '<?php echo base_url(); ?>';

$(document).on('click', '.resv-attachments', function() {

    $('#optionWindow').modal('hide');
    var resvId = $(this).attr('data_sysid');
    var resvNo = $(this).attr('data_resv_no');

    $('#resvAttachmentsModal').modal('show');
    $('#resvAttachmentsModallabel').html('Attachments of Reservation ' + resvNo);

});
</script>