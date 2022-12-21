<div class="modal fade move-transaction-modal" style="z-index: 1100" data-bs-backdrop="static" data-keyboard="false" aria-lableledby="popModalWindowlable">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="popModalWindowlabel">Move Transaction</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
            </div>

            <form>
                <input type="hidden" name="RTR_ID" />

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label"><b>Window *</b></label>
                            <select class="form-control select2" name="RTR_WINDOW">
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                                <option>6</option>
                                <option>7</option>
                                <option>8</option>
                            </select>
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
    var move_transaction_form = '.move-transaction-modal form';

    $(document).ready(function() {
        $(move_transaction_form).submit(function(e) {
            e.preventDefault();
        });

        $(document).on('click', `${move_transaction_form} .submit-btn`, function() {
            var fd = new FormData($(`${move_transaction_form}`)[0]);
            fd.append('RTR_RESERVATION_ID', <?= $reservation_id ?>);

            $.ajax({
                url: '<?= base_url('billing/move-transaction') ?>',
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
                        hideMoveTransactionModal();

                        if (typeof changeActiveWindow == 'function') {
                            let window_number = parseInt($(`${move_transaction_form} [name='RTR_WINDOW']`).val());
                            changeActiveWindow(window_number);
                        }
                    }
                }
            });
        });

    });

    function showMoveTransactionModal(transaction_id) {
        $(`${move_transaction_form} [name='RTR_ID']`).val(transaction_id);
        $('.move-transaction-modal').modal('show');
    }

    function hideMoveTransactionModal() {
        $('.move-transaction-modal').modal('hide');
    }
</script>