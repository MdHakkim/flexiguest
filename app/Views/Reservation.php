


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
                        <th>Name</th>
                        <th>Last Name</th>
                        <th>age</th>
                        <!-- <th>start Date</th>
                        <th>Salary</th> -->
                      </tr>
                    </thead>
                    
                  </table>
                </div>
              </div>
             
              <!--/ Multilingual -->
            </div>
            <!-- / Content -->

            <!-- Modal Window -->
           
            <div class="modal fade" id="reservationW" tabindex="-1" aria-lableledby="reservationWlable" aria-hidden="true">
              <div class="modal-dialog modal-xl">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="reservationWlable">New message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form id="reservationForm">
                      <div class="row g-3">
                        <div class="col-md-4">
                          <lable class="form-lable">Arrival/Departure Date</lable>
                          
                            <div class="input-group mb-3">
                              <!-- <input type="text" id="RESV_ARRIVAL_DT" name="RESV_ARRIVAL_DT" class="form-control datepicker" placeholder="YYYY-MM-DD" readonly="readonly"> -->
                              <input type="text" id="RESV_ARRIVAL_DT" name="RESV_ARRIVAL_DT" class="form-control" placeholder="DD/MM/YYYY">
                              <span class="input-group-append">
                                <span class="input-group-text bg-light d-block">
                                  <i class="fa fa-calendar"></i>
                                </span>
                              </span>
                              <input type="text" id="RESV_DEPARTURE" name="RESV_DEPARTURE" class="form-control flatpickr-input active" placeholder="YYYY-MM-DD" readonly="readonly">
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
                        <div class="col-md-2"></div>
                        <div class="col-md-3">
                          <lable class="form-lable">Guest Name</lable>
                            <div class="flxi_flex">
                              <select name="RESV_NAME"  id="RESV_NAME" data-width="100%" class="selectpicker RESV_NAME" data-live-search="true">
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
                          <div class="flxi_flex">
                            <select name="RESV_COMPANY"  id="RESV_COMPANY" data-width="100%" class="selectpicker RESV_COMPANY" data-live-search="true">
                                <option value="">Select</option>
                              </select>
                            <button type="button" onClick="compagnetFn()" class="btn flxi_btn btn-sm btn-primary"><i class="fa fa-plus" aria-hidden="true"></i></button>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <lable class="form-lable">Agent</lable>
                          <div class="flxi_flex">
                            <select name="RESV_AGENT"  id="RESV_AGENT" data-width="100%" class="selectpicker RESV_AGENT" data-live-search="true">
                                <option value="">Select</option>
                              </select>
                            <button type="button" onClick="compagnetFn()" class="btn flxi_btn btn-sm btn-primary"><i class="fa fa-plus" aria-hidden="true"></i></button>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <lable class="form-lable">Block</lable>
                          <select name="RESV_BLOCK" id="RESV_BLOCK" class="select2 form-select" data-allow-clear="true">
                            <option value="">Select Block</option>
                          </select>
                        </div>
                        <div class="col-md-3">
                          <lable class="form-lable">Member No</lable>
                          <input type="text" name="RESV_MEMBER_NO" id="RESV_MEMBER_NO" class="form-control" placeholder="member no" />
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
                            <input class="form-check-input flxCheckBox" type="checkbox"  id="RESV_CLOSED">
                            <input type="hidden" name="RESV_CLOSED" value="N" class="form-control" />
                            <lable class="form-check-lable" for="defaultCheck1"> Closed </lable>
                          </div>
                          <div class="form-check mt-3 me-1">
                            <input class="form-check-input flxCheckBox" type="checkbox" value="N" id="RESV_DAY_USE">
                            <input type="hidden" name="RESV_DAY_USE" value="N" class="form-control" />
                            <lable class="form-check-lable" for="defaultCheck1"> Day Use </lable>
                          </div>
                          <div class="form-check mt-3">
                            <input class="form-check-input flxCheckBox" type="checkbox" value="N" id="RESV_PSEUDO">
                            <input type="hidden" name="RESV_PSEUDO" value="N" class="form-control" />
                            <lable class="form-check-lable" for="defaultCheck1"> Pseudo </lable>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <lable class="form-lable">Rate Class</lable>
                          <select name="RESV_RATE_CLASS" id="RESV_RATE_CLASS" class=" select2 form-select" data-allow-clear="true">
                            <option value="">Select</option>
                          </select>
                        </div>
                        <div class="col-md-3">
                          <lable class="form-lable">Rate Category</lable>
                          <select name="RESV_RATE_CATEGORY" id="RESV_RATE_CATEGORY" class=" select2 form-select" data-allow-clear="true">
                            <option value="">Select</option>
                          </select>
                        </div>
                        <div class="col-md-3">
                          <lable class="form-lable">Rate Code</lable>
                          <select name="RESV_RATE_CODE" id="RESV_RATE_CODE" class=" select2 form-select" data-allow-clear="true">
                            <option value="">Select</option>
                          </select>
                        </div>
                        <div class="col-md-3">
                          <lable class="form-lable">Room Class</lable>
                          <select name="RESV_ROOM_CLASS" id="RESV_ROOM_CLASS" class=" select2 form-select" data-allow-clear="true">
                            <option value="">Select</option>
                          </select>
                        </div>
                        <div class="col-md-3">
                          <lable class="form-lable">Feature</lable>
                          <select name="RESV_FEATURE" id="RESV_FEATURE" class=" select2 form-select" data-allow-clear="true">
                            <option value="">Select</option>
                          </select>
                        </div>
                        <div class="col-md-3">
                          <lable class="form-lable">Packages</lable>
                          <select name="RESV_PACKAGES" id="RESV_PACKAGES" class=" select2 form-select" data-allow-clear="true">
                            <option value="">Select</option>
                          </select>
                        </div>
                        <div class="col-md-3">
                          <lable class="form-lable">Purpose Of Stay</lable>
                          <select name="RESV_PURPOSE_STAY" id="RESV_PURPOSE_STAY" class=" select2 form-select" data-allow-clear="true">
                            <option value="">Select</option>
                          </select>
                        </div>
                      </div>
                    </form>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" onClick="submitForm('reservationForm','R')" class="btn btn-primary">Save</button>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal fade" id="reservationChild" tabindex="-1" aria-lableledby="reservationChildlable" aria-hidden="true">
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
                              <input type="text" id="CUST_DOB" name="CUST_DOB" class="form-control flatpickr-input" placeholder="YYYY-MM-DD" readonly="readonly">
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
                        <div class="col-md-3">
                          <lable class="form-lable"></lable>
                          <input type="text" name="CUST_ADDRESS_2"  id="CUST_ADDRESS_2" class="form-control" placeholder="address 2" />
                        </div> 
                        <div class="col-md-3">
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

            <div class="modal fade" id="compnayAgentWindow" tabindex="-1" aria-lableledby="compnayAgentWindow" aria-hidden="true">
              <div class="modal-dialog modal-xl">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="compnayAgentWindowLable">New message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form id="compnayAgentForm">
                      <div class="row g-3">
                        <div class="col-md-3">
                          <lable class="form-lable">Type of Account</lable>
                          <select name="CUST_NATIONALITY"  id="CUST_NATIONALITY" class="select2 form-select" data-allow-clear="true">
                            <option value="">Select</option>
                            <option value="">Select</option>
                            <option value="">Select</option>
                          </select>
                        </div> 

                        <div class="col-md-3">
                        <input type="hidden" name="CUST_ID" id="CUST_ID" class="form-control"/>
                          <lable class="form-lable">Account</lable>
                          <input type="text" name="CUST_FIRST_NAME" id="CUST_FIRST_NAME" class="form-control" placeholder="first name" />
                        </div>
                        <!-- <div class="col-md-3">
                          <lable class="form-lable">Address1</lable>
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
                        </div> -->
                        <!-- <div class="col-md-3">
                          <lable class="form-lable">DOB</lable>
                            <div class="input-group mb-3">
                              <input type="text" id="CUST_DOB" name="CUST_DOB" class="form-control flatpickr-input" placeholder="YYYY-MM-DD" readonly="readonly">
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
                        </div> -->
                        <div class="col-md-3">
                          <lable class="form-lable">Address</lable>
                          <input type="text" name="CUST_ADDRESS_1"  id="CUST_ADDRESS_1" class="form-control" placeholder="addresss 1" />
                        </div> 
                        <div class="col-md-3 flx_top_lb">
                          <lable class="form-lable"></lable>
                          <input type="text" name="CUST_ADDRESS_2"  id="CUST_ADDRESS_2" class="form-control" placeholder="address 2" />
                        </div> 
                        <div class="col-md-3 flx_top_lb">
                          <lable class="form-lable"></lable>
                          <input type="text" name="CUST_ADDRESS_3"  id="CUST_ADDRESS_3" class="form-control" placeholder="address 3" />
                        </div> 
                        <div class="col-md-3">
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
                          <lable class="form-lable">Contact First</lable>
                          <input type="text" name="CUST_CLIENT_ID"  id="CUST_CLIENT_ID" class="form-control" placeholder="client id" />
                        </div>
                        <div class="col-md-3">
                          <lable class="form-lable">Contact Last</lable>
                          <input type="text" name="CUST_POSTAL_CODE"  id="CUST_POSTAL_CODE" class="form-control" placeholder="postal" />
                        </div> 
                        <div class="col-md-3">
                          <lable class="form-lable">Contact No</lable>
                          <select name="CUST_VIP"  id="CUST_VIP" class="select2 form-select" data-allow-clear="true">
                            <option value="">Select VIP</option>
                          </select>
                        </div> 
                        <div class="col-md-3">
                          <lable class="form-lable">Contact Email</lable>
                          <select name="CUST_NATIONALITY"  id="CUST_NATIONALITY" class="select2 form-select" data-allow-clear="true">
                            <option value="">Select</option>
                          </select>
                        </div> 
                        <div class="col-md-3">
                          <lable class="form-lable">Corporate ID</lable>
                          <input type="text" name="CUST_COMMUNICATION_DESC"  id="CUST_COMMUNICATION_DESC" class="form-control" placeholder="communication desc" />
                        </div> 
                        <!-- <div class="col-md-3">
                          <lable class="form-lable">Business Segment</lable>
                          <select name="CUST_BUS_SEGMENT"  id="CUST_BUS_SEGMENT" class="select2 form-select" data-allow-clear="true">
                            <option value="">Select</option>
                          </select>
                        </div> -->
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
<script>
  $(document).ready(function() {
    $('#dataTable_view').DataTable({
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url':'<?php echo base_url('/datatableView')?>'
        },
        'columns': [
          { data: 'name_e' },
          { data: 'email' },
          { data: 'age' }
        ],
        autowidth:true
      
    });
    $("#dataTable_view_wrapper .row:first").before('<div class="row flxi_pad_view"><div class="col-md-3 ps-0"><button type="button" class="btn btn-primary" onClick="addResvation()">Add  </button></div></div>');

    $('#RESV_ARRIVAL_DT').datepicker({
        format: 'd-M-yyyy'
    });
    $('#RESV_DEPARTURE').datepicker({
        format: 'd-M-yyyy'
    });
    $('#CUST_DOB').datepicker({
        format: 'd-M-yyyy'
    });

  });

  function childReservation(){
    $('#reservationChild').modal('show');
    runCountryList();
    runSupportingLov();
  }

  function addResvation(){
    $(':input','#reservationForm').val('').prop('checked', false).prop('selected', false);
    $('#reservationW').modal('show');
    runSupportingResevationLov();
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

  function submitForm(id,mode){
    $('#errorModal').hide();
    var formSerialization = $('#'+id).serializeArray();
    // console.log($('#'+id),"SDFDSF");
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
            }
          }
        }
    });
  }
  function runCountryList(){
    $.ajax({
        url: '<?php echo base_url('/countryList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        // dataType:'json',
        success:function(respn){
          $('#CUST_COUNTRY').html(respn).selectpicker('refresh');
          $('#CUST_NATIONALITY').html(respn);
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
          // console.log(vipData,busegmt,"testing");
          $(vipData).each(function(ind,data){
            option += '<option value="'+data['VIP_ID']+'">'+data['VIP_DESC']+'</option>';
          });
          $(busegmt).each(function(ind,data){
            option2 += '<option value="'+data['BUS_SEG_CODE']+'">'+data['BUS_SEG_DESC']+'</option>';
          });
          $('#CUST_VIP').html(option);
          $('#CUST_BUS_SEGMENT').html(option);
        }
    });
  }

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
          $('#RESV_NAME').html(respn).selectpicker('refresh');
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
          console.log(respn,"testing");
          $('#CUST_STATE').html(respn).selectpicker('refresh');
        }
    });
  });
  $(document).on('change','#CUST_STATE',function(){
    var ccode = $('#CUST_COUNTRY').find('option:selected').val();
    var scode = $(this).val();
    $.ajax({
        url: '<?php echo base_url('/cityList')?>',
        type: "post",
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        data:{ccode:ccode,scode:scode},
        // dataType:'json',
        success:function(respn){
          console.log(respn,"testing");
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
      success:function(respn){
        var memData = respn[0];
        var idArray = ['RESV_MEMBER_TY','RESV_RATE_CLASS','RESV_RATE_CODE','RESV_ROOM_CLASS','RESV_FEATURE','RESV_PURPOSE_STAY'];
        $(respn).each(function(ind,data){
          var option = '<option value="">Select</option>';
          $.each(data,function(i,valu){
            option += '<option value="'+valu['CODE']+'">'+valu['DESCS']+'</option>';
          });
          $('#'+idArray[ind]).html(option);
          if(idArray[ind]=='RESV_RATE_CLASS'){
            $('#RESV_RATE_CATEGORY').html(option);
          }
        });
      }
    });
  }

  function compagnetFn(){
    $('#compnayAgentWindow').modal('show');
  }
</script>

<?=$this->endSection()?>