<div class="modal fade post-transaction-modal" style="z-index: 1100" data-bs-backdrop="static" data-keyboard="false" aria-lableledby="popModalWindowlable">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="popModalWindowlabel">Transaction Posting</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
            </div>

            <form>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label"><b>Transaction Code *</b></label>
                            <select class="form-control select2" name="RTR_TRANSACTION_CODE_ID">
                                <option value="">Select</option>
                                <?php foreach ($transaction_codes as $transaction_code) : ?>
                                    <option value="<?= $transaction_code['TR_CD_ID'] ?>">
                                        <?= $transaction_code['TR_CD_CODE'] ?> - <?= $transaction_code['TR_CD_DESC'] ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label"><b>Amount *</b></label>
                            <input type="number" name="RTR_AMOUNT" class="form-control" placeholder="Amount">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label"><b>Quantity *</b></label>
                            <input type="number" name="RTR_QUANTITY" class="form-control" placeholder="Quantity">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Check No</label>
                            <input type="text" name="RTR_CHECK_NO" class="form-control" placeholder="Check No">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label"><b>Windows *</b></label>
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

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Supplement</label>
                            <textarea class="form-control" name="RTR_SUPPLEMENT"></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Referenece</label>
                            <textarea class="form-control" name="RTR_REFERENCE"></textarea>
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
    var post_transaction_form = '.post-transaction-modal form';

    $(document).ready(function() {
        $(post_transaction_form).submit(function(e) {
            e.preventDefault();
        });

        $(document).on('click', `${post_transaction_form} .submit-btn`, function() {
            var fd = new FormData($(`${post_transaction_form}`)[0]);
            fd.append('RTR_RESERVATION_ID', <?= $reservation_id ?>);
            fd.append('RTR_TRANSACTION_TYPE', 'Debited');

            $.ajax({
                url: '<?= base_url('billing/post-or-payment') ?>',
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
                        hidePostTransactionModal();

                        if(typeof loadWindowsData == 'function')
                            loadWindowsData();
                    }
                }
            });
        });
    });

    function showPostTransactionModal() {
        $('.post-transaction-modal').modal('show');
    }

    function hidePostTransactionModal() {
        $('.post-transaction-modal').modal('hide');
    }
</script>