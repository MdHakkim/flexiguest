<?= $this->extend("Layout/CalendarView") ?>
<?= $this->section("contentRender") ?>
<?= $this->include('Layout/SuccessReport') ?>
<?= $this->include('Layout/ErrorReport') ?>
<style>

/* .fc-scroller-canvas {
    max-width: 100% !important;
    min-width: 100% !important;
} */

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
              <!-- FullCalendar -->
              <div id="calendarRoomPlan"></div>
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

        <!-- OOO/OOS Modal window -->
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
    </div>

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
                                     
                                        <p>Click on a row to see the available rooms</p>
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

        left: 'today prev,next',

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
                  if (!empty($RoomResources)) {
                    foreach ($RoomResources as $resources) { ?> {
              id: '<?php echo $resources['RM_ID'] ?>',
              room: '<?php echo $resources['RM_NO'] ?>',
              room_type: '<?php echo $resources['RM_TYPE'] ?>',
              status: '<?php echo $resources['RM_STATUS_CODE'] ?>'
            },
        <?php }
                  } ?>
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
                alert(respn)
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
              eventDurationEditable: false                     
              
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
       console.log(info.newResource.extendedProps.status);
        //alert(info.event.title + " was dropped on " + info.event.start.toISOString()+"stop"+info.event.end.toISOString());
        let RESV_ID = info.event.id;
        let START = info.event.start;
        let s = new Date(START);
        START = s.toISOString(START);
        let END = info.event.end;
        let e = new Date(END);
        END = e.toISOString(END);
        let OLD_ROOM_ID = (info.oldResource != null) ? info.oldResource.id : '';
        let NEW_ROOM_ID = (info.newResource != null) ? info.newResource.id : '';
        let OLD_ROOM = (info.oldResource != null) ? info.oldResource.extendedProps.room : '';
        let NEW_ROOM = (info.newResource != null) ? info.newResource.extendedProps.room : '';
        if(info.newResource.extendedProps.status == 'Dirty')
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
                //   else if( data.status == 2 )  
                //   {
                //     alert("Can't change the dates. Already checked-in");
                //     info.revert();
                //   }
                // }
              }
            }


          });
        }
        else{
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

                $('#Room_Assign_Details').DataTable().destroy();
                $('#Room_Assign_Details > tbody').html(data.room_assign);
                $('#Room_Assign_Details').DataTable({ paging: true});
                
                $('#RoomAssign').modal('show');            
                //showModalAlert('error', mcontent);
              } 
              else{
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

    calendarRoom.render();

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


    //////// OOO/OOS Functions

$(document).on('click', '#showRoomOSModal', function() {
    $('#OOOSRoom').modal('show');    
    roomsList();
    roomsStatus();
    roomsReturnStatus();
    roomsChangeReason();    
    showRoomStatus();
});


function roomsList() {
    $.ajax({
        url: '<?php echo base_url('/roomPlanList') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        async: false,
        success: function(respn) {
            $('#ROOMS').html(respn);
        }
    });
}

function roomsStatus() {
    $.ajax({
        url: '<?php echo base_url('/roomsStatusList') ?>',
        type: "post",
        data:{type:1},
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        async: false,
        success: function(respn) {
            $('#ROOM_STATUS').html(respn);
        }
    });
}

function roomsReturnStatus() {
    $.ajax({
        url: '<?php echo base_url('/roomsStatusList') ?>',
        type: "post",
        data:{type:2},
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        async: false,
        success: function(respn) {
            $('#ROOM_RETURN_STATUS').html(respn);
        }
    });
}

function roomsChangeReason() {
    $.ajax({
        url: '<?php echo base_url('/roomsChangeReasonList') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        async: false,
        success: function(respn) {
            $('#ROOM_CHANGE_REASON').html(respn);
        }
    });
}

$(document).on('click', '.add-room-status', function() {
    hideModalAlerts();
    $('.dtr-bs-modal').modal('hide'); 

    bootbox.dialog({
        message: "Do you want to update room status?",
        buttons: {
            ok: {
                label: 'Yes',
                className: 'btn-success',
                callback: function(result) {
                    if (result) {
                      clearFormFields('#OOOS_DIV');
                      $("#ROOM_REMARKS").val('');
                      var today = moment().format('DD-MM-YYYY');   
                      $('#STATUS_FROM_DATE').datepicker("setDate", today);
                      $('#STATUS_TO_DATE').datepicker("setDate", today);
                      
                        $('#Status_Details').find('tr.table-warning').removeClass(
                            'table-warning');

                        //Disable Delete button
                        toggleButton('.delete_room_status', 'btn-danger', 'btn-dark',
                            true);

                        showModalAlert('info',
                            'Fill in the form and click the \'Save\' button to update the status'
                        );
                        $('#infoModal').delay(2500).fadeOut();

                    }
                }
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        }
    });
});

$(document).on('click', '.save-roomstatus-details', function() {
  $('#ROOMS').prop("disabled", false);
    hideModalAlerts();
    var formSerialization = $('#ooos-submit-form').serializeArray();
    var url = '<?php echo base_url('/insertRoomOOS') ?>';
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
            if (response == '2') {
                mcontent = '<li>Something went wrong</li>';
                showModalAlert('error', mcontent);
            } else if (response != '1') {
                var ERROR = respn['RESPONSE']['ERROR'];
                var mcontent = '';
                $.each(ERROR, function(ind, data) {
                    //console.log(data, "SDF");
                    mcontent += '<li>' + data + '</li>';
                });
                showModalAlert('error', mcontent);
            } else {
                var alertText = $('#OOOS_ID').val() == '' ?
                    '<li>Successfully added</li>' :
                    '<li>Successfully updated</li>';
                hideModalAlerts();
                showModalAlert('success', alertText);


                if (respn['RESPONSE']['OUTPUT'] != '') {
                  
                    $('#OOOS_ID').val(respn['RESPONSE']['OUTPUT']);
                    showRoomStatus();
                    clearFormFields('#OOOSRoom');
                }
            }
        }
    });
});


function showRoomStatus() {

    $('#Status_Details').find('tr.table-warning').removeClass('table-warning');

    $('#Status_Details').DataTable({
        'processing': true,
        async: false,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': '<?php echo base_url('/roomOOSList') ?>',
           
        },
        'columns': [{
                data: 'OOOS_ID',
                'visible': false
            }, {

                data: 'RM_NO',
                render: function(data, type, full, meta) {
                    if (full['RM_NO'] != null)
                        return full['RM_NO'];
                    else
                        return '';
                }
            },
            {
                data: 'RSM_RM_STATUS_CODE'
            },

            {
                data: 'STATUS_FROM_DATE'
            },
            {
                data: 'STATUS_TO_DATE'
            },
            
            {
                data: 'SM_RM_STATUS_CODE'
            },
            {

              data: 'RM_STATUS_CHANGE_CODE',
              render: function(data, type, full, meta) {
                  if (full['RM_STATUS_CHANGE_CODE'] != null)
                      return full['RM_STATUS_CHANGE_CODE']+' | '+full['RM_STATUS_CHANGE_DESC'];
                  else
                      return '';
              }
            },
            {
                data: 'ROOM_REMARKS'
            },

        ],
        "order": [
            [1, "asc"]
        ],
        'createdRow': function(row, data, dataIndex) {

            $(row).attr('data-status_id', data['OOOS_ID']);

            if (dataIndex == 0) {
                //$(row).addClass('table-warning');
                //loadRoomStatusDetails(data['OOOS_ID']);
            }
        },


        destroy: true,
        "ordering": true,
        "searching": false,
        autowidth: true,
        responsive: true
    });
}



function loadRoomStatusDetails(OOOS_ID) {
  $('#ROOMS').prop("disabled", true);
    var url = '<?php echo base_url('/showRoomStatusDetails') ?>';
    $.ajax({
        url: url,
        type: "post",
        async: false,
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        data: {
          OOOS_ID: OOOS_ID
        },
        dataType: 'json',
        success: function(respn) {
          console.log(respn)
            toggleButton('.delete-room-status', 'btn-dark', 'btn-danger', false);
            $(respn).each(function(inx, data) {
                $.each(data, function(fields, datavals) {                   
                    var field = $.trim(fields);
                    var dataval = $.trim(datavals);                
                    
                  
                   if ( field == 'STATUS_FROM_DATE' || field == 'STATUS_TO_DATE' ){
                        $('#' + field).datepicker("setDate", new Date(dataval)); 
                    } 
                   else if (field == 'ROOMS' || field == 'ROOM_STATUS' || field == 'ROOM_RETURN_STATUS' || field == 'ROOM_CHANGE_REASON') {
                        $('#' + field).val(dataval).trigger('change');
                        
                        if(field == 'ROOMS')  $('#Room_ID').val(dataval);
                    } 
                   else {
                        $('#' + field).val(dataval);
                    }
                });
            });
        }
    });
}

// Delete status
$(document).on('click', '.delete-room-status', function() {
    hideModalAlerts();
    $('.dtr-bs-modal').modal('hide');
    var status_id = $('#Status_Details').find("tr.table-warning").data("status_id");
    if(status_id > 0){
    bootbox.confirm({
        message: "Status is active. Do you want to Delete?",
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
                    url: '<?php echo base_url('/deleteRoomOOS') ?>',
                    type: "post",
                    data: {
                      OOOS_ID: status_id,
                      
                    },
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    dataType: 'json',
                    success: function(respn) {
                        var response = respn['SUCCESS'];
                        if (response == '0') {
                            clearFormFields('#OOOSRoom');
                            showModalAlert('error',
                                '<li>The status cannot be deleted</li>');
                            $('#warningModal').delay(2500).fadeOut();
                        } else {
                          clearFormFields('#OOOSRoom');
                            blockLoader('#OOOSRoom');
                            showModalAlert('warning',
                                '<li>The status has been deleted</li>');
                            $('#warningModal').delay(2500).fadeOut(); 
                            showRoomStatus();
                        }
                    }
                });
            }
        }
    });
  }else{
    showModalAlert('warning',
            '<li>Please select a status</li>');
        $('#warningModal').delay(2500).fadeOut(); 
  }

});



$(document).on('click', '#Status_Details > tbody > tr', function() {
    $('#Status_Details').find('tr.table-warning').removeClass('table-warning');
    $(this).addClass('table-warning');
    $.when(loadRoomStatusDetails($(this).data('status_id')))
        .done(function() {})
        .done(function() {
            blockLoader('#OOOS_DIV');
        });
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




  // Display function toggleButton
  <?php echo isset($toggleButton_javascript) ? $toggleButton_javascript : ''; ?>

  // Display function clearFormFields
  <?php echo isset($clearFormFields_javascript) ? $clearFormFields_javascript : ''; ?>

  // Display function blockLoader
  <?php echo isset($blockLoader_javascript) ? $blockLoader_javascript : ''; ?>
</script>

<?= $this->endSection() ?>