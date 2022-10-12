          <style>

.flex-grow-1.notification-text p{
  margin-bottom:0px !important;padding-bottom:0px !important;
  
}
          </style>
          <!-- Navbar -->

          <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
            <div class="container-fluid">
              <div class="layout-menu-toggle navbar-nav d-xl-none align-items-xl-center me-3 me-xl-0">
                <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                  <i class="bx bx-menu bx-sm"></i>
                </a>
              </div>

              <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                <!-- Search -->
                <div class="navbar-nav align-items-center">
                  <div class="nav-item navbar-search-wrapper mb-0">
                    <a class="nav-item nav-link search-toggler px-0" href="javascript:void(0);">
                      <i class="bx bx-search-alt bx-sm"></i>
                      <span class="d-none d-md-inline-block text-muted">Search Here</span>
                    </a>
                  </div>
                </div>
                <!-- /Search -->

                <ul class="navbar-nav flex-row align-items-center ms-auto">
                

                

                  <!-- Style Switcher -->
                  <li class="nav-item me-2 me-xl-0">
                    <a class="nav-link style-switcher-toggle hide-arrow" href="javascript:void(0);">
                      <i class="bx bx-sm"></i>
                    </a>
                  </li>
                  <!--/ Style Switcher -->

                  <!-- Quick links  -->
                  <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-2 me-xl-0">
                    <a
                      class="nav-link dropdown-toggle hide-arrow"
                      href="javascript:void(0);"
                      data-bs-toggle="dropdown"
                      data-bs-auto-close="outside"
                      aria-expanded="false"
                    >
                      <i class="bx bx-grid-alt bx-sm"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end py-0">
                      <div class="dropdown-menu-header border-bottom">
                        <div class="dropdown-header d-flex align-items-center py-3">
                          <h5 class="text-body me-auto mb-0">Shortcuts</h5>
                          <a
                            href="javascript:void(0)"
                            class="dropdown-shortcuts-add text-body"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="Add shortcuts"
                            ><i class="bx bx-sm bx-plus-circle"></i
                          ></a>
                        </div>
                      </div>
                      <div class="dropdown-shortcuts-list scrollable-container">
                 
           
                        <div class="row row-bordered overflow-visible g-0">
                          <div class="dropdown-shortcuts-item col">
                            <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                              <i class="bx bx-pie-chart-alt-2 fs-4"></i>
                            </span>
                            <a href="javascript:void(0);" class="stretched-link">Dashboard</a>
                            <small class="text-muted mb-0">User Profile</small>
                          </div>
                          <div class="dropdown-shortcuts-item col">
                            <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                              <i class="bx bx-cog fs-4"></i>
                            </span>
                            <a href="javascript:void(0);" class="stretched-link">Settings</a>
                            <small class="text-muted mb-0">Account Settings</small>
                          </div>
                        </div>

                        <div class="row row-bordered overflow-visible g-0">
                          <div class="dropdown-shortcuts-item col">
                            <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                              <i class="bx bx-calendar fs-4"></i>
                            </span>
                            <a href="javascript:void(0);" class="stretched-link">Calendar</a>
                            <small class="text-muted mb-0">Appointments</small>
                          </div>

                          <div class="dropdown-shortcuts-item col">
                            <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                              <i class="bx bx-help-circle fs-4"></i>
                            </span>
                            <a href="javascript:void(0);" class="stretched-link">Help Center</a>
                            <small class="text-muted mb-0">FAQs & Articles</small>
                          </div>
                     
                        </div>
              
                      </div>
                    </div>
                  </li>
                  <!-- Quick links -->

                  <!-- Notification -->
                  <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-2">
                    <a
                      class="nav-link dropdown-toggle hide-arrow"
                      href="javascript:void(0);"
                      data-bs-toggle="dropdown"
                      data-bs-auto-close="outside"
                      aria-expanded="false"
                    >
                      <i class="bx bx-bell bx-sm"></i>
                      <span class="badge rounded-pill badge-notifications bg-danger"> <?= view_cell('\App\Libraries\Notification::NotificationCount') ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end py-0">
                      <li class="dropdown-menu-header border-bottom">
                        <div class="dropdown-header d-flex align-items-center py-3">
                          <h5 class="text-body me-auto mb-0">Notification</h5>
                          <a
                            href="javascript:void(0)"
                            class="dropdown-notifications-all text-body read-all"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="Mark all as read"
                            ><i class="bx fs-4 bx-envelope-open"></i
                          ></a>
                        </div>
                      </li>
                      <!-- <li class="dropdown-notifications-list scrollable-container">
                        <ul class="list-group list-group-flush">
                         -->
                        
                           <?= view_cell('\App\Libraries\Notification::ShowAll') ?>
                        
                        <!-- </ul>
                      </li>
                      <li class="dropdown-menu-footer border-top">
                        <a href="javascript:void(0);" class="dropdown-item d-flex justify-content-center p-3">
                          View all notifications
                        </a>
                      </li> -->
                    </ul>
                  </li>
                  <!--/ Notification -->

                  <!-- User -->
                  <li class="nav-item navbar-dropdown dropdown-user dropdown">
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                      <div class="avatar avatar-online">
                        <img src="<?php echo base_url()?>/assets/img/avatars/1.png" alt class="rounded-circle" />
                      </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                      <li>
                        <a class="dropdown-item" href="javascript:void(0)">
                          <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                              <div class="avatar avatar-online">
                                <img src="<?php echo base_url()?>/assets/img/avatars/1.png" alt class="rounded-circle" />
                              </div>
                            </div>
                            <div class="flex-grow-1">
                              <span class="lh-1 d-block fw-semibold"><?php echo session()->get('name'); ?></span>
                              <small><?php echo session()->get('role'); ?></small>
                            </div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <div class="dropdown-divider"></div>
                      </li>
                      <li>
                        <a class="dropdown-item" href="javascript:void(0);">
                          <i class="bx bx-user me-2"></i>
                          <span class="align-middle">My Profile</span>
                        </a>
                      </li>
                      <li>
                        <a class="dropdown-item" href="javascript:void(0);">
                          <i class="bx bx-cog me-2"></i>
                          <span class="align-middle">Settings</span>
                        </a>
                      </li>
           
                      <li>
                        <div class="dropdown-divider"></div>
                      </li>
                      <li>
                        <a class="dropdown-item" href="javascript:void(0);">
                          <i class="bx bx-support me-2"></i>
                          <span class="align-middle">Help</span>
                        </a>
                      </li>
                      <li>
                        <a class="dropdown-item" href="javascript:void(0);">
                          <i class="bx bx-help-circle me-2"></i>
                          <span class="align-middle">FAQ</span>
                        </a>
                      </li>
                
                      <li>
                        <div class="dropdown-divider"></div>
                      </li>
                      <li>
                        <a class="dropdown-item" href="<?php echo base_url('/logout') ?>">
                          <i class="bx bx-power-off me-2"></i>
                          <span class="align-middle">Log Out</span>
                        </a>
                      </li>
                    </ul>
                  </li>
                  <!--/ User -->
                </ul>
              </div>

              <!-- Search Small Screens -->
              <div class="navbar-search-wrapper search-input-wrapper d-none">
                <input
                  type="text"
                  class="form-control search-input main-menu-search container-fluid border-0"
                  placeholder="Enter 3 or more characters..."
                  aria-label="Enter 3 or more characters..."
                />
                <i class="bx bx-x bx-sm search-toggler cursor-pointer"></i>
              </div>
            </div>
           
          </nav>

          <!-- / Navbar -->
          

           <!-- Toast with Placements -->
          <div class="bs-toast toast toast-placement-ex m-2"
                role="alert"
                aria-live="assertive"
                aria-atomic="true"
                data-delay="2000"
              >
                <div class="toast-header">
                  <div class="fw-semibold me-auto" id="toast-header-title"></div>
                  <small id="toast-body-time">11 mins ago</small>
                  <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body" id="toast-body-message"></div>
          </div>
              <!-- Toast with Placements -->

              <!-- Notification Modal window -->
    <div class="modal fade" id="ViewAllNotificationsModal" data-backdrop="static" data-keyboard="false"
        aria-labelledby="popModalWindowlabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="popModalWindowlabel">All Notifications</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="" onSubmit="return false">
                        <div id="ViewAllNotifications" class="content">                            
                            <div class="row g-3">                                
                                <div class="col-md-12">
                                    <div class="border rounded p-4 mb-3"> 
                                        <div class="table-responsive text-nowrap">
                                        <table id="dataTable_view1" class="dt-responsive table table-striped display nowrap" style="width:100%">
                                        <thead>
                                            <tr>
                                               
                                                <th>Notification ID</th> 
                                                <th>View</th> 
                                                <th class="all">Notification Type</th>
                                                <th>Reservation</th>    
                                                <th class="all">Message</th>
                                                <th class="all">Date & Time</th>
                                                <th class="all">Status</th>
                                            </tr>
                                        </thead>
                                        </table>
                                        </div>
                                        <br />
                                    </div>
                                </div>
                                <div class="d-flex col-12 justify-content-between">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>

                                </div>

                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- /Modal  window -->

        <!-- Modal -->
  <div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalCenterTitle"></h5>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
          ></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col mb-3 ">
              <span class="showDetails"></span>
             
            </div>
          </div>       
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
            Close
          </button>          
        </div>
      </div>
    </div>
  </div>

<script>

  
$(document).on('click', '#ViewAll', function() {
 


  var dt_notification_table = $('#dataTable_view1'),
      select2 = $('.select2'),
      userView = '',
      statusObj = {
        1: {
          title: 'Seen',
          class: 'bg-label-success'
        },
        0: {
          title: 'Unseen',
          class: 'bg-label-secondary'
        }
      };

  if (dt_notification_table.length) {
    var dt_notification = dt_notification_table.DataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': '<?php echo base_url('/userNotifications')?>'
        },
        columns: [        
          {
            data: 'NOTIF_TRAIL_ID',
            visible:false
          },
          {
                data: null,
                className: "text-center",
                "orderable": false,
                render: function(data, type, row, meta) {
                
                    if(data['NOTIF_TRAIL_ID'] != ''){
                        return (
                            '<div class="d-inline-block">' +
                            '<a  href="javascript:;" data_sysid="' + data['NOTIF_TRAIL_ID'] +
                            '" class="dropdown-item text-primary" onClick="viewAllNotif(' + data['NOTIF_TRAIL_ID'] +')"><i class="fa fa-eye" aria-hidden="true"></i></a>'+
                            '</div>'
                        );
                    }
                }
            },
          {
            data: 'NOTIF_TY_DESC'
          },            
          
          
          {
            data: 'NOTIFICATION_RESERVATION_ID',
            render: function(data, type, full, meta) {                                      
              if(full['NOTIFICATION_RESERVATION_ID'] != ''){
                return full['NOTIFICATION_RESERVATION_ID'];
               // return '<a href="javascript:;" onclick="viewAll(\'Reservations\','+full['NOTIFICATION_ID']+')" title="View Reservations"  rel="">'+full['NOTIFICATION_RESERVATION_ID']+'<br><span class="btn btn-sm btn-label-info">View All</span></br></a>';
              }else return '';
            }
          },      
          
          {
            data: 'NOTIFICATION_TEXT',
            render: function(data, type, full, meta) {
             if(full['NOTIFICATION_TEXT'] != ''){
                return full['NOTIFICATION_TEXT'];
                //return '<a href="javascript:;" onclick="viewAll(\'Messages\','+full['NOTIFICATION_ID']+')" title="View Message" rel="">'+full['NOTIFICATION_TEXT']+'<span class="btn btn-sm btn-label-info">View Message</span></a>';
             }else return '';
            }
          },
          
          {
            data: 'NOTIF_TRAIL_DATETIME'
          },
          {
            data: 'NOTIF_TRAIL_READ_STATUS'
          },
         
        ],
        columnDefs: [{
            width: "15%"
        }, {
            width: "15%"
        },        
        {
            width: "10%"
        },
        {
            width: "10%"
        },
        {
            targets: 5,
            width: "10%",
            render: function(data, type, full, meta) {
              if(full['NOTIF_TRAIL_DATETIME'] != ''){
                var NOTIFICATION_DATE_TIME = full['NOTIF_TRAIL_DATETIME'].split(".");               
              if(NOTIFICATION_DATE_TIME[0] == '1900-01-01 00:00:00'){
                  return '';
                }
              else 
                return NOTIFICATION_DATE_TIME[0];
               }
            }

        },
        {
            targets: 6,
            width: "10%",
            render: function(data, type, full, meta) {
              var $status = full['NOTIF_TRAIL_READ_STATUS'];
              return '<span class="notifi-status-'+full['NOTIF_TRAIL_ID']+' badge ' + statusObj[$status].class + '">' + statusObj[$status].title + '</span>';
              
            }
        }],
        "order": [
            [0, "desc"]
        ],
        'createdRow': function(row, data, dataIndex) {
          if(data['NOTIF_TRAIL_READ_STATUS'] == 0)
            $(row).addClass('table-warning');

            $(row).addClass('notifi-'+data['NOTIF_TRAIL_ID']);
         
        },

        destroy: true,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',

    });
  }
  $('#ViewAllNotificationsModal').modal('show');
  
});


$(document).on('click', '.read-all', function() {
  $(".badge-notifications").html('0');
  $(".notifi-icon-all").css('color','#ddd'); 
  $(".dropdown-notifications-read").css('display','none'); 
  $.ajax({
        url: '<?php echo base_url('/readNotifications') ?>',       
        type: "get",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },       
        dataType: 'json',
        success: function(respn) {
           
        }
    });
      
});

$(document).on('click', '.dropdown-notifications-item', function() {
    var NOTIF_TRAIL_ID  = $(this).attr('rel'); 
    $("#notifi-icon-"+ NOTIF_TRAIL_ID).css('color','#ddd'); 
    $("#notifications-read-"+ NOTIF_TRAIL_ID).css('display','none'); 
    $.ajax({
        url: '<?php echo base_url('/updateNotification') ?>',       
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },   
        data:{
          NOTIF_TRAIL_ID:NOTIF_TRAIL_ID
        },
        dataType: 'json',
        success: function(respn) {
          if(respn != null){
            $(".badge-notifications").html(respn.responseStatusCount);
            $("#toast-header-title").html(respn.NOTIF_TY_ICON+ ' ' +respn.NOTIF_TY_DESC);
            $("#toast-body-message").html(respn.NOTIFICATION_TEXT);
            $("#toast-body-time").html(respn.NOTIFICATION_DATE_TIME);            
            showMessage();
          }

        }
    });

  });
function showMessage(){ 
    // Placement Button click  
      selectedType = "bg-secondary";
      selectedPlacement = "top-1 end-0";
      selectedPlacement = selectedPlacement.split(' '); 
      toastPlacementExample = document.querySelector('.toast-placement-ex');
      toastPlacementHeaderExample = document.querySelector('.toast-placement-ex .toast-header');
      toastPlacementHeaderExample.classList.add(selectedType);
      DOMTokenList.prototype.add.apply(toastPlacementExample.classList, selectedPlacement);
      toastPlacement = new bootstrap.Toast(toastPlacementExample);
      toastPlacement.show();

    }

function viewAllNotif(notificationTrailId){
 $(".showDetails").html('');
  $.ajax({
        url: '<?php echo base_url('/viewAllNotification') ?>',
        async: false,
        type: "post",

        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
          notificationTrailId: notificationTrailId,
        },
        dataType: 'json',
        success: function(respn) {
          $("#dataTable_view1 .notifi-"+notificationTrailId).removeClass('table-warning');
          $(".notifi-status-"+notificationTrailId).html('Seen');
          $("#modalCenterTitle").html();
          $(".showDetails").html(respn.reservation+' <br> '+respn.text);
          $("#modalCenter").modal('show')
        }
    });
    
}

      


</script>