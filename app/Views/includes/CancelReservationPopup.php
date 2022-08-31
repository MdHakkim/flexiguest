<!-- Cancellation Historys window -->
<div class="modal fade" id="resvCancelHistoryWindow" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resvCancelHistoryWindowLabel">Cancellation History of Reservation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="table-responsive text-nowrap">
                        <table id="resv_cancel_history"
                            class="dt-responsive table table-striped table-bordered display nowrap">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th class="all">Code</th>
                                    <th class="all">Reason</th>
                                    <th>Description</th>
                                    <th class="text-center">User</th>
                                    <th class="all text-center">Date</th>
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

<!-- Add / Edit Cancellation History Form -->

<div class="modal fade" id="resvCnclModalWindow" data-backdrop="static" data-keyboard="false"
    aria-labelledby="resvCnclModalWindowlabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="resvCnclModalWindowlabel">Reservation Cancel History</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" data-bs-target="#optionWindow"
                    data-bs-toggle="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="submitResvCnclForm" class="needs-validation" novalidate>
                    <div class="row g-3">
                        <div class="border rounded p-3">

                            <div class="col-md-12">
                                <div class="row mb-3">
                                    <label for="CN_RS_ID" class="col-form-label col-md-3"><b>Cancellation
                                            Reason*</b></label>
                                    <div class="col-md-6">
                                        <select id="CN_RS_ID" name="CN_RS_ID" class="select2 form-select form-select-lg"
                                            data-allow-clear="true" required>
                                            <?= $cancelReasons ?>
                                        </select>
                                        <input type="hidden" name="RESV_ID" id="RESV_ID" readonly />
                                        <input type="hidden" name="CUST_ID" id="CUST_ID" readonly />
                                    </div>
                                </div>

                                <div class="row">
                                    <label for="HIST_ACTION_DESCRIPTION"
                                        class="col-form-label col-md-3">Comments</label>
                                    <div class="col-md-9">
                                        <textarea rows="3" class="form-control" name="HIST_ACTION_DESCRIPTION"
                                            id="HIST_ACTION_DESCRIPTION"></textarea>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="closeResvCnclBtn" class="btn btn-secondary" data-bs-dismiss="modal"
                    data-bs-target="#optionWindow" data-bs-toggle="modal">Close</button>
                <button type="button" id="submitResvCnclBtn" onClick="submitResvCnclForm('submitResvCnclForm')"
                    class="btn btn-danger">Cancel Reservation</button>
            </div>
        </div>
    </div>
</div>

<script>
// Show Add Reservation Cancel History Form

function addResvCnclForm() {
    $(':input', '#submitResvCnclForm').not('[type="radio"],[type="hidden"]').val('').prop('checked', false).prop(
        'selected',
        false);
    $('#submitResvCnclForm .select2').val(null).trigger('change');

    $('#submitResvCnclBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
    $('#resvCnclModalWindowlabel').html('Cancel Reservation ' + $('.cancel-reservation').attr('data_resv_no'));
    $('#resvCnclModalWindow').modal('show');
}


// Cancel History submit Function

function submitResvCnclForm(id) {

    var formSerialization = $('#' + id).serializeArray();
    formSerialization.push({
        name: "HIST_REASON",
        value: $('#CN_RS_ID option:selected').text()
    });

    var url = '<?php echo base_url('/insertResvCancelHistory') ?>';
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
                var alertText = '<li>The Reservation has been cancelled.</li>';
                showModalAlert('success', alertText);
                $('#dataTable_view').dataTable().fnDraw();

                $('#resvCnclModalWindow').modal('hide');

                afterResvCnclFormClose();
            }
        }
    });
}


// Click Cancel Reservation button

$(document).on('click', '.cancel-reservation', function() {

    var resvId = $(this).attr('data_sysid');
    $('#submitResvCnclForm #RESV_ID').val(resvId);

    var custId = $(this).attr('data_custId');
    $('#submitResvCnclForm #CUST_ID').val(custId);

    var formSerialization = $('#submitResvCnclForm').serializeArray();

    $('#optionWindow').modal('hide');

    bootbox.confirm({
        message: "Are you sure you want to cancel this reservation?",
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
                addResvCnclForm();
            } else
                $('#optionWindow').modal('show');
        }
    });
});

// Click Reinstate Reservation button

$(document).on('click', '.reinstate-reservation', function() {

    $('#optionWindow').modal('hide');
    var resvId = $(this).attr('data_sysid');

    bootbox.confirm({
        message: "Are you sure you want to reinstate this reservation?",
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
                var url = '<?php echo base_url('/reinstateReservation') ?>';
                $.ajax({
                    url: url,
                    type: "post",
                    async: false,
                    data: {
                        RESV_ID: resvId
                    },
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
                            var alertText =
                                '<li>The Reservation has been reinstated.</li>';
                            showModalAlert('success', alertText);

                            $('#dataTable_view').dataTable().fnDraw();
                            $('#optionWindow').modal('show');
                            displayResvOptionButtons(resvId);
                        }
                    }
                });
            } else
                $('#optionWindow').modal('show');
        }
    });
});

//Show Reservation Cancel Historys table in modal

function showResvCancelHistory(resvId = 0) {

    $('#resv_cancel_history').DataTable({
        'processing': true,
        async: false,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': '<?php echo base_url('/resvCancelHistoryView') ?>',
            'data': {
                "sysid": resvId
            }
        },
        'columns': [{
                data: ''
            },
            {
                data: 'CN_RS_CODE',
                "visible": false,
            },
            {
                data: 'CN_RS_DESC',
                className: "text-center"
            },
            {
                data: 'HIST_ACTION_DESCRIPTION',
                className: "text-center"
            },
            {
                data: 'USR_FULL_NAME',
                className: "text-center"
            },
            {
                data: 'HIST_DATETIME',
                className: "text-center"
            }
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
            width: "15%"
        }, {
            width: "25%"
        }, {
            width: "20%"
        }, {
            width: "17%"
        }, {
            width: "16%"
        }],
        "order": [
            [5, "desc"]
        ],
        destroy: true,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end">>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        language: {
            emptyTable: 'There are no cancellations for this reservation'
        },
        responsive: {
            details: {
                display: $.fn.dataTable.Responsive.display.modal({
                    header: function(row) {
                        var data = row.data();
                        return 'Reservation Cancellation History';
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

// Function to execute after Reservation Cancellation form submit

function afterResvCnclFormClose() {
    $('#resvCancelHistoryWindow').modal('show');
    showResvCancelHistory($('#submitResvCnclForm #RESV_ID').val());
    $('#resvCancelHistoryWindowLabel').html('Cancellation History of Reservation: ' + $('.cancel-reservation').attr(
        'data_resv_no'));
}

$(document).on('hide.bs.modal', '#resvCancelHistoryWindow', function() {
    $('#optionWindow').modal('show');
    displayResvOptionButtons($('#submitResvCnclForm #RESV_ID').val());
});
</script>