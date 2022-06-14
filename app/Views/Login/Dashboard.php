
<?=$this->extend("Layout/AppView")?>
<?=$this->section("titleRender")?>
<title>Dashboard - Analytics | FlexiGuest</title>
<?=$this->endSection()?>
<?=$this->section("contentRender")?>
        
       
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="row">
                <!-- Website Analytics-->
                <div class="col-md-12 col-lg-6 mb-4">
                  <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                      <h5 class="card-title mb-0">Hotel Analytics</h5>
                      <div class="dropdown">
                        <button
                          class="btn p-0"
                          type="button"
                          id="analyticsOptions"
                          data-bs-toggle="dropdown"
                          aria-haspopup="true"
                          aria-expanded="false"
                        >
                          <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="analyticsOptions">
                          <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                          <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                          <a class="dropdown-item" href="javascript:void(0);">Share</a>
                        </div>
                      </div>
                    </div>
                    <div class="card-body pb-2">
                      <div class="d-flex flex-wrap justify-content-around align-items-center mb-4">
                        <div class="user-analytics text-center me-2">
                          <i class="bx bx-user me-1"></i>
                          <span>InHouse Guests</span>
                          <div class="d-flex align-items-center mt-2">
                            <div class="chart-report" data-color="info" data-series="35"></div>
                            <h3 class="mb-0">610</h3>
                          </div>
                        </div>
                        <div class="sessions-analytics text-center me-2">
                          <i class="bx bx-pie-chart-alt me-1"></i>
                          <span>Arrivals</span>
                          <div class="d-flex align-items-center mt-2">
                            <div class="chart-report" data-color="success" data-series="76"></div>
                            <h3 class="mb-0">92</h3>
                          </div>
                        </div>
                        <div class="bounce-rate-analytics text-center">
                          <i class="bx bx-trending-up me-1"></i>
                          <span>Departures</span>
                          <div class="d-flex align-items-center mt-2">
                            <div class="chart-report" data-color="danger" data-series="65"></div>
                            <h3 class="mb-0">72</h3>
                          </div>
                        </div>
                      </div>
                      <div id="analyticsBarChart"></div>
                    </div>
                  </div>
                </div>

                <!-- Referral, conversion, impression & income charts -->
                <div class="col-md-12 col-lg-6">
                  <div class="row">
                    <!-- Referral Chart-->
                    <div class="col-12 col-sm-6 mb-4">
                      <div class="card">
                        <div class="card-body text-center">
                          <h2 class="mb-1">$32,690</h2>
                          <span class="text-muted">Referral 40%</span>
                          <div id="referralLineChart"></div>
                        </div>
                      </div>
                    </div>
                    <!-- Conversion Chart-->
                    <div class="col-12 col-sm-6 mb-4">
                      <div class="card">
                        <div class="card-header d-flex justify-content-between pb-3">
                          <div class="conversion-title">
                            <h5 class="card-title mb-1">Conversion</h5>
                            <p class="text-muted mb-0">
                              60%
                              <i class="bx bx-chevron-up text-success"></i>
                            </p>
                          </div>
                          <h2 class="mb-0">89k</h2>
                        </div>
                        <div class="card-body">
                          <div id="conversionBarchart"></div>
                        </div>
                      </div>
                    </div>
                    <!-- Impression Radial Chart-->
                    <div class="col-12 col-sm-6 mb-4">
                      <div class="card">
                        <div class="card-body text-center">
                          <div id="impressionDonutChart"></div>
                        </div>
                      </div>
                    </div>
                    <!-- Growth Chart-->
                    <div class="col-12 col-sm-6">
                      <div class="row">
                        <div class="col-12 mb-4">
                          <div class="card">
                            <div class="card-body">
                              <div class="d-flex justify-content-between">
                                <div class="gap-3 d-flex align-items-center">
                                  <div class="avatar">
                                    <span class="avatar-initial bg-label-success rounded-circle"
                                      ><i class="bx bx-dollar fs-4"></i
                                    ></span>
                                  </div>
                                  <div class="card-info">
                                    <h5 class="card-title me-2 mb-0">$38,566</h5>
                                    <small class="text-muted">Today's Revenue</small>
                                  </div>
                                </div>
                                <div id="conversationChart"></div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-12 mb-4">
                          <div class="card">
                            <div class="card-body">
                              <div class="d-flex justify-content-between">
                                <div class="gap-3 d-flex align-items-center">
                                  <div class="avatar">
                                    <span class="avatar-initial bg-label-warning rounded-circle"
                                      ><i class="bx bx-dollar fs-4"></i
                                    ></span>
                                  </div>
                                  <div class="card-info">
                                    <h5 class="card-title me-2 mb-0">$5,369</h5>
                                    <small class="text-muted">Today's Payments</small>
                                  </div>
                                </div>
                                <div id="incomeChart"></div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!--/ Referral, conversion, impression & income charts -->

                <!-- Activity -->
                <div class="col-xxl-3 col-md-6 col-lg-6 col-xl-6 mb-4">
                  <div class="card">
                    <div class="card-header">
                      <h5 class="card-title mb-0">Amenities</h5>
                    </div>
                    <div class="card-body">
                      <ul class="p-0 m-0">
                        <li class="d-flex pb-2 mb-4">
                          <div class="avatar avatar-sm flex-shrink-0 me-3">
                            <span class="avatar-initial bg-label-primary rounded-circle"
                              ><i class="bx bx-cube"></i
                            ></span>
                          </div>
                          <div class="d-flex flex-column w-100">
                            <div class="d-flex justify-content-between mb-1">
                              <span>Total Sales</span>
                              <span class="text-muted">$2,459</span>
                            </div>
                            <div class="progress" style="height: 6px">
                              <div
                                class="progress-bar bg-primary"
                                style="width: 40%"
                                role="progressbar"
                                aria-valuenow="40"
                                aria-valuemin="0"
                                aria-valuemax="100"
                              ></div>
                            </div>
                          </div>
                        </li>
                        <li class="d-flex pb-2 mb-4">
                          <div class="avatar avatar-sm flex-shrink-0 me-3">
                            <span class="avatar-initial bg-label-success rounded-circle"
                              ><i class="bx bx-dollar"></i
                            ></span>
                          </div>
                          <div class="d-flex flex-column w-100">
                            <div class="d-flex justify-content-between mb-1">
                              <span>Income</span>
                              <span class="text-muted">$8,478</span>
                            </div>
                            <div class="progress" style="height: 6px">
                              <div
                                class="progress-bar bg-success"
                                style="width: 80%"
                                role="progressbar"
                                aria-valuenow="80"
                                aria-valuemin="0"
                                aria-valuemax="100"
                              ></div>
                            </div>
                          </div>
                        </li>
                        <li class="d-flex pb-2 mb-4">
                          <div class="avatar avatar-sm flex-shrink-0 me-3">
                            <span class="avatar-initial bg-label-warning rounded-circle"
                              ><i class="bx bx-trending-up"></i
                            ></span>
                          </div>
                          <div class="d-flex flex-column w-100">
                            <div class="d-flex justify-content-between mb-1">
                              <span>Budget</span>
                              <span class="text-muted">$12,490</span>
                            </div>
                            <div class="progress" style="height: 6px">
                              <div
                                class="progress-bar bg-warning"
                                style="width: 80%"
                                role="progressbar"
                                aria-valuenow="80"
                                aria-valuemin="0"
                                aria-valuemax="100"
                              ></div>
                            </div>
                          </div>
                        </li>
                        <li class="d-flex mb-2">
                          <div class="avatar avatar-sm flex-shrink-0 me-3">
                            <span class="avatar-initial bg-label-danger rounded-circle"
                              ><i class="bx bx-check"></i
                            ></span>
                          </div>
                          <div class="d-flex flex-column w-100">
                            <div class="d-flex justify-content-between mb-1">
                              <span>Tasks</span>
                              <span class="text-muted">$184</span>
                            </div>
                            <div class="progress" style="height: 6px">
                              <div
                                class="progress-bar bg-danger"
                                style="width: 25%"
                                role="progressbar"
                                aria-valuenow="25"
                                aria-valuemin="0"
                                aria-valuemax="100"
                              ></div>
                            </div>
                          </div>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
                <!--/ Activity -->

                <!-- Profit Report & Registration -->
                <div class="col-xxl-3 col-md-6 col-lg-6 col-xl-6">
                  <div class="row">
                    <div class="col-12 col-sm-6 col-md-12 mb-4">
                      <div class="card h-100">
                        <div class="card-header">
                          <h5 class="card-title mb-0">Profit Report</h5>
                        </div>
                        <div class="card-body d-flex justify-content-between align-items-end">
                          <div class="gap-3 d-flex justify-content-between align-items-center w-100">
                            <div class="align-content-center d-flex">
                              <div class="chart-report" data-color="danger" data-series="25"></div>
                              <div class="chart-info">
                                <h5 class="mb-0">$12k</h5>
                                <small class="text-muted">2020</small>
                              </div>
                            </div>
                            <div class="align-content-center d-flex">
                              <div class="chart-report" data-color="info" data-series="50"></div>
                              <div class="chart-info">
                                <h5 class="mb-0">$64k</h5>
                                <small class="text-muted">2021</small>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-12 mb-4">
                      <div class="card">
                        <div class="card-header pb-2">
                          <h5 class="card-title mb-0">Reservations</h5>
                        </div>
                        <div class="card-body pb-2">
                          <div class="gap-3 d-flex justify-content-between align-items-end">
                            <div class="mb-3">
                              <div class="align-content-center d-flex">
                                <h5 class="mb-1">584</h5>
                                <i class="bx bx-chevron-up text-success"></i>
                              </div>
                              <small class="text-success">12.8%</small>
                            </div>
                            <div id="registrationsBarChart"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!--/ Profit Report & Registration -->

                <!-- Sales -->
                <div class="col-xxl-3 col-md-6 col-lg-6 col-xl-6 mb-4">
                  <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-start">
                      <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Check-Ins</h5>
                        <small class="card-subtitle text-muted">Calculated in last 7 days</small>
                      </div>
                      <div class="dropdown">
                        <button
                          class="btn p-0"
                          type="button"
                          id="salesReport"
                          data-bs-toggle="dropdown"
                          aria-haspopup="true"
                          aria-expanded="false"
                        >
                          <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="salesReport">
                          <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                          <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                          <a class="dropdown-item" href="javascript:void(0);">Share</a>
                        </div>
                      </div>
                    </div>
                    <div class="card-body">
                      <div id="salesChart"></div>
                      <ul class="p-0 m-0">
                        <li class="d-flex mb-3">
                          <span class="text-primary me-2"><i class="bx bx-up-arrow-alt bx-sm"></i></span>
                          <div class="gap-2 d-flex flex-wrap justify-content-between align-items-center w-100">
                            <div class="me-2">
                              <h6 class="lh-1 mb-0">Highest</h6>
                              <small class="text-muted">Saturday</small>
                            </div>
                            <div class="item-progress">286</div>
                          </div>
                        </li>
                        <li class="d-flex">
                          <span class="text-secondary me-2"><i class="bx bx-down-arrow-alt bx-sm"></i></span>
                          <div class="gap-2 d-flex flex-wrap justify-content-between align-items-center w-100">
                            <div class="me-2">
                              <h6 class="lh-1 mb-0">Lowest</h6>
                              <small class="text-muted">Thursday</small>
                            </div>
                            <div class="item-progress">79</div>
                          </div>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
                <!--/ Sales -->

                <!-- Growth Chart-->
                <div class="col-xxl-3 col-md-6 col-lg-6 col-xl-6 mb-4">
                  <div class="card">
                    <div class="card-body text-center">
                      <div class="dropdown mb-4">
                        <button
                          class="btn btn-sm btn-outline-secondary dropdown-toggle"
                          type="button"
                          id="dropdownMenuButtonSec"
                          data-bs-toggle="dropdown"
                          aria-haspopup="true"
                          aria-expanded="false"
                        >
                          2020
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButtonSec">
                          <a class="dropdown-item" href="javascript:void(0);">2022</a>
                          <a class="dropdown-item" href="javascript:void(0);">2021</a>
                          <a class="dropdown-item" href="javascript:void(0);">2020</a>
                        </div>
                      </div>
                      <div id="growthRadialChart"></div>
                      <h6 class="mt-5 mb-0">62% Growth in 2022</h6>
                    </div>
                  </div>
                </div>
                <!-- Growth Chart-->

                <!-- Finance Summary -->
                <div class="col-md-7 col-lg-7 mb-4 mb-md-0">
                  <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                      <div class="d-flex align-items-center me-3">
                        <img src="assets/img/avatars/4.png" alt="Avatar" class="rounded-circle me-3" width="54" />
                        <div class="card-title mb-0">
                          <h5 class="mb-0">Audit Report for Kiara Cruiser</h5>
                          <small class="text-muted">Awesome App for Project Management</small>
                        </div>
                      </div>
                      <div class="dropdown btn-pinned">
                        <button
                          class="btn p-0"
                          type="button"
                          id="financoalReport"
                          data-bs-toggle="dropdown"
                          aria-haspopup="true"
                          aria-expanded="false"
                        >
                          <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="financoalReport">
                          <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                          <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                          <a class="dropdown-item" href="javascript:void(0);">Share</a>
                        </div>
                      </div>
                    </div>
                    <div class="card-body">
                      <div class="gap-4 d-flex flex-wrap mt-4 mb-5">
                        <div class="d-flex flex-column me-2">
                          <h6>Start Date</h6>
                          <span class="badge bg-label-success">02 APR 22</span>
                        </div>
                        <div class="d-flex flex-column me-2">
                          <h6>End Date</h6>
                          <span class="badge bg-label-danger">06 MAY 22</span>
                        </div>
                        <div class="d-flex flex-column me-2">
                          <h6>Members</h6>
                          <ul class="list-unstyled avatar-group d-flex align-items-center me-2 mb-0">
                            <li
                              data-bs-toggle="tooltip"
                              data-popup="tooltip-custom"
                              data-bs-placement="top"
                              title="Vinnie Mostowy"
                              class="avatar avatar-xs pull-up"
                            >
                              <img class="rounded-circle" src="assets/img/avatars/5.png" alt="Avatar" />
                            </li>
                            <li
                              data-bs-toggle="tooltip"
                              data-popup="tooltip-custom"
                              data-bs-placement="top"
                              title="Allen Rieske"
                              class="avatar avatar-xs pull-up"
                            >
                              <img class="rounded-circle" src="assets/img/avatars/12.png" alt="Avatar" />
                            </li>
                            <li
                              data-bs-toggle="tooltip"
                              data-popup="tooltip-custom"
                              data-bs-placement="top"
                              title="Julee Rossignol"
                              class="avatar avatar-xs pull-up"
                            >
                              <img class="rounded-circle" src="assets/img/avatars/6.png" alt="Avatar" />
                            </li>
                            <li
                              data-bs-toggle="tooltip"
                              data-popup="tooltip-custom"
                              data-bs-placement="top"
                              title="Ellen Wagner"
                              class="avatar avatar-xs pull-up"
                            >
                              <img class="rounded-circle" src="assets/img/avatars/14.png" alt="Avatar" />
                            </li>
                            <li
                              data-bs-toggle="tooltip"
                              data-popup="tooltip-custom"
                              data-bs-placement="top"
                              title="Darcey Nooner"
                              class="avatar avatar-xs pull-up"
                            >
                              <img class="rounded-circle" src="assets/img/avatars/10.png" alt="Avatar" />
                            </li>
                          </ul>
                        </div>
                        <div class="d-flex flex-column me-2">
                          <h6>Budget</h6>
                          <span>$249k</span>
                        </div>
                        <div class="d-flex flex-column me-2">
                          <h6>Expenses</h6>
                          <span>$82k</span>
                        </div>
                      </div>
                      <div class="d-flex flex-column flex-grow-1">
                        <span class="d-block text-nowrap mb-1">Kiara Cruiser Progress</span>
                        <div class="progress w-100 mb-3" style="height: 8px">
                          <div
                            class="progress-bar bg-primary"
                            role="progressbar"
                            style="width: 80%"
                            aria-valuenow="80"
                            aria-valuemin="0"
                            aria-valuemax="100"
                          ></div>
                        </div>
                      </div>
                      <span
                        >I distinguish three main text objectives. First, your objective could be merely to inform
                        people. A second be to persuade people.</span
                      >
                    </div>
                    <div class="card-footer border-top">
                      <ul class="list-inline mb-0">
                        <li class="list-inline-item"><i class="bx bx-check"></i> 74 Tasks</li>
                        <li class="list-inline-item"><i class="bx bx-chat"></i> 678 Comments</li>
                      </ul>
                    </div>
                  </div>
                </div>
                <!-- Finance Summary -->

                <!-- Activity Timeline -->
                <div class="col-md-5 col-lg-5 mb-0">
                  <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                      <h5 class="card-title m-0 me-2">Activity Timeline</h5>
                      <div class="dropdown">
                        <button
                          class="btn p-0"
                          type="button"
                          id="timelineWapper"
                          data-bs-toggle="dropdown"
                          aria-haspopup="true"
                          aria-expanded="false"
                        >
                          <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="timelineWapper">
                          <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                          <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                          <a class="dropdown-item" href="javascript:void(0);">Share</a>
                        </div>
                      </div>
                    </div>
                    <div class="card-body">
                      <!-- Activity Timeline -->
                      <ul class="timeline">
                        <li class="timeline-item timeline-item-transparent ps-4">
                          <span class="timeline-point timeline-point-primary"></span>
                          <div class="timeline-event pb-2">
                            <div class="timeline-header mb-1">
                              <h6 class="mb-0">12 Invoices have been paid</h6>
                              <small class="text-muted">12 min ago</small>
                            </div>
                            <p class="mb-2">Invoices have been paid to the company</p>
                            <div class="d-flex">
                              <a href="javascript:void(0)" class="me-3">
                                <img
                                  src="assets/img/icons/misc/pdf.png"
                                  alt="PDF image"
                                  width="23"
                                  class="me-2"
                                />
                                <span class="text-body fw-bold">Invoices.pdf</span>
                              </a>
                            </div>
                          </div>
                        </li>
                        <li class="timeline-item timeline-item-transparent ps-4">
                          <span class="timeline-point timeline-point-warning"></span>
                          <div class="timeline-event pb-2">
                            <div class="timeline-header mb-1">
                              <h6 class="mb-0">Client Meeting</h6>
                              <small class="text-muted">45 min ago</small>
                            </div>
                            <p class="mb-2">Project meeting with john @10:15am</p>
                            <div class="d-flex flex-wrap">
                              <div class="avatar me-3">
                                <img src="assets/img/avatars/1.png" alt="Avatar" class="rounded-circle" />
                              </div>
                              <div>
                                <h6 class="mb-0">John Doe (Client)</h6>
                                <span class="text-muted">CEO of Pixinvent</span>
                              </div>
                            </div>
                          </div>
                        </li>
                        <li class="timeline-item timeline-item-transparent ps-4">
                          <span class="timeline-point timeline-point-info"></span>
                          <div class="timeline-event pb-0">
                            <div class="timeline-header mb-1">
                              <h6 class="mb-0">Westside Room Renovation Project</h6>
                              <small class="text-muted">2 Day Ago</small>
                            </div>
                            <p class="mb-2">5 team members in a project</p>
                            <div class="avatar-group d-flex align-items-center">
                              <div
                                class="avatar avatar-sm pull-up"
                                data-bs-toggle="tooltip"
                                data-popup="tooltip-custom"
                                data-bs-placement="top"
                                title="Vinnie Mostowy"
                              >
                                <img src="assets/img/avatars/5.png" alt="Avatar" class="rounded-circle" />
                              </div>
                              <div
                                class="avatar avatar-sm pull-up"
                                data-bs-toggle="tooltip"
                                data-popup="tooltip-custom"
                                data-bs-placement="top"
                                title="Marrie Patty"
                              >
                                <img src="assets/img/avatars/12.png" alt="Avatar" class="rounded-circle" />
                              </div>
                              <div
                                class="avatar avatar-sm pull-up"
                                data-bs-toggle="tooltip"
                                data-popup="tooltip-custom"
                                data-bs-placement="top"
                                title="Jimmy Jackson"
                              >
                                <img src="assets/img/avatars/9.png" alt="Avatar" class="rounded-circle" />
                              </div>
                              <div
                                class="avatar avatar-sm pull-up"
                                data-bs-toggle="tooltip"
                                data-popup="tooltip-custom"
                                data-bs-placement="top"
                                title="Kristine Gill"
                              >
                                <img src="assets/img/avatars/6.png" alt="Avatar" class="rounded-circle" />
                              </div>
                              <div
                                class="avatar avatar-sm pull-up"
                                data-bs-toggle="tooltip"
                                data-popup="tooltip-custom"
                                data-bs-placement="top"
                                title="Nelson Wilson"
                              >
                                <img src="assets/img/avatars/14.png" alt="Avatar" class="rounded-circle" />
                              </div>
                            </div>
                          </div>
                        </li>
                        <li class="timeline-end-indicator">
                          <i class="bx bx-check-circle"></i>
                        </li>
                      </ul>
                      <!-- /Activity Timeline -->
                    </div>
                  </div>
                </div>
                <!--/ Activity Timeline -->
              </div>
            </div>
            <!-- / Content -->


          

<?=$this->endSection()?>