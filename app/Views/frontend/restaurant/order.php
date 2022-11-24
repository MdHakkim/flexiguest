<?= $this->extend("Layout/AppView") ?>
<?= $this->section("contentRender") ?>
<?= $this->include('Layout/ErrorReport') ?>
<?= $this->include('Layout/SuccessReport') ?>
<?= $this->include('Layout/image_modal') ?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Masters /</span> Orders</h4>

        <!-- DataTable with Buttons -->
        <div class="card">
            <!-- <h5 class="card-header">Responsive Datatable</h5> -->
            <div class="container-fluid table-responsive" style="padding: 16px 16px 6px 16px">
                <table id="dataTable_view" class="dt-responsive table table-striped display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th class="all">Room</th>
                            <th class="all">Guest Name</th>
                            <th>Reservation</th>
                            <th>Delivery Status</th>
                            <th>Payment Status</th>
                            <th>Payment Method</th>
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
                            <input type="hidden" name="RO_ID" class="form-control" />
                            <input type="hidden" name="RO_CUSTOMER_ID" class="form-control" />
                            <input type="hidden" name="RO_ROOM_ID" class="form-control" />

                            <div class="col-md-6">
                                <label class="form-label"><b>Reservation *</b></label>
                                <select class="select2" name="RO_RESERVATION_ID" onchange="onChangeReservation()">
                                    <option value="">Select Reservation</option>
                                    <?php foreach ($reservations as $reservation) : ?>
                                        <option value="<?= $reservation['RESV_ID'] ?>" data-customer_id="<?= $reservation['CUST_ID'] ?>" data-customer_name="<?= $reservation['CUST_FIRST_NAME'] . ' ' . $reservation['CUST_MIDDLE_NAME'] . ' ' . $reservation['CUST_LAST_NAME'] ?>" data-room_no="<?= $reservation['RESV_ROOM'] ?>" data-room_id="<?= $reservation['RM_ID'] ?>">

                                            RES<?= $reservation['RESV_ID'] ?>
                                            -
                                            <?= $reservation['CUST_FIRST_NAME'] . ' ' . $reservation['CUST_LAST_NAME'] ?>
                                            -
                                            <?= $reservation['CUST_ID'] ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label"><b>Room No.</b></label>
                                <input type="text" name="RO_ROOM_NO" class="form-control" placeholder="Room No" readonly />
                            </div>

                            <div class="col-md-4">
                                <label class="form-label"><b>Restaurants *</b></label>
                                <select name="RO_RESTAURANT_IDS[]" class="select2 form-select" multiple>
                                    <?php foreach ($restaurants as $restaurant) { ?>
                                        <option value="<?= $restaurant['RE_ID'] ?>"><?= $restaurant['RE_RESTAURANT'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label"><b>Category *</b></label>
                                <select name="RO_MENU_CATEGORY_IDS[]" class="select2 form-select" multiple>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label"><b>Meal Type *</b></label>
                                <select name="RO_MEAL_TYPE_IDS[]" class="select2 form-select" multiple>
                                    <?php foreach ($meal_types as $meal_type) { ?>
                                        <option value="<?= $meal_type['MT_ID'] ?>"><?= $meal_type['MT_TYPE'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label"><b>Items *</b></label>
                                <select name="RO_ITEMS[][MI_ID]" class="select2 form-select" multiple>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label"><b>Total Payable *</b></label>
                                <input type="number" name="RO_TOTAL_PAYABLE" class="form-control" required />
                            </div>

                            <div class="col-md-12 selected-items">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5>Selected Items</h5>
                                        <div class="row items"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label"><b>Department</b></label>
                                <select name="RO_DEPARTMENT_ID" class="select2 form-select">
                                    <?php foreach ($departments as $department) { ?>
                                        <option value="<?= $department['DEPT_ID'] ?>"><?= $department['DEPT_DESC'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label"><b>Attendee</b></label>
                                <select name="RO_ATTENDANT_ID" class="select2 form-select">
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label"><b>Delivery Status</b></label>
                                <select class="select2" name="RO_DELIVERY_STATUS">
                                    <option>New</option>
                                    <option>Processing</option>
                                    <option>Delivered</option>
                                    <option>Cancelled</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label"><b>Payment Method</b></label>
                                <select class="select2" name="RO_PAYMENT_METHOD">
                                    <option>Pay at Reception</option>
                                    <option>Samsung Pay</option>
                                    <option>Credit/Debit card</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label"><b>Payment Status</b></label>
                                <select class="select2" name="RO_PAYMENT_STATUS">
                                    <option>UnPaid</option>
                                    <option>Paid</option>
                                </select>
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
    var selected_items = [];
    var selected_item_ids = [];

    function hideSelectedItems() {
        $('.selected-items').hide();
    }

    function showSelectedItems() {
        $('.selected-items').show();
    }

    var form_id = "#submit-form";

    var compAgntMode = '';
    var linkMode = '';

    $(document).ready(function() {
        hideSelectedItems();

        $(form_id).submit(function(e) {
            e.preventDefault();
        });


        linkMode = 'EX';

        $('#dataTable_view').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                'url': '<?php echo base_url('/restaurant/order/all-order') ?>'
            },
            'columns': [{
                    data: ''
                },
                {
                    data: 'RO_ID'
                },
                {
                    data: 'RM_NO'
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return `${data['CUST_FIRST_NAME']} ${data['CUST_LAST_NAME']}`;
                    }
                },
                {
                    data: 'RO_RESERVATION_ID'
                },
                {
                    data: 'RO_DELIVERY_STATUS'
                },
                {
                    data: 'RO_PAYMENT_STATUS'
                },
                {
                    data: 'RO_PAYMENT_METHOD'
                },
                {
                    data: 'RO_CREATED_AT'
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
                                    <a href="javascript:;" data_id="${data['RO_ID']}" class="dropdown-item editWindow text-primary">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </a>
                                </li>

                                <div class="dropdown-divider"></div>
                                
                                <li>
                                    <a href="javascript:;" data_id="${data['RO_ID']}" class="dropdown-item text-danger delete-record">
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
                            return 'Details of ' + data['RO_ID'];
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

        $(`${form_id} select[name='RO_DELIVERY_STATUS']`).val('New').trigger('change');
        $(`${form_id} select[name='RO_PAYMENT_METHOD']`).val('Pay at Reception').trigger('change');
        $(`${form_id} select[name='RO_PAYMENT_STATUS']`).val('UnPaid').trigger('change');
        $(`${form_id} [name='RO_TOTAL_PAYABLE']`).val(0);
    }

    // Show Add Rate Class Form
    function addForm() {
        resetForm();

        $('#submitBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
        $('#popModalWindowlabel').html('Add Order');

        $('#popModalWindow').modal('show');
    }

    // Add New or Edit Rate Class submit Function
    function submitForm() {
        hideModalAlerts();
        var fd = new FormData($(`${form_id}`)[0]);

        $.ajax({
            url: '<?= base_url('/restaurant/order/place-order') ?>',
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

        var order_id = $(this).attr('data_id');
        $(`${form_id} input[name='RO_ID']`).val(order_id);

        $('#popModalWindowlabel').html('Edit Menu Item');
        $('#popModalWindow').modal('show');

        var url = '<?php echo base_url('/restaurant/order/edit-order') ?>';
        $.ajax({
            url: url,
            type: "post",
            data: {
                id: order_id
            },
            dataType: 'json',
            success: function(response) {
                let data = response.RESPONSE.OUTPUT;

                if (data) {
                    $(`${form_id} select[name='RO_RESERVATION_ID']`).val(data.RO_RESERVATION_ID).trigger('change');
                    $(`${form_id} select[name='RO_RESTAURANT_IDS[]']`).val(data.restaurant_ids).trigger('change');
                    $(`${form_id} select[name='RO_MEAL_TYPE_IDS[]']`).val(data.meal_type_ids).trigger('change');

                    $(`${form_id} select[name='RO_DELIVERY_STATUS']`).val(data.RO_DELIVERY_STATUS).trigger('change');
                    $(`${form_id} select[name='RO_PAYMENT_METHOD']`).val(data.RO_PAYMENT_METHOD).trigger('change');
                    $(`${form_id} select[name='RO_PAYMENT_STATUS']`).val(data.RO_PAYMENT_STATUS).trigger('change');

                    setTimeout(function() {
                        $(`${form_id} select[name='RO_MENU_CATEGORY_IDS[]']`).val(data.category_ids).trigger('change');
                        $(`${form_id} select[name='RO_DEPARTMENT_ID']`).val(data.RO_DEPARTMENT_ID).trigger('change');

                        setTimeout(function() {
                            selected_item_ids = data.selected_item_ids;
                            selected_items = data.selected_items;

                            $(`${form_id} select[name='RO_ITEMS[][MI_ID]']`).val(data.selected_item_ids).trigger('change');
                            $(`${form_id} select[name='RO_ATTENDANT_ID']`).val(data.RO_ATTENDANT_ID).trigger('change');
                        }, 1000);
                    }, 500);


                }

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
                        url: '<?php echo base_url('/restaurant/order/delete-order') ?>',
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

    $(`${form_id} [name='RO_DEPARTMENT_ID']`).change(function() {
        let department_id = $(this).val();

        if (department_id) {
            $.ajax({
                url: '<?= base_url('/user-by-department') ?>',
                type: "post",
                data: {
                    department_ids: [department_id]
                },
                dataType: 'json',
                success: function(response) {
                    if (response['SUCCESS'] == 200) {
                        let users = response['RESPONSE']['OUTPUT'];

                        let html = '';
                        for (let user of users) {
                            html += `
                            <option value="${user.USR_ID}">${user.USR_FIRST_NAME} ${user.USR_LAST_NAME}</option>
                        `;
                        }

                        $(`${form_id} select[name='RO_ATTENDANT_ID']`).html(html);
                        $(`${form_id} select[name='RO_ATTENDANT_ID']`).trigger('change');
                    }
                }
            });
        }
    });

    $(`${form_id} [name='RO_RESTAURANT_IDS[]']`).change(function() {
        let restaurant_ids = $(this).val();

        if (restaurant_ids.length) {
            $.ajax({
                url: '<?= base_url('restaurant/menu-categories-by-restaurant') ?>',
                type: "post",
                data: {
                    restaurant_ids
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

                        $(`${form_id} select[name='RO_MENU_CATEGORY_IDS[]']`).html(html);
                        $(`${form_id} select[name='RO_MENU_CATEGORY_IDS[]']`).trigger('change');
                    }
                }
            });
        } else {
            $(`${form_id} select[name='RO_MENU_CATEGORY_IDS[]']`).html('');
            $(`${form_id} select[name='RO_MENU_CATEGORY_IDS[]']`).trigger('change');
        }

    });

    $(`${form_id} [name='RO_MENU_CATEGORY_IDS[]'], ${form_id} [name='RO_MEAL_TYPE_IDS[]']`).change(function() {
        let category_ids = $(`${form_id} [name='RO_MENU_CATEGORY_IDS[]']`).val();
        let meal_type_ids = $(`${form_id} [name='RO_MEAL_TYPE_IDS[]']`).val();

        if (category_ids.length) {
            $.ajax({
                url: '<?= base_url('restaurant/get-menu-items') ?>',
                type: "post",
                data: {
                    category_ids,
                    meal_type_ids
                },
                dataType: 'json',
                success: function(response) {
                    if (response['SUCCESS'] == 200) {
                        let items = response['RESPONSE']['OUTPUT'];

                        let html = '';
                        for (let item of items) {
                            html += `
                            <option value="${item.MI_ID}" data-item="${item.MI_ITEM}" data-price="${item.MI_PRICE}" >${item.MI_ITEM} - AED ${item.MI_PRICE}</option>
                        `;
                        }

                        $(`${form_id} select[name="RO_ITEMS[][MI_ID]"]`).html(html).trigger('change');
                    }
                }
            });
        } else {
            $(`${form_id} select[name="RO_ITEMS[][MI_ID]"]`).html('').trigger('change');
        }
    });

    function calculateTotalPayable() {
        let total_payable = 0;
        let items = $(`${form_id} [name='RO_ITEMS[][MI_ID]'] option:selected`);

        $.each(items, function(index, item) {
            total_payable += parseFloat(item.getAttribute('data-price')) * $(`${form_id} [name='RO_ITEMS[${index}][MI_QUANTITY]']`).val();
        });

        $(`${form_id} [name='RO_TOTAL_PAYABLE']`).val(total_payable);
    }

    $(`${form_id} [name='RO_ITEMS[][MI_ID]']`).change(function() {
        let ids = $(this).val();

        if (ids.length == 0) {
            selected_item_ids = [];
            selected_items = [];
            
            hideSelectedItems();
            return;
        }

        if (ids.length < selected_item_ids.length) {
            let remove_id = selected_item_ids.filter(x => !ids.includes(x))[0];

            $.each(selected_items, function(index, item) {
                if (item.id == remove_id) {
                    selected_items.splice(index, 1);
                    return false;
                }
            });

        } else {
            showSelectedItems();

            let items = $(`${form_id} [name='RO_ITEMS[][MI_ID]'] option:selected`);

            $.each(items, function(index, item) {
                if (!selected_item_ids.includes(item.value))
                    selected_items.push({
                        id: item.value,
                        item: item.getAttribute('data-item'),
                        price: item.getAttribute('data-price'),
                        quantity: 1,
                    });
            });
        }

        selected_item_ids = ids;

        let html = '';
        $.each(selected_items, function(index, item) {
            html += `
                <div class="col-md-6 mb-2">
                    ${item.item}
                </div>

                <div class="col-md-6 mb-2">
                    <div class="input-group">
                        <button type="button" onclick="decreaseQuantity(${index}, ${item.id})" class="btn btn-danger">
                            <i class="fa-solid fa-minus"></i>
                        </button>

                        <input type="text" name="RO_ITEMS[${index}][MI_QUANTITY]" class="form-control text-center" value="${item.quantity}" readonly />

                        <button type="button" onclick="increaseQuantity(${index}, ${item.id})" class="btn btn-success">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                </div>
            `;
        });

        $('.selected-items .items').html(html);
        calculateTotalPayable();
    });

    function updateItemQuantity(id, quantity) {
        $.each(selected_items, function(index, item) {
            if (item.id == id)
                item.quantity = quantity;
        });

        calculateTotalPayable();
    }

    function decreaseQuantity(index, id) {
        let el = $(`${form_id} input[name='RO_ITEMS[${index}][MI_QUANTITY]']`)[0];
        let value = parseInt($(el).val());
        if (value > 1) {
            $(el).val(--value);
            updateItemQuantity(id, value);
        }
    }

    function increaseQuantity(index, id) {
        let el = $(`${form_id} input[name='RO_ITEMS[${index}][MI_QUANTITY]']`)[0];
        let value = parseInt($(el).val());
        $(el).val(++value);
        updateItemQuantity(id, value);
    }

    function onChangeReservation() {
        let customer_id = $(`${form_id} select[name="RO_RESERVATION_ID"]`).find(":selected").data('customer_id');
        // let customer_name = $(`${form_id} select[name="RO_RESERVATION_ID"]`).find(":selected").data('customer_name');
        let room_id = $(`${form_id} select[name="RO_RESERVATION_ID"]`).find(":selected").data('room_id');
        let room_no = $(`${form_id} select[name="RO_RESERVATION_ID"]`).find(":selected").data('room_no');

        $(`${form_id} input[name="RO_CUSTOMER_ID"]`).val(customer_id);
        // $(`${form_id} input[name="RO_GUEST_NAME"]`).val(customer_name);
        $(`${form_id} input[name="RO_ROOM_ID"]`).val(room_id);
        $(`${form_id} input[name="RO_ROOM_NO"]`).val(room_no);
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