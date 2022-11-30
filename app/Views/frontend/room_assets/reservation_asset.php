<?= $this->extend("Layout/AppView") ?>
<?= $this->section("contentRender") ?>
<?= $this->include('Layout/ErrorReport') ?>
<?= $this->include('Layout/SuccessReport') ?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Room Assets /</span> Reservation Room Assets</h4>

        <!-- DataTable with Buttons -->
        <div class="card">
            <!-- <h5 class="card-header">Responsive Datatable</h5> -->
            <div class="container-fluid table-responsive" style="padding: 16px 16px 6px 16px">
                <table id="dataTable_view" class="dt-responsive table table-striped display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th>RESERVATION</th>
                            <th>Room No</th>
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
                    <h4 class="modal-title" id="popModalWindowlabel">Asset</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="submit-form" class="needs-validation" novalidate>
                        <div class="row g-3">
                            <!-- <input type="hidden" name="RA_ID" class="form-control" /> -->

                            <div class="col-md-6">
                                <b>Reservation# : </b>
                                <span class="reservation-id"></span>
                            </div>

                            <div class="col-md-6">
                                <b>Room# : </b>
                                <span class="room-no"></span>
                            </div>

                            <div class="col-md-12 assets">
                                <h5>Selected Assets</h5>

                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Asset</th>
                                            <th scope="col">Quantity</th>
                                            <th scope="col">Remarks</th>
                                            <th scope="col">Tracking Remarks</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- <tr>
                                            <th scope="row">1</th>
                                            <td>Mark</td>
                                            <td>Otto</td>
                                            <td>@mdo</td>
                                            <td>@mdo</td>
                                            <td>@mdo</td>
                                        </tr> -->
                                    </tbody>
                                </table>
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

    var selected_asset_ids = [];
    var selected_assets = [];

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
                'url': '<?php echo base_url('asset/reservation-asset/all-reservation-assets') ?>'
            },
            'columns': [{
                    data: ''
                },
                {
                    data: 'RRA_RESERVATION_ID'
                },
                {
                    data: 'RM_NO'
                },
                {
                    data: 'RRA_CREATED_AT'
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
                                    <a href="javascript:;"
                                        data-room_id="${data['RRA_ROOM_ID']}"
                                        data-room_no="${data['RM_NO']}"
                                        data-reservation_id='${data['RRA_RESERVATION_ID']}'
                                        data-assets='${data['assets']}'
                                        class="dropdown-item editWindow text-primary">
                                        
                                        <i class="fa-solid fa-pen-to-square"></i> View
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
                },
                // {
                //     width: "15%"
                // }, {
                //     width: "10%"
                // }, {
                //     width: "20%"
                // }, {
                //     width: "20%"
                // }, {
                //     width: "15%"
                // }
            ],
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
                            return 'Details of ' + data['RRA_RESERVATION_ID'];
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

    // Show Add Rate Class Form
    function addForm() {
        resetForm();
        $('#submitBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
        $('#popModalWindowlabel').html('Add Room Asset');

        $('#popModalWindow').modal('show');
    }

    function resetForm() {
        let id = "submit-form";

        $(`#${id} input`).val('');
        $(`#${id} select`).val('').trigger('change');
    }

    // Add New or Edit Rate Class submit Function
    function submitForm() {
        hideModalAlerts();
        let id = "submit-form";

        var fd = new FormData($(`#${id}`)[0]);

        $.ajax({
            url: '<?= base_url('asset/room-asset/store') ?>',
            type: "post",
            data: fd,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                var mcontent = '';
                $.each(response['RESPONSE']['REPORT_RES'], function(ind, data) {
                    mcontent += '<li>' + data + '</li>';
                });

                if (response['SUCCESS'] != 200)
                    showModalAlert('error', mcontent);
                else {
                    showModalAlert('success', mcontent);
                    $('#popModalWindow').modal('hide');
                    $('#dataTable_view').dataTable().fnDraw();
                }
            }
        });
    }

    // Show Edit Rate Class Form
    $(document).on('click', '.editWindow', function() {
        resetForm();

        let room_id = $(this).data('room_id');
        let room_no = $(this).data('room_no');
        let reservation_id = $(this).data('reservation_id');
        let assets = $(this).data('assets');

        $('.dtr-bs-modal').modal('hide');
        let id = "submit-form";

        $(`${form_id} .reservation-id`).html(reservation_id);
        $(`${form_id} .room-no`).html(room_no);

        let html = '';
        $.each(assets, function(index, item) {
            html += `
                <tr>
                    <input type='hidden' name='reservation_assets[]' value='${item.id}'/>
                    <th scope="row">${index+1}</th>
                    <td>${item.asset}</td>
                    <td>${item.quantity}</td>
                    <td>${item.remarks || ''}</td>
                    <td>${item.tracking_remarks || ''}</td>
                    <td>${item.status}</td>
                </tr>`;
        });

        $(`${form_id} .assets tbody`).html(html);

        $('#popModalWindowlabel').html('Reservation Room Assets');
        $('#popModalWindow').modal('show');
    });

    // Delete Rate Class
    // $(document).on('click', '.delete-record', function() {
    //     hideModalAlerts();
    //     $('.dtr-bs-modal').modal('hide');

    //     var id = $(this).attr('data_id');
    //     bootbox.confirm({
    //         message: "Are you sure you want to delete this record?",
    //         buttons: {
    //             confirm: {
    //                 label: 'Yes',
    //                 className: 'btn-success'
    //             },
    //             cancel: {
    //                 label: 'No',
    //                 className: 'btn-danger'
    //             }
    //         },
    //         callback: function(result) {
    //             if (result) {
    //                 $.ajax({
    //                     url: '<?php echo base_url('asset/asset/delete') ?>',
    //                     type: "post",
    //                     data: {
    //                         id: id,
    //                         '_method': 'delete'
    //                     },
    //                     headers: {
    //                         'X-Requested-With': 'XMLHttpRequest'
    //                     },
    //                     dataType: 'json',
    //                     success: function(response) {
    //                         if (response['SUCCESS'] != 200) {
    //                             showModalAlert('error', response['RESPONSE']['REPORT_RES']['msg']);
    //                         } else {
    //                             showModalAlert('success', response['RESPONSE']['REPORT_RES']['msg']);

    //                             $('#dataTable_view').dataTable().fnDraw();
    //                         }
    //                     }
    //                 });
    //             }
    //         }
    //     });
    // });

    $(`${form_id} [name='RA_ASSET_CATEGORY_ID[]']`).change(function() {
        let category_ids = $(this).val();

        if (category_ids.length)
            $.ajax({
                url: '<?= base_url('/asset/asset/asset-by-categories') ?>',
                type: "post",
                data: {
                    category_ids
                },
                dataType: 'json',
                success: function(response) {
                    if (response['SUCCESS'] == 200) {
                        let assets = response['RESPONSE']['OUTPUT'];

                        let html = '';
                        for (let asset of assets) {
                            html += `
                            <option value="${asset.AS_ID}">${asset.AS_ASSET}</option>
                        `;
                        }

                        $(`${form_id} select[name='RA_ASSETS[][RA_ASSET_ID]']`).html(html).trigger('change');
                    }
                }
            });
        else
            $(`${form_id} select[name='RA_ASSETS[][RA_ASSET_ID]']`).html('').trigger('change');
    });

    function hideSelectedAssets() {
        $(`${form_id} .selected-assets`).addClass('d-none');
    }

    function showSelectedAssets() {
        $(`${form_id} .selected-assets`).removeClass('d-none');
    }

    function updateAssetQuantity(id, quantity) {
        $.each(selected_assets, function(index, asset) {
            if (asset.id == id)
                asset.quantity = quantity;
        });
    }

    function decreaseQuantity(index, id) {
        let el = $(`${form_id} input[name='RA_ASSETS[${index}][RA_QUANTITY]']`)[0];
        let value = parseInt($(el).val());
        if (value > 1) {
            $(el).val(--value);
            updateAssetQuantity(id, value);
        }
    }

    function increaseQuantity(index, id) {
        let el = $(`${form_id} input[name='RA_ASSETS[${index}][RA_QUANTITY]']`)[0];
        let value = parseInt($(el).val());
        $(el).val(++value);
        updateAssetQuantity(id, value);
    }

    $(`${form_id} [name='RA_ASSETS[][RA_ASSET_ID]']`).change(function() {
        let asset_ids = $(this).val();

        if (asset_ids.length == 0) {
            selected_asset_ids = [];
            selected_assets = [];

            hideSelectedAssets();
            return;
        }

        if (asset_ids.length < selected_asset_ids.length) {
            let remove_id = selected_asset_ids.filter(x => !asset_ids.includes(x))[0];

            $.each(selected_assets, function(index, asset) {
                if (asset.id == remove_id) {
                    selected_assets.splice(index, 1);
                    return false;
                }
            });

        } else if (asset_ids.length > selected_asset_ids.length) {
            let assets = $(`${form_id} [name='RA_ASSETS[][RA_ASSET_ID]'] option:selected`);

            $.each(assets, function(index, asset) {
                if (!selected_asset_ids.includes(asset.value))
                    selected_assets.push({
                        id: asset.value,
                        asset: asset.innerText,
                        quantity: 1,
                    });
            });
        }

        selected_asset_ids = asset_ids;

        let html = '';
        $.each(selected_assets, function(index, asset) {
            html += `
                <div class="col-md-6 mb-2">
                    ${asset.asset}
                </div>

                <div class="col-md-6 mb-2">
                    <div class="input-group">
                        <button type="button" onclick="decreaseQuantity(${index}, ${asset.id})" class="btn btn-danger">
                            <i class="fa-solid fa-minus"></i>
                        </button>

                        <input type="text" name="RA_ASSETS[${index}][RA_QUANTITY]" class="form-control text-center" value="${asset.quantity}" readonly />

                        <button type="button" onclick="increaseQuantity(${index}, ${asset.id})" class="btn btn-success">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                </div>
            `;
        });

        $(`${form_id} .selected-assets .assets`).html(html);
        showSelectedAssets();
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