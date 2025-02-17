<div class="modal fade post-transaction-modal" style="z-index: 1100" data-bs-backdrop="static" data-keyboard="false" aria-lableledby="popModalWindowlable">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="popModalWindowlabel">Transaction Posting</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
            </div>

            <form>
                <input type="hidden" name="RTR_ID" />

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

                        <div class="col-md-2">
                            <label class="form-label"><b>Amount *</b></label>
                            <input type="number" name="RTR_AMOUNT" class="form-control" placeholder="Amount">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label"><b>Quantity *</b></label>
                            <input type="number" name="RTR_QUANTITY" class="form-control" placeholder="Quantity">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label"><b>Total Amount *</b></label>
                            <input type="number" name="RTR_TOTAL_AMOUNT" class="form-control" placeholder="Total Amount" disabled>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label"><b>Check No</b></label>
                            <input type="text" name="RTR_CHECK_NO" class="form-control" placeholder="Check No">
                        </div>

                        <div class="col-md-6">
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

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label"><b>Supplement</b></label>
                            <textarea class="form-control" name="RTR_SUPPLEMENT"></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label"><b>Referenece</b></label>
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

        $(document).on('change', `${post_transaction_form} [name='RTR_AMOUNT'], ${post_transaction_form} [name='RTR_QUANTITY']`, function() {
            let amount = $(`${post_transaction_form} [name='RTR_AMOUNT']`).val();
            let quantity = $(`${post_transaction_form} [name='RTR_QUANTITY']`).val();

            $(`${post_transaction_form} [name='RTR_TOTAL_AMOUNT']`).val(amount * (quantity || 1));
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

                        if (typeof loadWindowsData == 'function') {
                            let window_number = parseInt($(`${post_transaction_form} [name='RTR_WINDOW']`).val());
                            loadWindowsData(window_number);
                        }
                    }
                }
            });
        });
    });

    function resetPostTransactionForm() {
        $(`${post_transaction_form} input`).val('');
        $(`${post_transaction_form} textarea`).val('');
        $(`${post_transaction_form} select`).val('').trigger('change');
        $(`${post_transaction_form} [name='RTR_QUANTITY']`).val(1);

        $(`${post_transaction_form} select[name='RTR_WINDOW']`).val('1').trigger('change');

        $(`${post_transaction_form} [name='RTR_CHECK_NO']`).attr('readonly', false);

        $(`${post_transaction_form} input[name='RTR_TRANSACTION_CODE_ID']`).remove();
        $(`${post_transaction_form} input[name='RTR_WINDOW']`).remove();

        $(`${post_transaction_form} select[name='RTR_TRANSACTION_CODE_ID']`).attr('disabled', false);
        $(`${post_transaction_form} select[name='RTR_WINDOW']`).attr('disabled', false);
    }

    function showPostTransactionModal(transaction = null) {
        resetPostTransactionForm();

        if (transaction)
            editTransaction(transaction);
        $('.post-transaction-modal').modal('show');
    }

    function hidePostTransactionModal() {
        $('.post-transaction-modal').modal('hide');
    }

    function editTransaction(transaction) {
        $(transaction).each(function(inx, data) {
            $.each(data, function(field, val) {

                if ($(`${post_transaction_form} input[name='${field}'][type!='file']`).length)
                    $(`${post_transaction_form} input[name='${field}']`).val(val);

                else if ($(`${post_transaction_form} textarea[name='${field}']`).length)
                    $(`${post_transaction_form} textarea[name='${field}']`).val(val);

                else if ($(`${post_transaction_form} select[name='${field}']`).length)
                    $(`${post_transaction_form} select[name='${field}']`).val(val).trigger('change');

                if (['RTR_CHECK_NO'].includes(field))
                    $(`${post_transaction_form} input[name='${field}']`).attr('readonly', true);

                else if (['RTR_TRANSACTION_CODE_ID', 'RTR_WINDOW'].includes(field)) {
                    $(`${post_transaction_form} select[name='${field}']`).attr('disabled', true);

                    $(`${post_transaction_form}`).prepend(
                        `<input type='hidden' name='${field}' value='${val}'/>`
                    );
                }
            });
        });

        $(`${post_transaction_form} [name='RTR_AMOUNT']`).trigger('change');
    }
</script>