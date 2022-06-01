<?=$this->extend("Layout/AppView")?>
<?=$this->section("contentRender")?>
<?= $this->include('Layout/ErrorReport') ?>
<?= $this->include('Layout/SuccessReport') ?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Masters /</span> Transaction Code
            Groups</h4>

        <!-- DataTable with Buttons -->
        <div class="card">
            <!-- <h5 class="card-header">Responsive Datatable</h5> -->
            <div class="container-fluid table-responsive" style="padding: 16px 16px 6px 16px">
                <table id="dataTable_view" class="dt-responsive table table-striped display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Group Code</th>
                            <th>Description</th>
                            <th>Type</th>
                            <th>Generates</th>
                            <th>Seq.</th>
                            <th class="all">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <!--/ Multilingual -->
    </div>
    <!-- / Content -->

    <!-- Modal Window -->

    <div class="modal fade" id="popModalWindow" data-backdrop="static" data-keyboard="false"
        aria-lableledby="popModalWindowlable">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="popModalWindowlabel">Transaction Code Group</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="submitForm" class="needs-validation" novalidate>
                        <div class="row g-3">
                            <input type="hidden" name="TC_GR_ID" id="TC_GR_ID" class="form-control" />

                            <?php if($transactionCodeGroupTypes != NULL) { ?>

                            <div class="border rounded p-3 mb-3 text-center">

                                <?php foreach($transactionCodeGroupTypes as $typeId => $typeName) { ?>

                                <div class="form-check form-check-inline" style="margin-right: 50px;">
                                    <input class="form-check-input" type="radio" name="TC_GR_TY_ID"
                                        id="inlineRadio<?php echo $typeId; ?>" value="<?php echo $typeId; ?>"
                                        <?php echo $typeId == '1' ? 'checked' : ''; ?>>
                                    <label class="form-check-label"
                                        for="inlineRadio<?php echo $typeId; ?>"><?php echo $typeName; ?></label>
                                </div>

                                <?php } ?>

                            </div>

                            <?php } ?>

                            <div class="col-md-7">
                                <label class="form-label"><b>Group Code *</b></label>
                                <input type="text" name="TC_GR_CODE" id="TC_GR_CODE"
                                    class="form-control bootstrap-maxlength" maxlength="10" placeholder="eg: OTA"
                                    required />
                            </div>
                            <div class="col-md-8">
                                <label class="form-label"><b>Group Description *</b></label>
                                <input type="text" name="TC_GR_DESC" id="TC_GR_DESC"
                                    class="form-control bootstrap-maxlength" maxlength="50"
                                    placeholder="eg: Online Travel Agent" required />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Display Sequence</label>
                                <input type="number" name="TC_GR_DIS_SEQ" id="TC_GR_DIS_SEQ" class="form-control"
                                    min="0" placeholder="eg: 3" />
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="submitBtn" onClick="submitForm('submitForm')"
                        class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- /Modal window -->

    <div class="content-backdrop fade"></div>
</div>

<!-- Content wrapper -->
<script>
var compAgntMode = '';
var linkMode = '';

$(document).ready(function() {
    linkMode = 'EX';

    $('#dataTable_view').DataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': '<?php echo base_url('/transactionCodeGroupView')?>'
        },
        'columns': [{
                data: ''
            },
            {
                data: 'TC_GR_CODE'
            },
            {
                data: 'TC_GR_DESC'
            },
            {
                data: 'TC_GR_TYPE'
            },
            {
                "defaultContent": ""
            },
            {
                data: 'TC_GR_DIS_SEQ'
            },
            {
                data: null,
                "orderable": false,
                render: function(data, type, row, meta) {
                    return (
                        '<div class="d-inline-block">' +
                        '<a href="javascript:;" title="Edit or Delete" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></a>' +
                        '<ul class="dropdown-menu dropdown-menu-end">' +
                        '<li><a href="javascript:;" data_sysid="' + data['TC_GR_ID'] +
                        '" class="dropdown-item editWindow text-primary"><i class="fa-solid fa-pen-to-square"></i> Edit</a></li>' +
                        '<div class="dropdown-divider"></div>' +
                        '<li><a href="javascript:;" data_sysid="' + data['TC_GR_ID'] +
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
            width: "15%"
        }, {
            width: "18%"
        }, {
            width: "16%"
        }, {
            width: "16%"
        }, {
            width: "15%"
        }, {
            width: "10%"
        }],
        "order": [
            [5, "asc"]
        ],
        destroy: true,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        responsive: {
            details: {
                display: $.fn.dataTable.Responsive.display.modal({
                    header: function(row) {
                        var data = row.data();
                        return 'Details of ' + data['TC_GR_CODE'];
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
    $("#dataTable_view_wrapper .row:first").before(
        '<div class="row flxi_pad_view"><div class="col-md-3 ps-0"><button type="button" class="btn btn-primary" onClick="addForm()"><i class="fa-solid fa-plus fa-lg"></i> Add</button></div></div>'
    );
});

function hideModalAlerts() {
    $('#errorModal').hide();
    $('#successModal').hide();
    $('#warningModal').hide();
}

function showModalAlert(modalType, modalContent) {
    $('#' + modalType + 'Modal').show();
    $('#form' + modalType.charAt(0).toUpperCase() + modalType.slice(1) + 'Message').html('<ul>' + modalContent +
        '</ul>');
}

// Show Add Transaction Code Group Form

function addForm() {
    $(':input', '#submitForm').not('[type="radio"]').val('').prop('checked', false).prop('selected', false);
    $('.select2').val(null).trigger('change');

    $('#submitBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
    $('#popModalWindowlabel').html('Add New Transaction Code Group');
    $("#TC_GR_CODE").prop("readonly", false);

    $('#popModalWindow').modal('show');
}

// Delete Transaction Code Group

$(document).on('click', '.delete-record', function() {
    hideModalAlerts();
    $('.dtr-bs-modal').modal('hide');

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
                    url: '<?php echo base_url('/deleteTransactionCodeGroup')?>',
                    type: "post",
                    data: {
                        sysid: sysid
                    },
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    dataType: 'json',
                    success: function(respn) {
                        showModalAlert('warning',
                            '<li>The Transaction Code Group has been deleted</li>');
                        $('#dataTable_view').dataTable().fnDraw();
                    }
                });
            }
        }
    });
});

// $(document).on('click','.flxCheckBox',function(){
//   var checked = $(this).is(':checked');
//   var parent = $(this).parent();
//   if(checked){
//     parent.find('input[type=hidden]').val('Y');
//   }else{
//     parent.find('input[type=hidden]').val('N');
//   }
// });


// Show Edit Transaction Code Group Form

$(document).on('click', '.editWindow', function() {

    $('.dtr-bs-modal').modal('hide');

    var sysid = $(this).attr('data_sysid');
    $('#popModalWindowlabel').html('Edit Transaction Code Group');
    $("#TC_GR_CODE").prop("readonly", true);
    $("input[name=TC_GR_TY_ID").attr("disabled", true);

    $('#popModalWindow').modal('show');

    var url = '<?php echo base_url('/editTransactionCodeGroup')?>';
    $.ajax({
        url: url,
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
                    if (field == 'TC_GR_TY_ID') {
                        $("input[name=TC_GR_TY_ID][value='" + dataval + "']").prop(
                            "checked", true);
                    } else {
                        $('#' + field).val(dataval);
                    }
                });
            });
            $('#submitBtn').removeClass('btn-primary').addClass('btn-success').text('Update');
        }
    });
});


// Add New or Edit Transaction Code Group submit Function

function submitForm(id) {
    hideModalAlerts();
    var formSerialization = $('#' + id).serializeArray();
    var url = '<?php echo base_url('/insertTransactionCodeGroup')?>';
    $.ajax({
        url: url,
        type: "post",
        data: formSerialization,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        dataType: 'json',
        success: function(respn) {
            console.log(respn, "testing");
            var response = respn['SUCCESS'];
            if (response != '1') {
                var ERROR = respn['RESPONSE']['ERROR'];
                var mcontent = '';
                $.each(ERROR, function(ind, data) {
                    console.log(data, "SDF");
                    mcontent += '<li>' + data + '</li>';
                });
                showModalAlert('error', mcontent);
            } else {
                var alertText = $('#TC_GR_ID').val() == '' ? '<li>The new Transaction Code Group \'' + $(
                        '#TC_GR_CODE')
                    .val() + '\' has been created</li>' : '<li>The Transaction Code Group \'' + $(
                        '#TC_GR_CODE').val() +
                    '\' has been updated</li>';
                showModalAlert('success', alertText);

                $('#popModalWindow').modal('hide');
                $('#dataTable_view').dataTable().fnDraw();
            }
        }
    });
}
</script>

<?=$this->endSection()?>