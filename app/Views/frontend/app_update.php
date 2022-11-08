<?= $this->extend("Layout/AppView") ?>
<?= $this->section("contentRender") ?>
<?= $this->include('Layout/ErrorReport') ?>
<?= $this->include('Layout/SuccessReport') ?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Masters /</span> App Updates</h4>

        <!-- DataTable with Buttons -->
        <div class="card">
            <!-- <h5 class="card-header">Responsive Datatable</h5> -->
            <div class="container-fluid table-responsive" style="padding: 16px 16px 6px 16px">
                <table id="dataTable_view" class="dt-responsive table table-striped display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Display Sequence</th>
                            <th>Title</th>
                            <th>Cover Image</th>
                            <th>Description</th>
                            <th>Body</th>
                            <th>Created At</th>
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

    <div class="modal fade" id="popModalWindow" data-backdrop="static" data-keyboard="false" aria-lableledby="popModalWindowlable">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title" id="popModalWindowlabel">App Updates</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="submit-form" class="needs-validation" novalidate>
                        <div class="row g-3">
                            <input type="hidden" name="id" class="form-control" />

                            <div class="col-md-12">
                                <label class="form-label"><b>Title *</b></label>
                                <input type="text" name="AU_TITLE" class="form-control" placeholder="Title" required />
                            </div>

                            <div class="col-md-6">
                                <label class="form-label"><b>Cover Image *</b></label>
                                <input type="file" name="AU_COVER_IMAGE" class="form-control" required />
                            </div>

                            <div class="col-md-6">
                                <label class="form-label"><b>Display Sequence</b></label>
                                <input type="number" name="AU_SEQUENCE" class="form-control" placeholder="Label" required />
                            </div>

                            <div class="col-md-12">
                                <label class="form-label"><b>Description *</b></label>
                                <textarea type="number" name="AU_DESCRIPTION" class="form-control" placeholder="Description..."></textarea>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label"><b>Body *</b></label>
                                <textarea name="AU_BODY" class="d-none"></textarea>
                                <div id="snow-editor"></div>
                            </div>

                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>

                    <button type="button" id="submitBtn" onClick="submitForm()" class="btn btn-primary">
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- /Modal window -->

    <div class="content-backdrop fade"></div>
</div>

<!-- Content wrapper -->
<?= $this->endSection() ?>

<?= $this->section("script") ?>
<script>
    $(document).ready(function() {
        const snowEditor = new Quill('#snow-editor', {
            bounds: '#snow-editor',
            theme: 'snow',
            placeholder: 'Content...',
        });
    });

    var compAgntMode = '';
    var linkMode = '';

    $(document).ready(function() {
        linkMode = 'EX';

        $('#dataTable_view').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                'url': '<?php echo base_url('/app-update/all-app-updates') ?>'
            },
            'columns': [{
                    data: ''
                },
                {
                    data: 'AU_SEQUENCE'
                },
                {
                    data: 'AU_TITLE'
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return (
                            `
                                <img src='${data['AU_COVER_IMAGE']}' width='80' height='80'/>
                            `
                        );
                    }
                },
                {
                    data: 'AU_DESCRIPTION'
                },
                {
                    data: null,
                    render: function(data) {
                        return data['AU_BODY'].substr(0, 30);
                    }
                },
                {
                    data: 'AU_CREATED_AT'
                },
                {
                    data: null,
                    className: "text-center",
                    "orderable": false,
                    render: function(data, type, row, meta) {
                        return (
                            `
                        <div class="d-inline-block">
                            <a href="javascript:;" title="Edit or Delete" class="btn btn-sm btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a href="javascript:;" data_id="${data['AU_ID']}" class="dropdown-item editWindow text-primary">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </a>
                                </li>

                                <div class="dropdown-divider"></div>
                                
                                <li>
                                    <a href="javascript:;" data_id="${data['AU_ID']}" class="dropdown-item text-danger delete-record">
                                        <i class="fa-solid fa-ban"></i> Delete
                                    </a>
                                </li>
                            </ul>
                        </div>
                    `);
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
                width: "10%"
            }, {
                width: "20%"
            }, {
                width: "20%"
            }, {
                width: "15%"
            }],
            "order": [
                [1, "desc"]
            ],
            destroy: true,
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        header: function(row) {
                            var data = row.data();
                            return 'Details of ' + data['AU_TITLE'];
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

    function resetForm(){
        let id = "submit-form";

        $(`#${id} input`).val('');
        $(`#${id} textarea`).val('');
        $(`#${id} #snow-editor .ql-editor`).html('');
    }

    // Show Add Rate Class Form
    function addForm() {
        resetForm();

        $('#submitBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
        $('#popModalWindowlabel').html('Add App Update');

        $('#popModalWindow').modal('show');
    }

    // Add New or Edit Rate Class submit Function
    function submitForm() {
        hideModalAlerts();
        let id = "submit-form";

        $(`#${id} textarea[name='AU_BODY']`).val($("#snow-editor .ql-editor").html());
        if ($("#snow-editor .ql-editor").html() == "<p><br></p>")
            $(`#${id} textarea[name='AU_BODY']`).val('');

        var fd = new FormData($(`#${id}`)[0]);
        fd.delete('AU_COVER_IMAGE');

        let files = $(`#${id} input[name='AU_COVER_IMAGE']`)[0].files;
        if (files.length)
            fd.append('AU_COVER_IMAGE', files[0]);

        $.ajax({
            url: '<?= base_url('/app-update/store') ?>',
            type: "post",
            data: fd,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response['SUCCESS'] != 200) {

                    var ERROR = response['RESPONSE']['REPORT_RES'];
                    var mcontent = '';
                    $.each(ERROR, function(ind, data) {
                        
                        mcontent += '<li>' + data + '</li>';
                    });
                    showModalAlert('error', mcontent);
                } else {
                    var alertText = response['RESPONSE']['REPORT_RES']['msg'];

                    showModalAlert('success', alertText);

                    $('#popModalWindow').modal('hide');
                    $('#dataTable_view').dataTable().fnDraw();
                }
            }
        });
    }

    // Show Edit Rate Class Form
    $(document).on('click', '.editWindow', function() {
        resetForm();

        $('.dtr-bs-modal').modal('hide');
        var app_update_id = $(this).attr('data_id');

        let id = "submit-form";
        $(`#${id} input[name='id']`).val(app_update_id);

        $('#popModalWindowlabel').html('Edit App Update');
        $('#popModalWindow').modal('show');

        var url = '<?php echo base_url('/app-update/edit') ?>';
        $.ajax({
            url: url,
            type: "post",
            data: {
                id: app_update_id
            },
            dataType: 'json',
            success: function(respn) {
                $(respn).each(function(inx, data) {
                    

                    $.each(data, function(field, val) {

                        if ($(`#${id} input[name='${field}'][type!='file']`).length)
                            $(`#${id} input[name='${field}']`).val(val);

                        else if ($(`#${id} textarea[name='${field}']`).length)
                            $(`#${id} textarea[name='${field}']`).val(val);

                        if(field == 'AU_BODY')
                            $("#snow-editor .ql-editor").html(val);

                    });
                });

                $('#submitBtn').removeClass('btn-primary').addClass('btn-success').text('Update');
            }
        });
    });

    // Delete Rate Class
    $(document).on('click', '.delete-record', function() {
        hideModalAlerts();
        $('.dtr-bs-modal').modal('hide');

        var id = $(this).attr('data_id');
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
                        url: '<?php echo base_url('/app-update/delete') ?>',
                        type: "post",
                        data: {
                            id: id,
                            '_method': 'delete'
                        },
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response['SUCCESS'] != 200) {
                                showModalAlert('error', response['RESPONSE']['REPORT_RES']['msg']);
                            } else {
                                showModalAlert('success', response['RESPONSE']['REPORT_RES']['msg']);

                                $('#dataTable_view').dataTable().fnDraw();
                            }
                        }
                    });
                }
            }
        });
    });

    // bootstrap-maxlength & repeater (jquery)
    $(function() {
        var maxlengthInput = $('.bootstrap-maxlength'),
            formRepeater = $('.form-repeater');

        // Bootstrap Max Length
        // --------------------------------------------------------------------
        if (maxlengthInput.length) {
            /*maxlengthInput.each(function () {
              $(this).maxlength({
                warningClass: 'label label-success bg-success text-white',
                limitReachedClass: 'label label-danger',
                separator: ' out of ',
                preText: 'You typed ',
                postText: ' chars available.',
                validate: true,
                threshold: +this.getAttribute('maxlength')
              });
            });*/
        }

        // Form Repeater
        // ! Using jQuery each loop to add dynamic id and class for inputs. You may need to improve it based on form fields.
        // -----------------------------------------------------------------------------------------------------------------

        if (formRepeater.length) {
            var row = 2;
            var col = 1;
            formRepeater.on('submit', function(e) {
                e.preventDefault();
            });
            formRepeater.repeater({
                show: function() {
                    var fromControl = $(this).find('.form-control, .form-select');
                    var formLabel = $(this).find('.form-label');

                    fromControl.each(function(i) {
                        var id = 'form-repeater-' + row + '-' + col;
                        $(fromControl[i]).attr('id', id);
                        $(formLabel[i]).attr('for', id);
                        col++;
                    });

                    row++;

                    $(this).slideDown();
                },
                hide: function(e) {
                    confirm('Are you sure you want to delete this element?') && $(this).slideUp(e);
                },
                isFirstItemUndeletable: true

            });
        }
    });
</script>

<?= $this->endSection() ?>