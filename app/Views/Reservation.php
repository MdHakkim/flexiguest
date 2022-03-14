


<?=$this->extend("Layout/AppView")?>
<?=$this->section("contentRender")?>
<?= $this->include('Layout/ErrorReport') ?>
          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">DataTables /</span> Basic</h4>

              <!-- DataTable with Buttons -->
              <div class="card">
                <!-- <h5 class="card-header">Responsive Datatable</h5> -->
                <div class="container-fluid" style="padding:6px;">
                  <table id="dataTable_view" class="table table-striped">
                    <thead>
                      <tr>
                        <th>Reservation Name</th>
                        <th>Arrival Date</th>
                        <th>Night</th>
                        <th>Departure Date</th>
                        <th>No of Room</th>
                        <!-- <th>Feature</th> -->
                        <th>Purpose</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    
                  </table>
                </div>
              </div>
             
              <!--/ Multilingual -->
            </div>
            <!-- / Content -->

            <!-- Modal Window -->
           
            <div class="modal fade" id="reservationW" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog modal-xl">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="reservationWlable">New message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form id="reservationForm">
                      <div class="window-1">
                        <div class="row g-3">
                          <div class="col-md-3">
                            <input type="hidden" name="RESV_ID" id="RESV_ID" class="form-control"/>
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
                          <div class="col-md-2"></div>
                          <div class="col-md-3">
                            <lable class="form-lable">Guest Name</lable>
                              <div class="input-group mb-3">
                                <select id="RESV_NAME" class="selectpicker RESV_NAME" data-live-search="true">
                                  <option value="">Select</option>
                                </select>
                                <button type="button" onClick="childReservation()" class="btn flxi_btn btn-sm btn-primary"><i class="fa fa-plus" aria-hidden="true"></i></button>
                              </div>
                          </div>
                          <div class="col-md-3">
                            <lable class="form-lable">Member Type</lable>
                            <select name="RESV_MEMBER_TY" id="RESV_MEMBER_TY" class=" select2 form-select" data-allow-clear="true">
                              <option value="">Select</option>
                            </select>
                          </div>
                          <div class="col-md-3">
                            <lable class="form-lable">Company</lable>
                            <div class="input-group mb-3">
                              <select name="RESV_COMPANY"  id="RESV_COMPANY" class="selectpicker RESV_COMPANY" data-live-search="true">
                                  <option value="">Select</option>
                                </select>
                              <button type="button" onClick="companyAgentClick('COMPANY')" class="btn flxi_btn btn-sm btn-primary"><i class="fa fa-plus" aria-hidden="true"></i></button>
                            </div>
                          </div>
                          <div class="col-md-3">
                            <lable class="form-lable">Agent</lable>
                            <div class="input-group mb-3">
                              <select name="RESV_AGENT"  id="RESV_AGENT" class="selectpicker RESV_AGENT" data-live-search="true">
                                  <option value="">Select</option>
                                </select>
                              <button type="button" onClick="companyAgentClick('AGENT')" class="btn flxi_btn btn-sm btn-primary"><i class="fa fa-plus" aria-hidden="true"></i></button>
                            </div>
                          </div>
                          <div class="col-md-3">
                            <lable class="form-lable">Block</lable>
                            <select  id="RESV_BLOCK" data-width="100%" class="selectpicker RESV_BLOCK" data-live-search="true">
                                  <option value="">Select</option>
                                </select>
                          </div>
                          <div class="col-md-3">
                            <lable class="form-lable">Member No</lable>
                            <input type="text" id="RESV_MEMBER_NO" class="form-control RESV_MEMBER_NO" placeholder="member no" />
                          </div>
                          <div class="col-md-3">
                            <lable class="form-lable">CORP NO</lable>
                            <input type="text" name="RESV_CORP_NO" id="RESV_CORP_NO" class="form-control" placeholder="CORP no" />
                          </div>
                          <div class="col-md-3">
                            <lable class="form-lable">IATA NO</lable>
                            <input type="text" name="RESV_IATA_NO" id="RESV_IATA_NO" class="form-control" placeholder="IATA no" />
                          </div>
                          <div class="col-md-3 flxi_ds_flx">
                            <div class="form-check mt-3 me-1">
                              <input class="form-check-input flxCheckBox" type="checkbox"  id="RESV_CLOSED_CHK">
                              <!-- <input type="hidden" name="RESV_CLOSED" id="RESV_CLOSED" value="N" class="form-control" /> -->
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
                            <select name="RESV_RATE_CODE" id="RESV_RATE_CODE" class="select2 form-select" data-allow-clear="true">
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
                            <select name="RESV_FEATURE" id="RESV_FEATURE" class="select2 form-select" data-allow-clear="true">
                              <option value="">Select</option>
                            </select>
                          </div>
                          <div class="col-md-3">
                            <lable class="form-lable">Packages</lable>
                            <select name="RESV_PACKAGES" id="RESV_PACKAGES" class="select2 form-select" data-allow-clear="true">
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
                                <select name="RESV_NAME"  id="RESV_NAME" class="selectpicker RESV_NAME" data-live-search="true">
                                  <option value="">Select</option>
                                </select>
                                <button type="button" onClick="childReservation()" class="btn flxi_btn btn-sm btn-primary"><i class="fa fa-plus" aria-hidden="true"></i></button>
                              </div>
                          </div>
                          <div class="col-md-3">
                            <lable class="form-lable">Title / First Name</lable>
                            <div class="form-group flxi_join">
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
                                <input type="text" name="CUST_FIRST_NAME" id="CUST_FIRST_NAME" class="form-control" placeholder="first name" />
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
                          <div class="col-md-3">
                            <lable class="form-lable">Phone</lable>
                            <input type="text" name="CUST_PHONE" id="CUST_PHONE" class="form-control" placeholder="phone" />
                          </div>
                          <div class="col-md-3">
                            <lable class="form-lable">Member Type</lable>
                            <select name="RESV_MEMBER_TY" id="RESV_MEMBER_TY" class=" select2 form-select" data-allow-clear="true">
                              <option value="">Select</option>
                            </select>
                          </div>
                          <div class="col-md-3">
                            <lable class="form-lable">Member No</lable>
                            <input type="text" name="RESV_MEMBER_NO" id="RESV_MEMBER_NO" class="form-control" placeholder="member no" />
                          </div>
                          <div class="col-md-3">
                            <lable class="form-lable">Company</lable>
                            <div class="input-group mb-3">
                              <select name="RESV_COMPANY"  id="RESV_COMPANY" class="selectpicker RESV_COMPANY" data-live-search="true">
                                  <option value="">Select</option>
                                </select>
                              <button type="button" onClick="companyAgentClick('COMPANY')" class="btn flxi_btn btn-sm btn-primary"><i class="fa fa-plus" aria-hidden="true"></i></button>
                            </div>
                          </div>
                          <div class="col-md-3">
                            <lable class="form-lable">Agent</lable>
                            <div class="input-group mb-3">
                              <select name="RESV_AGENT"  id="RESV_AGENT" class="selectpicker RESV_AGENT" data-live-search="true">
                                  <option value="">Select</option>
                                </select>
                              <button type="button" onClick="companyAgentClick('AGENT')" class="btn flxi_btn btn-sm btn-primary"><i class="fa fa-plus" aria-hidden="true"></i></button>
                            </div>
                          </div>
                          <div class="col-md-3">
                            <lable class="form-lable">Block</lable>
                            <select name="RESV_BLOCK"  id="RESV_BLOCK" data-width="100%" class="selectpicker RESV_BLOCK" data-live-search="true">
                                  <option value="">Select</option>
                                </select>
                          </div>
                          
                          <div class="col-md-6"></div>
                          <div class="col-md-3">
                            <input type="hidden" name="RESV_ID" id="RESV_ID" class="form-control"/>
                            <lable class="form-lable">Arrival/Departure Date</lable>
                              <div class="input-group mb-3">
                                <input type="text" id="RESV_ARRIVAL_DT" name="RESV_ARRIVAL_DT" class="form-control RESV_ARRIVAL_DT" placeholder="DD-MM-YYYY">
                                <span class="input-group-append">
                                  <span class="input-group-text bg-light d-block">
                                    <i class="fa fa-calendar"></i>
                                  </span>
                                </span>
                                <input type="text" id="RESV_DEPARTURE" name="RESV_DEPARTURE" class="form-control RESV_DEPARTURE" placeholder="DD-MM-YYYY" >
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
                              <input type="number" name="RESV_NIGHT" id="RESV_NIGHT" class="form-control" placeholder="night" />
                              <input type="number" name="RESV_NO_F_ROOM" id="RESV_NO_F_ROOM" class="form-control" placeholder="no of room" />
                            </div>
                          </div>
                          <div class="col-md-3">
                            <lable class="form-lable">Adults/Children</lable>
                              <div class="input-group mb-3">
                                <input type="number" name="RESV_ADULTS" id="RESV_ADULTS" class="form-control" placeholder="adults" />
                                <input type="number" name="RESV_CHILDREN" id="RESV_CHILDREN" class="form-control" placeholder="children" />
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
                            <select name="RESV_RATE_CODE"  id="RESV_RATE_CODE" data-width="100%" class="selectpicker RESV_RATE_CODE" data-live-search="true">
                                <option value="">Select</option>
                            </select>
                          </div>
                          <div class="col-md-3">
                            <lable class="form-lable">Rate</lable>
                              <div class="input-group mb-3">
                                <input type="number" name="RESV_RATE" id="RESV_RATE" class="form-control" placeholder="rate" />
                                <button type="button" onClick="companyAgentClick('COMPANY')" class="btn flxi_btn btn-sm btn-primary"><i class="fa fa-plus" aria-hidden="true"></i></button>
                              </div>
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
                          <div class="col-md-3">
                            <lable class="form-lable">Reseravation Type</lable>
                            <select name="RESV_RESRV_TYPE"  id="RESV_RESRV_TYPE" class="select2 form-select" data-allow-clear="true">
                                <option value="">Select</option>
                            </select>
                          </div>
                          <div class="col-md-3">
                            <lable class="form-lable">Market</lable>
                            <select name="RESV_MARKET" id="RESV_MARKET" class="select2 form-select" data-allow-clear="true">
                              <option value="">Select</option>
                            </select>
                          </div>
                          <div class="col-md-3">
                            <lable class="form-lable">Source</lable>
                            <select name="RESV_SOURCE" id="RESV_SOURCE" class="select2 form-select" data-allow-clear="true">
                              <option value="">Select</option>
                            </select>
                          </div>
                          <div class="col-md-3">
                            <lable class="form-lable">Origin</lable>
                            <select name="RESV_ORIGIN" id="RESV_ORIGIN" class="select2 form-select" data-allow-clear="true">
                              <option value="">Select</option>
                            </select>
                          </div>
                          <div class="col-md-3">
                            <lable class="form-lable">Payment</lable>
                            <select name="RESV_PAYMENT_TYPE" id="RESV_PAYMENT_TYPE" class="select2 form-select" data-allow-clear="true">
                              <option value="">Select</option>
                            </select>
                          </div>
                          <div class="col-md-3">
                            <lable class="form-lable">Specials</lable>
                            <select name="RESV_SPECIALS" id="RESV_SPECIALS" class="select2 form-select" data-allow-clear="true">
                              <option value="">Select</option>
                            </select>
                          </div>
                          <div class="col-md-3">
                            <lable class="form-lable">Comments</lable>
                            <textarea class="form-control" name="RESV_COMMENTS" id="RESV_COMMENTS" rows="1"></textarea>
                          </div>
                          <div class="col-md-3">
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
                          <div class="col-md-3">
                            <div class="form-check mt-3">
                              <input class="form-check-input flxCheckBox" type="checkbox"  id="RESV_CONFIRM_YN_CHK">
                              <input type="hidden" name="RESV_CONFIRM_YN" id="RESV_CONFIRM_YN" value="N" class="form-control" />
                              <lable class="form-check-lable" for="defaultCheck1"> Confimation</lable>
                            </div>
                        </div> 
                         
                        </div>
                      </div>
                    </form>
                  </div>
                  <div class="modal-footer flxyFooter flxy_space">
                    <button type="button" id="previousbtn" onClick="previous()" class="btn btn-primary"><i class="fa-solid fa-angle-left"></i> Previous</button>
                    <button type="button" id="submitResrBtn" onClick="submitForm('reservationForm','R')" class="btn btn-primary submitResr">Save</button>
                    <button type="button" id="nextbtn" onClick="next()" class="btn btn-primary"> Next <i class="fa-solid fa-angle-right"></i></button>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal fade" id="reservationChild" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog modal-xl">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="reservationChildlable">New message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form id="customerForm">
                      <div class="row g-3">
                        <div class="col-md-3">
                        <input type="hidden" name="CUST_ID" id="CUST_ID" class="form-control"/>
                          <lable class="form-lable">First Name</lable>
                          <input type="text" name="CUST_FIRST_NAME" id="CUST_FIRST_NAME" class="form-control" placeholder="first name" />
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
                              <input type="text" id="CUST_DOB" name="CUST_DOB" class="form-control CUST_DOB" placeholder="YYYY-MM-DD" readonly="readonly">
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
                          <input type="text" name="CUST_ADDRESS_1"  id="CUST_ADDRESS_1" class="form-control" placeholder="addresss 1" />
                        </div> 
                        <div class="col-md-3 flxy_mgtop">
                          <lable class="form-lable"></lable>
                          <input type="text" name="CUST_ADDRESS_2"  id="CUST_ADDRESS_2" class="form-control" placeholder="address 2" />
                        </div> 
                        <div class="col-md-3 flxy_mgtop">
                          <lable class="form-lable"></lable>
                          <input type="text" name="CUST_ADDRESS_3"  id="CUST_ADDRESS_3" class="form-control" placeholder="address 3" />
                        </div> 
                        <div class="col-md-3 ">
                          <lable class="form-lable col-md-12">Country</lable>
                          <select name="CUST_COUNTRY"  id="CUST_COUNTRY" data-width="100%" class="selectpicker CUST_COUNTRY" data-live-search="true">
                            <option value="">Select</option>
                          </select>
                        </div> 
                        <div class="col-md-3">
                          <lable class="form-lable col-md-12">State</lable>
                          <select name="CUST_STATE"  id="CUST_STATE" data-width="100%" class="selectpicker CUST_STATE" data-live-search="true">
                            <option value="">Select</option>
                          </select>
                        </div> 
                        <div class="col-md-3">
                          <lable class="form-lable col-md-12">City</lable>
                          <select name="CUST_CITY"  id="CUST_CITY" data-width="100%" class="selectpicker CUST_CITY" data-live-search="true">
                            <option value="">Select</option>
                          </select>
                        </div> 
                        <div class="col-md-3">
                          <lable class="form-lable">Email</lable>
                          <input type="text" name="CUST_EMAIL"  id="CUST_EMAIL" class="form-control" placeholder="email" />
                        </div>
                        <div class="col-md-3">
                          <lable class="form-lable">Mobile</lable>
                          <input type="text" name="CUST_MOBILE"  id="CUST_MOBILE" class="form-control" placeholder="mobile" />
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
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" onClick="submitForm('customerForm','C')" class="btn btn-primary">Save</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- /Modal window -->
            
            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
          <?=$this->include("CompanyAgentModal")?>
<script>
  var compAgntMode='';
  var linkMode='';
  $(document).ready(function() {
    linkMode='EX';
    $('#dataTable_view').DataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'<?php echo base_url('/reservationView')?>'
        },
        'columns': [
          { data: 'FULLNAME' },
          { data: 'RESV_ARRIVAL_DT'},
          { data: 'RESV_NIGHT' },
          { data: 'RESV_DEPARTURE'},
          { data: 'RESV_NO_F_ROOM'},
          // { data: 'RESV_FEATURE'},
          { data: 'RESV_PURPOSE_STAY'},
          { data: null , render : function ( data, type, row, meta ) {
            return (
              '<div class="d-inline-block">' +
                '<a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></a>' +
                '<ul class="dropdown-menu dropdown-menu-end">' +
                  '<li><a href="javascript:;" data_sysid="'+data['RESV_ID']+'" class="dropdown-item editReserWindow">Edit</a></li>' +
                  '<div class="dropdown-divider"></div>' +
                  '<li><a href="javascript:;" data_sysid="'+data['RESV_ID']+'" class="dropdown-item text-danger delete-record">Delete</a></li>' +
                '</ul>' +
              '</div>'
            );
          }},
        ],
        autowidth:true
    });
    $("#dataTable_view_wrapper .row:first").before('<div class="row flxi_pad_view"><div class="col-md-3 ps-0"><button type="button" class="btn btn-primary" onClick="addResvation()"><i class="fa-solid fa-plus fa-lg"></i> Add</button></div></div>');

    $('.RESV_ARRIVAL_DT').datepicker({
        format: 'd-M-yyyy',
        autoclose: true
    });
    $('.RESV_DEPARTURE').datepicker({
        format: 'd-M-yyyy',
        autoclose: true
    });
    $('.CUST_DOB').datepicker({
        format: 'd-M-yyyy',
        autoclose: true
    });
    // $('#RESV_ETA').datetimepicker({
    //   format: 'hh:mm:ss a'
    // });    

  });

  function next(){
    $('.window-1,#nextbtn').hide();
    $('.window-2').show();
    $('#submitResrBtn').removeClass('submitResr');
    runCountryList();
    runInitializeConfig();
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
  
  $(document).on('click','.editReserWindow',function(){
    runSupportingResevationLov();
    runInitializeConfig();
    $('.window-1,#nextbtn,#previousbtn').hide();
    $('.window-2').show();
    $('.flxyFooter').removeClass('flxy_space');
    $('#submitResrBtn').removeClass('submitResr');
    var sysid = $(this).attr('data_sysid');
    $('#reservationW').modal('show');
    $.ajax({
        url: '<?php echo base_url('/editReservation')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{sysid:sysid},
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
              }else if(field=='RESV_CONFIRM_YN'){
                if(dataval=='Y'){
                  $('#'+field+'_CHK').prop('checked',true);
                }else{
                  $('#'+field+'_CHK').prop('checked',false)
                }
              }else{
                $('*#'+field).val(dataval).trigger('change');
              }
            });
          });
          $('#submitResrBtn').removeClass('btn-primary').addClass('btn-success').text('Update');
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

  function childReservation(){
    $('#reservationChild').modal('show');
    runCountryList();
    runSupportingLov();
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

  function submitForm(id,mode){
    $('#errorModal').hide();
    var formSerialization = $('#'+id).serializeArray();
    if(mode=='R'){
      var url = '<?php echo base_url('/insertReservation')?>';
    }else{
      var url = '<?php echo base_url('/insertCustomer')?>';
    }
    $.ajax({
        url: url,
        type: "post",
        data: formSerialization,
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        dataType:'json',
        success:function(respn){
          console.log(respn,"testing");
          var response = respn['SUCCESS'];
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
              $('#RESV_NAME').html(option).selectpicker('refresh');
            }else{
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
    var ccode = $('#CUST_COUNTRY').find('option:selected').val();
    $.ajax({
        url: '<?php echo base_url('/cityList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{ccode:ccode,scode:scode},
        // dataType:'json',
        success:function(respn){
            $('#CUST_CITY').html(respn).selectpicker('refresh');
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
        var idArray = ['RESV_MEMBER_TY','RESV_RATE_CLASS','RESV_RATE_CODE','RESV_ROOM_CLASS','RESV_FEATURE','RESV_PURPOSE_STAY','CUST_VIP'];
        $(respn).each(function(ind,data){
          var option = '<option value="">Select</option>';
          $.each(data,function(i,valu){
            var value = $.trim(valu['CODE']);//fields.trim();
            var desc = $.trim(valu['DESCS']);//datavals.trim();
            option += '<option value="'+value+'">'+desc+'</option>';
          });
          if(idArray[ind]=='RESV_MEMBER_TY'){
            $('*#'+idArray[ind]).html(option);
          }else{
            $('#'+idArray[ind]).html(option);
          }
          if(idArray[ind]=='RESV_RATE_CLASS'){
            $('#RESV_RATE_CATEGORY').html(option);
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
          var option = '<option value="">Select</option>';
          $.each(data,function(i,valu){
            var value = $.trim(valu['CODE']);//fields.trim();
            var desc = $.trim(valu['DESCS']);//datavals.trim();
            option += '<option value='+value+'>'+desc+'</option>';
          });
          $('#'+idArray[ind]).html(option);
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
</script>

<?=$this->endSection()?>