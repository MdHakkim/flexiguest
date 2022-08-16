<?= $this->extend("Layout/AppView") ?>
<?= $this->section("contentRender") ?>
<?= $this->include('Layout/ErrorReport') ?>
<?= $this->include('Layout/SuccessReport') ?>
<?= $this->include('Layout/image_modal') ?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Masters /</span> Products
        </h4>

        <!-- DataTable with Buttons -->
        <div class="card">
            <!-- <h5 class="card-header">Responsive Datatable</h5> -->
            <div class="container-fluid table-responsive" style="padding: 16px;">
                <table id="dataTable_view" class="dt-responsive table table-striped display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th class="all">Product Name</th>
                            <th class="all">Product Categpry</th>
                            <th>Image</th>
                            <th>Price</th>
                            <th>QTY in Stock</th>
                            <th>Escalated Hours</th>
                            <th>Escalated Minutes</th>
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
        aria-labelledby="popModalWindowlabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="popModalWindowlabel">Products</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="submitForm" enctype="multipart/form-data" class="needs-validation" novalidate>

                        <div class="row g-3">
                            <input type="hidden" name="PR_ID" id="PR_ID" class="form-control" />

                            <div class="col-md-6">
                                <label class="form-label"><b> Product Name *</b></label>
                                <input type="text" name="PR_NAME" id="PR_NAME" class="form-control bootstrap-maxlength"
                                    placeholder="eg: Accessories set per room" required />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><b>Product Category *</b></label>
                                <select id="PR_CATEGORY_ID" name="PR_CATEGORY_ID"
                                    class="select2 form-select form-select-lg" data-allow-clear="true" required>
                                    <?=$productCategoryLists?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><b>Image </b></label>
                                <input type="file" name="PR_IMAGE" id="PR_IMAGE" class="form-control" />
                            </div>

                            <div class="col-md-3">
                                <label class="form-label"><b>Product Price *</b></label>
                                <input type="number" name="PR_PRICE" id="PR_PRICE" min="1" step="any"
                                    class="form-control bootstrap-maxlength" min="1" required />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label"><b>Quantity in Stock *</b></label>
                                <input type="number" name="PR_QUANTITY" id="PR_QUANTITY" min="0" step="1"
                                    class="form-control bootstrap-maxlength" min="1" required />
                            </div>

                            <div class="col-md-3">
                                <label class="form-label"><b>Escalated Hours *</b></label>
                                <input type="number" name="PR_ESCALATED_HOURS" id="PR_ESCALATED_HOURS" min="0" step="1"
                                    class="form-control" required />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label"><b>Escalated Minutes *</b></label>
                                <input type="number" name="PR_ESCALATED_MINS" id="PR_ESCALATED_MINS" min="0" max="60"
                                    step="1" class="form-control" required />
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

    jQuery.fn.dataTableExt.oSort['string-num-asc'] = function(x1, y1) {
        var x = x1;
        var y = y1;
        var pattern = /[0-9]+/g;
        var matches;
        if (x1.length !== 0) {
            matches = x1.match(pattern);
            x = matches[0];
        }
        if (y1.length !== 0) {
            matches = y1.match(pattern);
            y = matches[0];
        }
        return ((x < y) ? -1 : ((x > y) ? 1 : 0));

    };

    jQuery.fn.dataTableExt.oSort['string-num-desc'] = function(x1, y1) {

        var x = x1;
        var y = y1;
        var pattern = /[0-9]+/g;
        var matches;
        if (x1.length !== 0) {
            matches = x1.match(pattern);
            x = matches[0];
        }
        if (y1.length !== 0) {
            matches = y1.match(pattern);
            y = matches[0];
        }
        $("#debug").html('x=' + x + ' y=' + y);
        return ((x < y) ? 1 : ((x > y) ? -1 : 0));

    };

    $('#dataTable_view').DataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': '<?php echo base_url('/ProductsView') ?>'
        },
        'columns': [{
                data: ''
            },
            {
                data: 'PR_NAME'
            },
            {
                data: 'PC_CATEGORY'
            },
            {
                data: null,
                render: function(data, type, row, meta) {
                    let html = '';
                    if (data['PR_IMAGE']) {
                        html += '<img onclick=\'displayImagePopup("' + data['PR_IMAGE'] +
                            '")\' src="' + data['PR_IMAGE'] + '" width="80" height="80" />';
                    }

                    return html;
                }
            },
            {
                data: 'PR_PRICE',
                className: "text-center",

            },
            {
                data: 'PR_QUANTITY',
                className: "text-center",

            },
            {
                data: 'PR_ESCALATED_HOURS',
                className: "text-center"
            },
            {
                data: 'PR_ESCALATED_MINS',
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
                        '<li><a href="javascript:;" data_sysid="' + data['PR_ID'] +
                        '" class="dropdown-item editWindow text-primary"><i class="fa-solid fa-pen-to-square"></i> Edit</a></li>' +
                        '<div class="dropdown-divider"></div>' +
                        '<li><a href="javascript:;" data_sysid="' + data['PR_ID'] +
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
                width: "20%"
            }, {
                width: "15%"
            }, {
                width: "15%"
            }, {
                width: "15%"
            },
            {
                width: "10%"
            },
            {
                width: "10%"
            },
            {
                width: "10%"
            },

        ],
        "order": [
            [1, "asc"]
        ],
        destroy: true,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        responsive: {
            details: {
                display: $.fn.dataTable.Responsive.display.modal({
                    header: function(row) {
                        var data = row.data();
                        return 'Product Details';
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
        },

    });
    $("#dataTable_view_wrapper .row:first").before(
        '<div class="row flxi_pad_view"><div class="col-md-3 ps-0"><button type="button" class="btn btn-primary" onClick="addForm()"><i class="fa-solid fa-plus fa-lg"></i> Add</button></div></div>'
    );

});

function addForm() {
    $(':input', '#submitForm').not('[type="radio"],[type="checkbox"]').val('').prop('checked', false).prop('selected',
        false);
    $('.select2').val(null).trigger('change');

    //$('#PR_ID,#PR_CATEGORY_ID').html('<option value="">Select</option>');

    $('#submitBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
    $('#popModalWindowlabel').html('Add New Product');
    $('#popModalWindow').modal('show');
}


// Show Edit Product Form

$(document).on('click', '.editWindow', function() {

    $('.dtr-bs-modal').modal('hide');

    var sysid = $(this).attr('data_sysid');
    $('#popModalWindowlabel').html('Edit Product');
    $('#popModalWindow').modal('show');

    var url = '<?php echo base_url('/editProduct') ?>';
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

                    if (field == 'PR_IMAGE') return;;

                    if ($('#' + field).attr('type') == 'checkbox') {
                        $('#' + field).prop('checked', dataval == 1 ? true : false);
                    } else if (field == 'PR_CATEGORY_ID') {
                        $('#' + field).val(dataval).trigger('change');
                    } else {
                        $('#' + field).val(dataval);
                    }

                });
            });
            $('#submitBtn').removeClass('btn-primary').addClass('btn-success').text('Update');
        }
    });
});


// Add New or Edit Product submit Function

function submitForm(id) {
    var form = $('#'+id)[0];
    var formData = new FormData(form);

    var url = '<?php echo base_url('/insertProduct') ?>';
    $.ajax({
        url: url,
        type: "post",
        data: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        dataType: 'json',
        processData: false,
        contentType: false,
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

                var alertText = $('#PR_ID').val() == '' ? '<li>New Product has been added </li>' :
                    '<li>The Product has been updated</li>';

                showModalAlert('success', alertText);

                $('#popModalWindow').modal('hide');
                $('#dataTable_view').dataTable().fnDraw();
            }
        }
    });
}

// Delete Product 

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
                    url: '<?php echo base_url('/deleteProduct') ?>',
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
                            '<li>The Product has been deleted</li>'
                        );
                        $('#dataTable_view').dataTable().fnDraw();
                    }
                });
            }
        }
    });
});
</script>

<?= $this->endSection() ?>