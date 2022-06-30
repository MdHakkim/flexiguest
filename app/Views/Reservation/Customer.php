<?=$this->extend("Layout/AppView")?>
<?=$this->section("contentRender")?>
<?=$this->include('Layout/ErrorReport')?>
<?=$this->include('Layout/SuccessReport')?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Reservations /</span> Customers</h4>

        <!-- DataTable with Buttons -->
        <div class="card">
            <!-- <h5 class="card-header">Responsive Datatable</h5> -->
            <div class="container-fluid p-3">
                <table id="dataTable_view" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Passport No</th>
                            <th>Country</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Client ID</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                </table>
            </div>
        </div>

        <!--/ Multilingual -->
    </div>
    <!-- / Content -->

    <!-- Modal Window -->

    <div class="modal fade" id="reservationChild" tabindex="-1" aria-labelledby="reservationChildLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reservationChildLabel">Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="customerForm" class="needs-validation" novalidate>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <input type="hidden" name="CUST_ID" id="CUST_ID" class="form-control" />
                                <label class="form-label">First Name</label>
                                <input type="text" name="CUST_FIRST_NAME" id="CUST_FIRST_NAME" class="form-control"
                                    placeholder="first name" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Middle Name</label>
                                <input type="text" name="CUST_MIDDLE_NAME" id="CUST_MIDDLE_NAME" class="form-control"
                                    placeholder="middle name" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="CUST_LAST_NAME" id="CUST_LAST_NAME" class="form-control"
                                    placeholder="last name" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Language/Title</label>
                                <div class="form-group flxi_join">
                                    <select name="CUST_LANG" id="CUST_LANG" class="form-select" data-allow-clear="true">
                                        <option value="">Select</option>
                                        <option value="EN">English</option>
                                        <option value="AR">Arabic</option>
                                        <option value="FR">French</option>
                                    </select>
                                    <select name="CUST_TITLE" id="CUST_TITLE" class="form-select"
                                        data-allow-clear="true">
                                        <option value="">Select</option>
                                        <option value="Mr.">Mr.</option>
                                        <option value="Ms.">Ms.</option>
                                        <option value="Shiekh.">Shiekh.</option>
                                        <option value="Shiekha.">Shiekha.</option>
                                        <option value="Dr.">Dr.</option>
                                        <option value="Ambassador.">Ambassador.</option>
                                        <option value="Madam Ambassadress">Madam Ambassadress</option>
                                        <option value="Prince.">Prince.</option>
                                        <option value="Princess.">Princess.</option>
                                        <option value="President">President</option>
                                        <option value="Prof.">Prof.</option>
                                        <option value="Minister.">Minister.</option>
                                        <option value="Admiral">Admiral</option>
                                        <option value="Lieutenant.">Lieutenant.</option>
                                        <option value="Consul.">Consul.</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">DOB</label>
                                <div class="input-group mb-3">
                                    <input type="text" id="CUST_DOB" name="CUST_DOB"
                                        class="form-control flatpickr-input" placeholder="YYYY-MM-DD">
                                    <span class="input-group-append">
                                        <span class="input-group-text bg-light d-block">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Passport</label>
                                <input type="text" name="CUST_PASSPORT" id="CUST_PASSPORT" class="form-control"
                                    placeholder="passport" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Address</label>
                                <input type="text" name="CUST_ADDRESS_1" id="CUST_ADDRESS_1" class="form-control"
                                    placeholder="addresss 1" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label"></label>
                                <input type="text" name="CUST_ADDRESS_2" id="CUST_ADDRESS_2" class="form-control"
                                    placeholder="address 2" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label"></label>
                                <input type="text" name="CUST_ADDRESS_3" id="CUST_ADDRESS_3" class="form-control"
                                    placeholder="address 3" />
                            </div>
                            <div class="col-md-3 ">
                                <label class="form-label col-md-12">Country</label>
                                <select name="CUST_COUNTRY" id="CUST_COUNTRY" data-width="100%"
                                    class="selectpicker CUST_COUNTRY" data-live-search="true">
                                    <option value="">Select</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label col-md-12">State</label>
                                <select name="CUST_STATE" id="CUST_STATE" data-width="100%"
                                    class="selectpicker CUST_STATE" data-live-search="true">
                                    <option value="">Select</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label col-md-12">City</label>
                                <select name="CUST_CITY" id="CUST_CITY" data-width="100%" class="selectpicker CUST_CITY"
                                    data-live-search="true">
                                    <option value="">Select</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Email</label>
                                <input type="text" name="CUST_EMAIL" id="CUST_EMAIL" class="form-control"
                                    placeholder="email" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Mobile</label>
                                <input type="text" name="CUST_MOBILE" id="CUST_MOBILE" class="form-control"
                                    placeholder="mobile" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="CUST_PHONE" id="CUST_PHONE" class="form-control"
                                    placeholder="phone" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Client ID</label>
                                <input type="text" name="CUST_CLIENT_ID" id="CUST_CLIENT_ID" class="form-control"
                                    placeholder="client id" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Postal Code</label>
                                <input type="text" name="CUST_POSTAL_CODE" id="CUST_POSTAL_CODE" class="form-control"
                                    placeholder="postal" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">VIP</label>
                                <select name="CUST_VIP" id="CUST_VIP" class="select2 form-select"
                                    data-allow-clear="true">
                                    <option value="">Select VIP</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Nationality</label>
                                <select name="CUST_NATIONALITY" id="CUST_NATIONALITY" class="select2 form-select"
                                    data-allow-clear="true">
                                    <option value="">Select</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Business Segment</label>
                                <select name="CUST_BUS_SEGMENT" id="CUST_BUS_SEGMENT" class="select2 form-select"
                                    data-allow-clear="true">
                                    <option value="">Select</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Communication</label>
                                <select name="CUST_COMMUNICATION" id="CUST_COMMUNICATION" class="select2 form-select"
                                    data-allow-clear="true">
                                    <option value="">Select Communication</option>
                                    <option value="WEB">Web</option>
                                    <option value="WHATSAPP">Whatsapp</option>
                                    <option value="FAX">Fax</option>
                                    <option value="OTHER">Other</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Communication Desc.</label>
                                <input type="text" name="CUST_COMMUNICATION_DESC" id="CUST_COMMUNICATION_DESC"
                                    class="form-control" placeholder="communication desc" />
                            </div>
                            <div class="col-md-3">
                                <div class="form-check mt-3">
                                    <input class="form-check-input flxCheckBox" type="checkbox" id="CUST_ACTIVE_CHK">
                                    <input type="hidden" name="CUST_ACTIVE" id="CUST_ACTIVE" value="Y"
                                        class="form-control" />
                                    <label class="form-check-label" for="defaultCheck1"> Active </label>
                                </div>

                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="custOptionsBtn" class="btn btn-primary custOptions" data_sysid=""
                        data_custname="">Options</button>
                    <button type="button" id="submitBtn" onClick="submitForm('customerForm','C')"
                        class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /Modal window -->

    <?= $this->include('includes/CustomerOptionsPopup') ?>

    <div class="content-backdrop fade"></div>
</div>
<!-- Content wrapper -->
<script>
$(document).ready(function() {
    $('#dataTable_view').DataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': '<?php echo base_url('/customerView')?>'
        },
        'columns': [{
                data: 'CUST_FIRST_NAME'
            },
            {
                data: 'CUST_PASSPORT'
            },
            {
                data: 'CUST_COUNTRY'
            },
            {
                data: 'CUST_EMAIL'
            },
            {
                data: 'CUST_MOBILE'
            },
            {
                data: 'CUST_CLIENT_ID'
            },
            {
                data: null,
                className: "text-center",
                "orderable": false,
                render: function(data, type, row, meta) {
                    return (
                        '<div class="d-inline-block">' +
                        '<a href="javascript:;" class="btn btn-sm btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></a>' +
                        '<ul class="dropdown-menu dropdown-menu-end">' +
                        '<li><a href="javascript:;" data_sysid="' + data['CUST_ID'] +
                        '" class="dropdown-item editWindow"><i class="fas fa-edit"></i> Edit</a></li>' +
                        '<div class="dropdown-divider"></div>' +
                        '<li><a href="javascript:;" data_sysid="' + data['CUST_ID'] +
                        '" data_custname="' + data['CUST_FIRST_NAME'] + ' ' + data[
                            'CUST_LAST_NAME'] +
                        '" class="dropdown-item custOptions"><i class="fa-solid fa-align-justify"></i> Options</a></li>' +
                        '<div class="dropdown-divider"></div>' +
                        '<li><a href="javascript:;" data_sysid="' + data['CUST_ID'] +
                        '" class="dropdown-item text-danger delete-record"><i class="fas fa-trash"></i> Delete</a></li>' +
                        '</ul>' +
                        '</div>'
                    );
                }
            },
        ],
        autowidth: true

    });
    $("#dataTable_view_wrapper .row:first").before(
        '<div class="row flxi_pad_view"><div class="col-md-3 ps-0"><button type="button" class="btn btn-primary" onClick="addForm()"><i class="fa-solid fa-plus fa-lg"></i>Add</button></div></div>'
    );
    $('#CUST_DOB').datepicker({
        format: 'd-M-yyyy',
        autoclose: true
    });

    <?php
    if(!empty($editId)) {  ?>
        editCust(<?php echo $editId; ?>);
    <?php
    }
    ?>

});

function addForm() {
    $(':input', '#customerForm').val('').prop('checked', false).prop('selected', false);
    $('#submitBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
    $('#custOptionsBtn').hide();
    $('#CUST_COUNTRY,#CUST_STATE,#CUST_CITY').html('<option value="">Select</option>').selectpicker('refresh');
    runCountryList();
    runSupportingLov();
    $('#reservationChild').modal('show');
    $('#reservationChildLabel').html('Add New Customer');
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
                    url: '<?php echo base_url('/deleteCustomer')?>',
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

function runSupportingLov() {
    $.ajax({
        url: '<?php echo base_url('/getSupportingLov')?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        dataType: 'json',
        async: false,
        success: function(respn) {
            var vipData = respn[0];
            var busegmt = respn[1];
            var option = '<option value="">Select Vip</option>';
            var option2 = '<option value="">Select Segment</option>';

            $(vipData).each(function(ind, data) {
                option += '<option value="' + data['VIP_ID'] + '">' + data['VIP_DESC'] +
                    '</option>';
            });
            $(busegmt).each(function(ind, data) {
                option2 += '<option value="' + data['BUS_SEG_CODE'] + '">' + data['BUS_SEG_DESC'] +
                    '</option>';
            });
            $('#CUST_VIP').html(option);
            $('#CUST_BUS_SEGMENT').html(option2);
        }
    });
}

$(document).on('click', '.flxCheckBox', function() {
    var checked = $(this).is(':checked');
    var parent = $(this).parent();
    if (checked) {
        parent.find('input[type=hidden]').val('Y');
    } else {
        parent.find('input[type=hidden]').val('N');
    }
});

function submitForm(id, mode) {
    hideModalAlerts();
    var formSerialization = $('#' + id).serializeArray();
    var url = '<?php echo base_url('/insertCustomer')?>';
    $.ajax({
        url: url,
        type: "post",
        data: formSerialization,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        dataType: 'json',
        success: function(respn) {
            var response = respn['SUCCESS'];

            if (response != '1') {
                var ERROR = respn['RESPONSE']['ERROR'];
                var mcontent = '';
                $.each(ERROR, function(ind, data) {
                    mcontent += '<li>' + data + '</li>';
                });
                showModalAlert('error', mcontent);
            } else {
                var alertText = $('#CUST_ID').val() == '' ? '<li>The new Customer has been created</li>' :
                    '<li>The Customer has been updated</li>';
                showModalAlert('success', alertText);

                $('#reservationChild').modal('hide');
                $('#dataTable_view').dataTable().fnDraw();
            }
        }
    });
}

function runCountryList() {
    $.ajax({
        url: '<?php echo base_url('/countryList')?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        async: false,
        // dataType:'json',
        success: function(respn) {

            $('#CUST_COUNTRY').html(respn).selectpicker('refresh');
            $('#CUST_NATIONALITY').html(respn);
        }
    });
}

$(document).on('change', '#CUST_COUNTRY', function() {
    var ccode = $(this).val();
    $.ajax({
        url: '<?php echo base_url('/stateList')?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            ccode: ccode
        },
        // dataType:'json',
        success: function(respn) {

            $('#CUST_STATE').html(respn).selectpicker('refresh');
        }
    });
});
$(document).on('change', '#CUST_STATE', function() {
    var ccode = $('#CUST_COUNTRY').find('option:selected').val();
    var scode = $(this).val();
    $.ajax({
        url: '<?php echo base_url('/cityList')?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            ccode: ccode,
            scode: scode
        },
        // dataType:'json',
        success: function(respn) {

            $('#CUST_CITY').html(respn).selectpicker('refresh');
        }
    });
});

function editCust(sysid) {
    runCountryList();
    runSupportingLov();
    
    $('#reservationChild').modal('show');
    $('#reservationChildLabel').html('Edit Customer');

    $('#custOptionsBtn').show();
    $('#custOptionsBtn').attr('data_sysid', sysid);

    var custArray = getCustomerDetails(sysid);
    $('#custOptionsBtn').attr('data_custname', custArray.CUST_FIRST_NAME + ' ' + custArray.CUST_LAST_NAME);

    $.ajax({
        url: '<?php echo base_url('/editCustomer')?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            sysid: sysid
        },
        dataType: 'json',
        success: function(respn) {

            $(respn).each(function(inx, data) {
                $.each(data, function(fields, datavals) {
                    var field = $.trim(fields); //fields.trim();
                    var dataval = $.trim(datavals); //datavals.trim();
                    if (field == 'CUST_COUNTRY_DESC' || field ==
                        'CUST_STATE_DESC' || field == 'CUST_CITY_DESC') {
                        return true;
                    };
                    // (field=='CUST_ACTIVE' ? (dataval=='Y' ? $('#CUST_ACTIVE_CHK').prop('checked',true) : $('#CUST_ACTIVE_CHK').prop('checked',false)) : '')
                    if (field == 'CUST_STATE' || field == 'CUST_CITY') {
                        var option = '<option value="' + dataval + '">' + data[
                            field + '_DESC'] + '</option>';
                        $('#' + field).html(option).selectpicker('refresh');
                    } else if (field == 'CUST_ACTIVE') {
                        // var rmSpace = dataval.trim();
                        if (dataval == 'Y') {
                            $('#CUST_ACTIVE_CHK').prop('checked', true);
                        } else {
                            $('#CUST_ACTIVE_CHK').prop('checked', false)
                        }
                    } else {
                        $('#' + field).val(dataval);
                        if (field == 'CUST_COUNTRY') {
                            $('#' + field).selectpicker('refresh');
                        }
                    }
                });
            });
            $('#submitBtn').removeClass('btn-primary').addClass('btn-success').text('Update');
        }
    });
}

$(document).on('click', '.editWindow', function() {
    var sysid = $(this).attr('data_sysid');
    editCust(sysid);
});

<?php if($add == 1) { ?>
$(window).on('load', function(){
    addForm();
});
<?php } ?>

// Display function clearFormFields
<?php echo isset($clearFormFields_javascript) ? $clearFormFields_javascript : ''; ?>
</script>

<?=$this->endSection()?>