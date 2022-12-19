<?= $this->extend("Layout/AppView") ?>
<?= $this->section("contentRender") ?>
<?= $this->include('Layout/SuccessReport') ?>
<?= $this->include('Layout/ErrorReport') ?>
<?= $this->include('includes/confirm_password_popup') ?>

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
                        <div class="col-md-2">150</div>

                        <div class="col-md-2"><b>Arrival</b></div>
                        <div class="col-md-2">2022-12-19</div>

                        <div class="col-md-2"><b>Departure</b></div>
                        <div class="col-md-2">2022-12-19</div>
                    </div>

                    <hr />

                    <div class="row">
                        <div class="col-md-2"><b>Company</b></div>
                        <div class="col-md-2">company</div>

                        <div class="col-md-2"><b>Group</b></div>
                        <div class="col-md-2">Group</div>

                        <div class="col-md-2"><b>Group</b></div>
                        <div class="col-md-2">Group</div>
                    </div>

                    <hr />

                    <div class="row">
                        <div class="col-md-2"><b>Rate Code</b></div>
                        <div class="col-md-2">COMP</div>

                        <div class="col-md-2"><b>Rate</b></div>
                        <div class="col-md-2">10</div>

                        <div class="col-md-2"><b>Room Type</b></div>
                        <div class="col-md-2">R3C1B</div>
                    </div>


                    <hr />

                    <div class="row">
                        <div class="col-md-2"><b>Status</b></div>
                        <div class="col-md-2">Checked In</div>
                    </div>
                </div>
            </div>

            <div class="card mt-2">
                <div class="card-header d-flex justify-content-between">
                    <span>
                        <span class="window-number">(1)</span>
                        Aamir Sohail
                    </span>
                    <span>CA</span>
                    <span>0.00</span>
                </div>

                <div class="container-fluid table-responsive" style="padding: 16px 16px 6px 16px">

                    <table class="billing-table dt-responsive table table-striped display nowrap" style="width:100%">
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
                            <tr>
                                <td>Data</td>
                                <td>Data</td>
                                <td>Data</td>
                                <td>Data</td>
                                <td>Data</td>
                                <td>Data</td>
                                <td>Data</td>
                                <td>
                                    <div class="d-inline-block">
                                        <a href="javascript:;" title="Edit or Delete" class="btn btn-sm btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </a>

                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a href="javascript:;" class="dropdown-item text-secondary">
                                                    <i class="fa-solid fa-pen-to-square"></i> Move Transaction
                                                </a>
                                            </li>

                                            <div class="dropdown-divider"></div>

                                            <li>
                                                <a href="javascript:;" class="dropdown-item text-secondary">
                                                    <i class="fa-solid fa-ban"></i> 
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
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
        <?php
        if (!$confirm_password) {
        ?>
            showConfirmPasswordModal();
        <?php
        }
        ?>

        $(document).ready(function() {
            $('.billing-table').DataTable({
                paging: false,
                searching: false,
                ordering: false,
                info: false
            });
        });

    });
</script>

<?= $this->endSection() ?>