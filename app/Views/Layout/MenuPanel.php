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
                        </div>
                      </div>
                      <div class="dropdown-shortcuts-list scrollable-container">
                 
           
                        <div class="row row-bordered overflow-visible g-0">
                          <div class="dropdown-shortcuts-item col">
                            <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                              <i class="bx bx-pie-chart-alt-2 fs-4"></i>
                            </span>
                            <a href="<?php echo base_url() ?>" class="stretched-link">Dashboard</a>
                            <small class="text-muted mb-0">Display all Info</small>
                          </div>
                          <div class="dropdown-shortcuts-item col">
                            <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                              <i class="bx bx-desktop fs-4"></i>
                            </span>
                            <a href="<?php echo base_url('/frontDesk') ?>" class="stretched-link">Front Desk</a>
                            <small class="text-muted mb-0">Arrivals, Guests etc</small>
                          </div>
                        </div>

                        <div class="row row-bordered overflow-visible g-0">
                          <div class="dropdown-shortcuts-item col">
                            <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                              <i class="fa fa-cart-flatbed-suitcase fs-4"></i>
                            </span>
                            <a href="<?php echo base_url('/reservation') ?>" class="stretched-link">Reservations</a>
                            <small class="text-muted mb-0">View All & Add New</small>
                          </div>

                          <div class="dropdown-shortcuts-item col">
                            <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                              <i class="bx bx-cog fs-4"></i>
                            </span>
                            <a href="<?php echo base_url('/my-profile/edit') ?>" class="stretched-link">Settings</a>
                            <small class="text-muted mb-0">Profile Settings</small>
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
                          <h5 class="text-body me-auto mb-0">Notifications</h5>
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
                          <li class="dropdown-notifications-list scrollable-container">
                           <?= view_cell('\App\Libraries\Notification::ShowAll') ?>
                          </li>
                        
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
                        <?php
                          $user_img = session()->get('USR_IMAGE');
                          $user_img = file_exists($user_img) ? base_url().'/'.$user_img : base_url().'/assets/img/avatars/avatar-generic.jpg';
                        ?>
                        <img src="<?=$user_img?>" alt class="rounded-circle" />
                      </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                      <li>
                        <a class="dropdown-item" href="<?php echo base_url('/my-profile') ?>">
                          <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                              <div class="avatar avatar-online">  
                                <img src="<?=$user_img?>" alt class="rounded-circle" />
                              </div>
                            </div>
                            <div class="flex-grow-1">
                              <span class="lh-1 d-block fw-semibold"><?php echo session()->get('USR_FIRST_NAME').' '.session()->get('USR_LAST_NAME'); ?></span>
                              <small><?php echo session()->get('USR_ROLE'); ?></small>
                            </div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <div class="dropdown-divider"></div>
                      </li>
                      <li>
                        <a class="dropdown-item" href="<?php echo base_url('/my-profile') ?>">
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
                    <h4 class="modal-title" id="popModalWindowlabel1">All Notifications</h4>
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
  
  $('#loader_flex_bg').show();

  var dt_notification_table = $('#dataTable_view1'),
      select2 = $('.select2'),
      userView = '',
      statusObj = {
        3: {
          title: 'Not resolved',
          class: 'bg-label-warning'
        },
        2: {
          title: 'Resolved',
          class: 'bg-label-info'
        },
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
        'initComplete': function(){$('#loader_flex_bg').hide();},
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
            data: 'NOTIFICATION_RESERVATION_ID'
          },      
          
          {
            data: 'NOTIFICATION_TEXT',
            "orderable": false,
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
            data: 'NOTIF_TRAIL_READ_STATUS',
           
          },
         
        ],
        columnDefs: [{
            width: "15%"
        }, {
            width: "15%"
        },        
        {
            width: "10%",
            "orderable": false
        },
        {
            width: "10%",
            targets: 3,
            "orderable": false,
            render: function(data, type, full, meta) {                   
              if(full['NOTIFICATION_RESERVATION_ID'] != ''){
                return getResvNo(full['NOTIFICATION_RESERVATION_ID'], full['NOTIF_TY_ID'],full['NOTIF_TRAIL_ID'],full['NOTIFICATION_ID'],full['NOTIF_TRAIL_READ_STATUS']);
              }
              else 
              return '';
              
            }
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
              // if(full['NOTIF_TY_ID'] == 4  ) {
              //   if(full['NOTIF_TRAIL_READ_STATUS'] == 2){
              //   $status = 2;
              //   }else{
              //     $status = 3;
              //   }
              // }
              // else
               $status = full['NOTIF_TRAIL_READ_STATUS'];

              
              return '<span class="notifi-status-'+full['NOTIF_TRAIL_ID']+' badge ' + statusObj[$status].class + '" id="not_status">' + statusObj[$status].title + '</span>';
              
            }
        }],
        "order": [
            [5, "desc"]
        ],
        'createdRow': function(row, data, dataIndex) {
          if(data['NOTIF_TRAIL_READ_STATUS'] == 0)
            $(row).addClass('table-warning');

            $(row).addClass('notifi-'+data['NOTIF_TRAIL_ID']);
         
        },

        destroy: true,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',

    });

    $('#ViewAllNotificationsModal').modal('show');

    
  }
 
 
  
  
  
});

function getResvNo(NOTIFICATION_RESERVATION_ID, NOTIF_TY_ID,NOTIF_TRAIL_ID, NOTIFICATION_ID, NOTIFICATION_READ_STATUS ){
  var resvNo = NOTIFICATION_RESERVATION_ID;
  
    // $.ajax({
    //     url: '<?php echo base_url('/getResvNo') ?>',       
    //     type: "post",
    //    async: false,
    //     data:{
    //       NOTIFICATION_RESERVATION_ID : NOTIFICATION_RESERVATION_ID, 
    //       NOTIFICATION_ID:NOTIFICATION_ID                     
    //     },
    //     headers: {
    //         'X-Requested-With': 'XMLHttpRequest'
    //     }, 
    //     dataType:'json',

    // }).done(function(respn) {         
          
    //   resvNo =  respn;
    //       });
          
          var result = '';
          if(resvNo != null){ 
            var result = resvNo;
            if(NOTIF_TY_ID == 4 && NOTIFICATION_READ_STATUS == 0){
              result +='<br><button type="button" class="btn btn-sm btn-primary" title="Resolve" id="resolveButton"  data-trail_id ="'+NOTIF_TRAIL_ID+'" data-notification_id = "'+NOTIFICATION_ID+'" >Resolve</button>';    
              
              }
            return result;
          }
}


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
  if( $(this).data('resolve') === "resolve"){
    $('#loader_flex_bg').show();
    $('#ViewAll').click();
    
  }else{
    
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
  }

  });




    $(document).on('click', '#resolveButton', function() {
		hideModalAlerts();
		$('.dtr-bs-modal').modal('hide');

		var trail_id = $(this).data('trail_id');
    var notificationId = $(this).data('notification_id');
		bootbox.confirm({
			message: "Are you sure you want to resolve this notification?",
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
						url: '<?php echo base_url('/resolveNotification') ?>',
						type: "post",
						data: {
							NOTIF_TRAIL_ID: trail_id,
              NOTIF_NOTIF_ID:notificationId

						},
						headers: {
							'X-Requested-With': 'XMLHttpRequest'
						},
						dataType: 'json',
						success: function(respn) {
                if(respn != null && respn.status === "success"){             
                $('#resolveButton').hide();
                $("#not_status").html('SEEN');
                $("#not_status").removeClass('bg-label-warning');
                $("#not_status").addClass('bg-label-success');
                $("#dataTable_view1 .notifi-"+trail_id).removeClass('table-warning');
                $(".badge-notifications").html(respn.responseStatusCount);

                var alertText = '<li>Successfully resolved</li>';
                showModalAlert('success', alertText);
              }
              else{
                var alertText = '<li>Failed</li>';
                showModalAlert('error', alertText);
              }
						}


					});
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
          $(".badge-notifications").html(respn.NotificationCount);
          $("#modalCenter").modal('show')
        }
    });
    
}

function loadNotifications(){
  $.ajax({
        url: '<?php echo base_url('/loadNotification') ?>',
        async: false,
        type: "post",

        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data: {
          realtime: 1,
        },
        dataType: 'json',
        success: function(respn) {
          if(respn != null){
            $(".dropdown-notifications-list").html(respn.notif_list);
            $(".badge-notifications").html(respn.notif_count);
           
          }

        }
    });
}

setInterval(loadNotifications, 100000);      


</script>