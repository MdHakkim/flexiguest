<?= $this->extend("Layout/AppView") ?>
<?= $this->section("contentRender") ?>
<?= $this->include('Layout/ErrorReport') ?>
<?= $this->include('Layout/SuccessReport') ?>

<style>
    .optional-files .image,
    .optional-files .file {
        position: relative;
        height: 100px;
        margin-bottom: 10px;
        width: 100px;
    }

    .optional-files .image img {
        width: 100%;
        height: 100%;
    }

    .optional-files .delete-icon {
        position: absolute;
        color: red;
        right: 5px;
        top: -8px;
        cursor: pointer;
    }

    .optional-files .file {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .optional-files .file a {
        display: flex;
        flex-direction: column;
        justify-content: space-evenly;
        height: 100%;
        overflow: hidden;
    }
</style>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Masters /</span> Guidelines</h4>

        <!-- DataTable with Buttons -->
        <div class="card">
            <!-- <h5 class="card-header">Responsive Datatable</h5> -->
            <div class="container-fluid table-responsive" style="padding: 16px 16px 6px 16px">
                <table id="dataTable_view" class="dt-responsive table table-striped display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Title</th>
                            <th>Cover Image</th>
                            <th>Description</th>
                            <th>Body</th>
                            <th>Created At</th>
                            <th>Optional Files</th>
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
                    <h4 class="modal-title" id="popModalWindowlabel">Add Guideline</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="submit-form" class="needs-validation" novalidate>
                        <div class="row g-3">
                            <input type="hidden" name="id" class="form-control" />

                            <div class="col-md-6">

                                <label class="form-label"><b>Title *</b></label>

                                <input type="text" name="GL_TITLE" class="form-control" placeholder="Title" required />
                            </div>

                            <div class="col-md-6">

                                <label class="form-label"><b>Cover Image *</b></label>

                                <input type="file" name="GL_COVER_IMAGE" class="form-control" required />
                            </div>

                            <div class="col-md-12">

                                <label class="form-label"><b>Description *</b></label>

                                <textarea type="number" name="GL_DESCRIPTION" class="form-control" placeholder="Description..."></textarea>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label"><b>Optional Files</b></label>

                                <input type="file" name="GL_FILES[]" class="form-control" multiple />
                            </div>

                            <div class="col-md-12 optional-files">
                                <div class="row">
                                    <!-- <div class="col-sm-4 col-md-3 col-lg-2 image">
                                        <img src="http://localhost/FlexiGuest/assets/Uploads/guidelines/files/1653303736-2.jpg"/>
                                        <i class="fa-solid fa-circle-minus delete-icon"></i>
                                    </div>
                                    
                                    <div class="col-sm-3 col-md-2 col-lg-1 file">
                                        <a href="http://localhost/FlexiGuest/assets/Uploads/guidelines/files/1653303736-2.jpg" target="_blank">
                                            <i class="fa-solid fa-file fa-4x"></i>
                                            Open File
                                        </a>

                                        <i class="fa-solid fa-circle-minus delete-icon"></i>
                                    </div> -->
                                </div>
                            </div>

                            <div class="col-md-12">

                                <label class="form-label"><b>Body *</b></label>

                                <textarea name="GL_BODY" class="d-none"></textarea>

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
                'url': '<?php echo base_url('/guideline/all-guidelines') ?>'
            },
            'columns': [{
                    data: ''
                },
                {
                    data: 'GL_TITLE'
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return (
                            `
                                <img src='${data['GL_COVER_IMAGE']}' width='80' height='80'/>
                            `
                        );
                    }
                },
                {
                    data: 'GL_DESCRIPTION'
                },
                {
                    data: 'GL_BODY'
                },
                {
                    data: 'GL_CREATED_AT'
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        let html = `<div class="optional-files"> <div class="row">`;

                        $.each(data['GUIDELINE_FILES'], function(key, file) {
                            if (file['GLF_FILE_TYPE'].includes('image'))
                                html += `
                                    <div class="col-sm-4 col-md-3 col-lg-2 image">
                                        <img src="${file['GLF_FILE_URL']}"/>
                                    </div>
                                `;
                            else
                                html += `
                                    <div class="col-sm-4 col-md-3 col-lg-2 file">
                                        <a href="<?= base_url() ?>/${file['GLF_FILE_URL']}" target="_blank">
                                            <i class="fa-solid fa-file fa-4x"></i>
                                            
                                            <span>${file['GLF_FILE_NAME']}</span>
                                        </a>
                                    </div>`;
                        });

                        return html + `</div></div>`;
                    }
                },
                {
                    data: null,
                    "orderable": false,
                    render: function(data, type, row, meta) {
                        return (
                            `
                        <div class="d-inline-block">
                            <a href="javascript:;" title="Edit or Delete" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a href="javascript:;" data_id="${data['GL_ID']}" class="dropdown-item editWindow text-primary">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </a>
                                </li>

                                <div class="dropdown-divider"></div>
                                
                                <li>
                                    <a href="javascript:;" data_id="${data['GL_ID']}" class="dropdown-item text-danger delete-record">
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
                width: "13%"
            }, {
                width: "10%"
            }, {
                width: "10%"
            }, {
                width: "5%"
            }, {
                width: "45%"
            }, {
                width: "5%"
            }],
            "order": [
                [5, "desc"]
            ],
            destroy: true,
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        header: function(row) {
                            var data = row.data();
                            return 'Details of ' + data['title'];
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

    function resetForm() {
        let id = "submit-form";

        $(`#${id} input`).val('');
        $(`#${id} textarea`).val('');
        $(`#${id} #snow-editor .ql-editor`).html('');
        $(`#${id} .optional-files .row`).html('');
    }

    // Show Add Rate Class Form
    function addForm() {
        resetForm();

        $('#submitBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
        $('#popModalWindowlabel').html('Add Guideline');

        $('#popModalWindow').modal('show');
    }

    // Add New or Edit Rate Class submit Function
    function submitForm() {
        hideModalAlerts();
        let id = "submit-form";

        if ($("#snow-editor .ql-editor").html() != "<p><br></p>")
            $(`#${id} textarea[name='GL_BODY']`).val($("#snow-editor .ql-editor").html());

        var fd = new FormData($(`#${id}`)[0]);
        fd.delete('GL_COVER_IMAGE');
        fd.delete('GL_FILES[]');

        files = $(`#${id} input[name='GL_COVER_IMAGE']`)[0].files;
        if (files.length)
            fd.append('GL_COVER_IMAGE', files[0]);

        files = $(`#${id} input[name='GL_FILES[]']`)[0].files;
        for (let i = 0; i < files.length; i++)
            fd.append('GL_FILES[]', files[i]);

        $.ajax({
            url: '<?= base_url('/guideline/store') ?>',
            type: "post",
            data: fd,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {

                if (response['SUCCESS'] != '200') {

                    var ERROR = response['RESPONSE']['REPORT_RES'];
                    var mcontent = '';
                    $.each(ERROR, function(ind, data) {
                        console.log(data, "SDF");
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
        var guideline_id = $(this).attr('data_id');

        let id = "submit-form";
        $(`#${id} input[name='id']`).val(guideline_id);

        $('#popModalWindowlabel').html('Edit Guideline');
        $('#popModalWindow').modal('show');

        var url = '<?php echo base_url('/guideline/edit') ?>';
        $.ajax({
            url: url,
            type: "post",
            data: {
                id: guideline_id
            },
            dataType: 'json',
            success: function(respn) {
                $(respn).each(function(inx, data) {
                    console.log(data);

                    $.each(data, function(field, val) {

                        if ($(`#${id} input[name='${field}'][type!='file']`).length)
                            $(`#${id} input[name='${field}']`).val(val);

                        else if ($(`#${id} textarea[name='${field}']`).length)
                            $(`#${id} textarea[name='${field}']`).val(val);

                        if (field == 'GL_BODY')
                            $("#snow-editor .ql-editor").html(val);

                        if (field == 'GUIDELINE_FILES' && val.length) {
                            let optional_files_html = "";

                            $.each(val, function(field, file) {
                                if (file.GLF_FILE_TYPE.includes('image')) {
                                    optional_files_html += `
                                        <div class="col-sm-4 col-md-3 col-lg-2 image">
                                            <img src="<?= base_url() ?>/${file.GLF_FILE_URL}"/>

                                            <i onClick="deleteOptionalFile(this, ${file.GLF_ID})" class="fa-solid fa-circle-minus delete-icon"></i>
                                        </div>`;
                                } else {
                                    optional_files_html += `
                                        <div class="col-sm-3 col-md-2 col-lg-1 file">
                                            <a href="<?= base_url() ?>/${file.GLF_FILE_URL}" target="_blank">
                                                <i class="fa-solid fa-file fa-4x"></i>
                                                ${file.GLF_FILE_NAME}
                                            </a>

                                            <i onClick="deleteOptionalFile(this, ${file.GLF_ID})" class="fa-solid fa-circle-minus delete-icon"></i>
                                        </div>`;
                                }
                            });

                            $(`#${id} .optional-files .row`).html(optional_files_html);
                        }

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
            message: "Are you confirm to delete this record?",
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
                        url: '<?php echo base_url('/guideline/delete') ?>',
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

    function deleteOptionalFile(e, file_id) {
        $.ajax({
            url: '<?= base_url('/guideline/delete-optional-file') ?>',
            type: "post",
            data: {
                file_id
            },
            dataType: 'json',
            success: function(respn) {
                console.log(respn, "testing");
                var response = respn['SUCCESS'];

                if (response != '200') {

                    var ERROR = respn['RESPONSE']['ERROR'];
                    var mcontent = '';
                    $.each(ERROR, function(ind, data) {
                        console.log(data, "SDF");
                        mcontent += '<li>' + data + '</li>';
                    });
                    showModalAlert('error', mcontent);
                } else {

                    var alertText = respn['RESPONSE']['REPORT_RES'];

                    showModalAlert('success', alertText);

                    $(e.parentNode).remove();
                }
            }
        });
    }

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