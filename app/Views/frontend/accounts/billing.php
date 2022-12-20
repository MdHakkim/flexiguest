<?= $this->extend("Layout/AppView") ?>
<?= $this->section("contentRender") ?>
<?= $this->include('Layout/SuccessReport') ?>
<?= $this->include('Layout/ErrorReport') ?>
<?= $this->include('includes/confirm_password_popup') ?>
<?= $this->include('includes/post_transaction_popup') ?>

<style>
    .text-right {
        text-align: right !important;
    }

    .windows .nav-tabs .active {
        color: blue;
    }
</style>

<?php
if ($confirm_password) {
?>

    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Accounts /</span> Billing</h4>

            <div class="card">
                <!-- <h5 class="card-header">Responsive Datatable</h5> -->
                <div class="container-fluid table-responsive" style="padding: 16px 16px 6px 16px">
                    <div class="row">
                        <div class="col-md-2"><b>Balance</b></div>
                        <div class="col-md-2"></div>

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
                    <span>CA</span>
                    <span>0.00</span>
                </div>

                <div class="container-fluid table-responsive" style="padding: 16px 16px 6px 16px">

                    <table class="billing-table table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Code</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Supplement</th>
                                <th>Refernce</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                        </tbody>
                    </table>

                    <div class="text-right function-btns">
                        <button class="btn btn-primary post-btn">Post</button>
                        <button class="btn btn-secondary">Back</button>
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
    var active_window = 1;

    $(document).ready(function() {
        <?php
        if (!$confirm_password) {
        ?>
            showConfirmPasswordModal();
        <?php
        }
        ?>


        $('.billing-table').DataTable({
            paging: false,
            searching: false,
            ordering: false,
            info: false
        });

        $(document).on('click', '.windows .nav-tabs .nav-item', function() {
            $('.windows .nav-tabs .nav-link').removeClass('active');
            $(this).children().addClass('active');

            active_window = $(this).data('window_tab');
            $('.window-number').html(`(${active_window})`);
            loadWindowsData();
        });

        $(document).on('click', '.function-btns .post-btn', function() {
            showPostTransactionModal();
        });

        loadWindowsData(); // call on load

    });

    function displayWindowLoader() {
        let html = `
                <tr>
                    <td colspan="8" class="text-center">
                        <i class="fas fa-circle-notch fa-spin"></i>
                    </td>
                </tr>
            `;
        $('.windows table tbody').html(html);
    }

    function loadWindowsData() {
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

                    $.each(data, function(index, item) {
                        if (item.RTR_WINDOW > max_window_number)
                            max_window_number = item.RTR_WINDOW;

                        if (item.RTR_WINDOW == active_window) {
                            html += `
                                <tr>
                                    <td>${index+1}</td>
                                    <td>${item.RTR_CREATED_AT}</td>
                                    <td>${item.TR_CD_CODE}</td>
                                    <td>${item.TR_CD_DESC}</td>
                                    <td>${item.RTR_AMOUNT}</td>
                                    <td>${item.RTR_SUPPLEMENT}</td>
                                    <td>${item.RTR_REFERENCE}</td>
                                    <td>
                                        <div class="d-inline-block">
                                            <a href="javascript:;" class="btn btn-sm btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </a>

                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a href="javascript:;" class="dropdown-item text-secondary">
                                                        Move Transaction
                                                    </a>
                                                </li>
                                                <div class="dropdown-divider"></div>
                                                <li>
                                                    <a href="javascript:;" class="dropdown-item text-secondary">
                                                        Delete
                                                    </a>
                                                </li>
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

                    if (!html)
                        html = '<tr><td colspan="8" class="text-center">No data available!</td></tr>';

                    $('.windows table tbody').html(html);
                }
            }
        });
    }
</script>

<?= $this->endSection() ?>