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
                    <button type="button" class="btn btn-primary show-cust-activity-log" data_sysid=""
                        data_custname="">Changes</button>
                    <button type="button" class="btn btn-primary data-port" data_sysid="" data_custname="">Data
                        Porting</button>
                    <button type="button" class="btn btn-primary delete-record" data_sysid=""
                        data_custname="">Delete</button><br />
                    <button type="button" class="btn btn-primary show-cust-memberships" data_sysid=""
                        data_custname="">Memberships</button>
                    <button type="button" class="btn btn-primary merge-profile" data_sysid=""
                        data_custname="">Merge</button>
                    <button type="button" title="Negotiated Rates" class="btn btn-primary show-cust-negotiated-rates"
                        data_sysid="" data_custname="">Neg.
                        Rates</button><br />
                    <button type="button" class="btn btn-primary show-cust-preferences" data_sysid=""
                        data_custname="">Preferences</button>
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
                        <input type="hidden" name="RESV_NAME" id="RESV_NAME" readonly />
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
                                    <th class="all text-center">Status</th>
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

<script>
var custOptId = '';

// Click Customer Options button

$(document).on('click', '.custOptions', function() {
    var custOptId = $(this).attr('data_sysid');
    var custName = $(this).attr('data_custname');

    $('.modal').modal('hide');
    $('#custOptionsWindow').modal('show');

    $('#custOptionsWindowLabel').html('Profile Options of : ' + custName);

    $('#custOptionsWindow').find(
            '.data-port,.delete-record,.show-cust-activity-log,.show-cust-memberships,.show-cust-negotiated-rates,.merge-profile,.show-cust-preferences'
        )
        .attr({
            'data_sysid': custOptId,
            'data_custname': custName
        });

    $('#CM_CUST_ID').val(custOptId);
    $('#RESV_NAME').val(custOptId);
    $('#neg_PROFILE_ID').val('profile_chk_1_' + custOptId);
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

// Click Changes button

$(document).on('click', '.show-cust-activity-log', function() {

    var custOptId = $(this).attr('data_sysid');
    var custName = $(this).attr('data_custname');

    $('#customerChangesWindow').modal('show');

    showCustomerChanges(custOptId);

    $('#customerChangesWindowLabel').html('Activity Log of Profile: ' + custName);

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


// Click Membership button

$(document).on('click', '.show-cust-memberships', function() {

    var custOptId = $(this).attr('data_sysid');
    var custName = $(this).attr('data_custname');

    $('#customerMembershipsWindow').modal('show');

    showCustomerMemberships(custOptId);

    $('#customerMembershipsWindowLabel').html('Membership List of Profile: ' + custName);

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
                data: 'CM_DIS_SEQ',
                className: "text-center"
            },
            {
                data: 'MEM_CODE',
                className: "text-center"
            },
            {
                data: 'MEM_DESC',
                className: "text-center"
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
                        '<a href="javascript:;" title="Edit or Delete" class="btn btn-sm btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></a>' +
                        '<ul class="dropdown-menu dropdown-menu-end">' +
                        '<li><a href="javascript:;" data_sysid="' + data['CM_ID'] +
                        '" class="dropdown-item editMemWindow text-primary" data-bs-dismiss="modal"><i class="fa-solid fa-pen-to-square"></i> Edit</a></li>' +
                        '<div class="dropdown-divider"></div>' +
                        '<li><a href="javascript:;" data_sysid="' + data['CM_ID'] +
                        '" data_custid="' + data['CUST_ID'] +
                        '" class="dropdown-item text-danger delete-mem-record"><i class="fa-solid fa-ban"></i> Delete</a></li>' +
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
            // Label
            targets: -2,
            width: "10%",
            render: function(data, type, full, meta) {
                var $status_number = full['CM_STATUS'];
                var $status = {
                    0: {
                        title: 'Inactive',
                        class: 'bg-label-danger'
                    },
                    1: {
                        title: 'Active',
                        class: 'bg-label-success'
                    }
                };
                if (typeof $status[$status_number] === 'undefined') {
                    return data;
                }
                return (
                    '<span class="badge rounded-pill ' +
                    $status[$status_number].class +
                    '">' +
                    $status[$status_number].title +
                    '</span>'
                );
            }
        }, {
            width: "23%"
        }],
        "order": [
            [2, "asc"]
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
        '<div class="row flxi_pad_view"><div class="col-md-3 ps-0"><button type="button" class="btn btn-primary" data-bs-dismiss="modal" onClick="addMemForm(\'#customerMembershipsWindow\')"><i class="fa-solid fa-plus fa-lg"></i> Add New</button></div></div>'
    );

}

$(document).on('click', '.delete-mem-record', function() {
    var sysid = $(this).attr('data_sysid');
    var custid = $(this).attr('data_custid');
    bootbox.confirm({
        message: "Are you sure you want to delete this membership?",
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
                    url: '<?php echo base_url('/deleteCustomerMembership') ?>',
                    type: "post",
                    data: {
                        sysid: sysid
                    },
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    dataType: 'json',
                    success: function(respn) {

                        showCustomerMemberships(custid);
                        showModalAlert('warning',
                            '<li>The Membership has been deleted</li>');
                    }
                });
            }
        }
    });

});

// Funtion to execute after Customer Memberhsip form submit

function afterMemFormClose() {
    $('#customerMembershipsWindow').modal('show');
    $('#customer_memberships').dataTable().fnDraw();
}
</script>

<?= $this->include('includes/CustomerMembershipPopup') ?>

<?= $this->include('includes/CustomerNegRatesPopup') ?>

<?= $this->include('includes/ProfileMergePopup') ?>

<?= $this->include('includes/CustomerPreferencesPopup') ?>