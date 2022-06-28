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
                                    class="form-control dateField" placeholder="d-Mon-yyyy" required />
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
                                    placeholder="d-Mon-yyyy" />
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
// Click Negotiated Rate button

$(document).on('click', '.show-cust-negotiated-rates', function() {

    var custOptId = $(this).attr('data_sysid');
    var custName = $(this).attr('data_custname');

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
                        '<a href="javascript:;" title="Edit or Delete" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></a>' +
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
}

const rateCodeList = <?php echo json_encode($rateCodeOptions); ?>;

$(document).on('click', '.edit-negotiated-rate', function() {

    hideModalAlerts();

    $('#NG_RT_ID').val($(this).attr('data_sysid'));
    $('#neg_PROFILE_ID').val($(this).attr('data-profile-id'));
    $('#neg_RT_CD_ID').val($(this).attr('data-ratecode-id')).trigger('change');
    $("#NG_RT_START_DT").datepicker("setDate", new Date($(this).attr('data-start-date')));
    $("#NG_RT_END_DT").datepicker("setDate", new Date($(this).attr('data-edit-date')));
    $("#NG_RT_DIS_SEQ").val($(this).attr('data-display-seq'));

    $('#addNegotiatedRate').modal('show');
});
</script>