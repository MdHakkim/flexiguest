<?=$this->extend("Layout/AppView")?>
<?=$this->section("contentRender")?>
<?= $this->include('Layout/ErrorReport') ?>
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Reservations /</span> Company</h4>

        <!-- DataTable with Buttons -->
        <div class="card">
            <!-- <h5 class="card-header">Responsive Datatable</h5> -->
            <div class="container-fluid p-3">
                <table id="dataTable_view" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Account</th>
                            <th>Country</th>
                            <th>Email</th>
                            <th>Corp ID</th>
                            <th>Mobile</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                </table>
            </div>
        </div>

        <!--/ Multilingual -->
    </div>
    <!-- / Content -->

    <div class="content-backdrop fade"></div>
</div>
<?=$this->include("Reservation/CompanyAgentModal")?>
<!-- Content wrapper -->
<script>
var compAgntMode = '';
var linkMode = '';
$(document).ready(function() {

    compAgntMode = 'COMPANY';
    $('#dataTable_view').DataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': '<?php echo base_url('/companyView')?>'
        },
        'columns': [{
                data: 'COM_ACCOUNT'
            },
            {
                data: 'COM_COUNTRY'
            },
            {
                data: 'COM_CONTACT_EMAIL'
            },
            {
                data: 'COM_CORP_ID'
            },
            {
                data: 'COM_CONTACT_NO'
            },
            // { data: 'CUST_CLIENT_ID' },
            {
                data: null,
                render: function(data, type, row, meta) {

                    return (
                        '<div class="d-inline-block">' +
                        '<a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></a>' +
                        '<ul class="dropdown-menu dropdown-menu-end">' +
                        '<li><a href="javascript:;" data_sysid="' + data['COM_ID'] +
                        '" class="dropdown-item editWindow">Edit</a></li>' +
                        // '<li><a href="javascript:;" class="dropdown-item">Archive</a></li>' +
                        '<div class="dropdown-divider"></div>' +
                        '<li><a href="javascript:;" data_sysid="' + data['COM_ID'] +
                        '" class="dropdown-item text-danger delete-record">Delete</a></li>' +
                        '</ul>' +
                        '</div>'
                    );
                }
            },
        ],
        autowidth: true

    });
    $("#dataTable_view_wrapper .row:first").before(
        '<div class="row flxi_pad_view"><div class="col-md-3 ps-0"><button type="button" class="btn btn-primary" onClick="addForm()"><i class="fa-solid fa-plus fa-lg"></i> Add</button></div></div>'
    );
    $('#CUST_DOB').datepicker({
        format: 'd-M-yyyy',
        autoclose: true
    });

    <?php
    if(!empty($editId)) {  ?>
        editComp(<?php echo $editId; ?>, compAgntMode);
        <?php
    }
    ?>
});

function addForm() {
    $(':input', '#compnayAgentForm').val('').prop('checked', false).prop('selected', false);
    $('#submitBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
    $('#COM_COUNTRY,#COM_STATE,#COM_CITY').html('<option value="">Select</option>').selectpicker('refresh');
    $('#compnayAgentWindow').modal('show');
    $('.companyData').show();
    $('.agentData').hide();
    $('#COM_TYPE').val(compAgntMode);
    runCountryListExdClass();
}

$(document).on('click', '.delete-record', function() {
    var sysid = $(this).attr('data_sysid');
    bootbox.confirm({
        message: "Are you sure you want to delete this record?",
        buttons: {
            confirm: {
                label: 'Yes',
                className: 'btn-success'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function(result) {
            if (result) {
                $.ajax({
                    url: '<?php echo base_url('/deleteCompany')?>',
                    type: "post",
                    data: {
                        sysid: sysid
                    },
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    dataType: 'json',
                    success: function(respn) {

                        $('#dataTable_view').dataTable().fnDraw();
                    }
                });
            }
        }
    });
});
</script>

<?=$this->endSection()?>