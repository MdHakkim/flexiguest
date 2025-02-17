<?= $this->extend("Layout/AppView") ?>
<?= $this->section("contentRender") ?>
<?= $this->include('Layout/ErrorReport') ?>
<?= $this->include('Layout/SuccessReport') ?>
<?= $this->include('Layout/image_modal') ?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Masters /</span> Menu Items</h4>

        <!-- DataTable with Buttons -->
        <div class="card">
            <!-- <h5 class="card-header">Responsive Datatable</h5> -->
            <div class="container-fluid table-responsive" style="padding: 16px 16px 6px 16px">
                <table id="dataTable_view" class="dt-responsive table table-striped display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th>Available</th>
                            <th class="all">Meal Type</th>
                            <th class="all">Item</th>
                            <th>Image</th>
                            <th>Description</th>
                            <th>Category ID</th>
                            <th>Category Name</th>
                            <th>Restaurant ID</th>
                            <th>Restaurant Name</th>
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
                    <h4 class="modal-title" id="popModalWindowlabel">Restuarant</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="submit-form" class="needs-validation" novalidate>
                        <div class="row g-3">
                            <input type="hidden" name="id" class="form-control" />

                            <div class="col-md-4">
                                <label class="form-label"><b>Restaurants *</b></label>
                                <select name="MI_RESTAURANT_ID" class="select2 form-select">
                                    <?php foreach ($restaurants as $restaurant) { ?>
                                        <option value="<?= $restaurant['RE_ID'] ?>"><?= $restaurant['RE_RESTAURANT'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label"><b>Category *</b></label>
                                <select name="MI_MENU_CATEGORY_ID" class="select2 form-select">
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label"><b>Meal Type *</b></label>
                                <select name="MI_MEAL_TYPE_ID" class="select2 form-select">
                                    <?php foreach ($meal_types as $meal_type) { ?>
                                        <option value="<?= $meal_type['MT_ID'] ?>"><?= $meal_type['MT_TYPE'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label"><b>Item *</b></label>
                                <input type="text" name="MI_ITEM" class="form-control" placeholder="Item" required />
                            </div>

                            <div class="col-md-8">
                                <label class="form-label" for="MI_IMAGE_URL"><b>Item Image *</b></label>
                                <input type="file" name="MI_IMAGE_URL" id="MI_IMAGE_URL" class="form-control" />
                            </div>

                            <div class="col-md-4">
                                <label class="form-label"><b>Price *</b></label>
                                <input type="number" name="MI_PRICE" class="form-control" required />
                            </div>

                            <div class="col-md-4">
                                <label class="form-label"><b>Available *</b></label>
                                <select name="MI_IS_AVAILABLE" class="form-select">
                                    <option value="1" selected>Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Sequence</label>
                                <input type="number" name="MI_SEQUENCE" class="form-control" required />
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Description</label>
                                <textarea name="MI_DESCRIPTION" class="form-control" required></textarea>
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
    var form_id = "#submit-form";

    var compAgntMode = '';
    var linkMode = '';

    $(document).ready(function() {
        $(form_id).submit(function(e) {
            e.preventDefault();
        });

        linkMode = 'EX';

        $('#dataTable_view').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                'url': '<?php echo base_url('/restaurant/menu-item/all-menu-item') ?>'
            },
            'columns': [{
                    data: ''
                },
                {
                    data: 'MI_ID'
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        let class_name = 'badge rounded-pill';

                        if (data['MI_IS_AVAILABLE'] == 1) {
                            class_name += ' bg-label-success';
                            data['MI_IS_AVAILABLE'] = 'Yes';
                        } else {
                            class_name += ' bg-label-danger';
                            data['MI_IS_AVAILABLE'] = 'No';
                        }

                        return (`
                            <span class="${class_name}">${data['MI_IS_AVAILABLE']}</span>
                        `);
                    }
                },
                {
                    data: 'MT_TYPE'
                },
                {
                    data: 'MI_ITEM'
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return (`<img onClick='displayImagePopup("<?= base_url() ?>/${data['MI_IMAGE_URL']}")' src='<?= base_url() ?>/${data['MI_IMAGE_URL']}' width='80' height='80'/>`);
                    }
                },
                {
                    data: null,
                    render: function(data) {
                        return data['MI_DESCRIPTION'].substr(0, 30);
                    }
                },
                {
                    data: 'MI_MENU_CATEGORY_ID'
                },
                {
                    data: 'MC_CATEGORY'
                },
                {
                    data: 'MI_RESTAURANT_ID'
                },
                {
                    data: 'RE_RESTAURANT'
                },
                {
                    data: 'MI_CREATED_AT'
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
                                    <a href="javascript:;" data_id="${data['MI_ID']}" data-restaurant_id="${data['MI_RESTAURANT_ID']}" class="dropdown-item editWindow text-primary">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </a>
                                </li>

                                <div class="dropdown-divider"></div>
                                
                                <li>
                                    <a href="javascript:;" data_id="${data['MI_ID']}" class="dropdown-item text-danger delete-record">
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
                            return 'Details of ' + data['MI_ID'];
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

    function resetForm() {
        $(`${form_id} input`).val('');
        $(`${form_id} textarea`).val('');
        $(`${form_id} select`).val('').trigger('change');
        $(`${form_id} select[name='MI_IS_AVAILABLE']`).val('1');
    }

    // Show Add Rate Class Form
    function addForm() {
        resetForm();

        $('#submitBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
        $('#popModalWindowlabel').html('Add Menu Item');

        $('#popModalWindow').modal('show');
    }

    // Add New or Edit Rate Class submit Function
    function submitForm() {
        hideModalAlerts();
        var fd = new FormData($(`${form_id}`)[0]);
        fd.delete('MI_IMAGE_URL');

        let files = $(`${form_id} input[name='MI_IMAGE_URL']`)[0].files;
        if (files.length)
            fd.append('MI_IMAGE_URL', files[0]);

        $.ajax({
            url: '<?= base_url('/restaurant/menu-item/store-menu-item') ?>',
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
        var menu_item_id = $(this).attr('data_id');
        var restaurant_id = $(this).data('restaurant_id');

        $(`${form_id} select[name='MI_RESTAURANT_ID']`).val(restaurant_id).trigger('change');
        $(`${form_id} input[name='id']`).val(menu_item_id);

        $('#popModalWindowlabel').html('Edit Menu Item');
        $('#popModalWindow').modal('show');

        var url = '<?php echo base_url('/restaurant/menu-item/edit-menu-item') ?>';
        $.ajax({
            url: url,
            type: "post",
            data: {
                id: menu_item_id
            },
            dataType: 'json',
            success: function(response) {
                $(response).each(function(inx, data) {
                    $.each(data, function(field, val) {
                        if ($(`${form_id} input[name='${field}'][type!='file']`).length)
                            $(`${form_id} input[name='${field}']`).val(val);

                        else if ($(`${form_id} textarea[name='${field}']`).length)
                            $(`${form_id} textarea[name='${field}']`).val(val);

                        else if (field == 'MI_MENU_CATEGORY_ID')
                            window.setTimeout(function() {
                                $(`${form_id} select[name='${field}']`).val(val).trigger('change');
                            }, 500);

                        else if (field != 'MI_RESTAURANT_ID' && $(`${form_id} select[name='${field}']`).length)
                            $(`${form_id} select[name='${field}']`).val(val).trigger('change');
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
                        url: '<?php echo base_url('/restaurant/menu-item/delete-menu-item') ?>',
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

    $(`${form_id} [name='MI_RESTAURANT_ID']`).change(function() {
        let restaurant_id = $(this).val();

        if (restaurant_id) {
            $.ajax({
                url: '<?= base_url('restaurant/menu-categories-by-restaurant') ?>',
                type: "post",
                data: {
                    restaurant_ids: [restaurant_id]
                },
                dataType: 'json',
                success: function(response) {
                    if (response['SUCCESS'] == 200) {
                        let categories = response['RESPONSE']['OUTPUT'];

                        let html = '';
                        for (let category of categories) {
                            html += `
                            <option value="${category.MC_ID}">${category.MC_CATEGORY}</option>
                        `;
                        }

                        $(`${form_id} select[name='MI_MENU_CATEGORY_ID']`).html(html);
                        $(`${form_id} select[name='MI_MENU_CATEGORY_ID']`).trigger('change');
                    }
                }
            });
        } else {
            $(`${form_id} select[name='MI_MENU_CATEGORY_ID']`).html('');
            $(`${form_id} select[name='MI_MENU_CATEGORY_ID']`).trigger('change');
        }
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