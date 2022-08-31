<?= $this->extend("Layout/AppView") ?>
<?= $this->section("contentRender") ?>
<?= $this->include('Layout/ErrorReport') ?>
<?= $this->include('Layout/SuccessReport') ?>
<!-- Content wrapper -->
<div class="content-wrapper">
  <!-- Content -->

  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="row g-4 mb-4">
    <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Users </span>/ Users List</h4>
    </div>
    <!-- Users List Table -->
    <div class="card">
      <div class="card-header border-bottom">
        <!-- <h5 class="card-title">Search</h5>
        <div class="row gap-3 gap-md-0 d-flex justify-content-between align-items-center py-3">
          <div class="user_role col-md-4"></div>
          <div class="user_dept col-md-4"></div>
          <div class="user_status col-md-4"></div>
        </div> -->
      </div>
      <div class="card-datatable table-responsive">
        <table class="datatables-users table border-top">
          <thead>
            <tr>
              <th></th>
              <th>ID</th>
              <th>User</th>
              <th>User Role</th>
              <th>Department</th>
              <th>DOJ</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
        </table>
      </div>

    </div>
  </div>
  <!-- / Content -->


  <!-- Modal Window -->

  <div class="modal fade" id="popModalWindow" data-backdrop="static" data-keyboard="false" aria-labelledby="popModalWindowlabel">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="popModalWindowlabel">Add User</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="submitForm" class="needs-validation" novalidate>
            <div class="row g-3">
              <input type="hidden" name="USR_ID" id="USR_ID" class="form-control" />
              <div class="border rounded p-3">

                <div class="col-md-12">
                  <div class="row mb-3">  
                  <div class="col-md-4">
                    <label for="html5-text-input" class="col-form-label"><b>USER NAME
                        *</b></label>
                      <input type="text" id="USR_NAME" name="USR_NAME" class="form-control" placeholder="User Name">
                      
                    </div>
                    <div class="col-md-4">
                    <label for="html5-text-input" class="col-form-label"><b>FIRST NAME
                        *</b></label>
                      <input type="text" id="USR_FIRST_NAME" name="USR_FIRST_NAME" class="form-control" placeholder="First Name">
                      
                    </div>
                  
                    <div class="col-md-4">
                    <label for="html5-text-input" class="col-form-label"><b>LAST NAME
                        *</b></label>
                      <input type="text" id="USR_LAST_NAME" name="USR_LAST_NAME" class="form-control" placeholder="Last Name">
                      
                    </div>
                  </div>

                  <div class="row mb-3">

                  <div class="col-md-4">
                    <label for="html5-text-input" class="col-form-label"><b>EMAIL
                        *</b></label>
                      <input type="text" id="USR_EMAIL" name="USR_EMAIL" class="form-control" placeholder="Email">
                      
                    </div>

                  <div class="col-md-4">
                    <label for="html5-text-input" class="col-form-label"><b>PASSWORD
                        *</b></label>
                      <input type="password" id="USR_PASSWORD" name="USR_PASSWORD" class="form-control" placeholder="Password">
                      
                    </div>
                    <div class="col-md-4">
                    <label for="html5-text-input" class="col-form-label"><b>CONFIRM PASSWORD
                        *</b></label>
                      <input type="password" id="USR_CONFIRM_PASSWORD" name="USR_CONFIRM_PASSWORD" class="form-control" placeholder="">
                      
                    </div> 
                  
                  </div>
                  <div class="row mb-3">

                  <div class="col-md-4">
                    <label for="html5-text-input" class="col-form-label"><b>Employee Number
                        *</b></label>
                        <input type="text" id="USR_NUMBER" name="USR_NUMBER" class="form-control" placeholder="Employee Number">
                        </select>
                    </div> 
                    

                  <div class="col-md-4">
                    <label for="html5-text-input" class="col-form-label"><b>User Role
                        *</b></label>
                        <select id="USR_ROLE_ID" name="USR_ROLE_ID" class="select2 form-select form-select-lg" data-allow-clear="true" required>                           
                        </select>
                        <input type="hidden" name="USR_ROLE" id="USR_ROLE" class="form-control" />
                    </div>  

                    <div class="col-md-4">
                      <label for="html5-text-input" class="col-form-label"><b>Department
                          *</b></label>
                          <select id="USR_DEPARTMENT" name="USR_DEPARTMENT" class="select2 form-select form-select-lg" data-allow-clear="true" required>
                              <?= $departmentList ?>
                          </select>
                    </div>                   
                  </div>

                  <div class="row mb-3">
                  <div class="col-md-4">
                    <label for="html5-text-input" class="col-form-label"><b>DOJ
                          *</b></label>
                        <div class="col-md-12">
                        <div class="input-group">
                            <input type="text" id="USR_DOJ" name="USR_DOJ" class="form-control" placeholder="DD-MM-YYYY">
                            <span class="input-group-append">
                                <span class="input-group-text bg-light d-block">
                                    <i class="fa fa-calendar"></i>
                                </span>
                            </span>
                        </div>
                        </div>                     
                    </div> 

                    <div class="col-md-4">
                      <label for="html5-text-input" class="col-form-label"><b>COUNTRY
                          *</b></label>
                          <select id="USR_COUNTRY" name="USR_COUNTRY" class="select2 form-select form-select-lg" data-allow-clear="true" required>
                             
                          </select>
                      </div>
                    <div class="col-md-4">
                      <label for="html5-text-input" class="col-form-label"><b>STATE
                          *</b></label>
                          <select id="USR_STATE" name="USR_STATE" class="select2 form-select form-select-lg" data-allow-clear="true" required>
                             
                          </select>
                      </div> 

                    
                    </div>                  
                   
                  <div class="row mb-3">   
                    
                  <div class="col-md-4">
                        <label for="html5-text-input" class="col-form-label"><b>CITY
                            *</b></label>
                          <select id="USR_CITY" name="USR_CITY" class="select2 form-select form-select-lg" data-allow-clear="true" required>                            
                          </select>
                    </div>                  

                  <div class="col-md-4">
                    <label for="html5-text-input" class="col-form-label"><b>ADDRESS
                        *</b></label>
                      <textarea class="form-control" name="USR_ADDRESS" id="USR_ADDRESS" rows="1"></textarea>                          
                  </div>  
                  <div class="col-md-4">
                    <label for="html5-text-input" class="col-form-label"><b>DOB
                          *</b></label>
                        <div class="col-md-12">
                        <div class="input-group">
                            <input type="text" id="USR_DOB" name="USR_DOB" class="form-control" placeholder="DD-MM-YYYY">
                            <span class="input-group-append">
                                <span class="input-group-text bg-light d-block">
                                    <i class="fa fa-calendar"></i>
                                </span>
                            </span>
                        </div>
                        </div>                       
                    </div>  
                   
                  </div>      
                  
                 
                  <div class="row mb-3"> 

                  <div class="col-md-4">
                    <label for="html5-text-input" class="col-form-label"><b>Phone Number
                        *</b></label>
                        <input type="text" id="USR_PHONE" name="USR_PHONE" class="form-control" placeholder="Phone Number">
                        </select>
                    </div> 

                  <div class="col-md-4">
                    <label for="html5-text-input" class="col-form-label"><b>Extension
                        *</b></label>
                        <input type="text" id="USR_TEL_EXT" name="USR_TEL_EXT" class="form-control" placeholder="Extension">
                        </select>
                    </div> 

                  <div class="col-md-4">   
                  <label for="html5-text-input" class="col-form-label" style="display: block"><b>GENDER
                          *</b></label>
                    <div class="form-check mb-2" style="float:left;margin-right:10px" >
                      <input type="radio" id="USR_GENDER_1" name="USR_GENDER" value="1" class="form-check-input" required="">
                      <label class="form-check-label" for="bs-validation-radio-male">Male</label>
                    </div>
                    <div class="form-check" style="float:left">
                      <input type="radio" id="USR_GENDER_0" name="USR_GENDER" value="0" class="form-check-input" required="">
                      <label class="form-check-label" for="bs-validation-radio-female">Female</label>
                    </div>
                  </div>
                  </div>                 

                </div>
              </div>

              <div class="text-left">
                <div class="col-md-12">
                  <label class="switch switch-lg">
                    <input id="USR_STATUS" name="USR_STATUS" type="checkbox" value="1" class="switch-input" />
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
  $(document).ready(function() {
    
    $('#USR_DOB').datepicker({
        format: 'd-M-yyyy',
        autoclose: true,
    });
    $('#USR_DOJ').datepicker({
        format: 'd-M-yyyy',
        autoclose: true,
    });
    // Variable declaration for table
    var dt_user_table = $('.datatables-users'),
      select2 = $('.select2'),
      userView = '',
      statusObj = {
        // 0: {
        //   title: 'Pending',
        //   class: 'bg-label-warning'
        // },
        1: {
          title: 'Active',
          class: 'bg-label-success'
        },
        0: {
          title: 'Inactive',
          class: 'bg-label-secondary'
        }
      };

    if (select2.length) {
      var $this = select2;
      $this.wrap('<div class="position-relative"></div>').select2({
        placeholder: 'Select Country',
        dropdownParent: $this.parent()
      });
    }


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
    // Users datatable
    if (dt_user_table.length) {
      var dt_user = dt_user_table.DataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
          'url': '<?php echo base_url('/UsersList') ?>'
        }, // JSON file to add data
        columns: [
          // columns according to JSON
          {
            data: ''
          },
          {
            data: 'USR_ID',
            'visible': false
          },
          {
            data: 'USR_NAME'
          },
          {
            data: 'ROLE_NAME'
          },
          {
            data: 'DEPT_CODE'
          },
          {
            data: 'USR_DOJ'
          },
          {
            data: 'USR_STATUS'
          },
          {
            data: 'action'
          }
        ],
        columnDefs: [{
            // For Responsive
            className: 'control',
            searchable: false,
            orderable: false,
            responsivePriority: 2,
            targets: 0,
            render: function(data, type, full, meta) {
              return '';
            }
          },
          {
            targets: 1,
            
            
          },
          {
            // User full name and email
            targets: 2,
            responsivePriority: 4,
            render: function(data, type, full, meta) {
              var $name = (full['USR_FIRST_NAME'] ?? '') + ' ' + (full['USR_LAST_NAME'] ?? ''),
                $email = full['USR_EMAIL']??'',
                $image = full['USR_IMAGE']??'';
              if ($image) {
                // For Avatar image
                var $output =
                  '<img src="' + assetsPath + '/img/avatars/' + $image + '" alt="Avatar" class="rounded-circle">';
              } else {
                // For Avatar badge
                var stateNum = Math.floor(Math.random() * 6);
                var states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
                var $state = states[stateNum],
                //  $name = full['USR_FIRST_NAME'] + ' ' + full['USR_LAST_NAME'],
                  $initials = $name.match(/\b\w/g) || [];
                $initials = (($initials.shift() || '') + ($initials.pop() || '')).toUpperCase();
                $output = '<span class="avatar-initial rounded-circle bg-label-' + $state + '">' + $initials + '</span>';
              }
              // Creates full output for row
              var $row_output =
                '<div class="d-flex justify-content-start align-items-center">' +
                '<div class="avatar-wrapper">' +
                '<div class="avatar avatar-sm me-3">' +
                $output +
                '</div>' +
                '</div>' +
                '<div class="d-flex flex-column">' +
                '<a href="javascript:;" class="text-body text-truncate editWindow" data_sysid="' + full['USR_ID'] +
                            '"><span class="fw-semibold">' +
                $name +
                '</span></a>' +
                '<small class="text-muted">' +
                $email +
                '</small>' +
                '</div>' +
                '</div>';
              return $row_output;
            }
          },
          {
            // User Role
            targets: 3,
            render: function(data, type, full, meta) {
              var $role = full['ROLE_NAME'] ?? '';
              if (full['USR_ROLE_ID'] <= 3) {
                 var roleBadgeObj = {
                //   GUEST: '<span class="badge badge-center rounded-pill bg-label-warning w-px-30 h-px-30 me-2"><i class="bx bx-user bx-xs"></i></span>',
                //   Editor: '<span class="badge badge-center rounded-pill bg-label-info w-px-30 h-px-30 me-2"><i class="bx bx-edit bx-xs"></i></span>',
                //   Admin: '<span class="badge badge-center rounded-pill bg-label-secondary w-px-30 h-px-30 me-2"><i class="bx bx-mobile-alt bx-xs"></i></span>'
                };
                roleBadge = roleBadgeObj[$role] ?? '';
                return "<span class='text-truncate d-flex align-items-center'>" + roleBadge + $role + '</span>';
              } else {
                return "<span class='text-truncate d-flex align-items-center'>" + $role + '</span>';
              }
            }
          },
          {
            // Department
            targets: 4,
            render: function(data, type, full, meta) {
              var $DEPT_CODE = (full['DEPT_DESC'] ?? "");
              return '<span class="fw-semibold">' + $DEPT_CODE  + '</span>';
            }
          },
          {
            // User Status
            targets: 6,
            render: function(data, type, full, meta) {
              var $status = full['USR_STATUS'];
              return '<span class="badge ' + statusObj[$status].class + '">' + statusObj[$status].title + '</span>';
            }
          },
          {
            // Actions
            targets: -1,
            title: 'Actions',
            searchable: false,
            orderable: false,
            render: function(data, type, full, meta) {

              if(full['USR_STATUS'] == 0)
                  suspend =  '<a href="javascript:;" data_sysid="' + full['USR_ID'] +
                            '" class="dropdown-item text-primary suspend-record" rel="1"><i class="fa-solid fa-check"></i> Restore</a>';
                
              else
                  suspend =  '<a href="javascript:;" data_sysid="' + full['USR_ID'] +
                            '" class="dropdown-item text-danger suspend-record" rel="0"><i class="fa-solid fa-ban"></i> Suspend</a>';
                

              return (
                '<div class="d-inline-block">' +
                '<button class="btn btn-sm btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></button>' +
                '<div class="dropdown-menu dropdown-menu-end">' +
                '<a href="javascript:;" data_sysid="' + full['USR_ID'] +
                            '" class="dropdown-item editWindow text-primary"><i class="fa-solid fa-pen-to-square"></i> Edit</a>' +suspend
                +
                '</div>'
              );
            }
          }
        ],
        order: [
          [1, 'desc']
        ],
        dom: '<"row mx-2"' +
          '<"col-md-2"<"me-3"l>>' +
          '<"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0"fB>>' +
          '>t' +
          '<"row mx-2"' +
          '<"col-sm-12 col-md-6"i>' +
          '<"col-sm-12 col-md-6"p>' +
          '>',
        language: {
          sLengthMenu: '_MENU_',
          search: '',
          searchPlaceholder: 'Search..'
        },
        // Buttons with Dropdown
        buttons: [{
            extend: 'collection',
            className: 'btn btn-label-secondary dropdown-toggle mx-3',
            text: '<i class="bx bx-upload me-2"></i>Export',
            buttons: [{
                extend: 'print',
                text: '<i class="bx bx-printer me-2" ></i>Print',
                className: 'dropdown-item',
                exportOptions: {
                  columns: [1,2, 3, 4]
                }
              },
              {
                extend: 'csv',
                text: '<i class="bx bx-file me-2" ></i>Csv',
                className: 'dropdown-item',
                exportOptions: {
                  columns: [1,2, 3, 4]
                }
              },
              {
                extend: 'excel',
                text: 'Excel',
                className: 'dropdown-item',
                exportOptions: {
                  columns: [1,2, 3, 4]
                }
              },
              {
                extend: 'pdf',
                text: '<i class="bx bxs-file-pdf me-2"></i>Pdf',
                className: 'dropdown-item',
                exportOptions: {
                  columns: [1,2, 3, 4]
                }
              },
              {
                extend: 'copy',
                text: '<i class="bx bx-copy me-2" ></i>Copy',
                className: 'dropdown-item',
                exportOptions: {
                  columns: [1,2, 3, 4]
                }
              }
            ]
          },
          {
            text: '<i class="bx bx-plus me-0 me-lg-2"></i><span class="d-none d-lg-inline-block">Add New User</span>',
            className: 'add-new btn btn-primary',
            attr: {
              'onClick':"addForm()"
            }
          }
        ],
        // For responsive popup
        responsive: {
          details: {
            display: $.fn.dataTable.Responsive.display.modal({
              header: function(row) {
                var data = row.data();
                return 'Details of ' + data['USR_FIRST_NAME'];
              }
            }),
            type: 'column',
            renderer: function(api, rowIdx, columns) {
              var data = $.map(columns, function(col, i) {
                return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
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
        initComplete: function() {
          // Adding role filter once table initialized
          this.api()
            .columns(2)
            .every(function() {
              var column = this;
              var select = $(
                  '<select id="UserRole" class="select2 form-select text-capitalize "><option value=""> Select Role </option></select>'
                )
                .appendTo('.user_role')
                .on('change', function() {
                  var val = $.fn.dataTable.util.escapeRegex($(this).val());
                  column.search(val ? '^' + val + '$' : '', true, false).draw();
                });

              column
                .data()
                .unique()
                .sort()
                .each(function(d, j) {
                  select.append('<option value="' + d + '">' + d + '</option>');
                });
            });
          // Adding Department filter once table initialized
          this.api()
            .columns(3)
            .every(function() {
              var column = this;
              var select = $(
                  '<select id="UserDepartment" class="form-select text-capitalize select2"><option value=""> Select Department </option></select>'
                )
                .appendTo('.user_dept')
                .on('change', function() {
                  var val = $.fn.dataTable.util.escapeRegex($(this).val());
                  column.search(val ? '^' + val + '$' : '', true, false).draw();
                });

              column
                .data()
                .unique()
                .sort()
                .each(function(d, j) {
                  select.append('<option value="' + d + '">' + d + '</option>');
                });
            });
          // Adding status filter once table initialized
          this.api()
            .columns(6)
            .every(function() {
              var column = this;
              var select = $(
                  '<select id="UserStatus" class="form-select text-capitalize select2"><option value=""> Select Status </option></select>'
                )
                .appendTo('.user_status')
                .on('change', function() {
                  var val = $.fn.dataTable.util.escapeRegex($(this).val());
                  column.search(val ? '^' + val + '$' : '', true, false).draw();
                });

              column
                .data()
                .unique()
                .sort()
                .each(function(d, j) {
                  select.append(
                    '<option value="' +
                    statusObj[d].title +
                    '" class="text-capitalize">' +
                    statusObj[d].title +
                    '</option>'
                  );
                });
            });
        }
      });
    }

    // Delete Record
    $('.datatables-users tbody').on('click', '.delete-record', function() {
      dt_user.row($(this).parents('tr')).remove().draw();
    });

    // Filter form control to default size
    // ? setTimeout used for multilingual table initialization
    setTimeout(() => {
      $('.dataTables_filter .form-control').removeClass('form-control-sm');
      $('.dataTables_length .form-select').removeClass('form-select-sm');
    }, 300);
  });

  function addForm() {
    $(':input', '#submitForm').not('[type="radio"],[type="checkbox"]').val('').prop('checked', false).prop('selected', false);
    //$('.select2').val(null).trigger('change');
    $('#USR_COUNTRY,#USR_STATE,#USR_CITY').html('<option value="">Select</option>');
    $('#submitBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
    $('#popModalWindowlabel').html('Add New User');
    $('#popModalWindow').modal('show');
    countryList();
    roleList()
  }

 

 // Add New or Edit Users submit Function

 function submitForm(id) {
    var user_role_text = $("#USR_ROLE_ID option:selected").text();
    $("#USR_ROLE").val(user_role_text);
    hideModalAlerts();
    var formSerialization = $('#' + id).serializeArray();
    var url = '<?php echo base_url('/insertUser') ?>';
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
                
                var alertText = $('#USR_ID').val() == '' ? '<li>New user has been added </li>' : '<li>The user has been updated</li>';

                showModalAlert('success', alertText);
                $('#infoModal').delay(2500).fadeOut();
                $('#successModal').delay(2500).fadeOut();  
                roleList();
                $('#popModalWindow').modal('hide');
                $('.datatables-users').dataTable().fnDraw();
            }
        }
    });
  }

        // User Edit Form

        $(document).on('click', '.editWindow', function() {  
             
        countryList(); 
        roleList();
        $('.dtr-bs-modal').modal('hide');

        var sysid = $(this).attr('data_sysid');
        $('#USR_ID').val(sysid);       
        
        $('#popModalWindowlabel').html('Edit User');

        $(".statusCheck").show();

        $('#popModalWindow').modal('show');

        var url = '<?php echo base_url('/editUser') ?>';
        
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
              var state_val = city_val ='';
                $(respn).each(function(inx, data) {
                    $.each(data, function(fields, datavals) {
                        var field = $.trim(fields); //fields.trim();
                        var dataval = $.trim(datavals); //datavals.trim();                       

                     
                        if (field == 'USR_ROLE_ID' || field == 'USR_DEPARTMENT') {
                         
                           $('#' + field).select2("val", dataval);

                        } else if ($('#' + field).attr('type') == 'checkbox') {

                            $('#' + field).prop('checked', dataval == 1 ? true : false);

                        } else if ($('[name=' + field + ']').attr('type') == 'radio') {

                            $('#' + field + '_' + dataval).prop('checked', true);
                        }

                        else if(field == 'USR_CITY')  {  
                         

                            city_val = dataval;   
                                                   
                        }  
                        else if(field == 'USR_STATE')  {  

                             state_val = dataval;    
                                                 
                        }                        
                        else if(field == 'USR_COUNTRY')  {     
                                                                                                 
                            $('#USR_COUNTRY').val(dataval).trigger('change',state_val);
                            $('#USR_STATE').val(state_val).trigger('change',city_val);
                            
                        }
                        else if(field == 'USR_PASSWORD')
                        {
                          // $('#USR_PASSWORD').val(dataval);
                          // $('#USR_CONFIRM_PASSWORD').val($('#USR_PASSWORD').val());
                        }

                        else if(field == 'USR_DOB' || field == 'USR_DOJ') {
                            $('#' + field).datepicker("setDate", new Date(dataval));
                          } 
                        else{
                           
                            $('#' + field).val(dataval);
                        }
                        

                    });
                });
                $('#submitBtn').removeClass('btn-primary').addClass('btn-success').text('Update');
            }
        });
    });

function countryList() {

  $.ajax({
        url: '<?php echo base_url('/userCountryList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
         async:false,
        success:function(respn){
          $('#USR_COUNTRY').html(respn);
        }
    });
    
    
}

$("#USR_COUNTRY").change(function(e,param = 0) {
 
      var ccode = $(this).val();
      $.ajax({
      url: '<?php echo base_url('/userStateList')?>',
      type: "post",
      async:false,
      headers: {'X-Requested-With': 'XMLHttpRequest'},
      data:{ccode:ccode,state_id:param},
      success:function(respn){          
        $('#USR_STATE').html(respn);
        
      }
  });
});

$("#USR_STATE").change(function(e,param = 0) { 
      var scode = $('#USR_STATE').val();
      var ccode = $('#USR_COUNTRY').val();
      $.ajax({
      url: '<?php echo base_url('/userCityList')?>',
      type: "post",
      headers: {'X-Requested-With': 'XMLHttpRequest'},
      data:{ccode: ccode,
            scode: scode,
            cityid:param},
      success:function(respn){         
         $('#USR_CITY').html(respn).trigger('change');
      }
  });
});


    // Suspend User 

    $(document).on('click', '.suspend-record', function() {
        hideModalAlerts();
        $('.dtr-bs-modal').modal('hide');
        var sysid = $(this).attr('data_sysid');
        var suspend = $(this).attr('rel');
        if(suspend == 1){
          message = "Are you sure you want to restore this record?";
        }
        else
          message = "Are you sure you want to suspend this record?";

        bootbox.confirm({
            message:  message,
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
                        url: '<?php echo base_url('/suspendUser') ?>',
                        type: "post",
                        data: {
                            sysid: sysid,
                            suspend:suspend

                        },
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        dataType: 'json',
                        success: function(respn) {

                          if(suspend == 1)
                          {
                            showModalAlert('info',
                                '<li>The User has been Restored</li>'
                            );
                          }
                          else{
                            showModalAlert('warning',
                                '<li>The User has been Suspended</li>'
                            );
                          }
                          
                            
                            $('.datatables-users').dataTable().fnDraw();
                        }
                    });
                }
            }
        });
    });


    function roleList() {
    $.ajax({
      url: '<?php echo base_url('/roleList') ?>',
      type: "GET",
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      },
      async: false,
      success: function(respn) {
        $('#USR_ROLE_ID').html(respn);
      }
    });

  }
    

 

</script>

<?= $this->endSection() ?>