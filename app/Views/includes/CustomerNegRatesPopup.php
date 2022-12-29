<!-- Negotiated Rates window -->
<div class="modal fade" id="customerNegotiatedRatesWindow" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customerNegotiatedRatesWindowLabel">Negotiated Rates of Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="table-responsive text-nowrap">
                        <table id="customer_negotiatedrates"
                            class="dt-responsive table table-striped table-bordered display nowrap">
                            <thead>
                                <tr>
                                    <th>Sequence</th>
                                    <th class="all">Rate Code</th>
                                    <th class="all text-center">Start Date</th>
                                    <th class="all text-center">End Date</th>
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
<!-- Negotiated Rates window end -->


<!-- Add Negotiated Rate Modal -->
<div class="modal fade" id="addNegotiatedRate" data-backdrop="static" data-keyboard="false"
    aria-lableledby="addNegotiatedRatelable">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="addNegotiatedRatelabel">Negotiated Rate</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
            </div>
            <div class="modal-body">
                <form id="negRateForm" class="needs-validation" novalidate>
                    <div class="row g-3">
                        <input type="hidden" name="neg_PROFILE_ID" id="neg_PROFILE_ID" />
                        <input type="hidden" name="NG_RT_ID" id="NG_RT_ID" />

                        <div class="col-md-9">
                            <label class="form-label"><b>Rate Code *</b></label>
                            <select id="neg_RT_CD_ID" name="neg_RT_CD_ID" class="select2 form-select form-select-lg"
                                data-allow-clear="true" required>
                                <?php foreach ($rateCodeOptions as $rateCodeOption) : ?>
                                <option value="<?= $rateCodeOption['id'] ?>"><?= $rateCodeOption['value'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Display Sequence</label>
                            <input type="number" name="NG_RT_DIS_SEQ" id="NG_RT_DIS_SEQ" class="form-control" min="0"
                                placeholder="eg: 3" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><b>Begin Date *</b></label>
                            <div class="input-group mb-6">
                                <input type="text" id="NG_RT_START_DT" name="NG_RT_START_DT"
                                    class="form-control dateField" placeholder="dd/mm/yyyy" required />
                                <span class="input-group-append">
                                    <span class="input-group-text bg-light d-block">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">End Date</label>
                            <div class="input-group mb-6">
                                <input type="text" id="NG_RT_END_DT" name="NG_RT_END_DT" class="form-control dateField"
                                    placeholder="dd/mm/yyyy" />
                                <span class="input-group-append">
                                    <span class="input-group-text bg-light d-block">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                </span>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="submitBtn" onClick="submitNegotiatedForm('negRateForm')"
                    class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>

<script>

$(document).ready(function() {

    $('.dateField').datepicker({
        autoclose: true,
        format: 'dd-M-yyyy',
    });

});

// Click Negotiated Rate button

$(document).on('click', '.show-cust-negotiated-rates', function() {

    var custOptId = $(this).attr('data_sysid');
    var custName = $(this).attr('data_custname');

    $('#customerNegotiatedRatesWindow').modal('show');

    showCustomerNegotiatedRates(custOptId);

    $('#customerNegotiatedRatesWindowLabel').html('Negotiated Rates of Profile: ' + custName);

});

//Show Customer Memberships table in modal

function showCustomerNegotiatedRates(custId = 0) {

    // Negotiated Rates List

    $('#customer_negotiatedrates').DataTable({
        'processing': true,
        async: false,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': '<?php echo base_url('/customerNegotiatedRateView')?>',
            'data': {
                "sysid": custId
            }
        },
        'columns': [{
                data: 'NG_RT_DIS_SEQ'
            },
            {
                data: 'RT_CD_CODE'
            },
            {
                data: 'NG_RT_START_DT',
                className: "text-center"
            },
            {
                data: 'NG_RT_END_DT',
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
                        '<li><a href="javascript:;" data_sysid="' + data['NG_RT_ID'] +
                        '" data-ratecode-id="' + data['RT_CD_ID'] +
                        '" data-profile-type="' + data['PROFILE_TYPE'] +
                        '" data-profile-id="' + data['PROFILE_ID'] +
                        '" data-start-date="' + data['NG_RT_START_DT'] +
                        '" data-edit-date="' + data['NG_RT_END_DT'] +
                        '" data-display-seq="' + data['NG_RT_DIS_SEQ'] +
                        '" class="dropdown-item edit-negotiated-rate text-primary"><i class="fa-solid fa-pen-to-square"></i> Edit</a></li>' +
                        '<div class="dropdown-divider"></div>' +
                        '<li><a href="<?php echo base_url('/editRateCode')?>/' + data['RT_CD_ID'] + '?showTab=3" target="_blank" class="dropdown-item text-info"><i class="fa-solid fa-eye"></i> View Rate Code</a></li>' +
                        '<div class="dropdown-divider"></div>' +
                        '<li><a href="javascript:;" data_sysid="' + data['NG_RT_ID'] +
                        '" class="dropdown-item text-danger delete-negotiated-rate"><i class="fa-solid fa-ban"></i> Delete</a></li>' +
                        '</ul>' +
                        '</div>'
                    );
                }
            },
        ],
        columnDefs: [{
            width: "5%"
        }, {
            width: "30%"
        }, {
            width: "15%"
        }, {
            width: "15%"
        }, {
            width: "35%"
        }],
        "order": [
            [0, "asc"]
        ],
        responsive: true,
        destroy: true,
        dom: "<'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-6'i><'col-sm-12 col-md-6 dataTables_pager'p>>",
        language: {
            emptyTable: 'There are no negotiated rates added'
        }
    });
    $("#customer_negotiatedrates_wrapper .row:first").before(
        '<div class="row flxi_pad_view"><div class="col-md-3 ps-0"><button type="button" class="btn btn-primary" data-bs-dismiss="modal" onClick="addNegotiatedForm()"><i class="fa-solid fa-plus fa-lg"></i> Add New</button></div></div>'
    );
}

const rateCodeList = <?php echo json_encode($rateCodeOptions); ?>;

$(document).on('click', '.edit-negotiated-rate', function() {
    hideModalAlerts();
    $('#NG_RT_ID').val($(this).attr('data_sysid'));
    $('#neg_PROFILE_ID').val('profile_chk_1_' + $(this).attr('data-profile-id'));
    $('#neg_RT_CD_ID').val($(this).attr('data-ratecode-id')).trigger('change');
    $("#NG_RT_START_DT").datepicker("setDate", new Date($(this).attr('data-start-date')));
    $("#NG_RT_END_DT").datepicker("setDate", new Date($(this).attr('data-edit-date')));
    $("#NG_RT_DIS_SEQ").val($(this).attr('data-display-seq'));
    $('#addNegotiatedRatelabel').html('Edit Negotiated Rate');

    $('#customerNegotiatedRatesWindow').modal('hide');
    $('#addNegotiatedRate').modal('show');
});

function addNegotiatedForm() {
    $("#NG_RT_ID").val("");
    $('#neg_RT_CD_ID').val(null).trigger('change');
    $("#NG_RT_START_DT").datepicker("setDate", new Date(<?php date('d-M-Y'); ?>));
    $("#NG_RT_END_DT").datepicker("setDate", new Date(<?php date('d-M-Y', strtotime('+1 day')); ?>));
    $("#NG_RT_DIS_SEQ").val("");
    $('#addNegotiatedRatelabel').html('Add New Negotiated Rate');
    $('#customerNegotiatedRatesWindow').modal('hide');
    $('#addNegotiatedRate').modal('show');
}

$(document).on('change', '#neg_RT_CD_ID', function() {
   var neg_RT_CD_ID =  $(this).val();
    $.ajax({
        url: '<?php echo base_url('/getRateCodeDateRange') ?>',
        type: "post",
        data: {neg_RT_CD_ID:neg_RT_CD_ID},
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        dataType: 'json',
        success: function(respn) {

            $(respn).each(function(inx, data) {
                $.each(data, function(fields, datavals) {
                   
                    var field = $.trim(fields);
                    var dataval = $.trim(datavals);

                    if(field == 'RT_CD_BEGIN_SELL_DT')
                    $("#NG_RT_START_DT").datepicker("setDate", new Date(dataval));
                    if(field == 'RT_CD_END_SELL_DT')
                    $("#NG_RT_END_DT").datepicker("setDate", new Date(dataval)); 
                    $("#NG_RT_START_DT").prop('readonly','readonly') 
                    $("#NG_RT_END_DT").prop('readonly','readonly')               
                    
                });
            });

        }
    });

});

// Add / Edit Negotiated Rate

function submitNegotiatedForm(id) {
    hideModalAlerts();
    var formSerialization = $('#' + id).serializeArray();
    var url = '<?php echo base_url('/insertCustomerNegotiatedRate')?>';
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

                if (respn['RESPONSE']['OUTPUT'] != '0') {

                    var alertText = $('#NG_RT_ID').val() == '' ?
                        '<li>The new Negotiated Rate has been created</li>' :
                        '<li>The Negotiated Rate has been updated</li>';

                    showModalAlert('success', alertText);
                } else
                    showModalAlert('error',
                        '<li>No new Negotiated Rates could be created. Please try again</li>');

                $('#addNegotiatedRate').modal('hide');
                $('#customer_negotiatedrates').dataTable().fnDraw();
                $('#customerNegotiatedRatesWindow').modal('show');
            }
        }
    });
}
</script>