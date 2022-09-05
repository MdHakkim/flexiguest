<div class="modal fade" id="RoomStatistics" tabindex="-1" aria-labelledby="RoomStatisticsLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="RoomStatistics">Room Statistics</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="RoomStatisticsForm">
                    
                    <div class="row g-0">
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
                    <div id="calendarStatistics"></div>
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
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                
            </div>
        </div>
    </div>
</div>
<!-- /Modal window -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    let date = new Date();
    let nextDay = new Date(new Date().getTime() + 24 * 60 * 60 * 1000);
    // prettier-ignore
    let nextMonth = date.getMonth() === 11 ? new Date(date.getFullYear() + 1, 0, 1) : new Date(date.getFullYear(), date.getMonth() + 1, 1);
    // prettier-ignore
    let prevMonth = date.getMonth() === 11 ? new Date(date.getFullYear() - 1, 0, 1) : new Date(date.getFullYear(), date.getMonth() - 1, 1);



    var calendarElSt = document.getElementById('calendarStatistics');
    //var tooltip = null;
    var calendarS = new FullCalendar.Calendar(calendarElSt, {
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

     
 



    });

    calendarS.render();

  });
</script>