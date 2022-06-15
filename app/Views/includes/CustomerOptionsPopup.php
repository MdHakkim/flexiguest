<!-- Option window -->
<div class="modal fade" id="custOptionsWindow" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="custOptionsWindowLabel">Profile Options</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
            </div>
            <div class="modal-body">
                <div class="flxy_opt_btn text-center">
                    <button type="button" class="btn btn-primary show-activity-log" data-bs-toggle="modal"
                        data-bs-target="#changesWindow">Changes</button>
                    <a href="#" class="btn btn-primary data-port" target="_blank" data_sysid="">Data Porting</a>
                    <button type="button" class="btn btn-primary delete-record" data_sysid="">Delete</button>
                    <button type="button" class="btn btn-primary">Memberships</button>
                    <button type="button" class="btn btn-primary">Neg. Rates</button>
                    <button type="button" class="btn btn-primary">Preferences</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- option window end -->

<script>

var custOptId = '';

$(document).on('click', '.custOptions', function() {
    var custOptId = $(this).attr('data_sysid');

    $('.modal').modal('hide');
    $('#custOptionsWindow').modal('show');

    $('#custOptionsWindow').find('.data-port,.delete-record').attr('data_sysid', custOptId);
    $('#custOptionsWindow').find('.data-port').attr('href', '<?php echo base_url('/printProfile')?>/' +
        custOptId);
});

</script>