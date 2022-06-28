<?= $this->extend("Layout/AppView") ?>
<?= $this->section("contentRender") ?>
<?= $this->include('Layout/ErrorReport') ?>
<?= $this->include('Layout/SuccessReport') ?>

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
                  <table class="table table-flush-spacing">
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
                                <input id="selectAll" name="MENU_ID[]" id="MENU_ID0" type="checkbox" value="0" class="switch-input" />
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
                        </td>
                      </tr>

                      <?php if (!empty($responseMenu)) {
                        foreach ($responseMenu as $row) {
                      ?>
                          <tr>
                            <td class="text-nowrap"><?php echo $row['MENU_NAME'] ?></td>
                            <td>
                              <div class="d-flex">
                                <div class="form-check me-3 me-lg-5">
                                  <label class="switch switch-lg">
                                    <input id="MENU_ID<?php echo $row['MENU_ID'] ?>" name="MENU_ID[]" type="checkbox" value="<?php echo $row['MENU_ID'] ?>" class="switch-input" />
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
                          </tr>
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

  </div>
  <!-- / Content -->
  <div class="content-backdrop fade"></div>
</div>
<script>
  $(document).ready(function() {

    loadRoles();

    // Users datatable
    var dt_user_table = $('.datatables-users');
    var dt_user_roles_table = $('.datatables-users-roles');
    userView = 'app-user-view-account.html';
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
              var $name = full['USR_FIRST_NAME'] + '' + full['USR_LAST_NAME'],
                $email = full['USR_EMAIL'],
                $image = full['USR_IMAGE'];
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
                '<a href="' +
                userView +
                '" class="text-body text-truncate"><span class="fw-semibold">' +
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
              var $role = full['ROLE_DESC'];
              if (full['USR_ROLE'] <= 3) {
                var roleBadgeObj = {
                  Guest: '<span class="badge badge-center rounded-pill bg-label-warning w-px-30 h-px-30 me-2"><i class="bx bx-user bx-xs"></i></span>',
                  Editor: '<span class="badge badge-center rounded-pill bg-label-info w-px-30 h-px-30 me-2"><i class="bx bx-edit bx-xs"></i></span>',
                  Admin: '<span class="badge badge-center rounded-pill bg-label-secondary w-px-30 h-px-30 me-2"><i class="bx bx-mobile-alt bx-xs"></i></span>'
                };
                return "<span class='text-truncate d-flex align-items-center'>" + roleBadgeObj[$role] + $role + '</span>';
              } else {
                return "<span class='text-truncate d-flex align-items-center'><span class='badge badge-center rounded-pill bg-label-primary w-px-30 h-px-30 me-2'><i class='bx bx-pie-chart-alt bx-xs'></i></span>" + $role + '</span>';
              }
            }
          },
          {
            // Department
            targets: 3,
            render: function(data, type, full, meta) {
              var $DEPT_CODE = (full['DEPT_CODE'] + '| ' + full['DEPT_DESC']);
              return '<span class="fw-semibold">' + $DEPT_CODE + '</span>';
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

      // Select All checkbox click
      const selectAll = document.querySelector('#selectAll'),
        checkboxList = document.querySelectorAll('[type="checkbox"]');
      selectAll.addEventListener('change', t => {
        checkboxList.forEach(e => {
          e.checked = t.target.checked;
        });
      });

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

  });

  // Edit Role Permissions

  $(document).on('click', '.role-edit-modal', function() {


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
            // alert(field);
            // alert($('#' + field+dataval).attr('type'));
            if ($('#' + field + dataval).attr('type') == 'checkbox') {
              $('#' + field + dataval).prop('checked', true);
            } else

              $('#' + field).val(dataval);

          });
        });
        $('#submitBtn').removeClass('btn-primary').addClass('btn-success').text('Update');
      }
    });
  });



  $(document).on('click', '.role-edit-modal', function() {
    $('#addRoleModal').modal('show');
  });





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
          loadRoles();
        }
      }
    });
  }

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
</script>


<?= $this->endSection() ?>