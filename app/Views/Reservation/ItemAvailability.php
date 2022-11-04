<?=$this->extend("Layout/AppView")?>
<?=$this->section("contentRender")?>
<?= $this->include('Layout/SuccessReport') ?>
<?= $this->include('Layout/ErrorReport') ?>

<style>

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
</style>

  <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Reservations /</span> Item Availability</h4>

              <!-- DataTable with Buttons -->
              <div class="card">
                <h5 class="card-header ">Item Availability</h5>
                <div class="container-fluid p-3 pt-0">
                  <!-- Calendar Sidebar -->
                  <div class="app-calendar-sidebar col" id="app-calendar-sidebar" style="display: none;">
                    <div class="border-bottom p-4 my-sm-0 mb-3">
                        <div class="d-grid">
                            <button class="btn btn-primary btn-toggle-sidebar">

                                <span class="align-middle">Available Items</span>
                            </button>
                        </div>
                    </div>
                    <div class="p-4">
                        <!-- inline calendar (flatpicker) -->
                        <div class="ms-n2">
                            <div class="inline-calendar"></div>
                        </div>

                        <hr class="container-m-nx my-4" />


                        <div class="app-calendar-events-filter">

                            <div class="form-check mb-2" style="display: none">
                                <input class="form-check-input input-filter" type="checkbox"
                                    id="select-business" data-value="business" checked />
                                <label class="form-check-label" for="select-business">View
                                    All</label>
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
                            <div id="calendar"></div>
                        </div>
                    </div>
                    <div class="app-overlay"></div>
                    <!-- FullCalendar Offcanvas -->

                </div>
                </div>
              </div>
            </div>
            <!-- / Content -->            
            
            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
<script>

$(document).ready(function() {  
    showInventoryAvailability();
});
    
    function showInventoryAvailability() {
        var output = '';
        $.ajax({
            url: '<?php echo base_url('/getInventoryCalendarData') ?>',
            type: "post",
            async: false,
            dataType: 'json',
            success: function(respn) {
                output = respn;
            }
        });

        itemCalendarResources = output['itemResources'];
        itemCalendarAvail = output['itemAvail'];

        if ($('#calendar>*').length == 0) {
            calendarRender(itemCalendarResources, itemCalendarAvail);
        }
        console.log(itemCalendarAvail)
    }


    function calendarRender(itemCalendarResources, itemCalendarAvail) {

        let date = new Date();
        let nextDay = new Date(new Date().getTime() + 24 * 60 * 60 * 1000);
        // prettier-ignore
        let nextMonth = date.getMonth() === 11 ? new Date(date.getFullYear() + 1, 0, 1) : new Date(date.getFullYear(), date
            .getMonth() + 1, 1);
        // prettier-ignore
        let prevMonth = date.getMonth() === 11 ? new Date(date.getFullYear() - 1, 0, 1) : new Date(date.getFullYear(), date
            .getMonth() - 1, 1);

        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
            //schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
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

            resourceColumns: [{
                labelText: 'Item',

                field: 'item',

            }, ],
            validRange: {
                start: '2022-01-01',
                end: '2040-12-31'
            },

            resources: itemCalendarResources,
            //events: itemCalendarAvail,
            events: function(info, successCallback, failureCallback) {
                //console.log(info.start)
                let START = info.start;
                let s = new Date(START);
                START = s.toISOString(START);
                let END = info.end;
                let e = new Date(END);
                END = e.toISOString(END);
                $.ajax({
                    url: '<?php echo base_url('/getInventoryAllocatedData') ?>',
                    type: "post",
                    dataType: 'json',
                    data: {
                        start: START,
                        end: END
                    },
                    success: function(res) {

                        var events = [];
                        res.forEach(function(evt) {
                            events.push({
                                id: evt.id,
                                resourceId: evt.resourceId,
                                title: evt.title,
                                start: evt.start,
                                end: evt.end,
                            });
                        });
                        successCallback(events);
                    },

                });
            },
            eventMouseEnter: function(info) {
                var titleText = info.event.title;
                var myarr = titleText.split("|");
                var title = 'Available Quantity : ' + myarr[0] + ", Total Quantity: " + myarr[1];
                var title =
                    $(info.el).tooltip({
                        title: title,
                        html: true,
                        placement: 'top',
                        trigger: 'hover',
                        container: 'body',

                    });
            },

            eventDidMount: function(info) {
                var tooltip = new Tooltip(info.el, {
                    title: 'titlesdasd',
                    placement: 'top',
                    trigger: 'hover',
                    container: 'body',
                });
            },


        });

        calendar.render();
    }


</script>

<?=$this->endSection()?>