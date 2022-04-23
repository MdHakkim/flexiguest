

<?=$this->extend("Layout/AppView")?>
<?=$this->section("contentRender")?>
<?= $this->include('Layout/ErrorReport') ?>
          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Reservation /</span> Reservation</h4>
              <!-- DataTable with Buttons -->
              <div class="card">
                <!-- <h5 class="card-header">Responsive Datatable</h5> -->
                <div class="container-fluid" style="padding:6px;">
                  <table id="dataTable_view" class="table table-striped">
                    <thead>
                      <tr>
                        <th>Reservation No</th>
                        <th>Reservation Name</th>
                        <th>Arrival Date</th>
                        <th>Departure Date</th>
                        <th>Night</th>
                        <th>No of Room</th>
                        <th>Purpose</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    
                  </table>
                </div>
              </div>
              <div id="triggCopyReserv"></div>
              <!--/ Multilingual -->
            </div>
            <!-- / Content -->

            <!-- Modal Window -->
           
            <div class="modal fade" id="reservationW" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog modal-xl">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="reservationWlable">Reservation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form id="reservationForm" novalidate>
                      <div class="window-1" id="window1">
                        <div class="row g-3">
                          <div class="col-md-6">
                            <input type="hidden" name="RESV_STATUS" id="RESV_STATUS" class="form-control"/>
                            <lable class="form-lable">Arrival/Departure Date</lable>
                              <div class="input-group mb-3">
                                <input type="text" id="RESV_ARRIVAL_DT" class="form-control RESV_ARRIVAL_DT" placeholder="DD-MM-YYYY">
                                <span class="input-group-append">
                                  <span class="input-group-text bg-light d-block">
                                    <i class="fa fa-calendar"></i>
                                  </span>
                                </span>
                                <input type="text" id="RESV_DEPARTURE" class="form-control RESV_DEPARTURE" placeholder="DD-MM-YYYY">
                                <span class="input-group-append">
                                  <span class="input-group-text bg-light d-block">
                                    <i class="fa fa-calendar"></i>
                                  </span>
                                </span>
                              </div>
                          </div>
                          <div class="col-md-3">
                            <lable class="form-lable">Night/No of Room</lable>
                            <div class="input-group mb-3">
                              <input type="number"  id="RESV_NIGHT" class="form-control RESV_NIGHT" placeholder="night" />
                              <input type="number"  id="RESV_NO_F_ROOM" class="form-control RESV_NO_F_ROOM" placeholder="no of room" />
                            </div>
                          </div>
                          <div class="col-md-3">
                            <lable class="form-lable">Adults/Children</lable>
                              <div class="input-group mb-3">
                                <input type="number"  id="RESV_ADULTS" class="form-control RESV_ADULTS" placeholder="adults" />
                                <input type="number"  id="RESV_CHILDREN" class="form-control RESV_CHILDREN" placeholder="children" />
                              </div>
                          </div>
                          <div class="col-md-3 mt-0">
                            <lable class="form-lable">Guest Name</lable>
                              <div class="input-group mb-3">
                                <select id="RESV_NAME" class="selectpicker RESV_NAME" data-live-search="true">
                                  <option value="">Select</option>
                                </select>
                                <button type="button" onClick="childReservation('C')" class="btn flxi_btn btn-sm btn-primary"><i class="fa fa-plus" aria-hidden="true"></i></button>
                              </div>
                          </div>
                          <div class="col-md-3 mt-0">
                            <lable class="form-lable">Member Type</lable>
                            <select id="RESV_MEMBER_TY" class=" select2 form-select" data-allow-clear="true">
                              <option value="">Select</option>
                            </select>
                          </div>
                          <div class="col-md-3 mt-0">
                            <lable class="form-lable">Company</lable>
                            <div class="input-group mb-3">
                              <select  id="RESV_COMPANY" class="selectpicker RESV_COMPANY" data-live-search="true">
                                  <option value="">Select</option>
                                </select>
                              <button type="button" onClick="companyAgentClick('COMPANY')" class="btn flxi_btn btn-sm btn-primary"><i class="fa fa-plus" aria-hidden="true"></i></button>
                            </div>
                          </div>
                          <div class="col-md-3 mt-0">
                            <lable class="form-lable">Agent</lable>
                            <div class="input-group mb-3">
                              <select id="RESV_AGENT" class="selectpicker RESV_AGENT" data-live-search="true">
                                  <option value="">Select</option>
                                </select>
                              <button type="button" onClick="companyAgentClick('AGENT')" class="btn flxi_btn btn-sm btn-primary"><i class="fa fa-plus" aria-hidden="true"></i></button>
                            </div>
                          </div>
                          <div class="col-md-3 mt-0">
                            <lable class="form-lable">Block</lable>
                            <select  id="RESV_BLOCK" data-width="100%" class="selectpicker RESV_BLOCK" data-live-search="true">
                                  <option value="">Select</option>
                                </select>
                          </div>
                          <div class="col-md-3 mt-0">
                            <lable class="form-lable">Member No</lable>
                            <input type="text" id="RESV_MEMBER_NO" class="form-control RESV_MEMBER_NO" placeholder="member no" />
                          </div>
                          <div class="col-md-3 mt-0">
                            <lable class="form-lable">CORP NO</lable>
                            <input type="text" name="RESV_CORP_NO" id="RESV_CORP_NO" class="form-control" placeholder="CORP no" />
                          </div>
                          <div class="col-md-3 mt-0">
                            <lable class="form-lable">IATA NO</lable>
                            <input type="text" name="RESV_IATA_NO" id="RESV_IATA_NO" class="form-control" placeholder="IATA no" />
                          </div>
                          <div class="col-md-3 flxi_ds_flx">
                            <div class="form-check mt-3 me-1">
                              <input class="form-check-input flxCheckBox" type="checkbox"  id="RESV_CLOSED_CHK">
                              <lable class="form-check-lable" for="defaultCheck1"> Closed </lable>
                            </div>
                            <div class="form-check mt-3 me-1">
                              <input class="form-check-input flxCheckBox" type="checkbox" value="N" id="RESV_DAY_USE_CHK">
                              <!-- <input type="hidden" name="RESV_DAY_USE" id="RESV_DAY_USE" value="N" class="form-control" /> -->
                              <lable class="form-check-lable" for="defaultCheck1"> Day Use </lable>
                            </div>
                            <div class="form-check mt-3">
                              <input class="form-check-input flxCheckBox" type="checkbox" value="N" id="RESV_PSEUDO_CHK">
                              <!-- <input type="hidden" name="RESV_PSEUDO" id="RESV_PSEUDO" value="N" class="form-control" /> -->
                              <lable class="form-check-lable" for="defaultCheck1"> Pseudo </lable>
                            </div>
                          </div>
                          <div class="col-md-3">
                            <lable class="form-lable">Rate Class</lable>
                            <select name="RESV_RATE_CLASS" id="RESV_RATE_CLASS" class="select2 form-select" data-allow-clear="true">
                              <option value="">Select</option>
                            </select>
                          </div>
                          <div class="col-md-3">
                            <lable class="form-lable">Rate Category</lable>
                            <select name="RESV_RATE_CATEGORY" id="RESV_RATE_CATEGORY" class="select2 form-select" data-allow-clear="true">
                              <option value="">Select</option>
                            </select>
                          </div>
                          <div class="col-md-3">
                            <lable class="form-lable">Rate Code</lable>
                            <select id="RESV_RATE_CODE" class="select2 form-select" data-allow-clear="true">
                              <option value="">Select</option>
                            </select>
                          </div>
                          <div class="col-md-3">
                            <lable class="form-lable">Room Class</lable>
                            <select name="RESV_ROOM_CLASS" id="RESV_ROOM_CLASS" class="select2 form-select" data-allow-clear="true">
                              <option value="">Select</option>
                            </select>
                          </div>
                          <div class="col-md-3">
                            <lable class="form-lable">Feature</lable>
                            <select id="RESV_FEATURE" class="select2 form-select" data-allow-clear="true">
                              <option value="">Select</option>
                            </select>
                          </div>
                          <div class="col-md-3">
                            <lable class="form-lable">Packages</lable>
                            <select id="RESV_PACKAGES" class="select2 form-select" data-allow-clear="true">
                              <option value="">Select</option>
                            </select>
                          </div>
                          <div class="col-md-3">
                            <lable class="form-lable">Purpose Of Stay</lable>
                            <select name="RESV_PURPOSE_STAY" id="RESV_PURPOSE_STAY" class="select2 form-select" data-allow-clear="true">
                              <option value="">Select</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="window-2">
                        <div class="row g-3">
                          <div class="col-md-3">
                            <lable class="form-lable">Guest Name</lable>
                              <div class="input-group mb-3">
                                <select name="RESV_NAME"  id="RESV_NAME" class="selectpicker RESV_NAME activeName" data-live-search="true" required>
                                  <option value="">Select</option>
                                </select>
                                <div class="invalid-feedback">
                                  Guest Name required can't empty.
                                </div>
                                <button type="button" onClick="childReservation('C')" class="btn flxi_btn btn-sm btn-primary"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                
                              </div>
                          </div>
                          <div class="col-md-3">
                            <lable class="form-lable">Title / First Name</lable>
                            <div class="input-group">
                              <select name="CUST_TITLE" id="CUST_TITLE" class="form-select" data-allow-clear="true" required>
                                  <option value="">Select</option>
                                  <option value="Mr">Mr.</option>
                                  <option value="Ms">Ms.</option>
                                  <option value="Shiekh.">Shiekh.</option>
                                  <option value="Shiekha.">Shiekha.</option>
                                  <option value="Dr.">Dr.</option>
                                  <option value="Ambassador.">Ambassador.</option>
                                  <option value="Prof.">Prof.</option>
                                </select>
                                <input type="text" name="CUST_FIRST_NAME" id="CUST_FIRST_NAME" class="form-control" placeholder="first name" />
                                <div class="invalid-feedback">
                                Title required can't empty.
                              </div>
                            </div>
                            
                          </div>
                          <div class="col-md-3">
                            <lable class="form-lable">Country</lable>
                            <select name="CUST_COUNTRY"  id="CUST_COUNTRY" data-width="100%" class="selectpicker CUST_COUNTRY" data-live-search="true">
                              <option value="">Select</option>
                            </select>
                          </div>
                          <div class="col-md-3">
                            <lable class="form-lable">VIP</lable>
                            <select name="CUST_VIP" id="CUST_VIP" class="select2 form-select" data-allow-clear="true">
                              <option value="">Select</option>
                            </select>
                          </div>
                          <div class="col-md-3 mt-0">
                            <lable class="form-lable">Phone</lable>
                            <input type="text" name="CUST_PHONE" id="CUST_PHONE" class="form-control" placeholder="phone" />
                          </div>
                          <div class="col-md-3 mt-0">
                            <lable class="form-lable">Member Type</lable>
                            <select name="RESV_MEMBER_TY" id="RESV_MEMBER_TY" class=" select2 form-select" data-allow-clear="true">
                              <option value="">Select</option>
                            </select>
                          </div>
                          <div class="col-md-3 mt-0">
                            <lable class="form-lable">Member No</lable>
                            <input type="text" name="RESV_MEMBER_NO" id="RESV_MEMBER_NO" class="form-control" placeholder="member no" />
                          </div>
                          <div class="col-md-3 mt-0">
                            <lable class="form-lable">Company</lable>
                            <div class="input-group mb-3">
                              <select name="RESV_COMPANY"  id="RESV_COMPANY" class="selectpicker RESV_COMPANY" data-live-search="true">
                                  <option value="">Select</option>
                                </select>
                              <button type="button" onClick="companyAgentClick('COMPANY')" class="btn flxi_btn btn-sm btn-primary"><i class="fa fa-plus" aria-hidden="true"></i></button>
                            </div>
                          </div>
                          <div class="col-md-3 mt-0">
                            <lable class="form-lable">Agent</lable>
                            <div class="input-group mb-3">
                              <select name="RESV_AGENT"  id="RESV_AGENT" class="selectpicker RESV_AGENT" data-live-search="true">
                                  <option value="">Select</option>
                                </select>
                              <button type="button" onClick="companyAgentClick('AGENT')" class="btn flxi_btn btn-sm btn-primary"><i class="fa fa-plus" aria-hidden="true"></i></button>
                            </div>
                          </div>
                          <div class="col-md-3 mt-0">
                            <lable class="form-lable">Block</lable>
                            <select name="RESV_BLOCK"  id="RESV_BLOCK" data-width="100%" class="selectpicker RESV_BLOCK" data-live-search="true">
                                  <option value="">Select</option>
                                </select>
                          </div>
                          <div class="col-md-3 mt-0">
                            <lable class="form-lable">Guest Balance</lable>
                            <input type="text" name="RESV_GUST_BAL" value="0.00" readonly id="RESV_GUST_BAL" class="form-control" placeholder="Guest Balance" />
                          </div>
                          <div class="col-md-3"></div>
                        </div>
                        <div class="row">
                          <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a href="#reservationDetail" class="nav-link active" data-bs-toggle="tab">Reservation Details</a>
                            </li>
                            <li class="nav-item">
                                <a href="#moreDetails" class="nav-link" data-bs-toggle="tab">More Fields</a>
                            </li>
                          </ul>
                          <div class="tab-content">
                            <div class="tab-pane fade show active" id="reservationDetail">
                              <div class="row">
                                <div class="col-md-6">
                                  <input type="hidden" name="RESV_FEATURE" id="RESV_FEATURE" class="form-control"/>
                                  <input type="hidden" name="RESV_ID" id="RESV_ID" class="form-control"/>
                                  <lable class="form-lable">Arrival/Departure Date</lable>
                                    <div class="input-group mb-3 flxy_fxcolm">
                                      <div class="flxy_join ">
                                        <div class="flxy_fixdate" required>
                                          <input type="text" id="RESV_ARRIVAL_DT" name="RESV_ARRIVAL_DT" class="form-control RESV_ARRIVAL_DT" placeholder="DD-MM-YYYY" required>
                                          <span class="input-group-append">
                                            <span class="input-group-text bg-light d-block">
                                              <i class="fa fa-calendar"></i>
                                            </span>
                                          </span>
                                          <div class="invalid-feedback flxy_date_vald">Arrival Date required can't empty.</div>
                                        </div>
                                        
                                      </div>
                                      <div class="flxy_join">
                                        <div class="flxy_fixdate">
                                          <input type="text" id="RESV_DEPARTURE" name="RESV_DEPARTURE" class="form-control RESV_DEPARTURE" placeholder="DD-MM-YYYY" required>
                                          <span class="input-group-append">
                                            <span class="input-group-text bg-light d-block">
                                              <i class="fa fa-calendar"></i>
                                            </span>
                                          </span>
                                        </div>
                                        <div class="invalid-feedback flxy_date_vald">Departure Date required can't empty.</div>
                                      </div>
                                    </div>
                                    
                                </div>
                                <div class="col-md-3">
                                  <lable class="form-lable">Night/No of Room</lable>
                                    <div class="input-group mb-3">
                                      <input type="number" name="RESV_NIGHT" id="RESV_NIGHT" class="form-control" placeholder="night" required/>
                                      <input type="number" name="RESV_NO_F_ROOM" id="RESV_NO_F_ROOM" class="form-control" placeholder="no of room" required/>
                                    </div>
                                  <div class="invalid-feedback">
                                    Night required can't empty.
                                  </div>
                                  <div class="invalid-feedback">
                                    No of room required can't empty.
                                  </div>
                                </div>
                                <div class="col-md-3">
                                  <lable class="form-lable">Adults/Children</lable>
                                    <div class="input-group mb-3 flxy_fxcolm">
                                      <div class="flxy_join">
                                        <input type="number" name="RESV_ADULTS" id="RESV_ADULTS" class="form-control" placeholder="adults" required/>
                                        <div class="invalid-feedback">Adults required can't empty.</div>
                                      </div>
                                      <div class="flxy_join">
                                        <input type="number" name="RESV_CHILDREN" id="RESV_CHILDREN" class="form-control" placeholder="children" required/>
                                        <div class="invalid-feedback"> Children required can't empty.</div>
                                      </div>
                                    </div>
                                    
                                </div>
                                <div class="col-md-3">
                                  <lable class="form-lable">Room Type</lable>
                                  <select name="RESV_RM_TYPE"  id="RESV_RM_TYPE" data-width="100%" class="selectpicker RESV_RM_TYPE" data-live-search="true">
                                      <option value="">Select</option>
                                  </select>
                                </div>
                                <div class="col-md-3">
                                  <lable class="form-lable">Room</lable>
                                  <select name="RESV_ROOM"  id="RESV_ROOM" data-width="100%" class="selectpicker RESV_ROOM" data-live-search="true">
                                      <option value="">Select</option>
                                  </select>
                                </div>
                                <div class="col-md-3">
                                  <lable class="form-lable">Rate Code</lable>
                                  <div class="input-group mb-3">
                                    <input type="text" readonly name="RESV_RATE_CODE" id="RESV_RATE_CODE" class="form-control" placeholder="rate" required />
                                    <button type="button" onClick="getRateQuery()" class="btn flxi_btn btn-sm btn-primary"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                  </div>
                                  <div class="invalid-feedback"> Rate Code required can't empty.</div>
                                </div>
                                <div class="col-md-3">
                                  <lable class="form-lable">Rate</lable>
                                  <input type="number" step="0.01"  name="RESV_RATE" id="RESV_RATE" class="form-control" placeholder="rate" required />
                                  <div class="invalid-feedback"> Rate required can't empty.</div>
                                </div>
                                <div class="col-md-3 mt-4">
                                    <lable class="form-check-lable"> Fixed Rate</lable>
                                    <label class="switch">
                                      <input type="checkbox" class="switch-input" id="RESV_FIXED_RATE_CHK" />
                                      <input type="hidden" name="RESV_FIXED_RATE" value="N" id="RESV_FIXED_RATE" class="form-control"/>
                                      <span class="switch-toggle-slider">
                                        <span class="switch-on">
                                          <i class="bx bx-check"></i>
                                        </span>
                                        <span class="switch-off">
                                          <i class="bx bx-x"></i>
                                        </span>
                                      </span>
                                    </label>
                                </div>
                                <div class="col-md-3">
                                  <lable class="form-lable">Package</lable>
                                  <select name="RESV_PACKAGES"  id="RESV_PACKAGES" data-width="100%" class="selectpicker RESV_PACKAGES" data-live-search="true">
                                      <option value="">Select</option>
                                  </select>
                                </div>
                                <div class="col-md-3">
                                  <lable class="form-lable">ETA - C/O Time</lable>
                                  <div class="flxi_flex">
                                    <input type="time" name="RESV_ETA" id="RESV_ETA" class="form-control" placeholder="estime Time" />
                                    <input type="time" name="RESV_CO_TIME" id="RESV_CO_TIME" class="form-control" placeholder="co time" />
                                  </div>
                                </div>
                                
                                <div class="col-md-3">
                                  <lable class="form-lable">RTC</lable>
                                  <select name="RESV_RTC"  id="RESV_RTC" data-width="100%" class="selectpicker RESV_RTC" data-live-search="true">
                                      <option value="">Select</option>
                                  </select>
                                </div>
                                <div class="col-md-3 mt-2">
                                  <lable class="form-lable">Reseravation Type</lable>
                                  <select name="RESV_RESRV_TYPE"  id="RESV_RESRV_TYPE" class="select2 form-select" data-allow-clear="true">
                                      <option value="">Select</option>
                                  </select>
                                </div>
                                <div class="col-md-3 mt-2">
                                  <lable class="form-lable">Market</lable>
                                  <select name="RESV_MARKET" id="RESV_MARKET" class="select2 form-select" data-allow-clear="true">
                                    <option value="">Select</option>
                                  </select>
                                </div>
                                <div class="col-md-3 mt-2">
                                  <lable class="form-lable">Source</lable>
                                  <select name="RESV_SOURCE" id="RESV_SOURCE" class="select2 form-select" data-allow-clear="true">
                                    <option value="">Select</option>
                                  </select>
                                </div>
                                <div class="col-md-3 mt-2">
                                  <lable class="form-lable">Origin</lable>
                                  <select name="RESV_ORIGIN" id="RESV_ORIGIN" class="select2 form-select" data-allow-clear="true">
                                    <option value="">Select</option>
                                  </select>
                                </div>
                                <div class="col-md-3 mt-2">
                                  <lable class="form-lable">Payment</lable>
                                  <select name="RESV_PAYMENT_TYPE" id="RESV_PAYMENT_TYPE" class="select2 form-select" data-allow-clear="true">
                                    <!-- <option value="">Select</option> -->
                                  </select>
                                  <div class="invalid-feedback"> Payment required can't empty.</div>
                                </div>
                                <div class="col-md-3 mt-2">
                                  <lable class="form-lable">Specials</lable>
                                  <select name="RESV_SPECIALS" id="RESV_SPECIALS" class="select2 form-select" data-allow-clear="true">
                                    <option value="">Select</option>
                                  </select>
                                </div>
                                <div class="col-md-3 mt-2">
                                  <lable class="form-lable">Comments</lable>
                                  <textarea class="form-control" name="RESV_COMMENTS" id="RESV_COMMENTS" rows="1"></textarea>
                                </div>
                                <div class="col-md-3 mt-2">
                                  <lable class="form-lable">Item Inventory</lable>
                                  <select name="RESV_ITEM_INVT" id="RESV_ITEM_INVT" class="select2 form-select" data-allow-clear="true">
                                    <option value="">Select</option>
                                  </select>
                                </div>
                                <div class="col-md-3">
                                  <lable class="form-lable">Booker Last / First</lable>
                                  <div class="flxi_flex">
                                    <input type="text" name="RESV_BOKR_LAST" id="RESV_BOKR_LAST" class="form-control" placeholder="booker last" />
                                    <input type="text" name="RESV_BOKR_FIRST" id="RESV_BOKR_FIRST" class="form-control" placeholder="booker first" />
                                  </div>
                                </div>
                                <div class="col-md-3">
                                  <lable class="form-lable">Booker Email/Phone</lable>
                                  <div class="flxi_flex">
                                    <input type="text" name="RESV_BOKR_EMAIL" id="RESV_BOKR_EMAIL" class="form-control" placeholder="email" />
                                    <input type="text" name="RESV_BOKR_PHONE" id="RESV_BOKR_PHONE" class="form-control" placeholder="phone" />
                                  </div>
                                </div>
                                <div class="col-md-3 mt-4">
                                  <lable class="form-check-lable" for="defaultCheck1"> Confimation</lable>
                                  <label class="switch">
                                    <input type="checkbox" class="switch-input" id="RESV_CONFIRM_YN_CHK" />
                                    <input type="hidden" name="RESV_CONFIRM_YN" value="N" id="RESV_CONFIRM_YN" class="form-control"/>
                                    <span class="switch-toggle-slider">
                                      <span class="switch-on">
                                        <i class="bx bx-check"></i>
                                      </span>
                                      <span class="switch-off">
                                        <i class="bx bx-x"></i>
                                      </span>
                                    </span>
                                  </label>
                                </div> 
                              </div>
                            </div>
                            <div class="tab-pane fade" id="moreDetails">
                              <div class="row">
                                <div class="col-md-3">
                                  <lable class="form-lable">C/O Time</lable>
                                  <input type="time" name="RESV_C_O_TIME" id="RESV_C_O_TIME" class="form-control" placeholder="c/o time" />
                                </div>
                                <div class="col-md-3">
                                  <lable class="form-lable">Tax Type</lable>
                                  <select name="RESV_TAX_TYPE" id="RESV_TAX_TYPE" class="select2 form-select" data-allow-clear="true">
                                    <option value="">Select</option>
                                  </select>
                                </div>
                                <div class="col-md-3 ">
                                  <lable class="form-lable">Exempt No</lable>
                                  <input type="text" name="RESV_EXEMPT_NO" id="RESV_EXEMPT_NO" class="form-control" placeholder="exempt no" />
                                </div>
                                <div class="col-md-3"></div>
                                <div class="col-md-3 mt-4">
                                  <lable class="form-lable">Pickup Requested ?</lable>
                                  <label class="switch">
                                  <input type="checkbox" class="switch-input" id="RESV_PICKUP_YN_CHK" />
                                  <input type="hidden" name="RESV_PICKUP_YN" value="N" id="RESV_PICKUP_YN" class="form-control"/>
                                  <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                      <i class="bx bx-check"></i>
                                    </span>
                                    <span class="switch-off">
                                      <i class="bx bx-x"></i>
                                    </span>
                                  </span>
                                </label>
                                </div>
                                <div class="col-md-3 ">
                                  <lable class="form-lable">Transport Type</lable>
                                  <select name="RESV_TRANSPORT_TYP" id="RESV_TRANSPORT_TYP" class="select2 form-select" data-allow-clear="true">
                                    <option value="">Select</option>
                                  </select>
                                </div>
                                <div class="col-md-3 ">
                                  <lable class="form-lable">Station Code</lable>
                                  <input type="text" name="RESV_STATION_CD" id="RESV_STATION_CD" class="form-control" placeholder="station code" />
                                </div>
                                <div class="col-md-3 ">
                                  <lable class="form-lable">Carrier Code</lable>
                                  <input type="text" name="RESV_CARRIER_CD" id="RESV_CARRIER_CD" class="form-control" placeholder="carrier code" />
                                </div>
                                <div class="col-md-3 ">
                                  <lable class="form-lable">Transport No</lable>
                                  <input type="text" name="RESV_TRANSPORT_NO" id="RESV_TRANSPORT_NO" class="form-control" placeholder="tranport no" />
                                </div>
                                <div class="col-md-3 ">
                                  <lable class="form-lable">Arrival Date</lable>
                                    <div class="input-group ">
                                      <input type="text" id="RESV_ARRIVAL_DT_PK" name="RESV_ARRIVAL_DT_PK" class="form-control" placeholder="DD-MM-YYYY">
                                      <span class="input-group-append">
                                        <span class="input-group-text bg-light d-block">
                                          <i class="fa fa-calendar"></i>
                                        </span>
                                      </span>
                                    </div>
                                </div>
                                <div class="col-md-3 ">
                                  <lable class="form-lable">Pick up Time</lable>
                                  <input type="time" name="RESV_PICKUP_TIME" id="RESV_PICKUP_TIME" class="form-control" placeholder="pickup time" />
                                </div>
                                <div class="col-md-3"></div>
                                <div class="col-md-3 mt-4">
                                  <lable class="form-lable">Drop off Requested ?</lable>
                                  <label class="switch">
                                  <input type="checkbox" class="switch-input" id="RESV_DROPOFF_YN_CHK"/>
                                  <input type="hidden" name="RESV_DROPOFF_YN" value="N" id="RESV_DROPOFF_YN" class="form-control"/>
                                  <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                      <i class="bx bx-check"></i>
                                    </span>
                                    <span class="switch-off">
                                      <i class="bx bx-x"></i>
                                    </span>
                                  </span>
                                </label>
                                </div>
                                <div class="col-md-3 ">
                                  <lable class="form-lable">Transport Type</lable>
                                  <select name="RESV_TRANSPORT_TYP_DO" id="RESV_TRANSPORT_TYP_DO" class="select2 form-select" data-allow-clear="true">
                                    <option value="">Select</option>
                                  </select>
                                </div>
                                <div class="col-md-3 ">
                                  <lable class="form-lable">Station Code</lable>
                                  <input type="text" name="RESV_STATION_CD_DO" id="RESV_STATION_CD_DO" class="form-control" placeholder="station code" />
                                </div>
                                <div class="col-md-3 ">
                                  <lable class="form-lable">Carrier Code</lable>
                                  <input type="text" name="RESV_CARRIER_CD_DO" id="RESV_CARRIER_CD_DO" class="form-control" placeholder="carrier code" />
                                </div>
                                <div class="col-md-3 ">
                                  <lable class="form-lable">Transport No</lable>
                                  <input type="text" name="RESV_TRANSPORT_NO_DO" id="RESV_TRANSPORT_NO_DO" class="form-control" placeholder="transport no" />
                                </div>
                                <div class="col-md-3 ">
                                  <lable class="form-lable">Arrival Date</lable>
                                  <div class="input-group">
                                    <input type="text" id="RESV_ARRIVAL_DT_DO" name="RESV_ARRIVAL_DT_DO" class="form-control" placeholder="DD-MM-YYYY">
                                      <span class="input-group-append">
                                        <span class="input-group-text bg-light d-block">
                                          <i class="fa fa-calendar"></i>
                                        </span>
                                      </span>
                                  </div>
                                </div>
                                <div class="col-md-3 ">
                                  <lable class="form-lable">Drop off Time</lable>
                                  <input type="time" name="RESV_DROPOFF_TIME" id="RESV_DROPOFF_TIME" class="form-control" placeholder="drop off time" />
                                </div>
                                <div class="col-md-3"></div>
                                <div class="col-md-3 ">
                                  <lable class="form-lable">Guest Type</lable>
                                  <select name="RESV_GUST_TY" id="RESV_GUST_TY" class="select2 form-select" data-allow-clear="true">
                                    <option value="">Select</option>
                                  </select>
                                </div>
                                <div class="col-md-3 ">
                                  <lable class="form-lable">Purpose of Stay</lable>
                                  <select name="RESV_EXT_PURP_STAY" id="RESV_EXT_PURP_STAY" class="select2 form-select" data-allow-clear="true">
                                    <option value="">Select</option>
                                  </select>
                                </div>
                                <div class="col-md-3 ">
                                  <lable class="form-lable">Entry Point</lable>
                                  <select name="RESV_ENTRY_PONT" id="RESV_ENTRY_PONT" class="select2 form-select" data-allow-clear="true">
                                    <option value="">Select</option>
                                  </select>
                                </div>
                                <div class="col-md-3">
                                  <lable class="form-lable">Reserv. Profile</lable>
                                  <select name="RESV_PROFILE" id="RESV_PROFILE" class="select2 form-select" data-allow-clear="true">
                                    <option value="">Select</option>
                                  </select>
                                </div>
                                <div class="col-md-3 mt-3">
                                  <lable class="form-lable">Name on Card</lable>
                                  <input type="text" name="RESV_NAME_ON_CARD" id="RESV_NAME_ON_CARD" class="form-control" placeholder="name on code" />
                                </div>
                                <div class="col-md-3 mt-5">
                                  <lable class="form-lable">Print Rate</lable>
                                  <label class="switch">
                                  <input type="checkbox" class="switch-input" id="RESV_EXT_PRINT_RT_CHK" />
                                  <input type="hidden" name="RESV_EXT_PRINT_RT" value="N" id="RESV_EXT_PRINT_RT" class="form-control" />
                                  <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                      <i class="bx bx-check"></i>
                                    </span>
                                    <span class="switch-off">
                                      <i class="bx bx-x"></i>
                                    </span>
                                  </span>
                                </label>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="flxyFooter flxy_space">
                        <button type="button" id="previousbtn" onClick="previous()" class="btn btn-primary"><i class="fa-solid fa-angle-left"></i> Previous</button>
                        <button type="button" id="submitResrBtn" onClick="submitForm('reservationForm','R',event)" class="btn btn-primary submitResr">Save</button>
                        <!--  -->
                        <button type="button" id="nextbtn" onClick="next()" class="btn btn-primary"> Next <i class="fa-solid fa-angle-right"></i></button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal fade" id="reservationChild" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog modal-xl">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="reservationChildlable">Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                  </div>
                  <div class="modal-body">
                      <div class="row profileSearch">
                        <div class="col-md-3 mb-2">
                          <lable class="form-lable">Name</lable>
                          <input type="text" id="CUST_LAST_NAME" class="form-control" placeholder="Name" />
                        </div>
                        <div class="col-md-3 mb-2">
                          <lable class="form-lable">First Name</lable>
                          <input type="text" id="CUST_FIRST_NAME" class="form-control" placeholder="First name" />
                        </div>
                        <div class="col-md-3 mb-2">
                          <lable class="form-lable">City</lable>
                          <input type="text" id="CUST_CITY" class="form-control" placeholder="City" />
                        </div>
                        <div class="col-md-3 mb-2">
                          <lable class="form-lable">Email ID</lable>
                          <input type="text" name="CUST_EMAIL" id="CUST_EMAIL" class="form-control" placeholder="Email"/>
                        </div>
                        <div class="col-md-3 mb-2">
                          <lable class="form-lable">Client ID</lable>
                          <input type="text" id="CUST_CLIENT_ID" class="form-control" placeholder="Client ID" />
                        </div>
                        <div class="col-md-3 mb-2">
                          <lable class="form-lable">IATA No</lable>
                          <input type="text" id="CUST_IATA_NO" class="form-control" placeholder="IATA No" />
                        </div>
                        <div class="col-md-3 mb-2">
                          <lable class="form-lable">Corp No</lable>
                          <input type="text" id="CUST_CORP_NO" class="form-control" placeholder="Corp No" />
                        </div>
                        <div class="col-md-3 mb-2">
                          <lable class="form-lable">A/R No</lable>
                          <input type="text" id="CUST_AR_NO" class="form-control" placeholder="A/R No" />
                        </div>
                        <div class="col-md-3 mb-2">
                          <lable class="form-lable">Mobile</lable>
                          <input type="text" id="CUST_MOBILE" class="form-control" placeholder="Mobile" />
                        </div>
                        <div class="col-md-3 mb-2">
                          <lable class="form-lable">Communication</lable>
                          <input type="text" id="CUST_COMMUNICATION_DESC" class="form-control" placeholder="Communication" />
                        </div>
                        <div class="col-md-2 mb-2">
                          <lable class="form-lable">Passport No</lable>
                          <input type="text" id="CUST_PASSPORT" class="form-control" placeholder="Passport No" />
                        </div>
                        <div class="col-md-4 mt-4">
                          <button type="button" onClick="searchData('profileSearch','S',event)" class="btn btn-info">Search</button>
                          <button type="button" onClick="searchData('profileSearch','C',event)" class="btn btn-warning">Clear</button>
                          <button type="button" onClick="searchData('profileSearch','N',event)" class="btn btn-primary">New</button>
                          <button type="button" onClick="searchData('profileSearch','PR',event)" class="btn btn-success">Ok</button>
                        </div>
                    </div>
                    <div class="row profileSearch mt-4">
                      <div class="flxy_table_resp">
                        <table class="table table-striped table-bordered">
                            <thead>
                              <tr>
                                <th scope="col"style="width:50px">Sr.No</th>
                                <th scope="col"style="width:250px">First Name</th>
                                <th scope="col"style="width:250px">Last Name</th>
                                <th scope="col"style="width:150px">DOB</th>
                                <th scope="col"style="width:250px">Passport</th>
                                <th scope="col"style="width:150px">Address</th>
                                <th scope="col"style="width:250px">City</th>
                                <th scope="col"style="width:250px">Email</th>
                                <th scope="col"style="width:250px">Mobile</th>
                                <th scope="col"style="width:250px">Nationality</th>
                                <th scope="col"style="width:150px">VIP</th>
                              </tr>
                            </thead>
                            <tbody id="searchRecord">
                              <tr><td class="text-center" colspan="11">No Record Found</td></tr>
                            </tbody>
                        </table>
                      </div>
                    </div>
                    <form id="customerForm" class="profileCreate">
                      <div class="row g-3">
                        <div class="col-md-3">
                        <input type="hidden" name="CUST_ID" id="CUST_ID" class="form-control"/>
                          <lable class="form-lable">First Name</lable>
                          <input type="text" name="CUST_FIRST_NAME" id="CUST_FIRST_NAME" class="form-control" placeholder="first name" required/>
                          <div class="invalid-feedback">
                          First name is required can't empty.
                          </div>
                        </div>
                        <div class="col-md-3">
                          <lable class="form-lable">Middle Name</lable>
                          <input type="text" name="CUST_MIDDLE_NAME"  id="CUST_MIDDLE_NAME" class="form-control" placeholder="middle name" />
                        </div>
                        <div class="col-md-3">
                          <lable class="form-lable">Last Name</lable>
                          <input type="text" name="CUST_LAST_NAME" id="CUST_LAST_NAME" class="form-control" placeholder="last name" />
                        </div>
                        <div class="col-md-3">
                          <lable class="form-lable">Language/Title</lable>
                            <div class="form-group flxi_join">
                              <select name="CUST_LANG" id="CUST_LANG" class="form-select" data-allow-clear="true">
                                <option value="">Select</option>
                                <option value="EN">English</option>
                                <option value="AR">Arabic</option>
                                <option value="FR">French</option>
                              </select>
                              <select name="CUST_TITLE" id="CUST_TITLE" class="form-select" data-allow-clear="true">
                                <option value="">Select</option>
                                <option value="Mr">Mr.</option>
                                <option value="Ms">Ms.</option>
                                <option value="Shiekh.">Shiekh.</option>
                                <option value="Shiekha.">Shiekha.</option>
                                <option value="Dr.">Dr.</option>
                                <option value="Ambassador.">Ambassador.</option>
                                <option value="Prof.">Prof.</option>
                              </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                          <lable class="form-lable">DOB</lable>
                            <div class="input-group mb-3">
                              <input type="text" id="CUST_DOB" name="CUST_DOB" class="form-control CUST_DOB" placeholder="YYYY-MM-DD">
                                <span class="input-group-append">
                                  <span class="input-group-text bg-light d-block">
                                    <i class="fa fa-calendar"></i>
                                  </span>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-3">
                          <lable class="form-lable">Passport</lable>
                          <input type="text" name="CUST_PASSPORT"  id="CUST_PASSPORT" class="form-control" placeholder="passport" />
                        </div>
                        <div class="col-md-3">
                          <lable class="form-lable">Address</lable>
                          <input type="text" name="CUST_ADDRESS_1"  id="CUST_ADDRESS_1" class="form-control" placeholder="addresss 1" required />
                          <div class="invalid-feedback">
                          address is required can't empty.
                          </div>
                        </div> 
                        <div class="col-md-3 flxy_mgtop">
                          <lable class="form-lable"></lable>
                          <input type="text" name="CUST_ADDRESS_2"  id="CUST_ADDRESS_2" class="form-control" placeholder="address 2" />
                        </div> 
                        <div class="col-md-3" style="margin-top: 23px !important;">
                          <lable class="form-lable"></lable>
                          <input type="text" name="CUST_ADDRESS_3"  id="CUST_ADDRESS_3" class="form-control" placeholder="address 3" />
                        </div> 
                        <div class="col-md-3 mt-0">
                          <lable class="form-lable col-md-12">Country</lable>
                          <select name="CUST_COUNTRY"  id="CUST_COUNTRY" data-width="100%" class="selectpicker CUST_COUNTRY" data-live-search="true" required>
                            <option value="">Select</option>
                          </select>
                          <div class="invalid-feedback">
                          country is required can't empty.
                          </div>
                        </div> 
                        <div class="col-md-3 mt-0">
                          <lable class="form-lable col-md-12">State</lable>
                          <select name="CUST_STATE"  id="CUST_STATE" data-width="100%" class="selectpicker CUST_STATE" data-live-search="true">
                            <option value="">Select</option>
                          </select>
                        </div> 
                        <div class="col-md-3 mt-0">
                          <lable class="form-lable col-md-12">City</lable>
                          <select name="CUST_CITY"  id="CUST_CITY" data-width="100%" class="selectpicker CUST_CITY" data-live-search="true">
                            <option value="">Select</option>
                          </select>
                        </div> 
                        <div class="col-md-3">
                          <lable class="form-lable">Email</lable>
                          <input type="text" name="CUST_EMAIL"  id="CUST_EMAIL" class="form-control" placeholder="email" required />
                          <div class="invalid-feedback">
                            Email is required can't empty.
                          </div>
                        </div>
                        <div class="col-md-3">
                          <lable class="form-lable">Mobile</lable>
                          <input type="text" name="CUST_MOBILE"  id="CUST_MOBILE" class="form-control" placeholder="mobile" required/>
                          <div class="invalid-feedback">
                            Mobile No is required can't empty.
                          </div>
                        </div>
                        <div class="col-md-3">
                          <lable class="form-lable">Phone</lable>
                          <input type="text" name="CUST_PHONE"  id="CUST_PHONE" class="form-control" placeholder="phone" />
                        </div>
                        <div class="col-md-3">
                          <lable class="form-lable">Client ID</lable>
                          <input type="text" name="CUST_CLIENT_ID"  id="CUST_CLIENT_ID" class="form-control" placeholder="client id" />
                        </div>
                        <div class="col-md-3">
                          <lable class="form-lable">Postal Code</lable>
                          <input type="text" name="CUST_POSTAL_CODE"  id="CUST_POSTAL_CODE" class="form-control" placeholder="postal" />
                        </div> 
                        <div class="col-md-3">
                          <lable class="form-lable">VIP</lable>
                          <select name="CUST_VIP"  id="CUST_VIP" class="select2 form-select" data-allow-clear="true">
                            <option value="">Select VIP</option>
                          </select>
                        </div> 
                        <div class="col-md-3">
                          <lable class="form-lable">Nationality</lable>
                          <select name="CUST_NATIONALITY"  id="CUST_NATIONALITY" class="select2 form-select" data-allow-clear="true">
                            <option value="">Select</option>
                          </select>
                        </div> 
                        <div class="col-md-3">
                          <lable class="form-lable">Business Segment</lable>
                          <select name="CUST_BUS_SEGMENT"  id="CUST_BUS_SEGMENT" class="select2 form-select" data-allow-clear="true">
                            <option value="">Select</option>
                          </select>
                        </div>
                        <div class="col-md-3">
                          <lable class="form-lable">Communication</lable>
                          <select name="CUST_COMMUNICATION"  id="CUST_COMMUNICATION" class="select2 form-select" data-allow-clear="true">
                            <option value="">Select Communication</option>
                            <option value="WEB">Web</option>
                            <option value="WHATSAPP">Whatsapp</option>
                            <option value="FAX">Fax</option>
                            <option value="OTHER">Other</option>
                          </select>
                        </div>
                        <div class="col-md-3">
                          <lable class="form-lable">Communcation Desc.</lable>
                          <input type="text" name="CUST_COMMUNICATION_DESC"  id="CUST_COMMUNICATION_DESC" class="form-control" placeholder="communication desc" />
                        </div> 
                        <div class="col-md-3">
                            <div class="form-check mt-3">
                              <input class="form-check-input flxCheckBox" type="checkbox"  id="CUST_ACTIVE_CHK">
                              <input type="hidden" name="CUST_ACTIVE" id="CUST_ACTIVE" value="N" class="form-control" />
                              <lable class="form-check-lable" for="defaultCheck1"> Active </lable>
                            </div>
                            
                        </div> 
                        
                      </div>
                    </form>
                  </div>
                  <div class="modal-footer profileCreate">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" onClick="submitForm('customerForm','C',event)" class="btn btn-primary">Save</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- /Modal window -->
            
            <!-- Rate Query Modal window -->
            <div class="modal fade" id="rateQueryWindow" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog modal-xl">
                <div class="modal-content">
                  <div class="modal-header flxy_padding">
                    <h5 class="modal-title" id="rateQueryWindowLable">Rate Query</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                  </div>
                  <div id="userInfoDate"></div>
                  <div class="modal-body flxy_padding">
                    <form id="rateQueryForm">
                      <div class="row g-3">
                        <div class="col-md-10 flxy_horiz_scroll">
                          <table class="table table-bordered" style="table-layout:fixed;">
                            <tbody id="rateQueryTable">
                            </tbody>
                          </table>
                        </div>
                        <div class="col-md-2 flxy_border_over">
                          <button type="button" onClick="detailOption('OB')" class="btn btn-secondary d-grid gap-2  mx-auto"><span class="btnName">Overbooking Detail</span></button>
                          <!-- <button type="button" onClick="submitForm('customerForm','C',event)" class="btn btn-primary">Save</button> -->
                        </div>
                      </div>
                    </form>
                  </div>
                  <div class="modal-footer flxy_paddng">
                    <div class="flxy_opertion flxy_opt1">
                      <div class="flxy_radio">
                        <div class="col-md-3 flxy_equal">
                          <lable class="form-check-lable">Averate Rate</lable>
                          <label class="switch">
                            <input type="checkbox" class="switch-input rateRadio" mode="AVG" />
                            <span class="switch-toggle-slider">
                              <span class="switch-on">
                                <i class="bx bx-check"></i>
                              </span>
                              <span class="switch-off">
                                <i class="bx bx-x"></i>
                              </span>
                            </span>
                          </label>
                        </div>
                        <div class="col-md-3 flxy_equal1">
                          <lable class="form-check-lable">Total Rate</lable>
                          <label class="switch">
                            <input type="checkbox" class="switch-input rateRadio" mode="TOT" />
                            <span class="switch-toggle-slider">
                              <span class="switch-on">
                                <i class="bx bx-check"></i>
                              </span>
                              <span class="switch-off">
                                <i class="bx bx-x"></i>
                              </span>
                            </span>
                          </label>
                        </div>
                        <div class="col-md-3 flxy_equal2">
                          <lable class="form-check-lable">First Night</lable>
                          <label class="switch">
                            <input type="checkbox" class="switch-input rateRadio" mode="FIS" />
                            <span class="switch-toggle-slider">
                              <span class="switch-on">
                                <i class="bx bx-check"></i>
                              </span>
                              <span class="switch-off">
                                <i class="bx bx-x"></i>
                              </span>
                            </span>
                          </label>
                        </div>
                      </div>
                    </div>
                    <div class="flxy_opertion flxy_right">
                      <button type="button" onClick="selectRate(event)" class="btn btn-primary">Ok</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Rate Query Modal window end -->

            <!-- Option window -->
            <div class="modal fade" id="optionWindow" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog modal-md">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="rateQueryWindowLable">Reservation Option</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                  </div>
                  <div class="modal-body">
                    <div id="Accompany">
                      <div class="flxy_opt_btn text-center">
                        <button type="button" onClick="reservExtraOption('ACP')" class="btn btn-primary">Accompanying</button>
                        <button type="button" onClick="reservExtraOption('ADO')" class="btn btn-primary">Add On</button>
                        <button type="button" onClick="reservExtraOption('CHG')" class="btn btn-primary">Changes</button>
                      </div>
                    </div>
                    <div id="Addon">
                      <div id="flxy_add_content">
                          <p>Which of these reservation attributes do you want to copy?</p>
                          <div class="row">
                            <div class="col-md-6">
                              <div class="col-md-12">
                                <lable class="form-lable col-md-12">Room Type</lable>
                                <select name="COPY_RM_TYPE"  id="COPY_RM_TYPE" data-width="100%" class="selectpicker COPY_RM_TYPE" data-live-search="true">
                                  <option value="">Select</option>
                                </select>
                              </div> 
                              <div class="form-check mt-3 p-0">
                                <label class="switch">
                                  <input type="checkbox" class="switch-input copyReser" checked id="COPY_PAYMENT" method="PM" />
                                  <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                      <i class="bx bx-check"></i>
                                    </span>
                                    <span class="switch-off">
                                      <i class="bx bx-x"></i>
                                    </span>
                                  </span>
                                </label>
                                <lable class="form-check-lable flxy_lab_left"> Payment Method</lable>
                              </div>
                              <div class="form-check mt-3 p-0">
                                <label class="switch">
                                  <input type="checkbox" class="switch-input copyReser" checked id="COPY_SPECIALS" method="SP" />
                                  <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                      <i class="bx bx-check"></i>
                                    </span>
                                    <span class="switch-off">
                                      <i class="bx bx-x"></i>
                                    </span>
                                  </span>
                                </label>
                                <lable class="form-check-lable flxy_lab_left"> Specials</lable>
                              </div>
                              <div class="form-check mt-3 p-0">
                                <label class="switch">
                                  <input type="checkbox" class="switch-input copyReser" checked id="COPY_CUST_REF" method="CR"  />
                                  <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                      <i class="bx bx-check"></i>
                                    </span>
                                    <span class="switch-off">
                                      <i class="bx bx-x"></i>
                                    </span>
                                  </span>
                                </label>
                                <lable class="form-check-lable flxy_lab_left"> Custome Referance</lable>
                              </div>
                              <div class="form-check mt-3 p-0">
                                <label class="switch">
                                  <input type="checkbox" class="switch-input copyReser" checked id="COPTY_ROUTING" method="RU" />
                                  <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                      <i class="bx bx-check"></i>
                                    </span>
                                    <span class="switch-off">
                                      <i class="bx bx-x"></i>
                                    </span>
                                  </span>
                                </label>
                                <lable class="form-check-lable flxy_lab_left"> Window/Room Routing instr.</lable>
                              </div>
                              <div class="form-check mt-3 p-0">
                                <label class="switch">
                                  <input type="checkbox" class="switch-input copyReser" checked id="COPTY_ROUTING" method="CM"  />
                                  <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                      <i class="bx bx-check"></i>
                                    </span>
                                    <span class="switch-off">
                                      <i class="bx bx-x"></i>
                                    </span>
                                  </span>
                                </label>
                                <lable class="form-check-lable flxy_lab_left"> Comments</lable>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="col-md-6 mb-5"></div>
                              <div class="form-check mt-3 p-0">
                                <label class="switch">
                                  <input type="checkbox" class="switch-input copyReser" checked id="COPY_PACKAGE" method="PK" />
                                  <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                      <i class="bx bx-check"></i>
                                    </span>
                                    <span class="switch-off">
                                      <i class="bx bx-x"></i>
                                    </span>
                                  </span>
                                </label>
                                <lable class="form-check-lable flxy_lab_left"> Packages</lable>
                              </div>
                              <div class="form-check mt-3 p-0">
                                <label class="switch">
                                  <input type="checkbox" class="switch-input copyReser" checked id="COPY_ITEM_INV" method="IN" />
                                  <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                      <i class="bx bx-check"></i>
                                    </span>
                                    <span class="switch-off">
                                      <i class="bx bx-x"></i>
                                    </span>
                                  </span>
                                </label>
                                <lable class="form-check-lable flxy_lab_left"> Item Inventory</lable>
                              </div>
                              <div class="form-check mt-3 p-0">
                                <label class="switch">
                                  <input type="checkbox" class="switch-input copyReser" checked id="COPY_GUEST_NAME" method="GU"/>
                                  <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                      <i class="bx bx-check"></i>
                                    </span>
                                    <span class="switch-off">
                                      <i class="bx bx-x"></i>
                                    </span>
                                  </span>
                                </label>
                                <lable class="form-check-lable flxy_lab_left"> Guest Name</lable>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-12" style="text-align:right;">
                              <button type="button" onClick="copyReservation()" class="btn btn-primary">Save</button>
                              <button type="button" data-bs-dismiss="modal" class="btn btn-secondary">Close</button>
                            </div>
                          </div>
                      </div>
                    </div>
                  </div>
                  <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div> -->
                </div>
              </div>
            </div>
            <!-- option window end -->

            <!-- Option window -->
            <div class="modal fade" id="appcompanyWindow" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog modal-md">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="rateQueryWindowLable">Accompanying Guest</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                  </div>
                  <div class="modal-body">
                    <div id="customeTrigger"></div>
                    <div class="row">
                      <table class="table table-striped">
                        <thead class="table-dark">
                          <tr>
                            <th>Name</th>
                            <th>City</th>
                            <th>DOB</th>
                          </tr>
                        </thead>
                        <tbody id="accompanyTd">
                          <tr><td class="text-center" colspan="3">No data</td></tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" onClick="accompanySet('A',event)" class="btn btn-primary">Attach</button>
                    <button type="button" onClick="accompanySet('D',event)" class="btn btn-warning">Detach</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- option window end -->

            <!-- RateQuery Detail window -->
            <div class="modal fade" id="reteQueryDetail" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog modal-md">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="rateQueryWindowLable">Overbooking Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                  </div>
                  <div class="modal-body">
                    <div class="row flxy_height">
                      <table class="table table-striped">
                        <thead class="table-dark">
                          <tr>
                            <th>Name</th>
                            <th>Room Type</th>
                            <th>Overbooking</th>
                          </tr>
                        </thead>
                        <tbody id="reteQueryDetailTd">
                          <tr><td class="text-center" colspan="3">No data</td></tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" data-bs-dismiss="modal" class="btn btn-secondary">Close</button>
                  </div>
                </div>
              </div>
            </div>
            <!--  RateQuery Detail window end -->
            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
          <?=$this->include("Reservation/CompanyAgentModal")?>
<script>
  var compAgntMode='';
  var linkMode='';
  var windowmode='';
  $(document).ready(function() {
    linkMode='EX';
    $('#loader_flex_bg').show();
    $('#dataTable_view').DataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'<?php echo base_url('/reservationView')?>'
        },
        'columns': [
          { data: 'RESV_NO' },
          { data: 'FULLNAME' },
          { data: 'RESV_ARRIVAL_DT'},
          { data: 'RESV_DEPARTURE'},
          { data: 'RESV_NIGHT' },
          { data: 'RESV_NO_F_ROOM'},
          // { data: 'RESV_FEATURE'},
          { data: 'RESV_PURPOSE_STAY'},
          { data: null , render : function ( data, type, row, meta ) {
            return (
              '<div class="d-inline-block flxy_option_view">' +
                '<a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></a>' +
                '<ul class="dropdown-menu dropdown-menu-end">' +
                  '<li><a href="javascript:;" data_sysid="'+data['RESV_ID']+'" class="dropdown-item editReserWindow"><i class="fas fa-edit"></i> Edit</a></li>' +
                  '<li><a href="javascript:;" data_sysid="'+data['RESV_ID']+'" rmtype="'+data['RESV_RM_TYPE']+'" rmtypedesc="'+data['RM_TY_DESC']+'"  class="dropdown-item reserOption"><i class="fa-solid fa-align-justify"></i> Options</a></li>' +
                  // '<div class="dropdown-divider"></div>' +
                  '<li><a href="javascript:;" data_sysid="'+data['RESV_ID']+'" class="dropdown-item text-danger delete-record"><i class="fas fa-trash"></i> Delete</a></li>' +
                '</ul>' +
              '</div>'
            );
          }},
        ],
        'autowidth':true,
        'order': [[ 0, "desc" ]],
        "fnInitComplete": function (oSettings, json) {
          $('#loader_flex_bg').hide();
        }
    });
    $("#dataTable_view_wrapper .row:first").before('<div class="row flxi_pad_view"><div class="col-md-3 ps-0"><button type="button" class="btn btn-primary" onClick="addResvation()"><i class="fa-solid fa-plus fa-lg"></i> Add</button></div></div>');
   
    $('.RESV_ARRIVAL_DT').datepicker({
        format: 'd-M-yyyy',
        autoclose: true,
    });
    $('.RESV_DEPARTURE').datepicker({
        format: 'd-M-yyyy',
        autoclose: true,
    });
    $('.CUST_DOB').datepicker({
        format: 'd-M-yyyy',
        autoclose: true
    });

    $('#RESV_ARRIVAL_DT_PK').datepicker({
        format: 'd-M-yyyy',
        autoclose: true,
    });
    $('#RESV_ARRIVAL_DT_DO').datepicker({
        format: 'd-M-yyyy',
        autoclose: true,
    });
    
  });

  function generateRateQuery(mode='AVG'){
    var formData={};
    $('.window-1').find('.input-group :input').each(function(i,data){
      var field = $(data).attr('id');
      var values = $(this).val();
      formData[field]=values; 
      formData['mode']=mode;
    });
    $.ajax({
        url: '<?php echo base_url('/getRateQueryData')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:formData,
        dataType:'json',
        success:function(respn){
          $('#rateQueryTable').html(respn[0]);
          checkArrivalDate();
        }
      });
  }

  function avaiableDatePeriod(){
    var arrival = $('#RESV_ARRIVAL_DT').val();
    var departure = $('#RESV_DEPARTURE').val();
    var night = $('#RESV_NIGHT').val();
    var nofroom = $('#RESV_NO_F_ROOM').val();
    var adult = $('#RESV_ADULTS').val();
    var children = $('#RESV_CHILDREN').val();
    var ulli = '<li>'+moment(arrival,'DD-MMM-YYYY').format('dddd')+' ,</li>';
    ulli+='<li>&nbsp;'+moment(arrival,'DD-MMM-YYYY').format('MMMM D YYYY')+' ,</li>';
    ulli+='<li>&nbsp;'+night+' Night ,</li>';
    ulli+='<li>&nbsp;'+nofroom+' Rooms ,</li>';
    ulli+='<li>&nbsp;'+adult+' Adults </li>';
    ulli+=(children!=0 ? '<li>,&nbsp;'+children+' Children</li>' : '');
    return '<ul class="flxy_row">'+ulli+'</ul>';
  }

  function next(){
    var fetchInfo = avaiableDatePeriod();
    $('#userInfoDate').html(fetchInfo);
    generateRateQuery();
    $('.rateRadio').prop('checked',false);
    $('.rateRadio:first').prop('checked',true);
    $('#rateQueryWindow').modal('show');
  }

  function getRateQuery(){
    var fetchInfo = avaiableDatePeriod();
    $('#userInfoDate').html(fetchInfo);
    generateRateQuery();
    $('.rateRadio').prop('checked',false);
    $('.rateRadio:first').prop('checked',true);
    $('#rateQueryWindow').modal('show');
  }

  $(document).on('click','.clickPrice',function(){
      $('#rateQueryTable .active').removeClass('active');
      $(this).addClass('active');
      var value = $(this).find('input').val();
      console.log(value);
  });

  function selectRate(){
    $('#rateQueryWindow').modal('hide');
    $('.window-1,#nextbtn').hide();
    $('.window-2').show();
    $('#submitResrBtn').removeClass('submitResr');
    runInitializeConfig();
    var activeRow = $('.clickPrice.active');
    var rmtype = $(activeRow).find('#ROOMTYPE').val();
    var rmprice = $(activeRow).find('#ACTUAL_ADULT_PRICE').val();
    var rateCode = $(activeRow).parent('.ratePrice').find('#RT_DESCRIPTION').val();
    $('[name="RESV_RATE_CODE"]').val(rateCode);
    $('#RESV_RATE').val(rmprice);
    localStorage.setItem('activerate', rmprice);
    $.ajax({
        url: '<?php echo base_url('/getRoomTypeDetails')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{rmtype:rmtype},
        dataType:'json',
        success:function(respn){
          var dataSet = respn[0];
          var option= '<option data-feture="'+$.trim(dataSet['RM_TY_FEATURE'])+'" data-desc="'+$.trim(dataSet['RM_TY_DESC'])+'" data-rmclass="'+$.trim(dataSet['RM_TY_ROOM_CLASS'])+'" value="'+dataSet['RM_TY_CODE']+'">'+dataSet['RM_TY_DESC']+'</option>';
          $('#RESV_RM_TYPE,#RESV_RTC').html(option).selectpicker('refresh');
        }
      });
  }

  function previous(){
    $('.window-1,#nextbtn').show();
    $('.window-2').hide();
    $('#submitResrBtn').addClass('submitResr');
  }

  $(document).on('keyup','.RESV_NIGHT,.RESV_NO_F_ROOM,.RESV_ADULTS,.RESV_CHILDREN,.RESV_MEMBER_NO',function(){
      var value = $(this).val();
      var name = $(this).attr('id');
      $('[name="'+name+'"]').val(value);
  });

  $(document).on('change','.RESV_ARRIVAL_DT,.RESV_DEPARTURE',function(){
      var value = $(this).val();
      var name = $(this).attr('id');
      $('[name="'+name+'"]').val(value);
  });

  $(document).on('blur','#RESV_RATE',function(){
      $('#RESV_FIXED_RATE_CHK').prop('checked',true);
      $('#RESV_FIXED_RATE').val('Y');
  });
  var ressysId='';
  var roomType='';
  var roomTypedesc='';
  $(document).on('click','.reserOption',function(){
      ressysId=$(this).attr('data_sysid');
      roomType = $(this).attr('rmtype');
      roomTypedesc = $(this).attr('rmtypedesc');
      $('#Accompany').show();
      $('#Addon').hide();
      $('#optionWindow').modal('show');
      $('.profileSearch').find('input,select').val('');
      windowmode='AC';
      customPop='';
  });
  
  $(document).on('click','.editReserWindow,#triggCopyReserv',function(event,param,paramArr,rmtype){
    $(':input','#reservationForm').val('').prop('checked', false).prop('selected', false);
    $('#RESV_NAME,#RESV_COMPANY,#RESV_AGENT,#RESV_BLOCK').html('<option value="">Select</option>').selectpicker('refresh');
    runSupportingResevationLov();
    runInitializeConfig();
    $('.window-1,#nextbtn,#previousbtn').hide();
    $('.window-2').show();
    $('.flxyFooter').removeClass('flxy_space');
    $('#submitResrBtn').removeClass('submitResr');
    $('#reservationW').modal('show');
    var sysid = $(this).attr('data_sysid');
    var mode='';
    if(param){
      sysid = param;
      mode='CPY';
      $('#submitResrBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
    }else{
      $('#submitResrBtn').removeClass('btn-primary').addClass('btn-success').text('Update');
    }
    $.ajax({
        url: '<?php echo base_url('/editReservation')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{sysid:sysid,mode:mode,paramArr:paramArr},
        dataType:'json',
        success:function(respn){
          // console.log(respn,"testing");
          $(respn).each(function(inx,data){
            $.each(data,function(fields,datavals){
              var field = $.trim(fields);//fields.trim();
              var dataval = $.trim(datavals);//datavals.trim();
              if(field=='RESV_NAME_DESC' || field=='RESV_COMPANY_DESC' || field=='RESV_AGENT_DESC' || field=='RESV_BLOCK_DESC'){ return true; };
              if(field=='RESV_NAME' || field=='RESV_COMPANY' || field=='RESV_AGENT' || field=='RESV_BLOCK' || field=='CUST_COUNTRY' || field=='RESV_RM_TYPE' || field=='RESV_ROOM' || field=='RESV_RTC'){
                var option = '<option value="'+dataval+'">'+data[field+'_DESC']+'</option>';
                $('*#'+field).html(option).selectpicker('refresh');
              }else if(field=='RESV_CONFIRM_YN' || field=='RESV_PICKUP_YN' || field=='RESV_DROPOFF_YN' ||       field=='RESV_EXT_PRINT_RT' || field=='RESV_FIXED_RATE'){
                if(dataval=='Y'){
                  $('#'+field+'_CHK').prop('checked',true);
                }else{
                  $('#'+field+'_CHK').prop('checked',false)
                }
                $('#'+field).val(dataval);
              }else{
                $('*#'+field).val(dataval).trigger('change');
              }
            });
          });
          checkArrivalDate();
        }
    });
  });

  $(document).on('click','.delete-record',function(){
    var sysid = $(this).attr('data_sysid');
      bootbox.confirm({
        message: "Are you confirm to delete this record?",
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
        callback: function (result) {
            if(result){
              $.ajax({
                url: '<?php echo base_url('/deleteReservation')?>',
                type: "post",
                data: {sysid:sysid},
                headers: {'X-Requested-With': 'XMLHttpRequest'},
                dataType:'json',
                success:function(respn){
                  console.log(respn,"testing");
                  $('#dataTable_view').dataTable().fnDraw();
                }
              });
            }
        }
    });
  });

  $(document).on('change','.switch-input',function(){
    var thiss = $(this);
    var checkWhich = $(this).attr('id');
    if(thiss.is(':checked')){
      thiss.next().val('Y');
    }else{
      if(checkWhich=='RESV_FIXED_RATE_CHK'){
        var previousRate = localStorage.getItem('activerate');
        $('#RESV_RATE').val(previousRate);
      }
      thiss.next().val('N');
    }
  });

  function childReservation(param){
    if(param=='C'){
      $('#customerForm').find('input,select').val('');
      windowmode='C';
      customPop='';
    }
    $('.profileCreate').hide();
    $('.profileSearch').show();
    $('#reservationChild').modal('show');
    runCountryList();
    runSupportingLov();
    $('#optionWindow').modal('hide');
  }

  function addResvation(){
    $(':input','#reservationForm').val('').prop('checked', false).prop('selected', false);
    $('#RESV_NAME,#RESV_COMPANY,#RESV_AGENT,#RESV_BLOCK').html('<option value="">Select</option>').selectpicker('refresh');
    $('#reservationW').modal('show');
    runSupportingResevationLov();
    $('.window-1,#nextbtn,#previousbtn').show();
    $('.flxyFooter').addClass('flxy_space');
    $('#submitResrBtn').addClass('submitResr');
    $('#submitResrBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
    $('.window-2').hide();
    runCountryList();
    var today = moment().format('DD-MM-YYYY');
    var end = moment().add(1,'days').format('DD-MM-YYYY');
    $('.RESV_ARRIVAL_DT').datepicker().datepicker("setDate",today);
    $('.RESV_DEPARTURE').datepicker().datepicker("setDate",end);
    $('#RESV_NIGHT,#RESV_NO_F_ROOM,#RESV_ADULTS').val('1');
    $('#RESV_CONFIRM_YN,#RESV_PICKUP_YN,#RESV_DROPOFF_YN,#RESV_EXT_PRINT_RT,#RESV_FIXED_RATE').val('N');
  }

  $(document).on('click','.flxCheckBox',function(){
    var checked = $(this).is(':checked');
    var parent = $(this).parent();
    if(checked){
      parent.find('input[type=hidden]').val('Y');
    }else{
      parent.find('input[type=hidden]').val('N');
    }
  });

  $(document).on('change','*#RESV_NAME',function(){
    var custId = $(this).find('option:selected').val();
    var thisActive = $(this).hasClass('activeName')
    thisActive ? '' : $('[name="RESV_NAME"]').val(custId).selectpicker('refresh');
    var url = '<?php echo base_url('/getCustomerDetail')?>';
    $.ajax({
        url: url,
        type: "post",
        data: {custId:custId},
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        dataType:'json',
        success:function(respn){
          if(respn!=''){
            var json = respn[0];
            console.log(json.CUST_COUNTRY,json.CUST_VIP,"GJLKES");
            $('#CUST_FIRST_NAME').val($.trim(json.CUST_FIRST_NAME))
            $('#CUST_TITLE').val($.trim(json.CUST_TITLE));
            $('#CUST_COUNTRY').val($.trim(json.CUST_COUNTRY)).selectpicker('refresh');
            $('#CUST_VIP').val($.trim(json.CUST_VIP));
            $('#CUST_PHONE').val($.trim(json.CUST_PHONE));
          }else{
            $('#CUST_FIRST_NAME,#CUST_TITLE,#CUST_COUNTRY,#CUST_VIP,#CUST_PHONE').val('');
          }
        }
      });
  });

  // validation start //  

  function reservationValidate(event,id,mode){
    event.preventDefault();
    checkPaymentValid();
    var form = document.getElementById(id);
    var condition = (mode=='R' ? !form.checkValidity() || !checkArrivalDate() || !checkDeparturDate() : !form.checkValidity());
    form.classList.add('was-validated');
    if (condition) {
      return false;
    }else{
      return true;
    }
  }

  function checkPaymentValid(){
    var payment = $('#RESV_PAYMENT_TYPE').val();
    if(payment==''){
      $('#RESV_PAYMENT_TYPE').parent('div').removeClass('is-valid').addClass('is-invalid');
    }else{
      $('#RESV_PAYMENT_TYPE').parent('div').removeClass('is-invalid').addClass('is-valid');
    }
  }

  $(document).on('change','#RESV_ARRIVAL_DT',function(){
    checkArrivalDate();
  });
  $(document).on('change','#RESV_DEPARTURE',function(){
    checkDeparturDate();
  });

  function checkArrivalDate() {
    var startField = $('[name="RESV_ARRIVAL_DT"]');
    var endField = $('[name="RESV_DEPARTURE"]');
    var startDt = $(startField).val();
    var endDt = $(endField).val();
    var startDtFmt = moment(startDt,'DD-MMM-YYYY');
    var endDtFmt = moment(endDt,'DD-MMM-YYYY');
    console.log(startDtFmt,endDtFmt,"startDtFmt TREU SDFF");
    if(startDtFmt<endDtFmt){
      $(startField).removeClass("is-invalid");
      $(startField).addClass("is-valid");
      $(startField)[0].setCustomValidity("");
      return true;
    }else{
      $(startField).removeClass("is-valid");
      $(startField).addClass("is-invalid");
      $(startField)[0].setCustomValidity("invalid");
      return false;
    }
  }
  function checkDeparturDate() {
    var startField = $('[name="RESV_ARRIVAL_DT"]');
    var endField = $('[name="RESV_DEPARTURE"]');
    var startDt = startField.val();
    var endDt = endField.val();
    var startDtFmt = moment(startDt,'DD-MMM-YYYY');
    var endDtFmt = moment(endDt,'DD-MMM-YYYY');
    if(endDtFmt>startDtFmt){
      endField.removeClass("is-invalid");
      endField.addClass("is-valid");
      $(endField)[0].setCustomValidity("");
      return true;
    }else{
      endField.removeClass("is-valid");
      endField.addClass("is-invalid");
      $(endField)[0].setCustomValidity("invalid");
      return false;
    }
  }
  // validation end //

  function submitForm(id,mode,event){
    var validate = reservationValidate(event,id,mode);
    if(!validate){
      return false;
    }
    if(mode=='R'){
      var url = '<?php echo base_url('/insertReservation')?>';
    }else{
      var url = '<?php echo base_url('/insertCustomer')?>';
    }
    $('#loader_flex_bg').show();
    $('#errorModal').hide();
    var formSerialization = $('#'+id).serializeArray();
    $.ajax({
        url: url,
        type: "post",
        data: formSerialization,
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        dataType:'json',
        success:function(respn){
          var response = respn['SUCCESS'];
          $('#loader_flex_bg').hide();
          if(response!='1'){
            $('#errorModal').show();
            var ERROR = respn['RESPONSE']['ERROR'];
            var error='<ul>';
            $.each(ERROR,function(ind,data){
              console.log(data,"SDF");
              error+='<li>'+data+'</li>';
            });
            error+='<ul>';
            $('#formErrorMessage').html(error);
          }else{
            if(mode=='C'){
              var response = respn['RESPONSE']['OUTPUT'];
              $('#reservationChild').modal('hide');
              var option = '<option value="'+response['ID']+'">'+response['FULLNAME']+'</option>';
              $('*#RESV_NAME').html(option).selectpicker('refresh');
              $('*#CUST_TITLE').val(response['CUST_TITLE']);
              $('*#CUST_FIRST_NAME').val(response['CUST_FIRST_NAME']);
              $('*#CUST_VIP').val(response['CUST_VIP']);
              $('*#CUST_PHONE').val(response['CUST_PHONE']);
              $('*#CUST_COUNTRY').val(response['CUST_COUNTRY']).selectpicker('refresh');
              var joinVaribl = windowmode+customPop;
              if(joinVaribl=='AC-N'){
                custId=response['ID'];
                $('#customeTrigger').trigger('click');
              }
            }else{
              var response = respn['RESPONSE']['REPORT_RES'][0];
              var confirmationNo = response['RESV_NO'];
              bootbox.alert({
                message: '<b>Confimation Number : </b>'+confirmationNo+'',
                size: 'small'
              });
              $('#reservationW').modal('hide');
              $('#dataTable_view').dataTable().fnDraw();
            }
          }
        }
    });
  }
  function runCountryList(type){
    $.ajax({
        url: '<?php echo base_url('/countryList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        // dataType:'json',
        success:function(respn){
          if(type=='COMPANY'){
            $('#COM_COUNTRY').html(respn).selectpicker('refresh');
          }else{
            $('*#CUST_COUNTRY').html(respn).selectpicker('refresh');
            $('#CUST_NATIONALITY').html(respn);
          }
        }
    });
  }

  function runSupportingLov(){
    $.ajax({
        url: '<?php echo base_url('/getSupportingLov')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        dataType:'json',
        success:function(respn){
          var vipData = respn[0];
          var busegmt = respn[1];
          var option = '<option value="">Select Vip</option>';
          var option2 = '<option value="">Select Segment</option>';
          $(vipData).each(function(ind,data){
            option+= '<option value="'+data['VIP_ID']+'">'+data['VIP_DESC']+'</option>';
          });
          $(busegmt).each(function(ind,data){
            option2+= '<option value="'+data['BUS_SEG_CODE']+'">'+data['BUS_SEG_DESC']+'</option>';
          });
          $('*#CUST_VIP').html(option);
          $('#CUST_BUS_SEGMENT').html(option2);
        }
    });
  }
  
  $(document).on('keyup','.COPY_RM_TYPE .form-control',function(){
    var search = $(this).val();
    $.ajax({
        url: '<?php echo base_url('/roomTypeList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{search:search},
        // dataType:'json',
        success:function(respn){
          $('#COPY_RM_TYPE').html(respn).selectpicker('refresh');
        }
    });
  });

  $(document).on('keyup','.RESV_RM_TYPE,.RESV_RTC .form-control',function(){
    var search = $(this).val();
    $.ajax({
        url: '<?php echo base_url('/roomTypeList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{search:search},
        // dataType:'json',
        success:function(respn){
          console.log(respn,"testing");
          $('#RESV_RM_TYPE,#RESV_RTC').html(respn).selectpicker('refresh');
        }
    });
  });

  $(document).on('change','#RESV_RM_TYPE,#RESV_RTC',function(){
    var feature = $(this).find('option:selected').attr('data-feture');
    $('[name="RESV_FEATURE"]').val(feature);
  });  

  $(document).on('keyup','.RESV_BLOCK .form-control',function(){
    var search = $(this).val();
    $.ajax({
        url: '<?php echo base_url('/blockList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{search:search},
        // dataType:'json',
        success:function(respn){
          console.log(respn,"testing");
          $('*#RESV_BLOCK').html(respn).selectpicker('refresh');
        }
    });
  });

  $(document).on('keyup','.RESV_COMPANY .form-control',function(){
    var search = $(this).val();
    $.ajax({
        url: '<?php echo base_url('/companyList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{search:search},
        // dataType:'json',
        success:function(respn){
          console.log(respn,"testing");
          $('*#RESV_COMPANY').html(respn).selectpicker('refresh');
        }
    });
  });

  $(document).on('keyup','.RESV_AGENT .form-control',function(){
    var search = $(this).val();
    $.ajax({
        url: '<?php echo base_url('/agentList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{search:search},
        // dataType:'json',
        success:function(respn){
          console.log(respn,"testing");
          $('*#RESV_AGENT').html(respn).selectpicker('refresh');
        }
    });
  });


  $(document).on('keyup','.RESV_NAME .form-control',function(){
    var search = $(this).val();
    $.ajax({
        url: '<?php echo base_url('/customerList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{search:search},
        // dataType:'json',
        success:function(respn){
          console.log(respn,"testing");
          $('*#RESV_NAME').html(respn).selectpicker('refresh');
        }
    });
  });

  $(document).on('keyup','.RESV_ROOM .form-control',function(){
    var search = $(this).val();
    $.ajax({
        url: '<?php echo base_url('/roomList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{search:search},
        // dataType:'json',
        success:function(respn){
          console.log(respn,"testing");
          $('*#RESV_ROOM').html(respn).selectpicker('refresh');
        }
    });
  });


  $(document).on('change','#CUST_COUNTRY',function(){
    var ccode = $(this).val();
    $.ajax({
        url: '<?php echo base_url('/stateList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{ccode:ccode},
        // dataType:'json',
        success:function(respn){
          $('#CUST_STATE').html(respn).selectpicker('refresh');
        }
    });
  });
  $(document).on('change','#CUST_STATE',function(){
    var scode = $(this).val();
    var ccode = $('#customerForm #CUST_COUNTRY').find('option:selected').val();
    $.ajax({
        url: '<?php echo base_url('/cityList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{ccode:ccode,scode:scode},
        // dataType:'json',
        success:function(respn){
            $('*#CUST_CITY').html(respn).selectpicker('refresh');
        }
    });
  });
  
  function runSupportingResevationLov(){
    $.ajax({
      url: '<?php echo base_url('/getSupportingReservationLov')?>',
      type: "post",
      headers: {'X-Requested-With': 'XMLHttpRequest'},
      dataType:'json',
      async:false,
      success:function(respn){
        var memData = respn[0];
        var idArray = ['RESV_MEMBER_TY','RESV_RATE_CLASS','RESV_RATE_CODE','RESV_ROOM_CLASS','RESV_FEATURE','RESV_PURPOSE_STAY','CUST_VIP','RESV_TRANSPORT_TYP','RESV_GUST_TY','RESV_ENTRY_PONT','RESV_PROFILE'];
        $(respn).each(function(ind,data){
          var option = '<option value="">Select</option>';
          $.each(data,function(i,valu){
            var value = $.trim(valu['CODE']);//fields.trim();
            var desc = $.trim(valu['DESCS']);//datavals.trim();
            option += '<option value="'+value+'">'+desc+'</option>';
          });
          if(idArray[ind]=='RESV_MEMBER_TY'){
            $('*#'+idArray[ind]).html(option);
          }else if(idArray[ind]=='RESV_RATE_CLASS'){
            $('#RESV_RATE_CATEGORY').html(option);
          }else{
            $('#'+idArray[ind]).html(option);
            if(idArray[ind]=='RESV_TRANSPORT_TYP'){
              $('#RESV_TRANSPORT_TYP_DO').html(option);
            }
            if(idArray[ind]=='RESV_PURPOSE_STAY'){
              $('#RESV_EXT_PURP_STAY').html(option);
            }
          }
        });
      }
    });
  }

  function runInitializeConfig(){
    $.ajax({
      url: '<?php echo base_url('/getInitializeListReserv')?>',
      type: "post",
      headers: {'X-Requested-With': 'XMLHttpRequest'},
      dataType:'json',
      async:false,
      success:function(respn){
        var memData = respn[0];
        var idArray = ['RESV_RESRV_TYPE','RESV_MARKET','RESV_SOURCE','RESV_ORIGIN','RESV_PAYMENT_TYPE'];
        
        $(respn).each(function(ind,data){
          var option = '';
          $.each(data,function(i,valu){
            var value = $.trim(valu['CODE']);//fields.trim();
            var desc = $.trim(valu['DESCS']);//datavals.trim();
            option+= '<option value=\''+value+'\'>'+desc+'</option>';
          });
          var options='<option value=\'\'>Select</option>'+option;
          console.log(options,"options");
          $('#'+idArray[ind]).html(options);
        });
      }
    });
  }


  function companyAgentClick(type){
    compAgntMode=type;
    if(type=='COMPANY'){
      $('.companyData').show();
      $('.agentData').hide();
    }else{
      $('.companyData').hide();
      $('.agentData').show();
    }
    runCountryListExdClass();
    $('#COM_TYPE').val(compAgntMode);
    $(':input','#compnayAgentForm').val('').prop('checked', false).prop('selected', false);
    $('#compnayAgentWindow').modal('show');
  }

  $(document).on('change','.rateRadio',function(){
    $('.rateRadio').not(this).prop('checked',false);
    var thiss = $(this);
    var mode = thiss.attr('mode');
    if(thiss.is(':checked')){
      generateRateQuery(mode);
    }
  });

  var customPop='';
  function searchData(form,mode,event){
    if(mode=='C'){
      $('.'+form).find('input,select').val('');
      $('#searchRecord').html('<tr><td class="text-center" colspan="11">No Record Found</td></tr>');
    }else if(mode=='S'){
      var formData={};
      $('.'+form).find('input,select').each(function(i,data){
        var field = $(data).attr('id');
        var values = $(this).val();
        formData[field]=values; 
      });
      formData['windowmode']=windowmode;
      $.ajax({
        url: '<?php echo base_url('/searchProfile')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:formData,
        dataType:'json',
        success:function(respn){
          var respone = respn['table'];
          console.log(respone,"SDFF");
          $('#searchRecord').html(respone);
        }
      });
    }else if(mode=='N'){
      $('#customerForm').find('input,select').val('');
      $('.profileCreate').show();
      $('.profileSearch').hide();
      customPop='-N';
    }
  }
  var custId ='';
  $(document).on('click','.activeRow,#customeTrigger',function(){
    var joinVaribl = windowmode+customPop;
    if(joinVaribl!='AC-N'){
      $('.activeRow').removeClass('activeTr');
      $(this).addClass('activeTr');
      custId = $(this).attr('data_sysid');
    }
    $('#appcompanyWindow').modal('show');
    $.ajax({
      url: '<?php echo base_url('/getExistingAppcompany')?>',
      type: "post",
      headers: {'X-Requested-With': 'XMLHttpRequest'},
      data:{custId:custId,ressysId:ressysId},
      dataType:'json',
      success:function(respn){
        var respone = respn['table'];
        $('#accompanyTd').html(respone);
      }
    });
  });

  $(document).on('click','.getExistCust',function(){
      $('.getExistCust').removeClass('activeTr');
      $(this).addClass('activeTr');
      custId = $(this).attr('data_sysid');
    $.ajax({
      url: '<?php echo base_url('/getExistCustomer')?>',
      type: "post",
      headers: {'X-Requested-With': 'XMLHttpRequest'},
      data:{custId:custId},
      dataType:'json',
      success:function(respn){
        var jsonForm = respn[0];
        $('#reservationChild').modal('hide');
        var option = '<option value="'+jsonForm['CUST_ID']+'">'+jsonForm['NAMES']+'</option>';
        $('*#RESV_NAME').html(option).selectpicker('refresh');
      }
    });
  });


  function accompanySet(mode,event){
    if(mode=='D'){
      $('.activeTrDetch').remove();
    }
    $.ajax({
      url: '<?php echo base_url('/appcompanyProfileSetup')?>',
      type: "post",
      headers: {'X-Requested-With': 'XMLHttpRequest'},
      data:{mode:mode,ACCOMP_CUST_ID:custId,ACCOMP_REF_RESV_ID:ressysId,ACCOPM_ID:ACCOPM_SYSID},
      dataType:'json',
      success:function(respn){
        var respone = respn['table'];
        $('#accompanyTd').html(respone);
      }
    });
  }
  var ACCOPM_SYSID='';
  $(document).on('click','.activeDetach',function(){
    $('.activeDetach').removeClass('activeTrDetch');
    $(this).addClass('activeTrDetch');
    ACCOPM_SYSID = $(this).attr('data_sysid');
  });

  var copyresr=[];
  function reservExtraOption(param){
    if(param=='ACP'){
      childReservation();
      $('#Addon').hide();
      $('#Accompany').show();
    }else if(param=='ADO'){
      $('#Accompany').hide();
      $('#Addon').show();
      copyresr=[];
      copyresr.push('PM','SP','CR','RU','CM','PK','IN','GU');
      $('#COPY_RM_TYPE').html('<option value="'+roomType+'">'+roomTypedesc+'</option>').selectpicker('refresh');
    }
  }

  
  $(document).on('change','.copyReser',function(){
    var checkedMe = $(this).is(':checked');
    var newData = $(this).attr('method');
    if(checkedMe){
      copyresr.push(newData);
    }else{
      copyresr = jQuery.grep(copyresr, function(value) { return value != newData; });
    }
  });

  function copyReservation(){
    var roomType = $('#COPY_RM_TYPE').val();
    $("#triggCopyReserv").trigger('click',[ressysId,copyresr,roomType]);
    $('#optionWindow').modal('hide');
    $('.copyReser').prop('checked',true);
    windowmode='C';
    customPop='';
  }

  function detailOption(mode){
    var roomType=$('#rateQueryTable .active').find('#ROOMTYPE').val();
    var fromdate = $('#RESV_ARRIVAL_DT').val();
    var uptodate = $('#RESV_DEPARTURE').val();
    $('#reteQueryDetail').modal('show');
    $.ajax({
      url: '<?php echo base_url('/rateQueryDetailOption')?>',
      type: "post",
      headers: {'X-Requested-With': 'XMLHttpRequest'},
      data:{mode:mode,fromdate:fromdate,uptodate:uptodate,roomType:roomType},
      dataType:'json',
      success:function(respn){
        var respone = respn['table'];
        $('#reteQueryDetailTd').html(respone);
      }
    });
  }
</script>

<?=$this->endSection()?>