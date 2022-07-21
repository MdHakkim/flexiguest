<!-- Preferences window -->
<div class="modal fade" id="customerPreferencesWindow" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customerPreferencesWindowLabel">Preferences of Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="table-responsive text-nowrap">
                        <table id="customer_preferences"
                            class="dt-responsive table table-striped table-bordered display nowrap">
                            <thead>
                                <tr>
                                    <th class="all">Preference Group</th>
                                    <th class="all text-center">Description</th>
                                    <th class="all text-center">Preferences</th>
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
<!-- Preferences window end -->


<!-- Add Preference Modal -->
<div class="modal fade" id="addPreference" data-backdrop="static" data-keyboard="false"
    aria-lableledby="addPreferencelable">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="addPreferencelabel">Preference</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
            </div>
            <div class="modal-body">
                <form id="preferenceForm" class="needs-validation" novalidate>
                    <div class="row g-3">
                        <input type="hidden" name="PF_ID" id="PF_ID" value="" />
                        <input type="hidden" name="pref_CUSTOMER_ID" id="pref_CUSTOMER_ID" />

                        <div class="col-md-12">
                            <div class="row mb-3">
                                <label for="pref_PF_GR_ID" class="col-form-label"><b>Preference Group *</b></label>
                                <select id="pref_PF_GR_ID" name="pref_PF_GR_ID"
                                    class="select2 form-select form-select-lg" data-allow-clear="true" required>
                                    <?php foreach ($prefGroupOptions as $prefGroupOption) : ?>
                                    <option value="<?= $prefGroupOption['id'] ?>"><?= $prefGroupOption['value'] ?>
                                    </option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="row mb-3">
                                <label for="pref_PREFS" class="col-form-label d-flex justify-content-between">
                                    <b>Preferences *</b>
                                    <span id="pref_checkAllSpan" style="display: none;">
                                        <input class="form-check-input" type="checkbox" value=""
                                            id="pref_checkAll">&nbsp;
                                        <label class="form-check-label" for="pref_checkAll">Select All</label>
                                    </span>
                                </label>
                                <select id="pref_PREFS" name="pref_PREFS[]" class="select2 form-select form-select-lg"
                                    multiple>

                                </select>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="submitBtn" onClick="submitPreferenceForm('preferenceForm')"
                    class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
// Click Preference button

$(document).on('click', '.show-cust-preferences', function() {

    var custOptId = $(this).attr('data_sysid');
    var custName = $(this).attr('data_custname');

    $('#customerPreferencesWindow').modal('show');

    showCustomerPreferences(custOptId);

    $('#customerPreferencesWindowLabel').html('Preferences of Profile: ' + custName);

});

//Show Customer Preferences table in modal

function showCustomerPreferences(custId = 0) {

    // Preferences List

    $('#customer_preferences').DataTable({
        'processing': true,
        async: false,
        'serverSide': true,
        'serverMethod': 'post',
        'searching': true,
        'ajax': {
            'url': '<?php echo base_url('/customerPreferenceView')?>',
            'data': {
                "sysid": custId
            }
        },
        'columns': [{
                data: 'PF_GR_CODE'
            },
            {
                data: 'PF_GR_DESC'
            },
            {
                data: 'PREFS'
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
                        '<li><a href="javascript:;" data-group-id="' + data['GROUP_ID'] +
                        '" data-customer-id="' + data['CUSTOMER_ID'] +
                        '" class="dropdown-item edit-preference text-primary"><i class="fa-solid fa-pen-to-square"></i> Edit</a></li>' +
                        '<div class="dropdown-divider"></div>' +
                        '<li><a href="javascript:;" data-group-id="' + data['GROUP_ID'] +
                        '" data-customer-id="' + data['CUSTOMER_ID'] +
                        '" class="dropdown-item text-danger delete-preference"><i class="fa-solid fa-ban"></i> Delete</a></li>' +
                        '</ul>' +
                        '</div>'
                    );
                }
            },
        ],
        columnDefs: [{
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
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        language: {
            emptyTable: 'There are no preferences added'
        }
    });
    $("#customer_preferences_wrapper .row:first").before(
        '<div class="row flxi_pad_view"><div class="col-md-3 ps-0"><button type="button" class="btn btn-primary" data-bs-dismiss="modal" onClick="addPreferenceForm()"><i class="fa-solid fa-plus fa-lg"></i> Add New</button></div></div>'
    );

    $('#pref_CUSTOMER_ID').val(custId);

}

const prefGroupList = <?php echo json_encode($prefGroupOptions); ?>;

function getPreferenceCodes(custId, pf_group) {

    var url = '<?php echo base_url('/showPreferenceCodeList')?>';
    $.ajax({
        url: url,
        type: "get",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            custId: custId,
            pf_group: pf_group
        },
        dataType: 'json',
        success: function(respn) {

            var prefCodeSelect = $('#pref_PREFS');

            prefCodeSelect.empty().trigger("change");

            if (respn.length == 0)
                $("#pref_checkAllSpan").hide();
            else {
                $("#pref_checkAllSpan").show();
                $("#pref_checkAll").prop("checked", false);
            }
            $(respn).each(function(inx, data) {
                var newOption = new Option(data.code + ' | ' + data.text, data.id, false,
                    false);
                prefCodeSelect.append(newOption);
            });
            prefCodeSelect.val(null).trigger('change');

            if (mode = 'add') {

            } else if (mode = 'edit') {
                var selPreferenceCodes = [];
                $(respn).each(function(inx, data) {
                    selPreferenceCodes.push(data['id']);
                });

                prefCodeSelect.val(selPreferenceCodes).trigger('change');
            }
        }
    });
}

function setPreferenceCodeOptions(custId, pf_group) {

    var url = '<?php echo base_url('/showPreferenceCodeList')?>';
    $.ajax({
        url: url,
        type: "get",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
            custId: custId,
            pf_group: pf_group
        },
        dataType: 'json',
        success: function(respn) {

            var prefCodeSelect = $('#pref_PREFS');

            var selPreferenceCodes = [];
            $(respn).each(function(inx, data) {
                selPreferenceCodes.push(data['id']);
            });

            prefCodeSelect.val(selPreferenceCodes).trigger('change');
        }
    });
}

$('#pref_PF_GR_ID').on('change.select2', function() {

    getPreferenceCodes('0', $(this).val());
});

$("#pref_checkAll").click(function() {
    if ($(this).is(':checked')) {
        $("#pref_PREFS > option").prop("selected", "selected");
        $("#pref_PREFS").trigger("change");
    } else {
        $("#pref_PREFS > option").removeAttr("selected");
        $("#pref_PREFS").val(null).trigger('change');
    }
});

$(document).on('click', '.edit-preference', function() {

    hideModalAlerts();

    $('#PF_ID').val('1');
    $('#pref_PF_GR_ID').val($(this).attr('data-group-id')).trigger('change');

    $('#addPreferencelabel').html('Edit Preference');

    $('#customerPreferencesWindow').modal('hide');
    $('#addPreference').modal('show');

    setPreferenceCodeOptions($(this).attr('data-customer-id'), $(this).attr('data-group-id'));
});

function addPreferenceForm() {

    $("#PF_ID").val("");
    $('#pref_PF_GR_ID').val(null).trigger('change');

    $('#addPreferencelabel').html('Add New Preference');

    $('#customerPreferencesWindow').modal('hide');
    $('#addPreference').modal('show');
}

// Add / Edit Preference

function submitPreferenceForm(id) {
    hideModalAlerts();
    var formSerialization = $('#' + id).serializeArray();
    var url = '<?php echo base_url('/insertCustomerPreference')?>';
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

                    var alertText = $('#PF_ID').val() == '' ?
                        '<li>The new Preference has been created</li>' :
                        '<li>The Preference has been updated</li>';

                    showModalAlert('success', alertText);
                } else
                    showModalAlert('error',
                        '<li>No new Preferences could be created. Please try again</li>');

                $('#addPreference').modal('hide');
                showCustomerPreferences($('#pref_CUSTOMER_ID').val());

                $('#customerPreferencesWindow').modal('show');
            }
        }
    });
}

$(document).on('click', '.delete-preference', function() {
    hideModalAlerts();

    var custId = $(this).attr('data-customer-id');
    var pf_group = $(this).attr('data-group-id');

    bootbox.confirm({
        message: "Are you sure you want to delete this preference?",
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
                    url: '<?php echo base_url('/deletePreference')?>',
                    type: "post",
                    data: {
                        custId: custId,
                        pf_group: pf_group
                    },
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    dataType: 'json',
                    success: function(respn) {
                        showModalAlert('warning',
                            '<li>The Preference has been deleted</li>'
                        );
                        showCustomerPreferences(custId);
                    }
                });
            }
        }
    });
});

$(document).on('hide.bs.modal', '#addPreference', function() {

    showCustomerPreferences($('#pref_CUSTOMER_ID').val());
    $('#customerPreferencesWindow').modal('show');

});
</script>