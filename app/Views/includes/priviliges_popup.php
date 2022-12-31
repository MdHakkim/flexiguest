<div class="modal fade priviliges-modal" style="z-index: 1100" data-bs-backdrop="static" data-keyboard="false" aria-lableledby="popModalWindowlable">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="popModalWindowlabel">Priviliges</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
            </div>

            <form>
                <div class="modal-body">
                    <div class="row text-center">
                        <div class="col-md-6">
                            <label class="switch">
                                <input type="checkbox" name="RESV_NO_POST" class="switch-input" />
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="bx bx-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="bx bx-x"></i>
                                    </span>
                                </span>
                                <span class="switch-label">Post</span>
                            </label>
                        </div>

                        <div class="col-md-6">
                            <label class="switch">
                                <input type="checkbox" name="RESV_POST_STAY_CHARGES" class="switch-input" />
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="bx bx-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="bx bx-x"></i>
                                    </span>
                                </span>
                                <span class="switch-label">Post Stay Charges</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary submit-btn">
                        Submit
                    </button>

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
    var priviliges_form = '.priviliges-modal form';

    $(document).ready(function() {
        $(priviliges_form).submit(function(e) {
            e.preventDefault();
        });

        $(document).on('click', `${priviliges_form} .submit-btn`, function() {
            var fd = new FormData($(`${priviliges_form}`)[0]);
            fd.append('RESV_ID', <?= $reservation_id ?>);

            $.ajax({
                url: '<?= base_url('reservation/move-transaction') ?>',
                type: "post",
                data: fd,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    var mcontent = '';
                    $.each(response['RESPONSE']['REPORT_RES'], function(ind, data) {
                        mcontent += '<li>' + data + '</li>';
                    });

                    if (response['SUCCESS'] != 200) {
                        showModalAlert('error', mcontent);
                    } else {
                        showModalAlert('success', mcontent);
                        hidePriviligesModal();
                    }
                }
            });
        });

    });

    // function resetMoveTransactionForm() {
    //     $(`${move_transaction_form} input`).val('');
    //     $(`${move_transaction_form} textarea`).val('');
    //     $(`${move_transaction_form} select`).val('').trigger('change');

    //     $(`${move_transaction_form} select[name='RTR_WINDOW']`).val('1').trigger('change');
    // }

    function showPriviligesModal() {
        $('.priviliges-modal').modal('show');
    }

    function hidePriviligesModal() {
        $('.priviliges-modal').modal('hide');
    }
</script>