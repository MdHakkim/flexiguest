<?= $this->extend("Layout/AppView") ?>
<?= $this->section("contentRender") ?>
<?= $this->include('Layout/SuccessReport') ?>
<?= $this->include('Layout/ErrorReport') ?>
<?= $this->include('includes/confirm_password_popup') ?>
<?= $this->include('includes/payment_popup') ?>

<?php
if ($confirm_password && isset($reservation) && in_array($reservation['RESV_STATUS'], ['Due Pre Check-In', 'Pre Checked-In'])) {
?>

    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Accounts /</span> Deposits</h4>

            <!-- DataTable with Buttons -->
            <div class="card">
                <!-- <h5 class="card-header">Responsive Datatable</h5> -->
                <div class="container-fluid table-responsive" style="padding: 16px 16px 6px 16px">

                    <table id="dataTable_view" class="deposit-table table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Code</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Supplement</th>
                                <th>Refernce</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody></tbody>
                    </table>
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

        <?php elseif (!in_array($reservation['RESV_STATUS'], ['Due Pre Check-In', 'Pre Checked-In'])) : ?>
            alert("Reservation status should be 'Due Pre Check-In' or 'Pre Checked-In'.");

        <?php elseif (!$confirm_password) : ?>
            showConfirmPasswordModal();
        <?php endif ?>
    });

    <?php
    if ($confirm_password && isset($reservation) && in_array($reservation['RESV_STATUS'], ['Due Pre Check-In', 'Pre Checked-In'])) {
    ?>

        var active_window = 1;

        $(document).ready(function() {
            $('.deposit-table').DataTable({
                paging: false,
                searching: false,
                ordering: false,
                // info: false
            });

            $("#dataTable_view_wrapper .row:first").before(
                `<div class="row flxi_pad_view">
                    <div class="col-md-3 ps-0">
                        <button type="button" class="btn btn-primary" onClick="addDeposit()">
                            <i class="fa-solid fa-plus fa-lg"></i> 
                            Add Deposit
                        </button>
                    </div>
                </div>`
            );
        });

        function addDeposit() {
            showPaymentModal();
        }
    <?php
    }
    ?>
</script>

<?= $this->endSection() ?>