<?= $this->extend("Layout/CalendarView") ?>
<?= $this->section("contentRender") ?>
<?= $this->include('Layout/SuccessReport') ?>
<?= $this->include('Layout/ErrorReport') ?>
<style>

#RoomStatisticsModal .fc-scroller, #calendarRoomPlan .fc-scroller {
    height: auto !important;
}
  #calendar {
    max-width: 1100px;
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
    background-color: #405974 !important;
    border-color: #405974 !important;
    color: rgb(255, 255, 255) !important;

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

</style>

<!-- Content wrapper -->
<div class="content-wrapper">
  <!-- Content -->
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="card app-calendar-wrapper">
   
      <div class="row g-0">
      <div class="row">

      <div class="col-md-3 mt-3 mb-3">
        <button type="button" class="btn btn-primary"  id="showRoomStatisticsModal" 
        > Statistics</button>
        <!-- <button type="button" class="btn btn-primary" id="showRoomOS"  > OOO / OOS</button></div>         -->
    </div>
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
            <div class="card-body">
              <!-- FullCalendar -->
              <div id="calendarRoomPlan"></div>
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



     

    <div class="modal fade" id="RoomStatisticsModal" data-backdrop="static" data-keyboard="false"
        aria-labelledby="popModalWindowlabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="popModalWindowlabel">Statistics</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
        <div class="modal fade" id="OOOSCharges" data-backdrop="static" data-keyboard="false"
        aria-labelledby="popModalWindowlabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="popModalWindowlabel">Fixed Charges</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form id="ooos-submit-form" onSubmit="return false">
                        <div id="OOOS_DIV" class="content">
                            <input type="hidden" name="OOOS_ID" id="OOOS_ID" class="form-control" />
                            <div class="row g-3">
                                <div class="border rounded p-4 mb-3">
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                        
                                            <label for="ROOM_CODE"
                                                class="col-form-label col-md-5"><b>Room *</b></label>
                                            <select id="ROOM_CODE" name="ROOM_CODE"
                                                class="select2 form-select form-select-lg"></select>
                                       
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="FIXD_ARRIVAL" class="col-form-label col-md-4"><b>
                                                    Arrival</b></label>
                                            <input type="text" name="FIXD_ARRIVAL" id="FIXD_ARRIVAL" disabled
                                                class="form-control" />

                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="FIXD_NIGHTS" class="col-form-label col-md-4"><b>
                                                    Nights</b></label>
                                            <input type="text" name="FIXD_NIGHTS" id="FIXD_NIGHTS" class="form-control"
                                                disabled />

                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="FIXD_DEPARTURE" class="col-form-label col-md-4"><b>
                                                    Departure</b></label>
                                            <input type="text" name="FIXD_DEPARTURE" id="FIXD_DEPARTURE" disabled
                                                class="form-control" />
                                            <input type="hidden" name="FIXD_DEPARTURE_UP" id="FIXD_DEPARTURE_UP"
                                                class="form-control" />

                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-2 mb-3">
                                            <label for="FIXD_CHRG_FREQUENCY" class="col-form-label col-md-4"><b>
                                                    FREQUENCY</b></label>

                                        </div>
                                    </div>

                                    <div class="row g-3 mb-3">
                                        <?php if (!empty($FrequencyList)) {
                                            foreach ($FrequencyList as $Freequency) {
                                        ?>
                                        <div class="col-md-2">
                                            <div class="form-check">
                                                <input id="FIXD_CHRG_FREQUENCY<?php echo $Freequency['ID']; ?>"
                                                    value="<?php echo $Freequency['ID']; ?>" name="FIXD_CHRG_FREQUENCY"
                                                    class="form-check-input" type="radio"
                                                    <?php if ($Freequency['ID'] == 2) { ?> checked="checked" <?php } ?>
                                                    onclick=frequency(<?php echo $Freequency['ID']; ?>)>
                                                <label class="form-check-label" for="FIXD_CHRG_FREQUENCY">
                                                    <?php echo $Freequency['FREQ_NAME']; ?> </label>
                                            </div>
                                        </div>
                                        <?php
                                            }
                                        } ?>
                                    </div>
                                    <div class="row g-3 ">
                                        <div class="col-md-4 mb-3">
                                            <label for="FIXD_CHRG_BEGIN_DATE" class="col-form-label col-md-4"><b>Start
                                                    Date *</b></label>
                                            <input class="form-control" type="text" placeholder="d-Mon-yyyy"
                                                id="FIXD_CHRG_BEGIN_DATE" name="FIXD_CHRG_BEGIN_DATE" />
                                        </div>
                                        <div class="col-md-4 END_DATE">
                                            <label for="FIXD_CHRG_END_DATE" class="col-form-label col-md-4"><b>End
                                                    Date *</b></label>
                                            <input class="form-control" type="text" placeholder="d-Mon-yyyy"
                                                id="FIXD_CHRG_END_DATE" name="FIXD_CHRG_END_DATE" />

                                        </div>
                                        <div class="col-md-4 WEEKLY_EXCECUTE" style="display: none">
                                            <label for="FIXD_CHRG_WEEKLY" class="col-form-label col-md-4"><b>Day to
                                                    Execute</b></label>
                                            <select name="FIXD_CHRG_WEEKLY" id="FIXD_CHRG_WEEKLY"
                                                class="select2 form-select form-select-lg">
                                                <option value="0" selected>Sunday</option>
                                                <option value="1">Monday</option>
                                                <option value="2">Tuesday</option>
                                                <option value="3">Wednesday</option>
                                                <option value="4">Thursday</option>
                                                <option value="5">Friday</option>
                                                <option value="6">Saturday</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 MONTHLY_EXCECUTE" style="display: none">
                                            <label for="FIXD_CHRG_MONTHLY" class="col-form-label col-md-5"><b>Day to
                                                    Execute ( 1-31 )</b></label>
                                            <input type="text" name="FIXD_CHRG_MONTHLY" id="FIXD_CHRG_MONTHLY"
                                                class="form-control" placeholder="eg: 1" value='1' />
                                        </div>

                                        <div class="col-md-4 QUARTERLY_EXCECUTE" style="display: none">
                                            <label for="FIXD_CHRG_QUARTERLY" class="col-form-label col-md-5"><b>Day to
                                                    Execute ( 1-31 )</b></label>
                                            <input type="text" name="FIXD_CHRG_QUARTERLY" id="FIXD_CHRG_QUARTERLY"
                                                class="form-control" placeholder="eg: 1" value='1' />
                                        </div>

                                        <div class="col-md-4 YEARLY_EXCECUTE" style="display: none">
                                            <label for="FIXD_CHRG_YEARLY" class="col-form-label col-md-5"><b>Day to
                                                    Execute</b></label>
                                            <input class="form-control dateFIXD_CHRG" type="text"
                                                placeholder="yyyy-mm-dd" id="FIXD_CHRG_YEARLY"
                                                name="FIXD_CHRG_YEARLY" />
                                        </div>
                                    </div>
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-4">
                                            <label for="FIXD_CHRG_TRNCODE"
                                                class="col-form-label col-md-5"><b>TRANSACTION CODE *</b></label>
                                            <select id="FIXD_CHRG_TRNCODE" name="FIXD_CHRG_TRNCODE"
                                                class="select2 form-select form-select-lg"></select>

                                        </div>
                                        <div class="col-md-4 ">
                                            <label for="FIXD_CHRG_AMT" class="col-form-label col-md-4"><b>AMOUNT
                                                    *</b></label>
                                            <input type="number" name="FIXD_CHRG_AMT" id="FIXD_CHRG_AMT"
                                                class="form-control" min="1" step="1" placeholder="eg: 12" />

                                        </div>

                                        <div class="col-md-4">
                                            <label for="FIXD_CHRG_QTY" class="col-form-label col-md-4"><b>QUANTITY
                                                    *</b></label>
                                            <input type="number" name="FIXD_CHRG_QTY" id="FIXD_CHRG_QTY"
                                                class="form-control" min="1" step="1" placeholder="eg: 12" />

                                        </div>
                                    </div>

                                    <div class="row g-3 ">
                                        <div class="col-md-3 mb-3">
                                            <div class="col-md-8 float-right">
                                                <button type="button" class="btn btn-success save-fixedcharge-detail">
                                                    <i class="fa-solid fa-floppy-disk"></i>&nbsp; Save
                                                </button>&nbsp;
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="border rounded p-4 mb-3">
                                        <div class="col-md-6 mb-3">
                                            <button type="button" class="btn btn-primary add-fixedcharge-detail">
                                                <i class="fa-solid fa-circle-plus"></i>&nbsp; Add New
                                            </button>&nbsp;

                                            <button type="button" class="btn btn-danger delete-fixedcharge-detail">
                                                <i class="fa-solid fa-ban"></i>&nbsp; Delete
                                            </button>&nbsp;
                                        </div>

                                        <div class="table-responsive text-nowrap">
                                            <table id="FixedCharge_Details" class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th class="all">Trans Code & Description</th>
                                                        <th class="all">Quantity</th>
                                                        <th class="all">Amount</th>
                                                        <th class="all">Frequency</th>
                                                        <th class="all">Begin Date</th>
                                                        <th class="all">End Date</th>
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

    <!-- /Modal Fixed charges window --> 

    
    </div>
  </div>
  <!-- / Content -->
  <div class="content-backdrop fade"></div>
</div>
<!-- Content wrapper -->

<?php //print_r($RoomReservations);
?>

<script>

$( document ).ready(function() {

    $('#RoomStatisticsModal').on('shown.bs.modal', function () {
      if($('#calendarStatistics1>*').length == 0)
        roomStatistics()
    })
    roomPlanFunc()
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

      events: [
        <?php
        if (!empty($RoomReservations)) {
          foreach ($RoomReservations as $row) {
            $start = date("Y-m-d H:i:s", strtotime($row['RESV_ARRIVAL_DT']));
            //$startDate = '2022-08-15';

            $end = date("Y-m-d 23:59:59", strtotime($row['RESV_DEPARTURE']));
            //$endDate = '2022-08-18 23:00:00';
        ?> {
              id: '<?php echo $row['RESV_ID'] ?>',
              resourceId: '<?php echo $row['RM_ID'] ?>',
              title: '<?php echo $row['FULLNAME'] . ' - ' . $row['RESV_STATUS']; ?>',
              start: '<?php echo $start; ?>',
              end: '<?php echo $end; ?>',
              backgroundColor: "#ccc",
              borderColor: "#ddd",
              textColor: "#fff",
              backgroundColor: "#ccc",
              borderColor: "#ddd",
              textColor: "#fff",
              
            },

        <?php }
        } ?>
       
      ],
      eventClick: function(info) {
        info.jsEvent.preventDefault(); // don't let the browser navigate

        if (info.event.id) {
          var base_url = '<?php echo base_url()?>';
          var url = '/reservation?RESV_ID='+info.event.id;
          window.open(base_url+url);
        }
      },
     
      

      eventDrop: function(info) {
        console.log(info);
        // console.log(info.newResource.id)
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

        if (confirm("Are you sure about this change?")) {
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
      }


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
   
    $('#showRoomStatisticsModal').click(function() {
      
    //window.setTimeout(clickToday, 100);
    $('#RoomStatisticsModal').modal('show');
    });


    //////// OOO/OOS Functions

$(document).on('click', '#OOO_OOS_Button', function() {
    $('#OOOSCharges').modal('show');    
    roomsList();
    roomsStatus();
    roomsChangeReason();    

});


function roomsList() {
    $.ajax({
        url: '<?php echo base_url('/roomsList') ?>',
        type: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        async: false,
        // dataType:'json',
        success: function(respn) {
            // console.log(respn,"testing");
            $('#RSV_ITM_CL_ID').html(respn);
        }
    });
}





  // Display function toggleButton
  <?php echo isset($toggleButton_javascript) ? $toggleButton_javascript : ''; ?>

  // Display function clearFormFields
  <?php echo isset($clearFormFields_javascript) ? $clearFormFields_javascript : ''; ?>

  // Display function blockLoader
  <?php echo isset($blockLoader_javascript) ? $blockLoader_javascript : ''; ?>
</script>

<?= $this->endSection() ?>