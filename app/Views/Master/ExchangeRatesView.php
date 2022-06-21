<?= $this->extend("Layout/AppView") ?>
<?= $this->section("contentRender") ?>
<?= $this->include('Layout/ErrorReport') ?>
<?= $this->include('Layout/SuccessReport') ?>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Masters /</span> Exchange Rates
        </h4>

        <!-- DataTable with Buttons -->
        <div class="card">
            <!-- <h5 class="card-header">Responsive Datatable</h5> -->
            <div class="container-fluid table-responsive" style="padding: 16px;">
                <table id="dataTable_view" class="dt-responsive table table-striped display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th class="all">Currency</th>
                            <th>Description</th>
                            <th class="all">Code</th>
                            <th>Exchange</th>
                            <th>Check</th>
                            <th>Settlement</th>
                            <th class="all">Net Buy Rate</th>
                            <th class="all">Begin Date</th>
                            <th>Begin Time</th>
                            <th>Comments</th>
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

    <div class="modal fade" id="popModalWindow" data-backdrop="static" data-keyboard="false" aria-labelledby="popModalWindowlabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="popModalWindowlabel">Transaction Code</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="submitForm" class="needs-validation" novalidate>
                        <div class="row g-3">
                            <input type="hidden" name="EXCH_RATE_ID" id="EXCH_RATE_ID" class="form-control" />

                            <div class="border rounded p-3">

                                <div class="col-md-12">
                                    <div class="row mb-3">
                                        <label for="html5-text-input" class="col-form-label col-md-3"><b>Currency
                                                *</b></label>

                                        <div class="col-md-5">
                                            <select id="EXCH_RATE_CUR_ID" name="EXCH_RATE_CUR_ID" class="select2 form-select form-select-lg" data-allow-clear="true" required>
                                                <?= $currencyLists ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="html5-text-input" class="col-form-label col-md-3"><b>Code
                                                *</b></label>
                                        <div class="col-md-7">

                                            <select id="EXCH_RATE_CODE_ID" name="EXCH_RATE_CODE_ID" class="select2 form-select form-select-lg" data-allow-clear="true" required>
                                                <?= $exchangeCodesLists ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">

                                        <label for="html5-text-input" class="col-form-label col-md-3"><b>Begin Date
                                                *</b></label>
                                        <div class="col-md-5">
                                            <div class="input-group">
                                                <input type="text" id="EXCH_RATE_BEGIN_DATE" name="EXCH_RATE_BEGIN_DATE" class="form-control" placeholder="DD-MM-YYYY">
                                                <span class="input-group-append">
                                                    <span class="input-group-text bg-light d-block">
                                                        <i class="fa fa-calendar"></i>
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">

                                        <label for="html5-text-input" class="col-form-label col-md-3"><b>Begin Time
                                                *</b></label>
                                        <div class="col-md-5">
                                            <div class="input-group">
                                                <input type="time" id="EXCH_RATE_BEGIN_TIME" name="EXCH_RATE_BEGIN_TIME" class="form-control" >

                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="html5-text-input" class="col-form-label col-md-3"><b>Comments</b></label>
                                        <div class="col-md-5">
                                            <div class="input-group">
                                                <textarea class="form-control" name="EXCH_RATE_COMMENTS" id="EXCH_RATE_COMMENTS" rows="1"></textarea>
                                            </div>
                                        </div>                                        
                                    </div>

                                    <div class="row mb-3">
                                        
                                        <div class="col-md-3">
                                            <label for="html5-text-input" class="col-form-label col-md-6"><b>Buy Rate *</b></label><input type="number" name="EXCH_RATE_BUY_RATE" id="EXCH_RATE_BUY_RATE" class="form-control" min="0" placeholder="">
                                        </div>

                                        
                                        <div class="col-md-3">
                                            <label for="html5-text-input" class="col-form-label col-md-8"><b>From Buy Rate *</b></label><input type="number" name="EXCH_RATE_FROM_BUY_RATE" id="EXCH_RATE_FROM_BUY_RATE" class="form-control" min="0" placeholder="">
                                        </div>

                                        <div class="col-md-3">
                                            <label for="html5-text-input" class="col-form-label col-md-8"><b>Net Buy Rate </b></label><input type="text" name="EXCH_RATE_NET_BUY_RATE" id="EXCH_RATE_NET_BUY_RATE" class="form-control"  placeholder="" disabled>
                                            <input type="hidden" name="EXCH_RATE_NET_BUY_RATE_HIDDEN" id="EXCH_RATE_NET_BUY_RATE_HIDDEN" class="form-control"  placeholder="">
                                        </div>

                                        <div class="col-md-3">
                                            <label for="html5-text-input" class="col-form-label col-md-8"><b>Buy Commi(%)</b></label><input type="number" name="EXCH_RATE_BUY_COM" id="EXCH_RATE_BUY_COM" class="form-control" min="0" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row mb-3">

                                        <label for="html5-text-input" class="col-form-label col-md-3"><b>Exchange Info</b></label>
                                        <div class="col-md-5">
                                            <div class="input-group">
                                                <span id="buyrate_info"></span>                                               
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="row mb-3">
                                        <label for="html5-text-input" class="col-form-label col-md-3"></label>
                                        <div class="col-md-5">
                                            <div class="input-group">                                                
                                                <span id="frombuyrate_info"></span>
                                            </div>
                                        </div>  

                                    </div>

                                    
                                </div>
                            </div>

                            <div class="text-left">
                                <div class="col-md-12">
                                    <label class="switch switch-lg">
                                        <input id="EXCH_RATE_STATUS" name="EXCH_RATE_STATUS" type="checkbox" value="1" class="switch-input" />
                                        <span class="switch-toggle-slider">
                                            <span class="switch-on">
                                                <i class="bx bx-check"></i>
                                            </span>
                                            <span class="switch-off">
                                                <i class="bx bx-x"></i>
                                            </span>
                                        </span>
                                        <span class="switch-label"><b>Active</b></span>
                                    </label>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="submitBtn" onClick="submitForm('submitForm')" class="btn btn-primary">Save</button>
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
                'url': '<?php echo base_url('/ExchangeRatesView') ?>'
            },
            'columns': [{
                    data: ''
                },
                {
                    data: 'EXCH_RATE_ID',
                    'visible': false
                },
                {
                    data: 'CUR_CODE'
                },
                {
                    data: 'CUR_DESC'
                },
                {
                    data: 'EXCH_CODE'
                },
                {
                    targets: -3,
                    data: 'EXCH_CASH',
                    render: function(data, type, full, meta) {
                        var $status_number = full['EXCH_CASH'];
                        var $status = { 
                        0: {  class: 'fa-solid fa-xmark' },
                        1: { class: 'fa-solid fa-check' }
                        };
                    
                        if (typeof $status[$status_number] === 'undefined') {
                        return data;
                        }
                        return (
                        '<i class="'+ $status[$status_number].class +'"></i>'
                        );
                    }
                },
                {
                    targets: -4,
                    data: 'EXCH_CHECK',
                    render: function(data, type, full, meta) {
                        var $status_number = full['EXCH_CHECK'];
                        var $status = { 
                        0: {  class: 'fa-solid fa-xmark' },
                        1: { class: 'fa-solid fa-check' }
                        };
                    
                        if (typeof $status[$status_number] === 'undefined') {
                        return data;
                        }
                        return (
                        '<i class="'+ $status[$status_number].class +'"></i>'
                        );
                    }
                },
                {
                    targets: -5,
                    data: 'EXCH_SETTLEMENT',
                    render: function(data, type, full, meta) {
                        var $status_number = full['EXCH_SETTLEMENT'];
                        var $status = { 
                        0: {  class: 'fa-solid fa-xmark' },
                        1: { class: 'fa-solid fa-check' }
                        };
                    
                        if (typeof $status[$status_number] === 'undefined') {
                        return data;
                        }
                        return (
                        '<i class="'+ $status[$status_number].class +'"></i>'
                        );
                    }
                },
                {
                    data: 'EXCH_RATE_NET_BUY_RATE'
                },
                {
                    data: 'EXCH_RATE_BEGIN_DATE'
                },
                {
                    data: 'EXCH_RATE_BEGIN_TIME'
                },
                {
                    data: 'EXCH_RATE_COMMENTS',
                    'visible': false
                },


                {
                    data: null,
                    "orderable": false,
                    render: function(data, type, row, meta) {
                        return (
                            '<div class="d-inline-block">' +
                            '<a href="javascript:;" title="Edit or Delete" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></a>' +
                            '<ul class="dropdown-menu dropdown-menu-end">' +
                            '<li><a href="javascript:;" data_sysid="' + data['EXCH_RATE_ID'] +
                            '" class="dropdown-item editWindow text-primary"><i class="fa-solid fa-pen-to-square"></i> Edit</a></li>' +
                            '<div class="dropdown-divider"></div>' +
                            '<li><a href="javascript:;" data_sysid="' + data['EXCH_RATE_ID'] +
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
                }, {
                    width: "10%"
                }, {
                    width: "10%"
                }, {
                    width: "10%"
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
                {
                    width: "10%"
                }
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
                            return 'Details';
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

        $('#EXCH_RATE_BEGIN_DATE').datepicker({
            format: 'd-M-yyyy',
            autoclose: true,
        });


        
        $( "#EXCH_RATE_BUY_RATE" ).focusout(function() {
            var F_CUR = $( "#EXCH_RATE_CUR_ID option:selected" ).text();
             TXT_ARRAY = F_CUR.split("|");
            let F_CUR_TEXT = TXT_ARRAY[0];
            
            
            $( "#buyrate_info" ).html('1 AED = '+ $(this).val()+' '+F_CUR_TEXT);
            
            var F_B_R = parseFloat(1)/parseFloat($(this).val());
            F_B_R = parseFloat(F_B_R).toFixed( 2 );
            $( "#frombuyrate_info" ).html('1 '+F_CUR_TEXT+' = '+F_B_R+' AED');
            $( "#EXCH_RATE_NET_BUY_RATE" ).val(F_B_R);
            $( "#EXCH_RATE_NET_BUY_RATE_HIDDEN" ).val(F_B_R);            
            $( "#EXCH_RATE_FROM_BUY_RATE" ).val(F_B_R);
            
        })
    });

    function addForm() {
        $(':input', '#submitForm').not('[type="radio"]').val('').prop('checked', false).prop('selected', false);
        $('.select2').val(null).trigger('change');

        $('#submitBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
        $('#popModalWindowlabel').html('Add New Exchange Rate');
        $(".statusCheck").hide();
        $('#popModalWindow').modal('show');

    }


    // Show Edit Exchange  Code Form

    $(document).on('click', '.editWindow', function() {

        $('.dtr-bs-modal').modal('hide');

        var sysid = $(this).attr('data_sysid');
        $('#popModalWindowlabel').html('Edit Exchange Rate');

        $(".statusCheck").show();

        $('#popModalWindow').modal('show');

        var url = '<?php echo base_url('/editExchangeRates') ?>';
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

                        var F_CUR = $( "#EXCH_RATE_CUR_ID option:selected" ).text();
                        TXT_ARRAY = F_CUR.split("|");
                        let F_CUR_TEXT = TXT_ARRAY[0];

                        $( "#buyrate_info" ).html('1 AED = '+ $('#EXCH_RATE_BUY_RATE').val()+' '+F_CUR_TEXT);
                        $( "#frombuyrate_info" ).html('1 '+F_CUR_TEXT+' = '+$('#EXCH_RATE_NET_BUY_RATE').val()+' AED');
                        
                        if (field == 'EXCH_RATE_CUR_ID' || field == 'EXCH_RATE_CODE_ID') {
                            $('#' + field).select2("val", dataval);
                        } else if ($('#' + field).attr('type') == 'checkbox') {
                            $('#' + field).prop('checked', dataval == 1 ? true : false);
                        } else {

                            $('#' + field).val(dataval);
                        }



                        // $("#TR_CD_ADJ option[value='"+sysid+"']").prop('disabled', true); 

                    });
                });
                $('#submitBtn').removeClass('btn-primary').addClass('btn-success').text('Update');
            }
        });
    });



    // Add New or Edit Exchange Rate submit Function

    function submitForm(id) {
        hideModalAlerts();
        var formSerialization = $('#' + id).serializeArray();
        var url = '<?php echo base_url('/insertExchangeRates') ?>';
        $.ajax({
            url: url,
            type: "post",
            data: formSerialization,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            dataType: 'json',
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
                    //var alertText = $('#TR_CD_ID').val() == '' ? '<li>The new Transaction Code \'' +
                    //$('#TR_CD_CODE')
                    // .val() + '\' has been created</li>' : '<li>The Transaction Code \'' + $(
                    //     '#TR_CD_CODE').val() +
                    //'\' has been updated</li>';
                    var alertText = $('#TR_CD_ID').val() == '' ? '<li>New Exchange Rate has been added </li>' : '<li>The  Exchange Rate has been updated</li>';


                    showModalAlert('success', alertText);

                    $('#popModalWindow').modal('hide');
                    $('#dataTable_view').dataTable().fnDraw();
                }
            }
        });
    }


    // Delete Exchange Rate 

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
                        url: '<?php echo base_url('/deleteExchangeRates') ?>',
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
                                '<li>The Exchange Rate has been deleted</li>'
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