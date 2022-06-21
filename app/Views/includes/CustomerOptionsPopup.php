<!-- Option window -->
<div class="modal fade" id="custOptionsWindow" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="custOptionsWindowLabel">Profile Options</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
            </div>
            <div class="modal-body">
                <div class="flxy_opt_btn text-center">
                    <button type="button" class="btn btn-primary show-cust-activity-log" data_sysid="" data_custname=""
                        data-bs-toggle="modal" data-bs-target="#customerChangesWindow">Changes</button>
                    <button type="button" class="btn btn-primary data-port" data_sysid="" data_custname="">Data
                        Porting</button>
                    <button type="button" class="btn btn-primary delete-record" data_sysid=""
                        data_custname="">Delete</button>
                    <button type="button" class="btn btn-primary show-cust-memberships" data_sysid="" data_custname=""
                        data-bs-toggle="modal" data-bs-target="#customerMembershipsWindow" data_sysid=""
                        data_custname="">Memberships</button>
                    <button type="button" class="btn btn-primary">Neg. Rates</button>
                    <button type="button" class="btn btn-primary">Preferences</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- option window end -->

<!-- Changes Log window -->
<div class="modal fade" id="customerChangesWindow" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customerChangesWindowLabel">Activity Log of Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="table-responsive text-nowrap">
                        <table id="customer_changes"
                            class="dt-responsive table table-striped table-bordered display nowrap">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th class="all">User</th>
                                    <th>Log ID</th>
                                    <th class="all">Date</th>
                                    <th>Time</th>
                                    <th class="all">Action Type</th>
                                    <th class="none">Description</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-bs-dismiss="modal" class="btn btn-secondary">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Changes Log window end -->


<!-- Memberships window -->
<div class="modal fade" id="customerMembershipsWindow" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customerMembershipsWindowLabel">Membership List of Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="table-responsive text-nowrap">
                        <table id="customer_memberships"
                            class="dt-responsive table table-striped table-bordered display nowrap">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>ID</th>
                                    <th>Sequence</th>
                                    <th class="text-center">Membership Type</th>
                                    <th class="all">Description</th>
                                    <th>Card No</th>
                                    <th class="text-center">Expiration</th>
                                    <th class="text-center">Status</th>
                                    <th class="all">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-bs-dismiss="modal" class="btn btn-secondary">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Changes Log window end -->


<!-- Add / Edit Membership Form -->

<div class="modal fade" id="memModalWindow" data-backdrop="static" data-keyboard="false"
    aria-labelledby="memModalWindowlabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="memModalWindowlabel">Customer Membership</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="submitMemForm" class="needs-validation" novalidate>
                    <div class="row g-3">
                        <input type="hidden" name="CM_ID" id="CM_ID" class="form-control" />

                        <div class="border rounded p-3">

                            <div class="col-md-12">
                                <div class="row mb-3">
                                    <label for="html5-text-input" class="col-form-label col-md-3"><b>Membership Name
                                            *</b></label>
                                    <div class="col-md-6">
                                        <input type="text" name="CUST_NAME" id="CUST_NAME"
                                            class="form-control bootstrap-maxlength" maxlength="50" readonly />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="html5-text-input" class="col-form-label col-md-3"><b>Membership Type
                                            *</b></label>
                                    <div class="col-md-6">
                                        <select id="MEM_ID" name="MEM_ID" class="select2 form-select form-select-lg"
                                            data-allow-clear="true" required>
                                            <?=$membershipTypeOptions?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="html5-text-input" class="col-form-label col-md-3"><b>Card Number
                                            *</b></label>
                                    <div class="col-md-7">
                                        <input type="text" name="CM_CARD_NUMBER" id="CM_CARD_NUMBER"
                                            class="form-control bootstrap-maxlength" maxlength="50" placeholder=""
                                            required />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="html5-text-input" class="col-form-label col-md-3"><b>Name on
                                            Card *</b></label>
                                    <div class="col-md-7">
                                        <input type="text" name="CM_NAME_CARD" id="CM_NAME_CARD"
                                            class="form-control bootstrap-maxlength" maxlength="50" placeholder=""
                                            required />
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="html5-text-input" class="col-form-label col-md-3"><b>Expiry Date
                                            *</b></label>
                                    <div class="col-md-4">
                                        <input type="text" id="CM_EXPIRY_DATE" name="CM_EXPIRY_DATE"
                                            class="form-control flatpickr-input" placeholder="YYYY-MM-DD">
                                    </div>
                                </div>

                                <div class="row">
                                    <label for="html5-text-input" class="col-form-label col-md-3">Sequence</label>
                                    <div class="col-md-2">
                                        <input type="number" name="CM_DIS_SEQ" id="CM_DIS_SEQ" class="form-control"
                                            min="0" placeholder="eg: 3" />
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="border rounded p-3">
                            <div class="row">

                                <div class="row mb-3">
                                    <label for="html5-text-input" class="col-form-label col-md-3">Member Since</label>
                                    <div class="col-md-4">
                                        <input type="text" id="CM_MEMBER_SINCE" name="CM_MEMBER_SINCE"
                                            class="form-control flatpickr-input" placeholder="YYYY-MM-DD">
                                    </div>
                                </div>

                                <div class="row">
                                    <label for="html5-text-input" class="col-form-label col-md-3">Comments</label>
                                    <div class="col-md-9">
                                        <textarea rows="3" class="form-control" name="CM_COMMENTS"
                                            id="CM_COMMENTS"></textarea>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="text-center statusCheck">
                            <div class="col-md-12">
                                <label class="switch switch-lg">
                                    <input id="CM_STATUS" name="CM_STATUS" type="checkbox" value="1"
                                        class="switch-input" />
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on">
                                            <i class="bx bx-check"></i>
                                        </span>
                                        <span class="switch-off">
                                            <i class="bx bx-x"></i>
                                        </span>
                                    </span>
                                    <span class="switch-label">Active</span>
                                </label>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="submitBtn" onClick="submitMemForm('submitMemForm')"
                    class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>


<script>
var custOptId = '';

$(document).on('click', '.custOptions', function() {
    var custOptId = $(this).attr('data_sysid');
    var custName = $(this).attr('data_custname');

    $('.modal').modal('hide');
    $('#custOptionsWindow').modal('show');

    $('#custOptionsWindow').find('.data-port,.delete-record,.show-cust-activity-log,.show-cust-memberships')
        .attr({
            'data_sysid': custOptId,
            'data_custname': custName
        });
});

// Print / Download PDF Popup

$(document).on('click', '.data-port', function() {

    var custOptId = $(this).attr('data_sysid');

    bootbox.dialog({
        title: 'Profile Data Portability',
        message: "Do you want to Print or Download the Profile?",
        backdrop: true,
        buttons: {
            ok: {
                label: 'Print Profile',
                className: 'btn-success',
                callback: function() {
                    window.open('<?php echo base_url('/printProfile')?>/' + custOptId, '_blank');
                    return false;
                }
            },
            noclose: {
                label: "Download Profile (PDF)",
                className: 'btn-info',
                callback: function() {
                    location.href = '<?php echo base_url('/exportProfile')?>/' + custOptId;
                    return false;
                }
            },
            cancel: {
                label: 'Cancel',
                className: 'btn-secondary'
            }
        }
    });

});


//Show Activity Log table in modal

function showCustomerChanges(custId = 0) {

    $('#customer_changes').DataTable({
        'processing': true,
        async: false,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': '<?php echo base_url('/customerChangesView')?>',
            'data': {
                "sysid": custId
            }
        },
        'columns': [{
                data: ''
            },
            {
                data: 'USR_NAME'
            },
            {
                data: 'LOG_ID',
                "visible": false,
            },
            {
                data: 'LOG_DATE'
            },
            {
                data: 'LOG_TIME'
            },
            {
                data: 'AC_TY_DESC'
            },
            {
                data: 'LOG_ACTION_DESCRIPTION'
            },
        ],
        columnDefs: [{
            width: "7%",
            className: 'control',
            responsivePriority: 1,
            orderable: false,
            targets: 0,
            searchable: false,
            render: function(data, type, full, meta) {
                return '';
            }
        }, {
            width: "35%"
        }, {
            width: "15%"
        }, {
            width: "10%"
        }, {
            width: "10%"
        }, {
            width: "23%"
        }],
        "order": [
            [2, "desc"]
        ],
        destroy: true,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end">>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        language: {
            emptyTable: 'There are no logs for this customer'
        },
        responsive: {
            details: {
                display: $.fn.dataTable.Responsive.display.modal({
                    header: function(row) {
                        var data = row.data();
                        return 'Log Details of Customer Profile';
                    }
                }),
                type: 'column',
                renderer: function(api, rowIdx, columns) {
                    var data = $.map(columns, function(col, i) {

                        return col.title !==
                            '' // ? Do not show row in modal popup if title is blank (for check box)
                            ?
                            '<tr data-dt-row="' +
                            col.rowIndex +
                            '" data-dt-column="' +
                            col.columnIndex +
                            '">' +
                            '<td>' +
                            col.title +
                            ':' +
                            '</td> ' +
                            '<td>' +
                            col.data +
                            '</td>' +
                            '</tr>' :
                            '';
                    }).join('');

                    return data ? $('<table class="table"/><tbody />').append(data) : false;
                }
            }
        }
    });
}

$(document).on('click', '.show-cust-activity-log', function() {

    var custOptId = $(this).attr('data_sysid');
    var custName = $(this).attr('data_custname');

    showCustomerChanges(custOptId);

    $('#customerChangesWindowLabel').html('Activity Log of Profile: ' + custName);

});


//Show Customer Memberships table in modal

function showCustomerMemberships(custId = 0) {

    $('#customer_memberships').DataTable({
        'processing': true,
        async: false,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': '<?php echo base_url('/customerMembershipsView')?>',
            'data': {
                "sysid": custId
            }
        },
        'columns': [{
                data: ''
            },
            {
                data: 'CM_ID',
                "visible": false,
            },
            {
                data: 'CM_DIS_SEQ'
            },
            {
                data: 'MEM_CODE'
            },
            {
                data: 'MEM_DESC'
            },
            {
                data: 'CM_CARD_NUMBER'
            },
            {
                data: 'CM_EXPIRY_DATE'
            },
            {
                data: 'CM_STATUS',
                className: "text-center"
            },
            {
                data: null,
                className: "text-center",
                "orderable": false,
                render: function(data, type, row, meta) {
                    return (
                        '<div class="d-inline-block">' +
                        '<a href="javascript:;" title="Edit or Delete" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></a>' +
                        '<ul class="dropdown-menu dropdown-menu-end">' +
                        '<li><a href="javascript:;" data_sysid="' + data['MEM_ID'] +
                        '" class="dropdown-item editWindow text-primary"><i class="fa-solid fa-pen-to-square"></i> Edit</a></li>' +
                        '<div class="dropdown-divider"></div>' +
                        '<li><a href="javascript:;" data_sysid="' + data['MEM_ID'] +
                        '" data_memcode="' + data['MEM_DESC'] +
                        '" class="dropdown-item copyWindow text-success"><i class="fa-solid fa-copy"></i> Copy</a></li>' +
                        '<div class="dropdown-divider"></div>' +
                        '<li><a href="javascript:;" data_sysid="' + data['MEM_ID'] +
                        '" class="dropdown-item text-danger delete-record"><i class="fa-solid fa-ban"></i> Delete</a></li>' +
                        '</ul>' +
                        '</div>'
                    );
                }
            },
        ],
        columnDefs: [{
            width: "7%",
            className: 'control',
            responsivePriority: 1,
            orderable: false,
            targets: 0,
            searchable: false,
            render: function(data, type, full, meta) {
                return '';
            }
        }, {
            width: "10%"
        }, {
            width: "15%"
        }, {
            width: "15%"
        }, {
            width: "10%"
        }, {
            width: "10%"
        }, {
            width: "10%"
        }, {
            width: "23%"
        }],
        "order": [
            [2, "desc"]
        ],
        destroy: true,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end">>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        language: {
            emptyTable: 'There are no memberships for this customer'
        },
        responsive: {
            details: {
                display: $.fn.dataTable.Responsive.display.modal({
                    header: function(row) {
                        var data = row.data();
                        return 'Membership Details';
                    }
                }),
                type: 'column',
                renderer: function(api, rowIdx, columns) {
                    var data = $.map(columns, function(col, i) {

                        return col.title !==
                            '' // ? Do not show row in modal popup if title is blank (for check box)
                            ?
                            '<tr data-dt-row="' +
                            col.rowIndex +
                            '" data-dt-column="' +
                            col.columnIndex +
                            '">' +
                            '<td>' +
                            col.title +
                            ':' +
                            '</td> ' +
                            '<td>' +
                            col.data +
                            '</td>' +
                            '</tr>' :
                            '';
                    }).join('');

                    return data ? $('<table class="table"/><tbody />').append(data) : false;
                }
            }
        }
    });
    $("#customer_memberships_wrapper .row:first").before(
        '<div class="row flxi_pad_view"><div class="col-md-3 ps-0"><button type="button" class="btn btn-primary" data-bs-dismiss="modal" onClick="addMemForm()"><i class="fa-solid fa-plus fa-lg"></i> Add New</button></div></div>'
    );
}

function getCustomerDetails(custId) {
    $.ajax({
        url: '<?php echo base_url('/editCustomer')?>',
        type: "post",
        async: false,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            sysid: custId
        },
        dataType: 'json',
        success: function(respn) {
            

            $(respn).each(function(inx, data) {
                return data;
            });
        }
    });
}

// Show Add Customer Membership Form

function addMemForm() {
    //$(':input', '#submitMemForm').not('[type="radio"]').val('').prop('checked', false).prop('selected', false);
    clearFormFields('#submitMemForm');
    $('.select2').val(null).trigger('change');

    $('#submitMemBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
    $('#memModalWindowlabel').html('Add New Membership for Customer');
    $(".memStatusCheck").hide();
    $('#memModalWindow').modal('show');
    $("#CM_STATUS").prop('checked', 'checked');
}

$(document).on('click', '.show-cust-memberships', function() {

    var custOptId = $(this).attr('data_sysid');
    var custName = $(this).attr('data_custname');

    showCustomerMemberships(custOptId);

    $('#customerMembershipsWindowLabel').html('Membership List of Profile: ' + custName);

});
</script>