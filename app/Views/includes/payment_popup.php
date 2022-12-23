<div class="modal fade payment-modal" style="z-index: 1100" data-bs-backdrop="static" data-keyboard="false" aria-lableledby="popModalWindowlable">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="popModalWindowlabel">Payment</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
            </div>

            <form>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label"><b>Payment Method *</b></label>
                            <select class="form-control select2" name="RTR_PAYMENT_METHOD_ID">
                                <option value="">Select</option>
                                <?php foreach ($payment_methods as $payment_method) : ?>
                                    <option value="<?= $payment_method['PYM_ID'] ?>" data-code="<?= $payment_method['PYM_TXN_CODE'] ?>">
                                        <?= $payment_method['PYM_DESC'] ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label"><b>Amount *</b></label>
                            <input type="number" name="RTR_AMOUNT" class="form-control" placeholder="Amount">
                        </div>

                        <div class="col-md-6 card-details d-none">
                            <label class="form-label"><b>Card Number *</b></label>
                            <input type="number" name="RTR_CARD_NUMBER" class="form-control" placeholder="card number">
                        </div>

                        <div class="col-md-6 card-details d-none">
                            <label class="form-label" for="paymentExpiryDate"><b>Card Expiry Date *</b></label>
                            <input type="text" name="RTR_CARD_EXPIRY" id="paymentExpiryDate" class="form-control expiry-date-mask" placeholder="MM/YY" />
                        </div>

                        <div class="col-md-12">
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
    var payment_form = '.payment-modal form';

    $(document).ready(function() {
        $(payment_form).submit(function(e) {
            e.preventDefault();
        });

        $(document).on('click', `${payment_form} .submit-btn`, function() {
            var fd = new FormData($(`${payment_form}`)[0]);
            fd.append('RTR_RESERVATION_ID', <?= $reservation_id ?>);
            fd.append('RTR_TRANSACTION_TYPE', 'Credited');
            fd.append('RTR_WINDOW', active_window);

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
                        hidePaymentModal();

                        if (typeof loadWindowsData == 'function')
                            loadWindowsData();
                    }
                }
            });
        });

        $(document).on('change', `${payment_form} [name='RTR_PAYMENT_METHOD_ID']`, function() {
            let code = $(this).find(":selected").data('code');

            if (code == '9000' || code == '9004')
                $('.card-details').addClass('d-none');
            else
                $('.card-details').removeClass('d-none');
        });

        let expiry_date_mask = document.querySelector(`${payment_form} [name='RTR_CARD_EXPIRY']`);
        new Cleave(expiry_date_mask, {
            date: true,
            delimiter: '/',
            datePattern: ['m', 'y']
        });
    });

    function resetPaymentModalForm() {
        $(`${payment_form} input`).val('');
        $(`${payment_form} textarea`).val('');
        $(`${payment_form} select`).val('').trigger('change');
        
        $('.card-details').addClass('d-none');
    }

    function showPaymentModal() {
        resetPaymentModalForm();
        $('.payment-modal').modal('show');
    }

    function hidePaymentModal() {
        $('.payment-modal').modal('hide');
    }
</script>