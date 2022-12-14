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
                            <th>Restaurant</th>
                            <th>Order ID</th>
                            <th>Reservation</th>
                            <th>Slot</th>
                            <th>No of Guests</th>
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
                'url': '<?php echo base_url('/restaurant/reservation/all-reservations') ?>'
            },
            'columns': [{
                    data: ''
                },
                {
                    data: 'RR_ID'
                },
                {
                    data: 'RE_RESTAURANT'
                },
                {
                    data: 'RR_ORDER_ID'
                },
                {
                    data: 'RO_RESERVATION_ID'
                },
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return `${data['RRS_FROM_TIME']} ${data['RRS_TO_TIME']}`;
                    }
                },
                {
                    data: 'RR_NO_OF_GUESTS'
                },
                {
                    data: 'RR_CREATED_AT'
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
                            return 'Details of ' + data['RE_ID'];
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