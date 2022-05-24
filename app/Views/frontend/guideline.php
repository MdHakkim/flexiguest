<?= $this->extend("Layout/AppView") ?>
<?= $this->section("contentRender") ?>
<?= $this->include('Layout/ErrorReport') ?>
<?= $this->include('Layout/SuccessReport') ?>

<style>
    .optional-files .image, .optional-files .file{
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
    .optional-files .file{
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .optional-files .file a{
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
                            <input type="hidden" name="id" id="guideline-id" class="form-control" />

                            <div class="col-md-6">

                                <lable class="form-lable"><b>Title *</b></lable>

                                <input type="text" name="title" class="form-control bootstrap-maxlength" maxlength="10" placeholder="Title" required />
                            </div>

                            <div class="col-md-6">

                                <lable class="form-lable"><b>Cover Image *</b></lable>

                                <input type="file" name="cover_image" class="form-control bootstrap-maxlength" required />
                            </div>

                            <div class="col-md-12">

                                <lable class="form-lable"><b>Description *</b></lable>

                                <textarea type="number" name="description" class="form-control" placeholder="Description..."></textarea>
                            </div>

                            <div class="col-md-12">
                                <lable class="form-lable"><b>Optional Files</b></lable>

                                <input type="file" name="files[]" class="form-control bootstrap-maxlength" multiple />
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

                                <lable class="form-lable"><b>Body *</b></lable>

                                <textarea name="body" class="d-none"></textarea>

                                <div id="snow-editor"></div>
                            </div>

                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>

                    <button type="button" id="submitBtn" onClick="submitForm('submit-form')" class="btn btn-primary">
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
                    data: 'title'
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return (
                            `
                                <img src='${data['cover_image']}' width='80' height='80'/>
                            `
                        );
                    }
                },
                {
                    data: 'description'
                },
                {
                    data: 'body'
                },
                {
                    data: 'created_at'
                },
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        let html = `<div class="optional-files"> <div class="row">`;

                        $.each(data['guideline_files'], function(key, file) {
                            if(file['file_type'].includes('image'))
                                html += `
                                    <div class="col-sm-4 col-md-3 col-lg-2 image">
                                        <img src="${file['file_url']}"/>
                                    </div>
                                `;
                            else
                                html += `
                                    <div class="col-sm-4 col-md-3 col-lg-2 file">
                                        <a href="<?=base_url()?>/${file['file_url']}" target="_blank">
                                            <i class="fa-solid fa-file fa-4x"></i>
                                            
                                            <span>${file['file_name']}</span>
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
                                    <a href="javascript:;" data_id="${data['id']}" class="dropdown-item editWindow text-primary">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </a>
                                </li>

                                <div class="dropdown-divider"></div>
                                
                                <li>
                                    <a href="javascript:;" data_id="${data['id']}" class="dropdown-item text-danger delete-record">
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
            }
            , {
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

    // Show Add Rate Class Form

    function addForm() {
        $(`#submit-form input[name='id']`).val('');
        $(`#submit-form input[name='title']`).val('');
        $(`#submit-form input[type='file']`).val('');
        $(`#submit-form textarea[name='description']`).val('');
        $(`#submit-form textarea[name='body']`).val('');
        $(`#submit-form #snow-editor .ql-editor`).html('');

        $('#submitBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
        $('#popModalWindowlabel').html('Add Guideline');

        $('#popModalWindow').modal('show');
    }

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
                        success: function(respn) {
                            showModalAlert('warning',
                                '<li>The Guideline has been deleted</li>');
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


    // Show Edit Rate Class Form

    $(document).on('click', '.editWindow', function() {
        $(`#submit-form input[type='file']`).val('');

        $('.dtr-bs-modal').modal('hide');

        var id = $(this).attr('data_id');
        $('#popModalWindowlabel').html('Edit Guideline');
        $('#popModalWindow').modal('show');

        var url = '<?php echo base_url('/guideline/edit') ?>';
        $.ajax({
            url: url,
            type: "post",
            data: {
                id: id
            },
            dataType: 'json',
            success: function(respn) {
                $(respn).each(function(inx, data) {
                    console.log(data);

                    $.each(data, function(field, val) {

                        if ($(`#submit-form input[name='${field}'][type!='file']`).length)
                            $(`#submit-form input[name='${field}']`).val(val);

                        else if ($(`#submit-form textarea[name='${field}']`).length)
                            $(`#submit-form textarea[name='${field}']`).val(val);

                        if(field == 'body')
                            $("#snow-editor .ql-editor").html(val);
                        
                        if(field == 'guideline_files' && val.length){
                            let optional_files_html = "";

                            $.each(val, function(field, file){
                                if(file.file_type.includes('image')){
                                    optional_files_html += `
                                        <div class="col-sm-4 col-md-3 col-lg-2 image">
                                            <img src="<?=base_url()?>/${file.file_url}"/>

                                            <i onClick="deleteOptionalFile(this, ${file.id})" class="fa-solid fa-circle-minus delete-icon"></i>
                                        </div>`;
                                }
                                else{
                                    optional_files_html += `
                                        <div class="col-sm-3 col-md-2 col-lg-1 file">
                                            <a href="<?=base_url()?>/${file.file_url}" target="_blank">
                                                <i class="fa-solid fa-file fa-4x"></i>
                                                ${file.file_name}
                                            </a>

                                            <i onClick="deleteOptionalFile(this, ${file.id})" class="fa-solid fa-circle-minus delete-icon"></i>
                                        </div>`;
                                }
                            });

                            $("#submit-form .optional-files .row").html(optional_files_html);
                        }

                    });
                });

                $('#submitBtn').removeClass('btn-primary').addClass('btn-success').text('Update');
            }
        });
    });

    function deleteOptionalFile(e, file_id){
        $.ajax({
            url: '<?= base_url('/guideline/delete-optional-file') ?>',
            type: "post",
            data: { file_id },
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


    // Show Copy Rate Class Form

    $(document).on('click', '.copyWindow', function() {

        $('.dtr-bs-modal').modal('hide');

        var sysid = $(this).attr('data_sysid');
        var rtcode = $(this).attr('data_rtcode');

        $('#main_RT_CL_ID').val(sysid);

        $('#copyModalWindowlabel').html('Create Rate Class Copies of \'' + rtcode + '\'');

        //Reset repeated fields every time modal is loaded
        $('[data-repeater-item]').slice(1).empty();
        $('#form-repeater-1-1').val("");

        $('#copyModalWindow').modal('show');

    });


    // Add New or Edit Rate Class submit Function

    function submitForm(id) {
        hideModalAlerts();

        if($("#snow-editor .ql-editor").html() != "<p><br></p>")
            $(`#${id} textarea[name='body']`).val($("#snow-editor .ql-editor").html());

        var fd = new FormData($(`#${id}`)[0]);
        fd.delete('cover_image');
        fd.delete('files[]');

        files = $(`#${id} input[name='cover_image']`)[0].files;
        if (files.length)
            fd.append('cover_image', files[0]);

        files = $(`#${id} input[name='files[]']`)[0].files;
        for(let i = 0; i < files.length; i++)
            fd.append('files[]', files[i]);

        $.ajax({
            url: '<?= base_url('/guideline/store') ?>',
            type: "post",
            data: fd,
            processData: false,
            contentType: false,
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

                    $('#popModalWindow').modal('hide');
                    $('#dataTable_view').dataTable().fnDraw();
                }
            }
        });
    }

    // Copy Rate Class to Multiple submit Function

    function copyForm(id) {
        hideModalAlerts();

        var formSerialization = $('#' + id).serializeArray();
        var url = '<?php echo base_url('/copyRateClass') ?>';
        $.ajax({
            url: url,
            type: "post",
            data: formSerialization,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            dataType: 'json',
            success: function(response) {
                if (response == '0') {
                    showModalAlert('error', '<li>No New Rate Classes were added</li>');
                } else {
                    showModalAlert('success', '<li>' + response +
                        ' new Rate Class copies have been created</li>');
                    $('#copyModalWindow').modal('hide');
                    $('#dataTable_view').dataTable().fnDraw();
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