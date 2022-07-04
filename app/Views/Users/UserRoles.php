<?= $this->extend("Layout/AppView") ?>
<?= $this->section("contentRender") ?>
<?= $this->include('Layout/ErrorReport') ?>
<?= $this->include('Layout/SuccessReport') ?>
<style>
  .parent:hover{
    background-color: #96b0d726;

  }
  .parent{
  cursor: pointer; 
  }
  .childClass{
    padding: 1px 1px 0px 10px !important;
    display:none;
    background-color: #96b0d726;
  }
  .childClass td{
    padding: 4px 1px 4px 15px !important;
    font-size: 14px;
    
  }

  .fa-chevron-down {
				transform: rotate(0deg);
				transition: all 0.6s;
  }

  .fa-chevron-down.active {
      transform: rotate(180deg);
    }
			

</style>
<div class="content-wrapper">
  <!-- Content -->

  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="breadcrumb-wrapper py-3 mb-2">Roles List</h4>
    <p>
      A role provided access to predefined menus and features so that depending on assigned role an administrator<br />
      can have access to what user needs.
    </p>
    <!-- Role cards -->
    <div class="row g-4">
      <div class="row g-4" id="roleDiv">

      </div>
      <div class="col-12">
        <!-- Role Table -->
        <div class="card">
          <div class="card-datatable table-responsive">
            <table class="datatables-users table border-top">
              <thead>
                <tr>
                  <th></th>
                  <th>User</th>
                  <th>User Role</th>
                  <th>Department</th>
                  <th>DOJ</th>
                  <th>Status</th>
                  <th></th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
        <!--/ Role Table -->
      </div>
    </div>
    <!--/ Role cards -->

    <!-- Add Role Modal -->
    <div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered modal-add-new-role">
        <div class="modal-content p-3 p-md-5">
          <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="modal-body">
            <div class="text-center mb-4">
              <h3 class="role-title">Add New Role</h3>
              <p>Set role permissions</p>
            </div>
            <!-- Add role form -->

            <form id="submitForm" class="needs-validation" novalidate>
              <input type="hidden" name="ROLE_ID" id="ROLE_ID" class="form-control" />
              <div class="col-12 mb-4">
                <label class="form-label" for="ROLE_NAME">Role Name</label>
                <input type="text" id="ROLE_NAME" name="ROLE_NAME" class="form-control" placeholder="Enter a role name" tabindex="-1" required />
              </div>
              <div class="col-12">
                <h5>Role Permissions</h5>
                <!-- Permission table -->
                <div class="table-responsive">
                  <table class="table table-flush-spacing" id="role_table">
                    <tbody>
                      <tr>
                        <td class="text-nowrap">
                          Administrator Access
                          <i class="bx bx-info-circle bx-xs" data-bs-toggle="tooltip" data-bs-placement="top" title="Allows a full access to the system"></i>
                        </td>
                        <!-- <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAll" />
                                <label class="form-check-label" for="selectAll"> Select All </label>
                            </div>
                            </td> -->

                        <td>
                          <div class="d-flex">
                            <div class="form-check me-3 me-lg-5">
                              <label class="switch switch-lg">
                                <input id="selectAll" name="" id="MENU_ID0" type="checkbox" value="0" class="switch-input " />
                                <span class="switch-toggle-slider">
                                  <span class="switch-on">
                                    <i class="bx bx-check"></i>
                                  </span>
                                  <span class="switch-off">
                                    <i class="bx bx-x"></i>
                                  </span>
                                </span>
                                <span class="switch-label"><b>Select All</b></span>
                              </label>
                            </div>

                          </div>
                        </td><td></td>
                      </tr>

                      <?php //print_r($responseSubMenu);
                      
                      if (!empty($responseMenu)) {
                        foreach ($responseMenu as $row) {
                      ?>
                          <tr class="parent" id="parent<?php echo $row['MENU_ID'] ?>" rel="<?php echo $row['MENU_ID'] ?>">
                            <td class="text-nowrap"><?php echo $row['MENU_NAME'] ?></td>
                            <td>
                              <div class="d-flex">
                                <div class="form-check me-3 me-lg-5">
                                  <label class="switch switch-md">
                                    <input id="MENU_ID<?php echo $row['MENU_ID'] ?>" name="MENU_ID[]" type="checkbox" value="<?php echo $row['MENU_ID'] ?>" class="switch-input menu-perm" />
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
                            </td>
                            <td><i class="fas fa-chevron-down fa-sm"></i></td>
                          </tr>                            
                            <?php 
                                if (!empty($responseSubMenu[$row['MENU_ID']])) {
                                  foreach ($responseSubMenu[$row['MENU_ID']] as $row1) {                        
                                  
                                  ?>
                                <tr class="child<?php echo $row['MENU_ID'] ?> childClass"  rel="<?php echo $row['MENU_ID'] ?>">
                                    <td class="text-nowrap"><i class="fa-solid fa-circle-arrow-right" style="padding-right:5px"></i><?php echo $row1['sub_menu_name'] ?></td>
                                    <td>
                                      <div class="d-flex">
                                        <div class="form-check me-3 me-lg-5">
                                          <label class="switch switch-md">
                                            <input id="MENU_ID<?php echo $row1['sub_menu_id'] ?>" name="sub_menu_id[]" type="checkbox" value="<?php echo $row1['sub_menu_id'] ?>" class="switch-input menu-perm" />
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
                                    </td>
                                    <td></td>
                                  </tr>
                                  <?php //}
                                } }?>
                      <?php }
                      } ?>
                    </tbody>
                  </table>
                </div>
                <!-- Permission table -->
              </div>
              <div class="col-12 text-center">
                  
                <button type="button" id="submitBtn" onClick="submitForm('submitForm')" class="btn btn-primary me-1 me-sm-3">Submit</button>
                <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">
                  Cancel
                </button>
              </div>
            </form>
            <!--/ Add role form -->
          </div>
        </div>
      </div>
    </div>
    <!--/ Add Role Modal -->




    <!-- View Role Modal -->
    <div class="modal fade" id="viewRoleModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered modal-add-new-role">
        <div class="modal-content p-3 p-md-5">
          <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="modal-body">
            <div class="text-center mb-4">
              <h3 class="role-title">View Roles</h3>

            </div>
            <!-- view role table -->

            <div class="card">
              <div class="card-datatable table-responsive">
                <table class="datatables-users-roles table border-top">
                  <thead>
                    <tr>
                      <th>Role</th>
                      <th>Total Users</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>

            <!--/ view role table -->



          </div>
        </div>
      </div>
    </div>
    <!--/ View Role Modal -->



    <!-- Modal Window -->

    <div class="modal fade" id="popModalWindow" data-backdrop="static" data-keyboard="false" aria-labelledby="popModalWindowlabel">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="popModalWindowlabel">Add User</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="submitUserForm" class="needs-validation" novalidate>
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
                        <input type="password" id="USR_PASSWORD" name="USR_PASSWORD" class="form-control" placeholder="">

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
                        <div class="form-check mb-2" style="float:left;margin-right:10px">
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
            <button type="button" id="submitBtn" onClick="submitUserForm('submitUserForm')" class="btn btn-primary">Save</button>
          </div>
        </div>
      </div>
    </div>

    <!-- /Modal window -->

  </div>
  <!-- / Content -->
  <div class="content-backdrop fade"></div>
</div>
<script>
  $(document).ready(function() {

    // // Select All checkbox click
    // const selectAll = document.querySelector('#selectAll'),
    // checkboxList = document.querySelectorAll('[type="checkbox"]');
    // selectAll.addEventListener('change', t => {
    // checkboxList.forEach(e => {
    // e.checked = t.target.checked;
    // });
    // });

    loadRoles();
    roleList();

    $('#USR_DOB').datepicker({
        format: 'd-M-yyyy',
        autoclose: true,
    });
    $('#USR_DOJ').datepicker({
        format: 'd-M-yyyy',
        autoclose: true,
    });
    

    // Users datatable
    var dt_user_table = $('.datatables-users');
    var dt_user_roles_table = $('.datatables-users-roles');

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
            data: ''
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
            // User full name and email
            targets: 1,
            responsivePriority: 4,
            render: function(data, type, full, meta) {
              var $name = (full['USR_FIRST_NAME'] ?? '') + '' + (full['USR_LAST_NAME'] ?? ''),
                $email = full['USR_EMAIL'] ?? '',
                $image = full['USR_IMAGE'] ?? '';
              if ($image) {
                // For Avatar image
                var $output =
                  '<img src="' + assetsPath + '/img/avatars/' + $image + '" alt="Avatar" class="rounded-circle">';
              } else {
                // For Avatar badge
                var stateNum = Math.floor(Math.random() * 6);
                var states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
                var $state = states[stateNum],
                  $name = full['USR_FIRST_NAME'] + ' ' + full['USR_LAST_NAME'],
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
            targets: 2,
            render: function(data, type, full, meta) {
              var $role = full['ROLE_NAME'] ?? '';
              if (full['USR_ROLE_ID'] <= 3) {
                var roleBadgeObj = {
                  GUEST: '<span class="badge badge-center rounded-pill bg-label-warning w-px-30 h-px-30 me-2"><i class="bx bx-user bx-xs"></i></span>',
                  Editor: '<span class="badge badge-center rounded-pill bg-label-info w-px-30 h-px-30 me-2"><i class="bx bx-edit bx-xs"></i></span>',
                  Admin: '<span class="badge badge-center rounded-pill bg-label-secondary w-px-30 h-px-30 me-2"><i class="bx bx-mobile-alt bx-xs"></i></span>'
                };
                roleBadge = roleBadgeObj[$role] ?? '';
                return "<span class='text-truncate d-flex align-items-center'>" + roleBadge + $role + '</span>';
              } else {
                return "<span class='text-truncate d-flex align-items-center'><span class='badge badge-center rounded-pill bg-label-primary w-px-30 h-px-30 me-2'><i class='bx bx-pie-chart-alt bx-xs'></i></span>" + $role + '</span>';
              }
            }
          },
          {
            // Department
            targets: 3,
            render: function(data, type, full, meta) {
              var $DEPT_DESC = (full['DEPT_DESC']);
              return '<span class="fw-semibold">' + $DEPT_DESC + '</span>';
            }
          },
          {
            // User Status
            targets: 5,
            render: function(data, type, full, meta) {
              var $status = full['USR_STATUS'];
              return '<span class="badge ' + statusObj[$status].class + '">' + statusObj[$status].title + '</span>';
            }
          },
          {
            // Actions
            targets: -1,
            title: 'Action',
            searchable: false,
            orderable: false,
            render: function(data, type, full, meta) {
                

              return (
               
                '<a href="javascript:;" data_sysid="' + full['USR_ID'] +
                            '" class=" editWindow text-primary"><i class="fa-solid fa-pen-to-square"></i> </a>' 
                
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
        buttons: [],
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
            .columns(5)
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


    if (dt_user_roles_table.length) {
      var dt_user_roles = dt_user_roles_table.DataTable({

        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
          'url': '<?php echo base_url('/viewUserRoles') ?>'
        },
        'columns': [{
            data: 'ROLE_NAME'
          },

          {
            data: 'USR_COUNT'
          },
          {
            data: null,
            "orderable": false,
            render: function(data, type, row, meta) {
              return (
                '<div class="d-inline-block">' +

                '<a href="javascript:;" data_sysid="' + data['ROLE_ID'] +
                '" class="role-edit-modal text-primary" href="javascript:;" ><i class="fa-solid fa-pen-to-square"></i> Edit</a>' +
                '</div>'
              );
            }
          },
        ],
        columnDefs: [{
            width: "15%"
          },
          {
            width: "15%"
          },
          {
            width: "10%"
          }
        ],
        "order": [
          [0, "asc"]
        ],



      });
    }


    $(document).on("click", ".add-new-role", function() {
      $('#submitBtn').removeClass('btn-primary').addClass('btn-success').text('Submit');
      $('#ROLE_NAME').val('');
      $('#ROLE_ID').val('');
      $('[type="checkbox"]').prop('checked', false);
      $('#submitForm').not('[type="checkbox"]').prop('checked', false);
      var roleEditList = document.querySelectorAll('.role-edit-modal'),
        roleAdd = document.querySelector('.add-new-role'),
        roleTitle = document.querySelector('.role-title');

      roleTitle.innerHTML = 'Add New Role';
      if (roleEditList) {
        roleEditList.forEach(function(roleEditEl) {
          roleEditEl.onclick = function() {
            roleTitle.innerHTML = 'Edit Role'; // reset text
          };
        });
      }

      


    });



    $(document).on('show.bs.modal', '.modal', function() {
      var maxZ = parseInt($('.modal-backdrop').css('z-index')) || 1090;
      $('.modal:visible').each(function() {
        maxZ = Math.max(parseInt($(this).css('z-index')), maxZ);
      });

      $('.modal-backdrop').css('z-index', maxZ);
      $(this).css("z-index", maxZ + 1);
      $('.modal-dialog', this).css("z-index", maxZ + 2);
    });



    $(document).on('hidden.bs.modal', '.modal', function() {
      if ($('.modal:visible').length) {
        $(document.body).addClass('modal-open');
        var maxZ = 1040;
        $('.modal:visible').each(function() {
          maxZ = Math.max(parseInt($(this).css('z-index')), maxZ);
        });
        $('.modal-backdrop').css('z-index', maxZ - 1);

      }

    });


    $('.menu-perm').change(function() {   
      var thisVal = $(this).val();
      // get parent menu id
      var parent = $(this).closest('tr').attr("rel");

      if(thisVal != parent)
      {

      }



      if($(this).is(":checked") == false){      
        
        var totalChildMenu = document.querySelectorAll('.child'+parent).length; 
        var totalChildMenuChecked = $('.child'+parent).find($('[name="sub_menu_id[]"]:checked')).length;         
         
        if(totalChildMenuChecked == 0){
           $("#MENU_ID"+ parent).prop('checked', false);  
        }  

        $(".child"+ $(this).val()).find('.menu-perm').prop('checked', false);  
      }
      
      else{
        $('#MENU_ID'+parent).prop('checked', true);
        $(".child"+ $(this).val()).find('.menu-perm').prop('checked', true);
      }


      


      ////  Administrator Access
      var totalMenu = $('[name="MENU_ID[]"]').length; 
      var totalSubMenu = $('[name="sub_menu_id[]"]').length; 
          
      if(($('[name="MENU_ID[]"]:checked').length + $('[name="sub_menu_id[]"]:checked').length) === (totalMenu + totalSubMenu))
      $('#selectAll').prop('checked', true);
      else
      $('#selectAll').prop('checked', false);      
   
    });

    // Select All checkbox click
    const selectAll = document.querySelector('#selectAll'),
        checkboxList = document.querySelectorAll('[type="checkbox"]');
        selectAll.addEventListener('change', t => {
        checkboxList.forEach(e => {
          e.checked = t.target.checked;
        });
      });

  });

  // Edit Role Permissions

  $(document).on('click', '.role-edit-modal', function() {
    checkboxList = document.querySelectorAll('[name="MENU_ID"]');

   // alert(checkboxList.length);

    $('[type="checkbox"]').prop('checked', false);
    $('.dtr-bs-modal').modal('hide');
    roleTitle = document.querySelector('.role-title');
    var sysid = $(this).attr('data_sysid');
    roleTitle.innerHTML = 'Edit Role'; // reset text


    var url = '<?php echo base_url('/editRolePermission') ?>';
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
               $('#' + field).prop('disabled',false);
                $('#submitBtn').prop('disabled',false);
            if ($('#' + field + dataval).attr('type') == 'checkbox') {
              $('#' + field + dataval).prop('checked', true);
            } else if(dataval == 'Admin'){
                $('#' + field).val(dataval);
                $('#' + field).prop('disabled',true);
                $('#submitBtn').prop('disabled',true);
                $('#selectAll').prop('checked', true);

            }
            else{
              $('#' + field).val(dataval);
            }

              

          });
        });

            ////  Administrator Access
            var totalMenu = $('[name="MENU_ID[]"]').length; 
            var totalSubMenu = $('[name="sub_menu_id[]"]').length; 
            
                
            if(($('[name="MENU_ID[]"]:checked').length + $('[name="sub_menu_id[]"]:checked').length) === (totalMenu + totalSubMenu))
            $('#selectAll').prop('checked', true);
            else
            $('#selectAll').prop('checked', false); 
        $('#submitBtn').removeClass('btn-primary').addClass('btn-success').text('Update');
      }
    });
  });



  $(document).on('click', '.role-edit-modal', function() {
    $('#addRoleModal').modal('show');
  });



  // Add New or Edit Users submit Function

 function submitUserForm(id) {
    hideModalAlerts();

    var user_role_text = $("#USR_ROLE_ID option:selected").text();
    $("#USR_ROLE").val(user_role_text);

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

                $('#popModalWindow').modal('hide');
                $('.datatables-users').dataTable().fnDraw();
                $('.datatables-users-roles').dataTable().fnDraw();
                
            }
        }
    });
  }








  // Add Permissions Function

  function submitForm(id) {
    hideModalAlerts();
    var formSerialization = $('#' + id).serializeArray();
    var url = '<?php echo base_url('/addRolePermission') ?>';
    $.ajax({
      url: url,
      type: "post",
      data: formSerialization,
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      },
      dataType: 'json',
      success: function(respn) {
        console.log(respn, "testing");
        var response = respn['SUCCESS'];
        if (response != '1') {
          var ERROR = respn['RESPONSE']['ERROR'];
          var mcontent = '';
          $.each(ERROR, function(ind, data) {
            console.log(data, "SDF");
            mcontent += '<li>' + data + '</li>';
          });
          showModalAlert('error', mcontent);
        } else {
          var alertText = $('#ROLE_ID').val() == '' ? '<li>The new ROLE  \'' + $('#ROLE_NAME')
            .val() + '\' has been created  with permissions</li>' : '<li>The ROLE \'' + $('#ROLE_NAME').val() +
            '\' has been updated</li>';
          showModalAlert('success', alertText);
          $('#addRoleModal').modal('hide');
          $('.datatables-users').dataTable().fnDraw();
          $('.datatables-users-roles').dataTable().fnDraw();
          loadRoles();
          roleList();
          
      
        }
      }
    });
  }

  function loadRoles() {

    $.ajax({
      url: '<?php echo base_url('/loadUserRoles') ?>',
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      },
      dataType: 'html',
      success: function(respn) {

        $("#roleDiv").html(respn);

      }
    });
  }


  // User Edit Form

  $(document).on('click', '.editWindow', function() {

    countryList();
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
        var state_val = city_val = '';
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
            } else if (field == 'USR_CITY') {


              city_val = dataval;

            } else if (field == 'USR_STATE') {

              state_val = dataval;

            } else if (field == 'USR_COUNTRY') {

              $('#USR_COUNTRY').val(dataval).trigger('change', state_val);
              $('#USR_STATE').val(state_val).trigger('change', city_val);

            } else if (field == 'USR_PASSWORD') {
              $('#USR_CONFIRM_PASSWORD').val($('#USR_PASSWORD').val());
            } else {

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
      url: '<?php echo base_url('/userCountryList') ?>',
      type: "post",
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      },
      async: false,
      success: function(respn) {
        $('#USR_COUNTRY').html(respn);
      }
    });

  }

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


  $("#USR_COUNTRY").change(function(e, param = 0) {
    var ccode = $(this).val();
    $.ajax({
      url: '<?php echo base_url('/userStateList') ?>',
      type: "post",
      async: false,
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      },
      data: {
        ccode: ccode,
        state_id: param
      },
      success: function(respn) {
        $('#USR_STATE').html(respn);

      }
    });
  });



  $("#USR_STATE").change(function(e, param = 0) {
    var scode = $('#USR_STATE').val();
    var ccode = $('#USR_COUNTRY').val();
    $.ajax({
      url: '<?php echo base_url('/userCityList') ?>',
      type: "post",
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      },
      data: {
        ccode: ccode,
        scode: scode,
        cityid: param
      },
      success: function(respn) {
        $('#USR_CITY').html(respn).trigger('change');
      }
    });
  });


  $("#slide1").click(function(){
    $("#slide1").slideToggle('slow');
  });


  $( ".parent" ).click(function() {
    
    child = $(this).attr('rel');
    $(".child"+ child).slideToggle(400);
    $(this).find($(".fa-chevron-down")).toggleClass("active");
 
});
</script>


<?= $this->endSection() ?>