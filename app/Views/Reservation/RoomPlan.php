<?= $this->extend("Layout/CalendarView") ?>
<?= $this->section("contentRender") ?>
<?= $this->include('Layout/SuccessReport') ?>
<?= $this->include('Layout/ErrorReport') ?>
<style>

/* .fc-scroller-canvas {
    max-width: 100% !important;
    min-width: 100% !important;
} */
#RoomStatisticsModal{

}

.fc-resource-area .fc-rows table tr td:first-child:hover{
  cursor: pointer;
  color: #000;

}
.fc-time-area.fc-widget-header .fc-content table tr:nth-child(2){
  display: none;
}
.fc-cell-text{
  font-size: 14px !important;
}
.flxy_table_resp .table-bordered th{
  padding-right: 12px !important;
} 
/* .fc-time-area  .fc-rows td.fc-widget-content {
 
 padding: 0 25px !important;
} */

 .fc-time-area.fc-widget-header{
  padding: 0 0px !important;
}


.fc-divider {
  padding: 0 !important;
}


.fc-time-area .fc-rows td>div {
    position: relative;
    height: 37px !important;
}

.fc-rows table tr{
  height: 37px !important;
}
#RoomStatisticsModal .fc-scroller, #calendarRoomPlan .fc-scroller {
    height: auto !important;
}
  #calendar {
    max-width: 1000px;
    margin: 40px auto;
    font-size: 14px;
  }

  .fc-time {
    display: none !important;
  }

  .fc-event-time {
    display: none
  }


  .fc-time-grid-event.fc-short .fc-time,
  .fc-time-grid-event .fc-time {
    display: none !important;
  }

  .fc-time-grid .fc-content-skeleton {
    position: absolute;
    z-index: 3;
    top: 0;
    left: 0;
    right: 0;
    height: 100%;
  }

  .fc-event-container .fc-timeline-event {
    /* background-color: #405974 !important;
    border-color: #405974 !important;
    color: rgb(255, 255, 255) !important; */

    top: 3px !important;
  }

  .fc-timeline-event .fc-time,
  .fc-timeline-event .fc-title {
    padding: 4px !important;
    font-size: 13px !important;
  }

  .fc-resource-area col.fc-main-col {
    width: 35% !important;
  }

  .tooltip {
    opacity: 1;
    font-size: 13px !important;
  }

  #errorModal {
    display: none;
  }

  #errorModal {
    position: fixed;
    top: 10px;
    right: 22px;
    z-index: 10000;
    width: 500px;
  }
  table tr{
    cursor: pointer;
  }

  .fc-custom1-button{
    margin-left:10px !important;
    pointer-events: none;
    background-color: #ccc;
    color: #fff;
    
  }




</style>

<!-- Content wrapper -->
<div class="content-wrapper">
  <!-- Content -->
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="card app-calendar-wrapper">
   
      <div class="row g-0">
        <div class="row">
          <div class="col-md-6 mt-3 mb-3">
            <button type="button" class="btn btn-primary"  id="showRoomStatisticsModal" 
            > Statistics</button>
            <button type="button" class="btn btn-primary" id="showRoomOSModal"  > OOO / OOS</button>
          </div> 
        </div>       

        <!-- Calendar & Modal -->
        <div class="app-calendar-content col">
          <div class="card shadow-none border-0">

          <div class="card-body"> 
                  <form class="dt_adv_search" method="POST" action="<?php echo base_url();?>/roomPlan" >
                  <input type="hidden" id="SEARCH_CLEAR" name="SEARCH_CLEAR" value="0"/>
                  <div class="border rounded p-3 mb-3">
                    <div class="row">
                      <div class="col-12">
                        <div class="row g-3">
                          <div class="col-12 col-sm-6 col-lg-4">
                            <label class="form-label"><b>Date:</b></label>
                            <input type="text" id="SEARCH_DATE" name="SEARCH_DATE" class="form-control dt-date" data-column="0" placeholder="" value="" />

                          </div>
                          <div class="col-12 col-sm-6 col-lg-4">
                            <label class="form-label"><b>Room Type:</b></label>                            
                            <select id="SEARCH_ROOM_TYPE" name="SEARCH_ROOM_TYPE" class="select2 form-select" data-allow-clear="true"  >                                       
                            </select>   
                          </div>
                          <div class="col-12 col-sm-6 col-lg-4">
                            <label class="form-label"><b>Room Class:</b></label>
                            <select id="SEARCH_ROOM_CLASS" name="SEARCH_ROOM_CLASS" class="select2 form-select" data-allow-clear="true" placeholder="Room Class">                                       
                            </select>  
                            
                          </div>

                          <div class="col-12 col-sm-6 col-lg-4">
                            <label class="form-label"><b>Floor:</b></label>
                            <select id="SEARCH_ROOM_FLOOR" name="SEARCH_ROOM_FLOOR" class="select2 form-select" data-allow-clear="true" >                                       
                            </select>                            
                          </div>

                          <div class="col-12 col-sm-6 col-lg-4">
                            <label class="form-label"><b>Room Status:</b></label>
                            <select id="SEARCH_ROOM_STATUS" name="SEARCH_ROOM_STATUS" class="select2 form-select" data-allow-clear="true" >                                       
                            </select>                             
                          </div>

                          <div class="col-12 col-sm-6 col-lg-4">
                            <label class="form-label"><b>Room:</b></label>
                            <select id="SEARCH_ROOM" name="SEARCH_ROOM" class="select2 form-select" data-allow-clear="true" >                                       
                            </select>                             
                          </div>
                                

                          <div class="col-12 col-sm-6 col-lg-4">
                          <label class="switch switch-md">
                              <input id="SEARCH_ASSIGNED_ROOMS" name="SEARCH_ASSIGNED_ROOMS" type="checkbox" 
                                  class="switch-input"  />
                              <span class="switch-toggle-slider">
                                  <span class="switch-on">
                                      <i class="bx bx-check"></i>
                                  </span>
                                  <span class="switch-off">
                                      <i class="bx bx-x"></i>
                                  </span>
                              </span>
                              <span class="switch-label"><b>Assigned rooms </b></span>
                          </label>
                          </div>
                          <div class="col-12 col-sm-6 col-lg-4">
                          <label class="switch switch-md">
                            <input id="SEARCH_UNASSIGNED_ROOMS" name="SEARCH_UNASSIGNED_ROOMS" type="checkbox" 
                                class="switch-input"  />
                            <span class="switch-toggle-slider">
                                <span class="switch-on">
                                    <i class="bx bx-check"></i>
                                </span>
                                <span class="switch-off">
                                    <i class="bx bx-x"></i>
                                </span>
                            </span>
                            <span class="switch-label"><b>Unassigned rooms </b></span>
                        </label>
                          </div>
                          <div class="col-12 col-sm-6 col-lg-4">
                            <button type="submit" class="btn btn-primary submitAdvSearch" name="Search" id="Search" >
                                <i class='bx bx-search'></i>&nbsp;
                                Search
                            </button>
                                                                
                            <button type="button" class="btn btn-secondary clearAdvSearch">Clear</button>
                            <button type="button" class="empty_rooms" style="display: none;"></button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  </form>
                </div>
          <div class="card-body">         
            <!-- <div class="col-12 col-sm-6 col-lg-4 pb-2 pt-0 text-end">
              <button type="submit" class="btn btn-primary" name="Assign Rooms" id="assignRooms"  >
              <i class="fa-solid fa-list-check"></i>
                  Assign Room
              </button>                           
            
          </div> -->
                   
              <!-- FullCalendar -->
          <div id="calendarRoomPlan"></div>
          <?php if(empty($RoomResources) || empty($RoomReservations)){?>
          <div class="alert alert-danger mt-3" role="alert">Sorry. No Records Found!!</div>
          <?php } else{ ?>
                <div class="col-lg-8">                      
                  <div class="demo-inline-spacing">
                    <?php echo $pager_links;?>                                        
                  </div>
                </div>
              <?php } ?>              
            </div>
          </div>
          <div class="app-overlay"></div>
          <!-- FullCalendar Offcanvas -->
          
        </div>
        <!-- /Calendar & Modal -->
      </div>



     

    <div class="modal fade" id="RoomStatisticsModal" data-backdrop="static" data-keyboard="false"
        aria-labelledby="popModalWindowlabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="popModalWindowlabel">Statistics</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" ></button>
                </div>
                <div class="modal-body">
               <!-- Calendar Sidebar -->
        <div class="app-calendar-sidebar col" id="app-calendar-sidebar" style="display: none">
          <div class="border-bottom p-4 my-sm-0 mb-3">
            <div class="d-grid">
              <button class="btn btn-primary btn-toggle-sidebar" data-bs-toggle="offcanvas" data-bs-target="#addEventSidebar" aria-controls="addEventSidebar">
                <i class="bx bx-plus"></i>
                <span class="align-middle">Add Event</span>
              </button>
            </div>
          </div>
          <div class="p-4">
            <!-- inline calendar (flatpicker) -->
            <div class="ms-n2">
              <div class="inline-calendar"></div>
            </div>

            <hr class="container-m-nx my-4" />

            <!-- Filter -->
            <div class="mb-4">
              <small class="text-small text-muted text-uppercase align-middle">Filter</small>
            </div>

            <div class="form-check mb-2">
              <input class="form-check-input select-all" type="checkbox" id="selectAll" data-value="all" checked />
              <label class="form-check-label" for="selectAll">View All</label>
            </div>

            <div class="app-calendar-events-filter">
              <div class="form-check form-check-danger mb-2">
                <input class="form-check-input input-filter" type="checkbox" id="select-personal" data-value="personal" checked />
                <label class="form-check-label" for="select-personal">Personal</label>
              </div>
              <div class="form-check mb-2">
                <input class="form-check-input input-filter" type="checkbox" id="select-business" data-value="business" checked />
                <label class="form-check-label" for="select-business">Business</label>
              </div>
              <div class="form-check form-check-warning mb-2">
                <input class="form-check-input input-filter" type="checkbox" id="select-family" data-value="family" checked />
                <label class="form-check-label" for="select-family">Family</label>
              </div>
              <div class="form-check form-check-success mb-2">
                <input class="form-check-input input-filter" type="checkbox" id="select-holiday" data-value="holiday" checked />
                <label class="form-check-label" for="select-holiday">Holiday</label>
              </div>
              <div class="form-check form-check-info">
                <input class="form-check-input input-filter" type="checkbox" id="select-etc" data-value="etc" checked />
                <label class="form-check-label" for="select-etc">ETC</label>
              </div>
            </div>
          </div>
        </div>
        <!-- /Calendar Sidebar -->

        <!-- Calendar & Modal -->
        <div class="app-calendar-content col">
          <div class="card shadow-none border-0">
            <div class="card-body pb-0">
              <!-- FullCalendar -->
              <div id="calendarStatistics1"></div>
            </div>
          </div>
          <div class="app-overlay"></div>
          <!-- FullCalendar Offcanvas -->
          <div class="offcanvas offcanvas-end event-sidebar" tabindex="-1" id="addEventSidebar" aria-labelledby="addEventSidebarLabel">
            <div class="offcanvas-header border-bottom">
              <h6 class="offcanvas-title" id="addEventSidebarLabel">Add Event</h6>
              <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
              <form class="event-form pt-0" id="eventForm" onsubmit="return false">
                <div class="mb-3">
                  <label class="form-label" for="eventTitle">Title</label>
                  <input type="text" class="form-control" id="eventTitle" name="eventTitle" placeholder="Event Title" />
                </div>
                <div class="mb-3">
                  <label class="form-label" for="eventLabel">Label</label>
                  <select class="select2 select-event-label form-select" id="eventLabel" name="eventLabel">
                    <option data-label="primary" value="Business" selected>Business</option>
                    <option data-label="danger" value="Personal">Personal</option>
                    <option data-label="warning" value="Family">Family</option>
                    <option data-label="success" value="Holiday">Holiday</option>
                    <option data-label="info" value="ETC">ETC</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label class="form-label" for="eventStartDate">Start Date</label>
                  <input type="text" class="form-control" id="eventStartDate" name="eventStartDate" placeholder="Start Date" />
                </div>
                <div class="mb-3">
                  <label class="form-label" for="eventEndDate">End Date</label>
                  <input type="text" class="form-control" id="eventEndDate" name="eventEndDate" placeholder="End Date" />
                </div>
                <div class="mb-3">
                  <label class="switch">
                    <input type="checkbox" class="switch-input allDay-switch" />
                    <span class="switch-toggle-slider">
                      <span class="switch-on"></span>
                      <span class="switch-off"></span>
                    </span>
                    <span class="switch-label">All Day</span>
                  </label>
                </div>
                <div class="mb-3">
                  <label class="form-label" for="eventURL">Event URL</label>
                  <input type="url" class="form-control" id="eventURL" name="eventURL" placeholder="https://www.google.com" />
                </div>
                <div class="select2-primary mb-3">
                  <label class="form-label" for="eventGuests">Add Guests</label>
                  <select class="select2 select-event-guests form-select" id="eventGuests" name="eventGuests" multiple>
                    <option data-avatar="1.png" value="Jane Foster">Jane Foster</option>
                    <option data-avatar="3.png" value="Donna Frank">Donna Frank</option>
                    <option data-avatar="5.png" value="Gabrielle Robertson">Gabrielle Robertson</option>
                    <option data-avatar="7.png" value="Lori Spears">Lori Spears</option>
                    <option data-avatar="9.png" value="Sandy Vega">Sandy Vega</option>
                    <option data-avatar="11.png" value="Cheryl May">Cheryl May</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label class="form-label" for="eventLocation">Location</label>
                  <input type="text" class="form-control" id="eventLocation" name="eventLocation" placeholder="Enter Location" />
                </div>
                <div class="mb-3">
                  <label class="form-label" for="eventDescription">Description</label>
                  <textarea class="form-control" name="eventDescription" id="eventDescription"></textarea>
                </div>
                <div class="d-flex justify-content-start justify-content-sm-between my-4 mb-3">
                  <div>
                    <button type="submit" class="btn btn-primary btn-add-event me-1 me-sm-3">Add</button>
                    <button type="submit" class="btn btn-primary btn-update-event d-none me-1 me-sm-3">
                      Update
                    </button>
                    <button type="reset" class="btn btn-label-secondary btn-cancel me-1 me-sm-0" data-bs-dismiss="offcanvas">
                      Cancel
                    </button>
                  </div>
                  <div><button class="btn btn-label-danger btn-delete-event d-none">Delete</button></div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <!-- /Calendar & Modal -->

                </div>
            </div>
        </div>
    </div>

        <!-- OOO/OOS Modal window
        <div class="modal fade" id="OOOSRoom" data-backdrop="static" data-keyboard="false"
        aria-labelledby="popModalWindowlabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="popModalWindowlabel">OOO / OOS </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="OOS_Close"></button>
                </div>
                <div class="modal-body">
                    <form id="ooos-submit-form" onSubmit="return false">
                        <div id="OOOS_DIV" class="content">
                            <input type="hidden" name="OOOS_ID" id="OOOS_ID" class="form-control" />
                            <input type="hidden" name="Room_ID" id="Room_ID" class="form-control" />
                            <div class="row g-3">
                                <div class="border rounded p-4 mb-3">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">                                        
                                            <label for="ROOMS"
                                                class="col-form-label col-md-5"><b>Room *</b></label>
                                            <select id="ROOMS" name="ROOMS"
                                                class="select2 form-select form-select-lg"></select>                                       
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="STATUS_FROM_DATE" class="col-form-label col-md-4"><b>
                                                    From Date</b></label>
                                            <input type="text" name="STATUS_FROM_DATE" id="STATUS_FROM_DATE"
                                                class="form-control" />
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="STATUS_TO_DATE" class="col-form-label col-md-4"><b>
                                                    Through Date</b></label>
                                            <input type="text" name="STATUS_TO_DATE" id="STATUS_TO_DATE" class="form-control"
                                                 />
                                        </div>
                                        <div class="col-md-4 mb-3">                                        
                                        <label for="ROOM_STATUS"
                                            class="col-form-label col-md-5"><b>Status </b></label>
                                        <select id="ROOM_STATUS" name="ROOM_STATUS"
                                            class="select2 form-select form-select-lg"></select>                                   
                                       </div>

                                       <div class="col-md-4 mb-3">                                        
                                        <label for="ROOM_RETURN_STATUS"
                                            class="col-form-label col-md-5"><b>Return Status </b></label>
                                        <select id="ROOM_RETURN_STATUS" name="ROOM_RETURN_STATUS"
                                            class="select2 form-select form-select-lg">
                                          </select>                                   
                                       </div>
                                       <div class="col-md-4 mb-3">                                        
                                        <label for="ROOM_CHANGE_REASON"
                                            class="col-form-label col-md-5"><b>Change Reason *</b></label>
                                        <select id="ROOM_CHANGE_REASON" name="ROOM_CHANGE_REASON"
                                            class="select2 form-select form-select-lg">
                                          </select>                                   
                                       </div>
                                       <div class="col-md-8 mb-3">                                        
                                        <label for="ROOM_REMARKS"
                                            class="col-form-label col-md-5"><b>Remarks</b></label>
                                               <textarea name="ROOM_REMARKS" id="ROOM_REMARKS" class="form-control" rows="4"></textarea>                                               
                                       </div>
                                    </div>

                                    <div class="row g-3 ">
                                        <div class="col-md-3 mb-3">
                                            <div class="col-md-8 float-right">
                                                <button type="button" class="btn btn-success save-roomstatus-details">
                                                    <i class="fa-solid fa-floppy-disk"></i>&nbsp; Save
                                                </button>&nbsp;
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="border rounded p-4 mb-3">
                                        <div class="col-md-6 mb-3">
                                            <button type="button" class="btn btn-primary add-room-status">
                                                <i class="fa-solid fa-circle-plus"></i>&nbsp; Add New
                                            </button>&nbsp;

                                            <button type="button" class="btn btn-danger delete-room-status">
                                                <i class="fa-solid fa-ban"></i>&nbsp; Delete
                                            </button>&nbsp;
                                        </div>

                                        <div class="table-responsive text-nowrap">
                                            <table id="Status_Details" class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                      <th></th>
                                                      <th class="all">Room</th>
                                                      <th class="all">Status</th>
                                                      <th class="all">From Date</th>
                                                      <th class="all">Through Date</th>
                                                      <th class="all">Return As</th>
                                                      <th class="all">Reason</th>
                                                      <th class="all">Remarks</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                        <br />
                                    </div>
                                </div>
                                <div class="d-flex col-12 justify-content-between">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal" id="OOS_Close">Close</button>
                                </div>

                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div> -->

    <!-- /Modal OOS window --> 

    <!-- Room Assign Modal window -->
    <div class="modal fade" id="RoomAssign" data-backdrop="static" data-keyboard="false"
        aria-labelledby="popModalWindowlabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="popModalWindowlabel">Room Assignment </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="OOS_Close"></button>
                </div>
                <div class="modal-body">
                    <form id="roomassign-submit-form" onSubmit="return false">
                        <div id="RoomAssign_DIV" class="content">
                            <input type="hidden" name="Assign_ID" id="Assign_ID" class="form-control" />
                            <input type="hidden" name="Room_ID" id="Room_ID" class="form-control" />
                            <div class="row g-3">
                                
                                <div class="col-md-12">
                                    <div class="border rounded p-4 mb-3">
                                     
                                        <p><b>* Click on a row to see the available rooms</b></p>
                                        <div class="table-responsive text-nowrap">
                                            <table id="Room_Assign_Details" class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                      <th class="all">Room Type</th>
                                                      <th class="all">Name</th>
                                                      <th class="all">Arrival Date</th>
                                                      <th class="all">Departure Date</th>
                                                      <th class="all">Status</th>
                                                      <th class="all">Adults</th>
                                                      <th class="all">Kids</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                        <br />
                                    </div>
                                </div>
                                <div class="d-flex col-12 justify-content-between">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal" id="OOS_Close">Close</button>
                                </div>

                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- /Modal RoomAssign window --> 


        <!-- VacantRooms Modal window -->
        <div class="modal fade" id="VacantRoomsModal" data-backdrop="static" data-keyboard="false"
        aria-labelledby="popModalWindowlabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="popModalWindowlabel">Available Rooms </h4>
                    <button type="button" class="btn-close roomAssignClose" data-bs-dismiss="modal" aria-label="Close" id="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="roomassign-submit-form" onSubmit="return false">
                        <div id="VacantRooms_DIV" class="content">
                            <input type="hidden" name="Resv_ID" id="Resv_ID" class="form-control" />
                            <input type="hidden" name="Room_Type_ID" id="Room_Type_ID" class="form-control" />
                            <div class="row g-3">                                
                                <div class="col-md-12">
                                    <div class="border rounded p-4 mb-3">  
                                        <div class="table-responsive text-nowrap">
                                            <table id="Vacant_Room_Details" class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                      <th class="all">Action</th>
                                                      <th class="all">Room</th>
                                                      <th class="all">Room Type</th>
                                                      <th class="all">HK Status</th>
                                                      <th class="all">Floor</th>
                                                      <th class="all">Features</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                        <br />
                                    </div>
                                </div>
                                <div class="d-flex col-12 justify-content-between">
                                    <button type="button" class="btn btn-secondary roomAssignClose"
                                        data-bs-dismiss="modal" id="Close">Close</button>
                                </div>

                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- /Modal RoomAssign window --> 


    <!-- Room Details Modal Window -->

    <div class="modal fade" id="RoomDetailsModal" tabindex="-1" aria-lableledby="popModalWindowlable" aria-hidden="true">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="popModalWindowlable">Room Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
      </div>
      <div class="modal-body">
        <form id="submitForm">
          <div class="row g-3">          
            <div class="col-md-4">
              <lable class="form-lable">Room No / Room Class</lable>
                <div class="input-group mb-3">
                    <div class="col-md-6">
                      <input type="text" name="RM_NO" id="RM_NO" class="form-control" placeholder="room no" readonly />
                    </div>
                      <div class="col-md-6">
                      <input type="text" readonly name="RM_CLASS" id="RM_CLASS" class="form-control" placeholder="room class" />
                    </div>
                </div>
            </div>
            <div class="col-md-4">
              <lable class="form-lable">Room Type</lable>
              <input type="text" name="RM_DESC" id="RM_DESC" class="form-control" readonly/>
              
            </div>
            <div class="col-md-4">
              <lable class="form-lable">Pub. Rate Code</lable>
              <input type="text" name="RM_PUBLIC_RATE_CODE" id="RM_PUBLIC_RATE_CODE" class="form-control" readonly/>
             
            </div>
            <div class="col-md-4">
              <lable class="form-lable">Floor Preference</lable>
              <input type="text" name="RM_FLOOR_PREFERN" id="RM_FLOOR_PREFERN" class="form-control" readonly/>
             
            </div>
            <div class="col-md-4">
              <lable class="form-lable">Pub. Rate Amount</lable>
              <input type="text" name="RM_PUBLIC_RATE_AMOUNT" id="RM_PUBLIC_RATE_AMOUNT" class="form-control" readonly/>
            </div>  
            <div class="col-md-4">
              <lable class="form-lable">Smoking Preference</lable>
              <input type="text" name="RM_SMOKING_PREFERN" id="RM_SMOKING_PREFERN" class="form-control" placeholder="rate amount" readonly />             
            </div>
            <div class="col-md-4">
              <lable class="form-lable">Display Seq./Max Occupancy</lable>
                <div class="input-group mb-3">
                  <input type="text" name="RM_DISP_SEQ" id="RM_DISP_SEQ" class="form-control" readonly/>
                  <input type="text" name="RM_MAX_OCCUPANCY" id="RM_MAX_OCCUPANCY" class="form-control" readonly />
                </div>
            </div>
            <div class="col-md-4">
              <lable class="form-lable">Measurement/Square Units</lable>
                <div class="input-group mb-3">
                  <input type="text" name="RM_MEASUREMENT" id="RM_MEASUREMENT" class="form-control" placeholder="measurement" readonly />
                  <input type="text" name="RM_SQUARE_UNITS" id="RM_SQUARE_UNITS" class="form-control" placeholder="square units" readonly />
                </div>
            </div>
            <div class="col-md-12">
              <lable class="form-lable">Features</lable>
              <input type="text" name="RM_FEATURE" id="RM_FEATURE" class="form-control" readonly />              
            </div>
           
            <div class="col-md-4">
              <lable class="form-lable">Phone No</lable>
              <input type="text" name="RM_PHONE_NO" id="RM_PHONE_NO" class="form-control" readonly />
            </div>
            <div class="col-md-4">
              <lable class="form-lable">Stayover/Departure Credits</lable>
                <div class="input-group mb-3">
                  <input type="text" name="RM_STAYOVER_CR" id="RM_STAYOVER_CR" class="form-control" readonly />
                  <input type="text" name="RM_DEPARTURE_CR" id="RM_DEPARTURE_CR" class="form-control" readonly />
                </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>        
      </div>
    </div>
  </div>
</div>
<!-- /Room Details Modal window -->

    
    </div>
  </div>
  <!-- / Content -->
  <div class="content-backdrop fade"></div>
</div>
<!-- Content wrapper -->

<script>

$( document ).ready(function() {
  

    roomType();
    roomClass();
    rooms();
    roomsStatusList();
    roomFloor();
    roomAssigned();  
  
   

    $('.dt-date').datepicker({
        format: 'dd-M-yyyy',
        autoclose: true,
        onSelect: function() {
            $(this).change();
        },
        
    });

    $('.dt-date').datepicker('setDate', '<?php echo (isset($SEARCH_DATE) && $SEARCH_DATE != '')?date('d-M-Y',strtotime($SEARCH_DATE)):date('d-M-Y',strtotime("now"))?>');

    $('#RoomStatisticsModal').on('shown.bs.modal', function () {
      if($('#calendarStatistics1>*').length == 0)
        roomStatistics()
    })
    roomPlanFunc()

    var today = moment().format('DD-MM-YYYY');             

    $('#STATUS_FROM_DATE').datepicker({
        format: 'd-M-yyyy',
        autoclose: true,
        
    }).datepicker("setDate", today);

    $('#STATUS_TO_DATE').datepicker({
        format: 'd-M-yyyy',
        autoclose: true,
        
    }).datepicker("setDate", today);
});

function roomPlanFunc(){
    let date = new Date();
    let nextDay = new Date(new Date().getTime() + 24 * 60 * 60 * 1000);
    // prettier-ignore
    let nextMonth = date.getMonth() === 11 ? new Date(date.getFullYear() + 1, 0, 1) : new Date(date.getFullYear(), date.getMonth() + 1, 1);
    // prettier-ignore
    let prevMonth = date.getMonth() === 11 ? new Date(date.getFullYear() - 1, 0, 1) : new Date(date.getFullYear(), date.getMonth() - 1, 1);

    var calendarEl = document.getElementById('calendarRoomPlan');
    //var tooltip = null;
    var calendarRoom = new FullCalendar.Calendar(calendarEl, {
      //schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
      schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
      //titleFormat: 'YYYY-MM-DD',
      timeZone: 'UTC',
      plugins: ['resourceTimeline', 'interaction'],
      
        header: {

        left: 'today prev,next,custom1',

        center: 'title',

        right: ''

      },
      aspectRatio: 1.5,
      defaultView: 'resourceTimelineWeek',   
      minTime:			'10:00:00',
      maxTime:			'11:59:59',
      droppable: true,
      editable: true,
      resourceAreaWidth: '35%',
      selectable: true,
   
      customButtons: {
      custom1: {
        text: 'Assign Room',
        backgroundColor:'#ccc'
        
      },
     
    },

      resourceColumns: [
        {
          labelText: 'Room',
          field: 'room',
        },
        {
          labelText: 'Room Type',
          field: 'room_type'
        },
        {
          labelText: 'Status',
          field: 'status',
          render: function(resource, el) {
            var extendedProps = resource.extendedProps;
            el.style.color = '#fff'
            if (extendedProps.status == 'Dirty') {
              el.style.backgroundColor = '#c30808ed';
            } else if (extendedProps.status == 'Clean') {
              el.style.backgroundColor = 'rgb(6 125 133)'
            } else if (extendedProps.status == 'Inspected') {
              el.style.backgroundColor = '#065c06'
            }
            else if (extendedProps.status == 'Out of Service') {
              el.style.backgroundColor = '#495563'
            }
            else if (extendedProps.status == 'Out of Order') {
              el.style.backgroundColor = '#495563'
            }            
            
          },

        }

      ],
      
      resources: [<?php
                  if (!empty($RoomResources) && !empty($RoomReservations)) {
                    foreach ($RoomResources as $resources) { ?> {
              id: '<?php echo $resources['RM_ID'] ?>',
              room: '<?php echo $resources['RM_NO'] ?>',
              room_type: '<?php echo $resources['RM_TYPE'] ?>',
              status: '<?php echo ($resources['RM_STATUS_CODE'] == NULL) ? 'Dirty': $resources['RM_STATUS_CODE']; ?>'
            },
        <?php }
                  } 
                 ?>
      ],
      resourceRender: function (renderInfo) {
        renderInfo.el.addEventListener("click", function () {            
            console.log('clicked:' + renderInfo.resource.id);
            $.ajax({
              url: '<?php echo base_url('/editRoom') ?>',
              data: {'sysid':renderInfo.resource.id},
              type: "POST",
              dataType: 'json',

              success:function(respn){
                $(respn).each(function(inx,data){
                  $.each(data,function(fields,datavals){
                    var field = $.trim(fields);
                    var dataval = $.trim(datavals);
                    $('#'+field).val(dataval);
                    $("#RoomDetailsModal").modal('show'); 
                  });
                });                
              }
              
            });
            
           });
      },

      events: [
        <?php
        if (!empty($RoomReservations)) {
          foreach ($RoomReservations as $row) {
            $start = date("Y-m-d 11:00:00", strtotime($row['RESV_ARRIVAL_DT']));
            //$startDate = '2022-08-15';

            $end = date("Y-m-d 11:00:00", strtotime($row['RESV_DEPARTURE']));
            //$endDate = '2022-08-18 23:00:00';
        ?> {
              id: '<?php echo $row['RESV_ID'] ?>',
              resourceId: '<?php echo $row['RM_ID'] ?>',
              title: '<?php echo $row['FULLNAME'] . ' - ' . $row['RESV_STATUS']; ?>',
              start: '<?php echo $start; ?>',
              end: '<?php echo $end; ?>',
              backgroundColor: "#405974 ",
              borderColor: "#405974",
              textColor: "#fff",  
                                       
            },

        <?php }
        } ?>
<?php
        if (!empty($RoomOOS)) {
          foreach ($RoomOOS as $row) { ?>
            {
              id: '<?php echo $row['OOOS_ID'] ?>',
              resourceId: '<?php echo $row['ROOMS'] ?>',
              title: '<?php echo $row['REASON']; ?>',
              start: '<?php echo date("Y-m-d 00:00:00", strtotime($row["STATUS_FROM_DATE"])); ?>',
              end: '<?php echo date("Y-m-d 13:00:00", strtotime($row["STATUS_TO_DATE"])); ?>',
              backgroundColor: '#6c757da3',
              borderColor: "#6c757da3",
              textColor: "#fff",  
              disableDragging: true,
              editable: false,
              eventStartEditable: false, 
              eventDurationEditable: false,
              My_Custom_Value: 'OOS',                    
              
            },

            <?php }
          } ?>


       
      ],
      eventClick: function(info) {
        
        console.log(info);
        info.jsEvent.preventDefault(); 

        $.ajax({
          url: '<?php echo base_url('/checkReservationExists') ?>',
          data: 'ID=' + info.event.id,
          type: "POST",
          dataType: 'json',
          success: function(data) {
            if (data.status == 1) {
            var base_url = '<?php echo base_url()?>';
            var url = '/reservation?RESV_ID='+info.event.id;
              window.open(base_url+url);
            }
            else{
              $("#showRoomOSModal").click()
            }


          }
        });


        
      },
     
      

      eventDrop: function(info) {
       console.log(info.event.extendedProps.My_Custom_Value);
        // alert(info.event.title + " was dropped on " + info.event.start.toISOString()+"stop"+info.event.end.toISOString());
        if(info.event.extendedProps.My_Custom_Value !='OOS'){
        let RESV_ID = info.event.id;
        console.log(RESV_ID)
        let START = info.event.start;
        let s = new Date(START);
        START = s.toISOString(START);
        let END = info.event.end;
        let e = new Date(END);
        END = e.toISOString(END);
        message = '';
        let OLD_ROOM_ID = (info.oldResource != null) ? info.oldResource.id : '';
        let NEW_ROOM_ID = (info.newResource != null) ? info.newResource.id : '';
        let OLD_ROOM = (info.oldResource != null) ? info.oldResource.extendedProps.room : '';
        let NEW_ROOM = (info.newResource != null) ? info.newResource.extendedProps.room : '';
        if(info.oldResource != null && info.newResource.extendedProps.status == 'Dirty')
        var message = "Room status is Dirty. Are you sure about this change?";
        else
        var message = "Are you sure about this change?";
        if (confirm(message)) {
          $.ajax({
            url: '<?php echo base_url('/updateRoomPlan') ?>',
            data: 'RESV_ID=' + RESV_ID + '&START=' + START + '&END=' + END + '&OLD_ROOM_ID=' + OLD_ROOM_ID + '&NEW_ROOM_ID=' + NEW_ROOM_ID + '&OLD_ROOM=' + OLD_ROOM + '&NEW_ROOM=' + NEW_ROOM,
            type: "POST",
            dataType: 'json',
            success: function(data) {
              if (data.status_message != '') {
                var mcontent = '';
                mcontent += '<li>' + data.status_message + '</li>';

                if (data.status == 1) {
                  info.revert();
                  showModalAlert('error', mcontent);
                } else if (data.status == 0) {
                  showModalAlert('success', mcontent);
                }
                else if(data.status == 2)  
                {
                  if(confirm(data.status_message)){
                    if((data.OLD_ROOM_TYPE != data.NEW_ROOM_TYPE)){
                      if(confirm("Room type has been changed to  "+data.NEW_ROOM_TYPE+". Do you want to continue?")){                        
                        if(confirm("Room type has been changed to  "+data.NEW_ROOM_TYPE+". Do you want to change RTC to "+data.NEW_ROOM_TYPE+"?")){
                          updateRoomRTC(RESV_ID,data.NEW_RESV_RM_TYPE_ID,data.NEW_ROOM_TYPE);
                        }
                          updateRoomDetails(RESV_ID,NEW_ROOM_ID,NEW_ROOM,data.NEW_RESV_RM_TYPE_ID,data.NEW_ROOM_TYPE);
                      }
                      else{
                        info.revert();
                      }                       
                    }else
                       updateRoomDetails(RESV_ID,NEW_ROOM_ID,NEW_ROOM,data.NEW_RESV_RM_TYPE_ID,data.NEW_ROOM_TYPE);
                                   
                  }else{
                       info.revert();
                  }
                }
                
              }
            }


          });
        }
        else{
          info.revert();
        }
      }
      else{
        var mcontent = '';
          mcontent += "<li>Error. The room is out of service</li>";       
          showModalAlert('error', mcontent);
          info.revert();        
      }
      },
      eventResize: function(info) {
        //if (confirm("Change the dates?")) {
        // alert(info.event.id)
        let RESV_ID = info.event.id;
        let START = info.event.start;
        let s = new Date(START);
        START = s.toISOString(START);
        let END = info.event.end;
        let e = new Date(END);
        END = e.toISOString(END);
        //alert(info.event.title + " end is now " + info.event.end.toISOString());
        $.ajax({
          url: '<?php echo base_url('/changeReservationDates') ?>',
          data: 'RESV_ID=' + RESV_ID + '&START=' + START + '&END=' + END,
          type: "POST",
          dataType: 'json',
          success: function(data) {
            if (data.status_message != '') {
              var mcontent = '';
              mcontent += '<li>' + data.status_message + '</li>';

              if (data.status == 1) {
                info.revert();
                showModalAlert('error', mcontent);
              } else if (data.status == 0) {
                showModalAlert('success', mcontent);
              }

            }


          }
        });

      // }else{
      //   info.revert();
      // }

        // if (!confirm("is this okay?")) {
        //   info.revert();
        // }
      },

      eventMouseEnter: function (info) {
          $(info.el).tooltip({
              title: info.event.title,
              html: true,
              placement: 'top',
              trigger: 'hover',
              container: 'body',

        });
      },
    
      eventTimeFormat: { // like '14:30:00'
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: false
      },
      dateClick: function(info) {
       
        if (info.resource.id) {
          let START = info.dateStr;
          const STARTARRAY = START.split("T");
          START = STARTARRAY[0];
        $.ajax({
          url: '<?php echo base_url('/checkArrivalExists') ?>',
          data: {
                ARRIVAL: START,
                ROOM_ID: info.resource.id
            },
          type: "POST",
          dataType: 'json',
          success: function(data) {
            if (data.status_message != '') {
              var mcontent = '';
              mcontent += '<li>' + data.status_message + '</li>';

              if (data.status == 1) {  
                var mcontent = '';
                mcontent += '<li>This reservation has no room assignment!</li>';
                showModalAlert('info', mcontent);
                $('#Room_Assign_Details').DataTable().destroy();
                $('#Room_Assign_Details > tbody').html(data.room_assign);
                $('#Room_Assign_Details').DataTable({ paging: true});
                $(".fc-custom1-button").css('pointer-events','all');
                $(".fc-custom1-button").css('background-color','#5a8dee');
                $(".fc-custom1-button").css('color','#fff');
               
                         
                //showModalAlert('error', mcontent);
              } 
              else{
                $(".fc-custom1-button").css('pointer-events','none');
                $(".fc-custom1-button").css('background-color','#e5edfc');
                $(".fc-custom1-button").css('color','#5a8dee');
                var base_url = '<?php echo base_url()?>';                
                var url = '/reservation?ROOM_ID='+info.resource.id+'&ARRIVAL_DATE='+START;
                window.open(base_url+url);

              }

            }
          }
        });
      }

      },
     

    });
   <?php  if(isset($SEARCH_DATE) && $SEARCH_DATE != ''){ ?>
    calendarRoom.gotoDate('<?php echo date('Y-m-d',strtotime($SEARCH_DATE))?>');
  <?php } ?>
    calendarRoom.render();
  }

  function updateRoomDetails(resv_id,room_id,room_no,room_type_id,room_type){   
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('/updateRoomPlanDetails') ?>',
        data: {
                resv_id: resv_id,
                room_no: room_no,
                room_id: room_id,
                room_type:room_type,
                room_type_id:room_type_id
            },
        dataType: 'json',
        success:function(data) {
              var mcontent = '';
              mcontent += '<li>' + data.status_message + '</li>';
              if (data.status == "1") {
                showModalAlert('success', mcontent);
              } else if (data.status == "2") {
                showModalAlert('error', mcontent);
              }

        }
        
    });  
  }

  function updateRoomRTC(RESV_ID, RESV_RM_TYPE_ID, NEW_ROOM_TYPE){
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url('/updateRoomRTC') ?>',
        data: {
                resv_id: RESV_ID,               
                room_type:NEW_ROOM_TYPE,
                room_type_id:RESV_RM_TYPE_ID
            },
        dataType: 'json',
        success:function(data) {
              // var mcontent = '';
              // mcontent += '<li>' + data.status_message + '</li>';
              // if (data.status == "1") {
              //   showModalAlert('success', mcontent);
              // } else if (data.status == "2") {
              //   showModalAlert('error', mcontent);
              // }

        }
        
    });  
  }

  function roomStatistics(){
    let date = new Date();
    let nextDay = new Date(new Date().getTime() + 24 * 60 * 60 * 1000);
    // prettier-ignore
    let nextMonth = date.getMonth() === 11 ? new Date(date.getFullYear() + 1, 0, 1) : new Date(date.getFullYear(), date.getMonth() + 1, 1);
    // prettier-ignore
    let prevMonth = date.getMonth() === 11 ? new Date(date.getFullYear() - 1, 0, 1) : new Date(date.getFullYear(), date.getMonth() - 1, 1);



    var calendarEl = document.getElementById('calendarStatistics1');
    //var tooltip = null;
    var calendarStat = new FullCalendar.Calendar(calendarEl, {
      //schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
      schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
      //titleFormat: 'YYYY-MM-DD',
      timeZone: 'UTC',
      plugins: ['resourceTimeline', 'interaction'],

      droppable: true,

      header: {

        left: 'today prev,next',

        center: 'title',

        right: ''

      },


      aspectRatio: 1.5,

      defaultView: 'resourceTimelineWeek',

      slotLabelFormat: [{
        weekday: 'short',
        month: 'numeric',
        day: 'numeric',
        omitCommas: true
      }],
      slotLabelInterval: {
        days: 1
      },

      editable: true,

      resourceAreaWidth: '35%',

      resourceColumns: [

        {

          labelText: 'Statistics',

          field: 'stat',

        },

        
      ],
      
      resources: [{
                id: '1',
                stat: 'Total Rooms Reserved',
              },
              {
                id: '2',
                stat: 'Occupancy %',
              },
              {
                id: '3',
                stat: 'Arrival Rooms',
              },
              {
                id: '4',
                stat: 'Stay Over',
              },
              {
                id: '5',
                stat: 'Departure Rooms',
              },
              
           
      ],

      events: function(info, successCallback, failureCallback) {
        console.log(info) 
            let START = info.start;
            let s = new Date(START);
            START = s.toISOString(START);
            let END = info.end;
            let e = new Date(END);
            END = e.toISOString(END);
            $.ajax({
            url: '<?php echo base_url('/getRoomStatistics') ?>',
            type: "post",  
            dataType: 'json',
            data: {
                start: START,
                end: END
            },
            success: function(res) {
              var events = [];
              res.forEach(function (evt) {
                events.push({
                  id:evt.id,
                  resourceId:evt.resourceId,
                  title: evt.title,
                  start: evt.start,
                  end: evt.end,
                  backgroundColor: "#405974 ",
                  borderColor: "#405974",
                  textColor: "#fff", 
                });
              });
              successCallback(events);
            },
            
        });
    },



    });

    calendarStat.render();

  }



  //$(document).on('click', '#showRoomStatisticsModal', function() {
   
   // if($('#calendarStatistics1>*').length == 0) {
   
    $('#showRoomStatisticsModal').click(function(){
      
    //window.setTimeout(clickToday, 100);
    $('#RoomStatisticsModal').modal('show');
    });




$(document).on('click', '#Room_Assign_Details > tbody > tr', function() {
 
    $('#Room_Assign_Details').find('tr.table-warning').removeClass('table-warning');
    
    $(this).addClass('table-warning');

    loadRoomAssignDetails($(this).data('roomtype_id'),$(this).data('resv_id'),$(this).data('arrival_date'))
   
});


function loadRoomAssignDetails(roomtype_id,resv_id,arrival_date){
  $('#Resv_ID').val(resv_id);
  $('#Room_Type_ID').val(roomtype_id);
  $('#Arrival_Date').val(arrival_date);

    $.ajax({
        url: '<?php echo base_url('/getAllVacantRooms') ?>',
        data: {
            ROOM_TYPE_ID: roomtype_id,
            ARRIVAL_DATE: arrival_date,
          },
        type: "POST",
        dataType: 'json',
        success: function(data) {           
            if (data.status == 1) {  
              $('#Vacant_Room_Details').DataTable().destroy();
              $('#Vacant_Room_Details > tbody').html(data.vacant_rooms);
              $('#Vacant_Room_Details').DataTable({ paging: true});                
              $('#VacantRoomsModal').modal('show'); 
            }
        }
      });
}

$(document).on('click', '#Vacant_Room_Details > tbody > tr', function() {
 
 $('#Vacant_Room_Details').find('tr.table-warning').removeClass('table-warning');
 
 $(this).addClass('table-warning');
 var Resv_ID = $('#Resv_ID').val();
 var Room_ID = $(this).data('room_id');
 $.ajax({
          url: '<?php echo base_url('/updateRoomAssign') ?>',
          data: {
            Resv_ID: Resv_ID,
            Room_ID: Room_ID,
            },
          type: "POST",
          dataType: 'json',
          success: function(data) {           
            if (data.status_message != '') {
              var mcontent = '';
              mcontent += '<li>' + data.status_message + '</li>';

              if (data.status == 1) {                             
                showModalAlert('success', mcontent);
                $(".roomAssignClose").click();
                $(".RoomID_"+Resv_ID).remove();
              } 
              else{                
                showModalAlert('error', mcontent);
              }

            }
          }
        });



 

});




$(document).on('click', '#OOS_Close', function() {
  location.reload();
});





//////////// Search /////////////


function roomType() {
  var roomType = '<?php echo isset($SEARCH_ROOM_TYPE) ? $SEARCH_ROOM_TYPE : null ?>';
    $.ajax({
        url: '<?php echo base_url('/roomTypeSearchList') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        async: false,
        success: function(respn) {
            $('#SEARCH_ROOM_TYPE').html(respn);
            $('#SEARCH_ROOM_TYPE').val(roomType).change();
        }
    });
}

$(document).on('change', '#SEARCH_ROOM_TYPE', function() { 
   $("#SEARCH_ROOM_CLASS").val($(this).find(':selected').data('rmclass')).change();
   rooms($('#SEARCH_ROOM_TYPE').find(':selected').data('room-type-id'));   
});

function roomClass() {
  var roomClass = '<?php echo isset($SEARCH_ROOM_CLASS)? $SEARCH_ROOM_CLASS: null; ?>';
  //$('#SEARCH_ROOM_CLASS').attr('disabled','disabled');
    $.ajax({
        url: '<?php echo base_url('/roomClassSearchList') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        async: false,
        success: function(respn) {
            $('#SEARCH_ROOM_CLASS').html(respn);
            $('#SEARCH_ROOM_CLASS').val(roomClass).trigger('change');
            
        }
    });
}

function rooms(room_type = '',room_floor = '', room_status = '') { 

  var room = '<?php echo isset($SEARCH_ROOM)?$SEARCH_ROOM:null; ?>';
    $.ajax({
        url: '<?php echo base_url('/roomSearchList') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        data:{room_type:room_type,room_floor:room_floor,room_status:room_status},
        async: false,
        success: function(respn) {
            $('#SEARCH_ROOM').html(respn);
            $('#SEARCH_ROOM').val(room).change();
        }
    });
}

function roomsStatusList() {
  var roomStatus = '<?php echo isset($SEARCH_ROOM_STATUS)?$SEARCH_ROOM_STATUS:null; ?>';
    $.ajax({
        url: '<?php echo base_url('/roomStatusList') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        async: false,
        success: function(respn) {
            $('#SEARCH_ROOM_STATUS').html(respn);
            $('#SEARCH_ROOM_STATUS').val(roomStatus).change();
        }
    });
    
}

function roomFloor() {
    var roomFloor = '<?php echo isset($SEARCH_ROOM_FLOOR)?$SEARCH_ROOM_FLOOR:null; ?>';
    $.ajax({
        url: '<?php echo base_url('/roomFloorList') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        async: false,
        success: function(respn) {
            $('#SEARCH_ROOM_FLOOR').html(respn);
            $('#SEARCH_ROOM_FLOOR').val(roomFloor).change();
        }
    });
}

$(document).on('change', '#SEARCH_ROOM_FLOOR', function() {
  var room_type  = $('#SEARCH_ROOM_TYPE').find(':selected').data('room-type-id');
  var room_floor = $('#SEARCH_ROOM_FLOOR').find(':selected').data('rm-pref');
  rooms(room_type,room_floor);
});

$(document).on('change', '#SEARCH_ROOM_STATUS', function() {
  var room_type  = $('#SEARCH_ROOM_TYPE').find(':selected').data('room-type-id');
  var room_floor = $('#SEARCH_ROOM_FLOOR').find(':selected').data('rm-pref');
  var room_status = $(this).val();
  rooms(room_type,room_floor,room_status);
});

function roomAssigned(){

  <?php // if(isset($SEARCH_CLEAR) && $SEARCH_CLEAR == 1){ ?>
    $('#SEARCH_ASSIGNED_ROOMS').prop('checked', true);
    $('#SEARCH_UNASSIGNED_ROOMS').prop('checked', true );
    var assigned_rooms = '<?php echo isset($SEARCH_ASSIGNED_ROOMS)?$SEARCH_ASSIGNED_ROOMS:''; ?>';
    var unassigned_rooms = '<?php echo isset($SEARCH_UNASSIGNED_ROOMS)?$SEARCH_UNASSIGNED_ROOMS:''; ?>'; 

    if(assigned_rooms != '' || unassigned_rooms !=''){
      $('#SEARCH_ASSIGNED_ROOMS').prop('checked', (assigned_rooms == 'on') ? true : false);
      $('#SEARCH_UNASSIGNED_ROOMS').prop('checked', (unassigned_rooms == 'on') ? true : false);
    }
    <?PHP //} ?>
}




// $(document).on('click', '.submitAdvSearch', function() {
//     hideModalAlerts();
//     var formSerialization = $('.dt_adv_search').serializeArray();
//     console.log(formSerialization);
//     var url = '<?php //echo base_url('/searchRooms') ?>';
//     $.ajax({
//         url: url,
//         type: "post",
//         data: formSerialization,
//         headers: {
//             'X-Requested-With': 'XMLHttpRequest'
//         },
//         dataType: 'json',
//         success: function(respn) {
          
//             // var response = respn['SUCCESS'];
//             // if (response == '2') {
//             //     mcontent = '<li>Something went wrong</li>';
//             //     showModalAlert('error', mcontent);
//             // } else if (response != '1') {
//             //     var ERROR = respn['RESPONSE']['ERROR'];
//             //     var mcontent = '';
//             //     $.each(ERROR, function(ind, data) {
//             //         //console.log(data, "SDF");
//             //         mcontent += '<li>' + data + '</li>';
//             //     });
//             //     showModalAlert('error', mcontent);
//             // } else {
//             //     var alertText = $('#OOOS_ID').val() == '' ?
//             //         '<li>Successfully added</li>' :
//             //         '<li>Successfully updated</li>';
//             //     hideModalAlerts();
//             //     showModalAlert('success', alertText);


//             //     if (respn['RESPONSE']['OUTPUT'] != '') {
                  
//             //         $('#OOOS_ID').val(respn['RESPONSE']['OUTPUT']);
//             //         showRoomStatus();
//             //         clearFormFields('#OOOSRoom');
//             //     }
//             // }
//         }
//     });
// });


$(document).on('click', '.clearAdvSearch', function() {
  clearFormFields('.dt_adv_search');
  $("#SEARCH_CLEAR").val('1');
  $(".dt_adv_search").submit();    
});



$(document).on('click', '.fc-custom1-button', function() {
  $('#RoomAssign').modal('show');   
});



// $(document).on('change', '#SEARCH_ASSIGNED_ROOMS', function() {

//       if ($('#SEARCH_ASSIGNED_ROOMS').prop('checked') == true) 
//       {
//         $('#SEARCH_UNASSIGNED_ROOMS').prop('checked', false);
//       }
     
// });

// $(document).on('change', '#SEARCH_UNASSIGNED_ROOMS', function() {

//     if ($('#SEARCH_UNASSIGNED_ROOMS').prop('checked') == true) 
//     {
//       $('#SEARCH_ASSIGNED_ROOMS').prop('checked', false);
//     }

// });


  // Display function toggleButton
  <?php echo isset($toggleButton_javascript) ? $toggleButton_javascript : ''; ?>

  // Display function clearFormFields
  <?php echo isset($clearFormFields_javascript) ? $clearFormFields_javascript : ''; ?>

  // Display function blockLoader
  <?php echo isset($blockLoader_javascript) ? $blockLoader_javascript : ''; ?>
</script>

<?= $this->endSection() ?>