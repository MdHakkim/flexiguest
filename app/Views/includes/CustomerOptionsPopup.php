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
                        data-bs-toggle="modal" data-bs-target="#customerMembershipsWindow">Memberships</button>
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
                                    <th>Sequence</th>
                                    <th class="text-center">Membership Type</th>
                                    <th class="all">Description</th>
                                    <th class="text-center">Card No</th>
                                    <th class="text-center">Expiration</th>
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

$(document).on('click', '.custOptions', function() {
    var custOptId = $(this).attr('data_sysid');
    var custName = $(this).attr('data_custname');

    $('.modal').modal('hide');
    $('#custOptionsWindow').modal('show');

    $('#custOptionsWindow').find('.data-port,.delete-record,.show-cust-activity-log').attr({
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

function showCustomerChanges(custOptId = 0) {

    $('#customer_changes').DataTable({
        'processing': true,
        async: false,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': '<?php echo base_url('/customerChangesView')?>',
            'data': {
                "sysid": custOptId
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
                        return 'Log Details of Customer Profile ' + custOptId;
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

function showCustomerMemberships(custOptId = 0) {

$('#customer_memberships').DataTable({
    'processing': true,
    async: false,
    'serverSide': true,
    'serverMethod': 'post',
    'ajax': {
        'url': '<?php echo base_url('/customerMembershipsView')?>',
        'data': {
            "sysid": custOptId
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
                    return 'Log Details of Customer Profile ' + custOptId;
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

$(document).on('click', '.show-cust-memberships', function() {

    var custOptId = $(this).attr('data_sysid');
    var custName = $(this).attr('data_custname');

    showCustomerMemberships(custOptId);

    $('#customerMembershipsWindowLabel').html('Membership List of Profile: ' + custName);

});
</script>