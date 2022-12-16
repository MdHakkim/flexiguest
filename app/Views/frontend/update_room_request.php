<?= $this->extend("Layout/AppView") ?>
<?= $this->section("contentRender") ?>
<?= $this->include('Layout/ErrorReport') ?>
<?= $this->include('Layout/SuccessReport') ?>
<?= $this->include('Layout/image_modal') ?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Restaurant /</span> Reservation</h4>

        <!-- DataTable with Buttons -->
        <div class="card">
            <!-- <h5 class="card-header">Responsive Datatable</h5> -->
            <div class="container-fluid table-responsive" style="padding: 16px 16px 6px 16px">
                <table id="dataTable_view" class="dt-responsive table table-striped display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th>Customer ID</th>
                            <th>Reservation ID</th>
                            <th>Room Type</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <!-- <th class="all">Action</th> -->
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <!--/ Multilingual -->
    </div>
    <!-- / Content -->

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
                'url': '<?php echo base_url('/upgrade-room-request/all-requests') ?>'
            },
            'columns': [{
                    data: ''
                },
                {
                    data: 'URR_ID'
                },
                {
                    data: 'URR_CUSTOMER_ID'
                },
                {
                    data: 'URR_RESERVATION_ID'
                },
                {
                    data: 'RM_TY_DESC'
                },
                {
                    // data: 'URR_STATUS'
                    data: null,
                    render: function(data, type, row, meta) {

                        if (data['URR_STATUS'] == 'Requested') {

                            let html = `
                            <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Requested
                            </button>

                            <ul class="dropdown-menu">
                                <li>
                                    <h6 class="dropdown-header">Change Status</h6>
                                </li>

                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <li>

                                    <a class="dropdown-item change-request-status" data-request_id="${data['URR_ID']}" data-status="Approved" href="javascript:void(0);">
                                        Approve
                                    </a>

                                    <a class="dropdown-item change-request-status" data-request_id="${data['URR_ID']}" data-status="Rejected" href="javascript:void(0);">
                                        Reject
                                    </a>
                                </li>
                            </ul>
                        `;

                            return html;
                        } else {
                            let class_name = 'bg-label-secondary';

                            if (data['URR_STATUS'] == 'Approved')
                                class_name = ' bg-label-success';

                            else if (data['URR_STATUS'] == 'Rejected')
                                class_name = ' bg-label-danger';

                            return (`
                            <span class="badge rounded-pill ${class_name}">${data['URR_STATUS']}</span>
                        `);
                        }
                        return data['URR_STATUS'];
                    }
                },
                {
                    data: 'URR_CREATED_AT'
                },
                // {
                //     data: null,
                //     className: "text-center",
                //     "orderable": false,
                //     render: function(data, type, row, meta) {
                //         return (
                //             `
                //         <div class="d-inline-block">
                //             <a href="javascript:;" title="Edit or Delete" class="btn btn-sm btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                //                 <i class="bx bx-dots-vertical-rounded"></i>
                //             </a>

                //             <ul class="dropdown-menu dropdown-menu-end">
                //                 <li>
                //                     <a href="javascript:;" data_id="${data['RE_ID']}" class="dropdown-item editWindow text-primary">
                //                         <i class="fa-solid fa-pen-to-square"></i> Edit
                //                     </a>
                //                 </li>

                //                 <div class="dropdown-divider"></div>

                //                 <li>
                //                     <a href="javascript:;" data_id="${data['RE_ID']}" class="dropdown-item text-danger delete-record">
                //                         <i class="fa-solid fa-ban"></i> Delete
                //                     </a>
                //                 </li>
                //             </ul>
                //         </div>
                //     `);
                //     }
                // },
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
                            return 'Details of ' + data['URR_ID'];
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

        // $("#dataTable_view_wrapper .row:first").before(
        //     '<div class="row flxi_pad_view"><div class="col-md-3 ps-0"><button type="button" class="btn btn-primary" onClick="addForm()"><i class="fa-solid fa-plus fa-lg"></i> Add</button></div></div>'
        // );

        $(document).on('click', '.change-request-status', function() {
            let request_id = $(this).data('request_id');
            let status = $(this).data('status');

            let fd = new FormData();
            fd.append('URR_ID', request_id);
            fd.append('URR_STATUS', status);

            $.ajax({
                url: '<?= base_url('upgrade-room-request/update-status') ?>',
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

                    if (response['SUCCESS'] != 200) {
                        showModalAlert('error', mcontent);
                    } else {
                        showModalAlert('success', mcontent);

                        $('#popModalWindow').modal('hide');
                        $('#dataTable_view').dataTable().fnDraw();
                    }
                }
            });
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