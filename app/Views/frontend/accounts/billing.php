<?= $this->extend("Layout/AppView") ?>
<?= $this->section("contentRender") ?>
<?= $this->include('Layout/SuccessReport') ?>
<?= $this->include('Layout/ErrorReport') ?>
<?= $this->include('includes/confirm_password_popup') ?>
<?= $this->include('includes/post_transaction_popup') ?>
<?= $this->include('includes/payment_popup') ?>
<?= $this->include('includes/move_transaction_popup') ?>

<style>
    .text-right {
        text-align: right !important;
    }

    .windows .nav-tabs .active {
        color: blue;
    }
</style>

<?php
if ($confirm_password && isset($reservation) && in_array($reservation['RESV_STATUS'], ['Checked-In', 'Check-Out-Requested'])) {
?>

    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Accounts /</span> Billing</h4>

            <div class="card">
                <!-- <h5 class="card-header">Responsive Datatable</h5> -->
                <div class="container-fluid table-responsive" style="padding: 16px 16px 6px 16px">
                    <div class="row">
                        <div class="col-md-2"><b>Balance</b></div>
                        <div class="col-md-2 total-balance">0.00</div>

                        <div class="col-md-2"><b>Arrival</b></div>
                        <div class="col-md-2"><?= $reservation['RESV_ARRIVAL_DT'] ?></div>

                        <div class="col-md-2"><b>Departure</b></div>
                        <div class="col-md-2"><?= $reservation['RESV_DEPARTURE'] ?></div>
                    </div>

                    <hr />

                    <div class="row">
                        <div class="col-md-2"><b>Company</b></div>
                        <div class="col-md-2"></div>

                        <div class="col-md-2"><b>Group</b></div>
                        <div class="col-md-2"></div>

                        <div class="col-md-2"><b>Prs</b></div>
                        <div class="col-md-2"></div>
                    </div>

                    <hr />

                    <div class="row">
                        <div class="col-md-2"><b>Rate Code</b></div>
                        <div class="col-md-2"><?= $reservation['RESV_RATE_CODE'] ?></div>

                        <div class="col-md-2"><b>Rate</b></div>
                        <div class="col-md-2"><?= $reservation['RESV_RATE'] ?></div>

                        <div class="col-md-2"><b>Room Type</b></div>
                        <div class="col-md-2"><?= $reservation['RESV_RM_TYPE'] ?></div>
                    </div>


                    <hr />

                    <div class="row">
                        <div class="col-md-2"><b>Status</b></div>
                        <div class="col-md-2"><?= $reservation['RESV_STATUS'] ?></div>
                    </div>
                </div>
            </div>

            <div class="card mt-2 windows">

                <div class="card-header pb-1">
                    <ul class="nav nav-tabs">
                        <li class="nav-item" data-window_tab='1'>
                            <a class="nav-link active" href="javascript:void(0)">1</a>
                        </li>
                    </ul>
                </div>

                <div class="card-header pb-1 d-flex justify-content-between">
                    <span>
                        <span class="window-number">(1)</span>
                        <?= $reservation['CUST_NAME'] ?>
                    </span>
                    <span><?= $reservation['RESV_PAYMENT_TYPE'] ?></span>
                    <span class="window-total-balance">0.00</span>
                </div>

                <div class="container-fluid table-responsive" style="padding: 16px 16px 6px 16px">

                    <table class="billing-table table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Code</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Supplement</th>
                                <th>Reference</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                        </tbody>
                    </table>

                    <div class="text-right function-btns">
                        <button class="btn btn-primary post-btn">Post</button>
                        <button class="btn btn-primary payment-btn">Payment</button>
                        <button class="btn btn-danger delete-btn">Delete Window</button>
                    </div>
                </div>
            </div>

        </div>
    </div>

<?php
}
?>

<?= $this->endSection() ?>


<?= $this->section("script") ?>

<script>
    $(document).ready(function() {
        <?php if (empty($reservation)) : ?>
            alert('Invalid reservation.');

        <?php elseif (!in_array($reservation['RESV_STATUS'], ['Checked-In', 'Check-Out-Requested'])) : ?>
            alert("Reservation status should be 'Checked-In' or 'Check-Out-Requested'.");

        <?php elseif (!$confirm_password) : ?>
            showConfirmPasswordModal();
        <?php endif ?>
    });

    <?php
    if ($confirm_password && isset($reservation) && in_array($reservation['RESV_STATUS'], ['Checked-In', 'Check-Out-Requested'])) {
    ?>

        var active_window = 1;

        $(document).ready(function() {
            $('.billing-table').DataTable({
                paging: false,
                searching: false,
                ordering: false,
                info: false
            });

            loadWindowsData(); // call on load

            $(document).on('click', '.windows .nav-tabs .nav-item', function() {
                $('.windows .nav-tabs .nav-link').removeClass('active');
                $(this).children().addClass('active');

                active_window = $(this).data('window_tab');
                $('.window-number').html(`(${active_window})`);
                loadWindowsData();
            });

            $(document).on('click', '.transaction-btns .move-transaction-btn', function() {
                let transaction_id = $(this).data('transaction_id');
                showMoveTransactionModal(transaction_id);
            });

            $(document).on('click', '.transaction-btns .edit-transaction-btn', function() {
                let transaction = $(this).data('transaction');

                if (transaction.RTR_TRANSACTION_TYPE == 'Debited')
                    showPostTransactionModal(transaction);
                else
                    showPaymentModal(transaction);
            });

            $(document).on('click', '.transaction-btns .delete-transaction-btn', function() {
                let transaction_id = $(this).data('transaction_id');
                deleteTransaction(transaction_id);
            });

            $(document).on('click', '.function-btns .post-btn', function() {
                showPostTransactionModal();
            });

            $(document).on('click', '.function-btns .payment-btn', function() {
                showPaymentModal();
            });

            $(document).on('click', '.function-btns .delete-btn', function() {
                deleteWindow();
            });
        });

        function changeActiveWindow(window_number) {
            active_window = window_number;

            $('.windows .nav-tabs .nav-link').removeClass('active');

            if ($(`.windows .nav-tabs .nav-item:nth-child(${window_number})`).length)
                $(`.windows .nav-tabs .nav-item:nth-child(${window_number})`).click();
            else {
                $(`.windows .nav-tabs .nav-item:nth-child(${window_number-1})`).click();
                active_window = window_number - 1;
            }
        }

        function displayWindowLoader() {
            let html = `
                <tr>
                    <td colspan="8" class="text-center">
                        <div class="spinner-border text-primary" role="status">
                          <span class="visually-hidden">Loading...</span>
                        </div>
                    </td>
                </tr>
            `;
            $('.windows table tbody').html(html);
        }

        function loadWindowsData(window_number = null) {
            displayWindowLoader();

            let fd = new FormData();
            fd.append('reservation_id', <?= $reservation_id ?>)

            $.ajax({
                url: '<?= base_url('billing/load-windows-data') ?>',
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

                    if (response['SUCCESS'] != 200)
                        showModalAlert('error', mcontent);
                    else {
                        let data = response['RESPONSE']['OUTPUT'];

                        let max_window_number = 1;
                        let html = '';
                        let total_balance = 0,
                            window_total_balance = 0;


                        $.each(data, function(index, item) {
                            total_balance += parseFloat(item.RTR_AMOUNT);

                            if (item.RTR_WINDOW > max_window_number)
                                max_window_number = item.RTR_WINDOW;

                            if (item.RTR_WINDOW == active_window) {
                                window_total_balance += parseFloat(item.RTR_AMOUNT);

                                html += `
                                <tr>
                                    <td>${item.RTR_ID}</td>
                                    <td>${item.RTR_CREATED_AT}</td>
                                    <td>${item.RTR_TRANSACTION_TYPE == 'Debited' ? item.TR_CD_CODE : item.PYM_TXN_CODE}</td>
                                    <td>${item.RTR_TRANSACTION_TYPE == 'Debited' ? item.TR_CD_DESC : item.PYM_DESC}</td>
                                    <td>${item.RTR_TRANSACTION_TYPE == 'Debited' ? item.RTR_AMOUNT * item.RTR_QUANTITY : item.RTR_AMOUNT}</td>
                                    <td>${item.RTR_SUPPLEMENT || ''}</td>
                                    <td>${item.RTR_REFERENCE || ''}</td>
                                    <td>
                                        <div class="d-inline-block">
                                            <a href="javascript:;" class="btn btn-sm btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </a>

                                            <ul class="dropdown-menu transaction-btns" role="menu">
                                                <li>
                                                    <a href="javascript:void(0);" class="dropdown-item move-transaction-btn" data-transaction_id="${item.RTR_ID}">
                                                        Move
                                                    </a>
                                                </li>
                                                
                                                ${!item.RTR_MODEL
                                                    ?
                                                `<div class="dropdown-divider"></div>
                                                
                                                <li>
                                                    <a href="javascript:void(0);" class="dropdown-item edit-transaction-btn" 
                                                        data-transaction='${JSON.stringify(item)}'>
                                                        Edit
                                                    </a>
                                                </li>

                                                <div class="dropdown-divider"></div>

                                                <li>
                                                    <a href="javascript:void(0);" class="dropdown-item delete-transaction-btn" data-transaction_id="${item.RTR_ID}">
                                                        Delete
                                                    </a>
                                                </li>` 
                                                : 
                                                ``}
                                                
                                            </ul>
                                        </div>
                                    </td>
                                </tr>`;
                            }
                        });

                        if ($('.windows .nav-tabs').children().length != max_window_number) {
                            window_tabs_html = '';
                            for (let i = 1; i <= max_window_number; i++) {
                                window_tabs_html += `
                                <li class="nav-item" data-window_tab='${i}'>
                                    <a class="nav-link ${i == 1 ? 'active' : ''}" href="javascript:void(0)">${i}</a>
                                </li>`;
                            }
                            $('.windows .nav-tabs').html(window_tabs_html);
                        }

                        $('.windows .window-total-balance').html(window_total_balance.toFixed(2));
                        $('.total-balance').html(total_balance.toFixed(2));

                        if (!html)
                            html = '<tr><td colspan="8" class="text-center">No data available!</td></tr>';

                        $('.windows table tbody').html(html);

                        if (window_number)
                            changeActiveWindow(window_number);
                    }
                }
            });
        }

        function deleteWindow() {
            let fd = new FormData();
            fd.append('reservation_id', <?= $reservation_id ?>);
            fd.append('window_number', active_window);

            if (active_window)
                $.ajax({
                    url: '<?= base_url('billing/delete-window') ?>',
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

                        if (response['SUCCESS'] != 200)
                            showModalAlert('error', mcontent);
                        else {
                            showModalAlert('success', mcontent);

                            loadWindowsData(active_window);
                        }
                    }
                });
        }

        function deleteTransaction(transaction_id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                customClass: {
                    confirmButton: 'btn btn-primary me-3',
                    cancelButton: 'btn btn-label-secondary'
                },
                buttonsStyling: false
            }).then(function(result) {
                if (result.value) {
                    let fd = new FormData();
                    fd.append('RTR_ID', transaction_id);

                    if (transaction_id)
                        $.ajax({
                            url: '<?= base_url('billing/delete-transaction') ?>',
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

                                if (response['SUCCESS'] != 200)
                                    showModalAlert('error', mcontent);
                                else {
                                    showModalAlert('success', mcontent);

                                    loadWindowsData(active_window);
                                }
                            }
                        });
                }
            });
        }
    <?php
    }
    ?>
</script>

<?= $this->endSection() ?>