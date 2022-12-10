<?= $this->extend("Layout/AppView") ?>
<?= $this->section("contentRender") ?>
<?= $this->include('Layout/ErrorReport') ?>
<?= $this->include('Layout/SuccessReport') ?>
<?= $this->include('Layout/image_modal') ?>
<style>
	.Reservation,
	.Guest {
		display: none;
	}

	.flatpickr-wrapper {
		width: 100%;
	}

	#modalCenter {
		z-index: 1092 !important;
	}
	#Taskassignment_sheet_details .disabled{
		opacity: 0.5;
        background: rgb(147 158 170 / 45%);
	}
	 td.word-wrap{
		white-space: break-spaces;
    	word-break: break-all;
	}
	.float-right{
		float:right;
	}
	#image-popup{
		z-index : 1200 !important;
	}

</style>

<!-- Content wrapper -->
<div class="content-wrapper">
	<!-- Content -->

	<div class="container-xxl flex-grow-1 container-p-y">
		<h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Task Assignment /</span> Task Assignment Overview</h4>

		<!-- DataTable with Buttons -->
		<div class="card">
			<!-- <h5 class="card-header">Responsive Datatable</h5> -->
			<div class="container-fluid table-responsive" style="padding: 16px 16px 6px 16px">

				<form class="search-form mb-2" method="POST">
					<div class="border rounded p-3 mb-3">
						<div class="row g-3">
							<div class="col-md-4">
                            <label class="form-label"><b>Task Date:</b></label>
                                <input type="text" id="HKTAO_TASK_DATE" name="HKTAO_TASK_DATE" class="form-control dt-date" data-column="0" placeholder="dd-M-yyyy" value="" />
							</div>

							<div class="col-md-4">
								<label class="form-label"><b>Task Code</b></label>
								<select name="HKATO_TASK_CODE_SEARCH" class="select2 form-select" id="HKATO_TASK_CODE_SEARCH">
								</select>
							</div>

							<div class="col-md-4">
								<label class="form-label"><b>Created By</b></label>
								<select name="HKATO_CREATED_BY" class="select2 form-select" id="HKATO_CREATED_BY">
								<option value="">Select</option> 
								</select>
							</div>
							
							<div class="col-md-12">
							    <div class="pt-1 text-end">
									<button type="button" class="btn btn-primary submit-search-form">
										<i class='bx bx-search'></i> Search
									</button>

									<button type="button" class="btn btn-secondary clear-search-form">Clear</button>
								</div>
							</div>
						</div>
					</div>
				</form>

				<table id="dataTable_view" class="dt-responsive table table-striped display nowrap" style="width:100%">
					<thead>
						<tr>
						    <th class="all">Action</th>
                            <th >Task ID</th>
							<th class="all">Task Date</th>
							<th class="all">Task Codes</th>
							<th class="all">Created By</th>							
							<th class="all">Created On</th>							
							<!-- <th class="all">Auto</th> -->
							<th class="all">Total Sheets</th>
							<th class="all">Total Rooms</th>
							
                            
						</tr>
					</thead>
				</table>
			</div>
		</div>

		<!--/ Multilingual -->
	</div>

	  <!-- Modal Window -->

	  <div class="modal fade" id="popModalWindow" data-backdrop="static" data-keyboard="false"
        aria-lableledby="popModalWindowlable">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="popModalWindowlabel">Task Assignment</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
                </div>
                <div class="modal-body">
                <form id="submitForm" class="needs-validation" novalidate>
                
                       <input type="hidden" name="HKTAO_ID" id="HKTAO_ID" class="form-control" />
                        <div class="row g-3">     

						    <div class="col-md-6">
                                <label class="form-label"><b>Task Date *</b></label>
                                <input type="text" name="HKTAO_TASK_DATE" id="HKTAO_TASK_DATE" class="form-control bootstrap-maxlength dt-date"
                                 required />
                            </div>   
                            <div class="col-md-6">
                                <label class="form-label"><b>Task Code *</b></label>
                                <select name="HKATO_TASK_CODE" id="HKATO_TASK_CODE" class="select2 form-select"
                                        data-allow-clear="true"> 
										                                      
                                </select>                               
                            </div>                
                        </div>
						<div class="row g-3 pt-3"> 
						<div class="col-12 col-sm-6 col-lg-4">
                          <label class="switch switch-md">
                              <input id="HKATO_AUTO1" name="HKATO_AUTO" type="radio" 
                                  class="switch-input" value="0" checked="checked"/>
                              <span class="switch-toggle-slider">
                                  <span class="switch-on">
                                      <i class="bx bx-check"></i>
                                  </span>
                                  <span class="switch-off">
                                      <i class="bx bx-x"></i>
                                  </span>
                              </span>
                              <span class="switch-label"><b> New Task</b></span>
                          </label>
                          </div>
                          <!-- <div class="col-12 col-sm-6 col-lg-4">
                          <label class="switch switch-md">
                            <input id="HKATO_AUTO2" name="HKATO_AUTO" type="radio" 
                                class="switch-input"  value="1"/>
                            <span class="switch-toggle-slider">
                                <span class="switch-on">
                                    <i class="bx bx-check"></i>
                                </span>
                                <span class="switch-off">
                                    <i class="bx bx-x"></i>
                                </span>
                            </span>
                            <span class="switch-label"><b>Auto Task</b></span>
                        </label>
                          </div> -->
						</div>
                    
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="submitBtn" onClick="submitForm('submitForm')"
                        class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Window Task Asssignment shett -->
    <div class="modal fade" id="TaskAssignmentDetails" data-backdrop="static" data-keyboard="false"
        aria-labelledby="popModalWindowlabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="popModalWindowlabel">Task Assignment</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card-header border-bottom">
                        <ul class="nav nav-pills" role="tablist">
                            <li class="nav-item">
                                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#task-sheets" aria-controls="task-sheets"
                                    aria-selected="true">
                                    Task Sheets
                                </button>
                            </li>
                            <!-- <li class="nav-item">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#task-room"
                                    aria-controls="task-room" aria-selected="false" onclick="showRoomAssignment()">
                                    Assign Room
                                </button>
                            </li>
                            -->
                        </ul>

                    </div>
                    <div class="tab-content">

                        <div class="tab-pane fade show active" id="task-sheets" role="tabpanel">
                            <form id="tasksheet-submit-form" onSubmit="return false">
							<input type="hidden" name="HKAT_TASK_ID" id="HKAT_TASK_ID" class="form-control" />   
							<input type="hidden" name="TASK_ID" id="TASK_ID" class="form-control" />     
							<input type="hidden" name="HKAT_SHEET_NO" id="HKAT_SHEET_NO" class="form-control" />    

                           
								<div class="border rounded p-4 mb-3"> 
								<div class="row mb-3">
								<div class="col-md-12">
										<strong></strong>
									</div>
									<div class="col-md-4">
										<label for="HKAT_SHEET_NUMBER"
											class="col-form-label col-md-5"><b>TASK SHEET </b></label>
											<input type="number" name="HKAT_SHEET_NUMBER" id="HKAT_SHEET_NUMBER"
											class="form-control"  readonly/>
									</div>
									<div class="col-md-4">
										<label for="TASK_ASSIGNMENT_DATE"
											class="col-form-label col-md-5"><b>TASK DATE</b></label>
										<input type="text" name="TASK_ASSIGNMENT_DATE" 
											class="form-control" value="" readonly/>
									</div>
									<div class="col-md-4 ">
										<label for="TASK_CODE" class="col-form-label col-md-4"><b>TASK CODE
												</b></label>
										<input type="text" name="TASK_CODE" 
											class="form-control" readonly/>
									</div>									
								</div>
                                
								<div class="row g-3 mb-3">
									<div class="col-md-4 ">
										<label for="HKAT_ATTENDANT_ID" class="col-form-label col-md-4"><b>ATTENDANT ID
												*</b></label>
										<select id="HKAT_ATTENDANT_ID" name="HKAT_ATTENDANT_ID"
											class="select2 form-select form-select-lg"></select>
									</div>

									<div class="col-md-8">
										<label for="HKAT_SHEET_INSTRUCTIONS" class="col-form-label col-md-4"><b>TASK INSTRUCTIONS
												</b></label>
											<textarea  name="HKAT_SHEET_INSTRUCTIONS" id="HKAT_SHEET_INSTRUCTIONS"
											class="form-control" row="1"></textarea>

									</div>
								</div>
								

                                    <div class="row g-3 ">
                                        <div class="col-md-3 mb-3">
                                            <div class="col-md-8 ">
                                                <button type="button" class="btn btn-success save-tasksheet-detail">
                                                    <i class="fa-solid fa-floppy-disk"></i>&nbsp; Add
                                                </button>&nbsp;
                                            </div>
                                        </div>
                                    </div>
                               </div>
                                <div class="col-md-12">
                                    <div class="border rounded p-4 mb-3">                                        

                                        <div class="table-responsive text-nowrap">
                                            <table id="Taskassignment_sheet_details" class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
														<th></th>
														<th class="all">Action</th>
                                                        <th class="all">Sheet No</th>
														<th class="all">Attendant</th>
														<th class="all">Status</th>
                                                        <th class="all">Inspected Status</th>
														<th class="all">Task Instructions</th>
														<th class="all">Inspected On</th>
														
                                                        
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                        <br />
                                    </div>
                                </div>
                                
                                    <button type="button" class="btn btn-secondary float-right"
                                        data-bs-dismiss="modal" >Close</button>
                           
                                </form>
                                </div>
                                <!-- <div class="tab-pane fade" id="task-room" role="tabpanel">
							<form id="roomassign-submit-form" onSubmit="return false">
								
								<div class="border rounded p-4 mb-3"> 
								<div class="row mb-3">
								<div class="col-md-12">
										<strong></strong>
									</div>
								
									<div class="col-md-4">
										<label for="TASK_ASSIGNMENT_DATE"
											class="col-form-label col-md-5"><b>TASK DATE</b></label>
										<input type="text" name="TASK_ASSIGNMENT_DATE" 
											class="form-control" value="" readonly/>
									</div>
									<div class="col-md-4 ">
										<label for="TASK_CODE" class="col-form-label col-md-4"><b>TASK CODE
												</b></label>
										<input type="text" name="TASK_CODE" 
											class="form-control" readonly/>
									</div>									
								</div>
                                
								<div class="row g-3 mb-3">
									<div class="col-md-4 ">
										<label for="HKARM_TASK_SHEET_ID" class="col-form-label col-md-4"><b>Task Sheet
												*</b></label>
										<select id="HKARM_TASK_SHEET_ID" name="HKARM_TASK_SHEET_ID"
											class="select2 form-select form-select-lg"></select>
									</div>
									<div class="col-md-4 ">
										<label for="HKARM_ROOM_ID" class="col-form-label col-md-4"><b>Room
												*</b></label>
										<select id="HKARM_ROOM_ID" name="HKARM_ROOM_ID"
											class="select2 form-select form-select-lg"></select>
									</div>
									<div class="col-md-4 ">
										<label for="HKARM_CREDITS" class="col-form-label col-md-4"><b>Room Credits
												</b></label>
												<input type="number" name="HKARM_CREDITS"  id="HKARM_CREDITS"
											class="form-control" min="1"/>
									</div>

									<div class="col-md-12">
										<label for="HKARM_INSTRUCTIONS" class="col-form-label col-md-4"><b>ROOM INSTRUCTIONS
												</b></label>
											<textarea  name="HKARM_INSTRUCTIONS" id="HKARM_INSTRUCTIONS"
											class="form-control" row="1"></textarea>

									</div>
								</div>
								

                                    <div class="row g-3 ">
                                        <div class="col-md-3 mb-3">
                                            <div class="col-md-8 float-right">
                                                <button type="button" class="btn btn-success save-taskroom-detail">
                                                    <i class="fa-solid fa-floppy-disk"></i>&nbsp; Add
                                                </button>&nbsp;
                                            </div>
                                        </div>
                                    </div>
                               </div>
                                <div class="col-md-12">
                                    <div class="border rounded p-4 mb-3">                                        

                                        <div class="table-responsive text-nowrap">
                                            <table id="Taskassignment_room_details" class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
														<th></th>
                                                        <th class="all">Sheet No</th>														
														<th class="all">Room</th>
														<th class="all">Room Credits</th>
                                                        <th class="all">Room Instructions</th>
                                                        <th class="all">Action</th>
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
                                </form>
                                </div> -->
							

                        </div>
                    
                </div>
            </div>
        </div>
    </div>

    <!-- /Modal window -->


    <!-- Modal Window  Assign Rooms -->

	<div class="modal fade" id="roomAssignmentModal" data-backdrop="static" data-keyboard="false"
        aria-lableledby="popModalWindowlable">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="popModalWindowlabel">Room Assignment</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close" data-bs-toggle="modal" data-bs-target="#TaskAssignmentDetails"></button>
                </div>
                <div class="modal-body">
                <form id="roomassign-submit-form" onSubmit="return false">								
					<div class="border rounded p-4 mb-3"> 
					<div class="row mb-3">
					<div class="col-md-12">
							<strong></strong>
						</div>
					
						<div class="col-md-4">
							<label for="TASK_ASSIGNMENT_DATE"
								class="col-form-label col-md-5"><b>TASK DATE</b></label>
							<input type="text" name="TASK_ASSIGNMENT_DATE" 
								class="form-control" value="" readonly/>
						</div>
						<div class="col-md-4 ">
							<label for="TASK_CODE" class="col-form-label col-md-4"><b>TASK CODE
									</b></label>
							<input type="text" name="TASK_CODE" 
								class="form-control" readonly/>
						</div>	
						<div class="col-md-4 ">
							<label for="HKARM_TASK_SHEET_ID" class="col-form-label col-md-4"><b>Task Sheet
									</b></label>
							<input type="text" name="HKARM_TASK_SHEET_ID" id="HKARM_TASK_SHEET_ID"
								class="form-control" readonly/>
						</div>								
					</div>
					
					<div class="row g-3 mb-3">
						
						<div class="col-md-4 ">
							<label for="HKARM_ROOM_ID" class="col-form-label col-md-4"><b>Room
									*</b></label>
							<select id="HKARM_ROOM_ID" name="HKARM_ROOM_ID"
								class="select2 form-select form-select-lg"></select>
						</div>
						<div class="col-md-4 ">
							<label for="HKARM_CREDITS" class="col-form-label col-md-4"><b>Room Credits
									</b></label>
									<input type="number" name="HKARM_CREDITS"  id="HKARM_CREDITS"
								class="form-control" min="1"/>
						</div>

						<div class="col-md-4">
							<label for="HKARM_INSTRUCTIONS" class="col-form-label col-md-12"><b>ROOM INSTRUCTIONS
									</b></label>
								<textarea  name="HKARM_INSTRUCTIONS" id="HKARM_INSTRUCTIONS"
								class="form-control" row="1"></textarea>

						</div>
					</div>
					

						<div class="row g-3 ">
							<div class="col-md-3 mb-3">
								<div class="col-md-8">
									<button type="button" class="btn btn-success save-taskroom-detail">
										<i class="fa-solid fa-floppy-disk"></i>&nbsp; Add
									</button>&nbsp;
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="border rounded p-4 mb-3">                                        

							<div class="table-responsive text-nowrap">
								<table id="Taskassignment_room_details" class="table table-bordered table-hover">
									<thead>
										<tr><th>ID</th>
											<th class="all">Room</th>
											<th class="all">Room Credits</th>
											<th class="all">Room Instructions</th>
											<th class="all">Action</th>
										</tr>
									</thead>
								</table>
							</div>
							<br />
						</div>
					</div>
					                         
					</form>
                </div>
                <div class="modal-footer">				
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#TaskAssignmentDetails">Close</button>
                   
                </div>
            </div>
        </div>
    </div>


	<!-- Modal Window  View Rooms -->

	<div class="modal fade" id="roomViewModal" data-backdrop="static" data-keyboard="false"
        aria-lableledby="popModalWindowlable">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="popModalWindowlabel">Rooms</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close" data-bs-toggle="modal" data-bs-target="#TaskAssignmentDetails"></button>
                </div>
                <div class="modal-body">
                							
					<div class="border rounded p-4 mb-3"> 
					<div class="row mb-3">
					<div class="col-md-12">
							<strong></strong>
						</div>
					
						<div class="col-md-4">
							<label for="TASK_ASSIGNMENT_DATE"
								class="col-form-label col-md-5"><b>TASK DATE</b></label>
							<input type="text" name="TASK_ASSIGNMENT_DATE" 
								class="form-control" value="" readonly/>
						</div>
						<div class="col-md-4 ">
							<label for="TASK_CODE" class="col-form-label col-md-4"><b>TASK CODE
									</b></label>
							<input type="text" name="TASK_CODE" 
								class="form-control" readonly/>
						</div>	
						<div class="col-md-4 ">
							<label for="HKARM_TASK_SHEET_ID" class="col-form-label col-md-4"><b>Task Sheet
									</b></label>
							<input type="text" name="HKARM_TASK_SHEET_ID" 
								class="form-control" readonly/>
						</div>								
					</div>
					
					</div>
					<div class="col-md-12">
						<div class="border rounded p-4 mb-3">  
							<div class="table-responsive text-nowrap">
								<table id="Taskassignment_room_view" class="table table-bordered table-hover">
									<thead>
										<tr><th>ID</th>
											<th class="all">Room</th>
											<th class="all">Room Credits</th>
											<th class="all">Room Instructions</th>
											<th class="all">Started At</th>
											<th class="all">Completed At</th>
											<th class="all">Inspected By</th>	
											<th class="all">Inspected Time</th>
											<th class="all">Comments</th>
											<th class="all">Total Time(HH:MM)</th>
										</tr>
									</thead>
								</table>
							</div>
							<br />
						</div>
					</div>
					   
                </div>
                <div class="modal-footer">				
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#TaskAssignmentDetails">Close</button>
                   
                </div>
            </div>
        </div>
    </div>

	<div class="modal fade" id="comment-modal" tabindex="-1" aria-hidden="true" style="z-index: 1100;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Comments</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                   
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

	<!-- / Content -->
	<div class="content-backdrop fade"></div>
</div>

<!-- Content wrapper -->
<script>
	var search_form_data = null;
	var compAgntMode = '';
	var linkMode = '';

	var rooms = [];
	$(document).ready(function() {
		var rooms = [];
		taskCodelist();
		usersList();

       $('.dt-date').datepicker({
			format: 'dd-M-yyyy',
			autoclose: true,
			startDate: '-0m',
			onSelect: function() {
				$(this).change();
			},        
    	});
//.datepicker("setDate", new Date())
		function usersList() {
			$.ajax({
				url: '<?php echo base_url('/usersList') ?>',
				type: "GET",
				headers: {
					'X-Requested-With': 'XMLHttpRequest'
				},
				async: false,
				success: function(respn) {
					$('#HKATO_CREATED_BY').html('<option value="">Select </option>');
					$('#HKATO_CREATED_BY').append(respn);
				}
			});
		}

		


		linkMode = 'EX';

		var dt_taskassignment_table = $('#dataTable_view'),
			select2 = $('.select2'),
			userView = '',	
            statusObj = {
				1: {
					title: 'YES',
					class: 'bg-label-success'
				},
				0: {
					title: 'NO',
					class: 'bg-label-secondary'
				}
			};
            

		if (dt_taskassignment_table.length) {
			var dt_taskassignment = dt_taskassignment_table.DataTable({
				'processing': true,
				'serverSide': true,
				'serverMethod': 'post',
				'ajax': {
					'url': '<?php echo base_url('/TaskAssignmentView') ?>',
					'type': 'POST',
					'data': function(d) {
						d['HKTAO_TASK_DATE'] = $('.search-form [name="HKTAO_TASK_DATE"]').val();
						d['HKATO_TASK_CODE_SEARCH'] = $('.search-form [name="HKATO_TASK_CODE_SEARCH"]').val();
						d['HKATO_CREATED_BY'] = $('.search-form [name="HKATO_CREATED_BY"]').val();
					},
				},
				columns: [{
						data: null,
						className: "text-center",
						"orderable": false,
						render: function(data, type, full, meta) {
							
								var taskAssignButtons = 
									'<div class="d-inline-block flxy_option_view dropend">' +
									'<a href="javascript:;" class="btn btn-sm btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></a>' +
                                    '<ul class="dropdown-menu dropdown-menu-end">' +									
									'<li><a href="javascript:;" data-sysid="' + full['HKTAO_ID'] +
									'" data-row-ind="' + meta.row + '"  data-task_date="' + full['HKTAO_TASK_DATE']  + '" data-task_code="' + full['HKT_CODE']  + '" data-task_id="' + full['HKT_ID']  + '" data-task_desc="' + full['HKT_DESCRIPTION']  + '" class="dropdown-item viewTaskAssignment text-success"><i class="fa-solid fa-align-justify"></i> Add Sheet</a></li>'
									;

									// '<div class="dropdown-divider" ></div><li><a href="javascript:;" data-sysid="' + full['HKTAO_ID'] +
									// '" class="dropdown-item text-danger deleteTaskAssignment"><i class="fas fa-trash"></i> Delete</a></li>'
                                    taskAssignButtons += '</ul>' +
									'</div>';
								return taskAssignButtons;							
						}
					},
					{
						data: 'HKTAO_ID',
						visible: false
					},
					{
						data: 'HKTAO_TASK_DATE',
                        render: function(data, type, full, meta) {
							if (full['HKTAO_TASK_DATE'] != '') {
								var HKTAO_TASK_DATE = full['HKTAO_TASK_DATE'].split(".");
								if (HKTAO_TASK_DATE[0] == '1900-01-01') {
									return '';
								} else
									return HKTAO_TASK_DATE[0];
							}
						}	
					},
					{
						data: 'HKT_CODE'						
					},
					{
						data: 'USR_FIRST_NAME',	
                        render: function(data, type, full, meta) {
							if(full['USR_FIRST_NAME'] != null );
							    return full['USR_FIRST_NAME']+' '+full['USR_LAST_NAME'];

						}					
					},
					{
						data: 'HKATO_CREATED_AT',
                        render: function(data, type, full, meta) {
							if (full['HKATO_CREATED_AT'] != '') {
								var HKATO_CREATED_AT = full['HKATO_CREATED_AT'].split(" ");
								if (HKATO_CREATED_AT[0] == '1900-01-01 00:00:00') {
									return '';
								} else
									return HKATO_CREATED_AT[0];
							}
						}	
						
					},
					// {
					// 	data: 'HKATO_AUTO',
					// 	render: function(data, type, full, meta) {
					// 		var $status = full['HKATO_AUTO'];
					// 		return '<span class="badge ' + statusObj[$status].class + '">' + statusObj[$status].title + '</span>';
					// 	}

					// },
					{
						data: 'HKATO_TOTAL_SHEETS',
						render: function(data, type, full, meta) {
							if(full['HKATO_TOTAL_SHEETS'] > 0 ){
							var HKATO_TOTAL_SHEETS = 	

							'<a href="javascript:;" data-sysid="' + full['HKTAO_ID'] +
									'" data-row-ind="' + meta.row + '"  data-task_date="' + full['HKTAO_TASK_DATE']  + '" data-task_code="' + full['HKT_CODE']  + '" data-task_id="' + full['HKT_ID']  + '" data-task_desc="' + full['HKT_DESCRIPTION']  + '" class="viewTaskAssignment btn btn-outline-info">'+full['HKATO_TOTAL_SHEETS']+' </a>';

								return HKATO_TOTAL_SHEETS;	
							}else{
								return '0';
							}
						}
						
					},
					{
						data: 'HKATO_TOTAL_ROOMS',
						render: function(data, type, full, meta) {
							if(full['HKATO_TOTAL_ROOMS'] > 0 ){
								var HKATO_TOTAL_ROOMS = 						

								'<a href="javascript:;" data-sysid="' + full['HKTAO_ID'] +
									'" data-row-ind="' + meta.row + '"  data-task_date="' + full['HKTAO_TASK_DATE']  + '" data-task_code="' + full['HKT_CODE']  + '" data-task_id="' + full['HKT_ID']  + '" data-task_desc="' + full['HKT_DESCRIPTION']  + '" class="viewTaskAssignment btn btn-outline-info" data-room-view="1">'+full['HKATO_TOTAL_ROOMS']+' </a>';

									return HKATO_TOTAL_ROOMS;	
							}
							else{
								return '0';	
							}
						}
						
					},					
					
                    			
					
				
				],
				columnDefs: [{
						width: "7%"
						
					}, {
						width: "15%"
					}, {
						width: "15%"
					}, {
						width: "18%"
					}, {
						width: "16%"
					},
					{
						width: "16%"
					},
					{
						width: "10%"
					},
					{
						width: "10%"
					},
                   
					
				],
				"order": [
					[1, "desc"]
				],
				destroy: true,
				dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
				responsive: {
					details: {
						display: $.fn.dataTable.Responsive.display.modal({
							header: function(row) {
								var data = row.data();
								return 'Details';
							}
						}),
						type: 'column',
						renderer: function(api, rowIdx, columns) {
							var data = $.map(columns, function(col, i) {
								return col.title !==
									'' // ? Do not show row in modal popup if title is blank (for check box)
									?
									'<tr data-dt-row="' +
									col.rowIndex +
									'" data-dt-column="' +
									col.columnIndex +
									'">' +
									'<td>' +
									col.title +
									':' +
									'</td> ' +
									'<td>' +
									col.data +
									'</td>' +
									'</tr>' :
									'';
							}).join('');

							return data ? $('<table class="table"/><tbody />').append(data) : false;
						}
					}
				}

			});
		}
		$("#dataTable_view_wrapper .row:first").before(
			'<div class="row flxi_pad_view"><div class="col-md-3 ps-0"><button type="button" class="btn btn-primary" onClick="addForm()"><i class="fa-solid fa-plus fa-lg"></i> Add</button></div></div>'
		);
	});


	function taskCodelist() {
		$.ajax({

			url: '<?php echo base_url('/taskcodeList') ?>',
			type: "GET",
			headers: {
				'X-Requested-With': 'XMLHttpRequest'
			},
			async: false,
			success: function(respn) {
				$('#HKATO_TASK_CODE').html(respn);
				$('#HKATO_TASK_CODE_SEARCH').html(respn);
				
			}
		});
	}



	// Show Add Task Assigment
	function addForm() {
		// $("#HKTAO_TASK_DATE").val(new Date())
		$('.dt-date').datepicker('update','');
		$('#submitForm').not('[type="text"],[type="radio"],[type="checkbox"]').val('').prop('checked', false).prop('selected', false);
		$('.select2').val(null).trigger('change');		
		$('#submitBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
		$('#popModalWindowlabel').html('New Task Assigment');
		$("#HKTAO_TASK_DATE").val('');
		taskCodelist();
		$('#popModalWindow').modal('show');
	}


	function submitForm(id) {

		hideModalAlerts();
		$('#loader_flex_bg').show();
		
		var formSerialization = $('#' + id).serializeArray();

		var url = '<?php echo base_url('/insertTaskAssignment') ?>';
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
				if (response != '1') {
					var ERROR = respn['RESPONSE']['ERROR'];
					var mcontent = '';
					$.each(ERROR, function(ind, data) {
						mcontent += '<li>' + data + '</li>';
					});
					showModalAlert('error', mcontent);
					$('#loader_flex_bg').hide();
				} else {
					var alertText = $('#HKTAO_ID').val() == '' ? '<li>The task assignment has been created</li>' : '<li>The task assignment has been updated</li>';
					showModalAlert('success', alertText);
					$('#popModalWindow').modal('hide');
					$('#loader_flex_bg').hide();
					$('#dataTable_view').dataTable().fnDraw();
				}
			}
		});
	}

	$(document).on('click', '.submit-search-form', function() {
		search_form_data = new FormData($(`form.search-form`)[0]);
		$('#dataTable_view').dataTable().fnDraw();
	});

	$(document).on('click', '.clear-search-form', function() {
		$(`.search-form input`).val('');
		$(`.search-form textarea`).val('');
		$(`.search-form select`).val('').trigger('change');
		$('#dataTable_view').dataTable().fnDraw();
	});

	function attendantList() {
		$.ajax({
			url: '<?php echo base_url('/attendantList') ?>',
			type: "POST",
			headers: {
				'X-Requested-With': 'XMLHttpRequest'
			},
			async: false,
			success: function(respn) {
				$('#HKAT_ATTENDANT_ID').html('<option value="">Select </option>');
				$('#HKAT_ATTENDANT_ID').append(respn);
			}
		});
	}

	$(document).on('click', '.viewTaskAssignment', function() {
		$("#HKAT_TASK_ID").val($(this).data('sysid'));
		$("#TASK_ID").val($(this).data('task_id'));
		var HKAT_TASK_ID = $(this).data('sysid');
		
		$('input[name="TASK_ASSIGNMENT_DATE"]').val($(this).data('task_date'));
		$('input[name="TASK_CODE"]').val($(this).data('task_code')+' - '+$(this).data('task_desc'));
		attendantList();
		showTasksheetTable($(this).data('sysid'));
		$.ajax({
			url: '<?php echo base_url('/getLastSheetNo') ?>',
			type: "POST",
			data:{HKAT_TASK_ID: $(this).data('sysid')},
			headers: {
				'X-Requested-With': 'XMLHttpRequest'
			},
			async: false,
			success: function(respn) {
				$("#HKAT_SHEET_NUMBER").val(respn);
				$("#HKAT_SHEET_NO").val(respn);
				
			}
		});

		// showTasksheetTable(HKAT_TASK_ID);
		// showRoomAssignment();
		
		 //Make First Tab active
		 if($(this).data('room-view') == 1)
         $('[data-bs-target="#task-room"]').trigger('click');
		 else
		 $('[data-bs-target="#task-sheets"]').trigger('click');
		
		// $(".second-tab").removeclass('active')

		$('#TaskAssignmentDetails').modal('show');
	});

	function showTaskRoom() {
		
		console.log('sdflsflksd')
	}

	function showTasksheetTable(HKTAO_ID)
	{
		
		$('#Taskassignment_sheet_details').DataTable().destroy();
		$('#Taskassignment_sheet_details').DataTable({
        'processing': true,
        async: false,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': '<?php echo base_url('/showTaskSheets') ?>',
            'data': {
                "HKTAO_ID": HKTAO_ID
            }
        },
        'columns': [{
                data: 'HKAT_ID',
                'visible': false
            },
			{   
				data: null,
                className: "text-center",
                "orderable": false,
                render: function(data, type, row, meta) {
					var disabled = '';
					if(data['STATS'] != null && data['STATS'] != 1){
						disabled = 'disabled';
					}
					var taskAssignButtons = 
						'<div class="d-inline-block flxy_option_view dropend">' +
						'<a href="javascript:;" class="btn btn-sm btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></a>' +
						'<ul class="dropdown-menu dropdown-menu-end">' +									
						'<li><a href="javascript:;" data-task_id="' + data['HKAT_TASK_ID'] +
						'" data-tasksheet_id="' + data['HKAT_TASK_SHEET_ID'] +
						'" data-sysid="' + data['HKAT_ID'] +
						'" class="dropdown-item text-primary assign_rooms '+disabled+'" data-bs-toggle="modal" data-bs-target="#roomAssignmentModal"><i class="fa-solid fa-align-justify "></i> Assign Rooms</a></li><div class="dropdown-divider" ></div><li><a href="javascript:;" data-task_id="' + data['HKAT_TASK_ID'] +
						'" data-tasksheet_id="' + data['HKAT_TASK_SHEET_ID'] +
						'" data-sysid="' + data['HKAT_ID'] +
						'" class="dropdown-item text-primary view_rooms" data-bs-toggle="modal" data-bs-target="#roomViewModal"><i class="fa-solid fa-eye "></i> View Rooms</a></li><div class="dropdown-divider" ></div><li><a href="javascript:;" data-tasksheet_id="' + data['HKAT_TASK_SHEET_ID'] +
						'" data-sysid="' + data['HKAT_ID'] +
						'" class="dropdown-item text-info printTaskAssignment"><i class="fa fa-print" aria-hidden="true"></i> Print</a></li><div class="dropdown-divider" ></div><li><a href="javascript:;" data-tasksheet_id="' + data['HKAT_TASK_SHEET_ID'] +
						'" data-sysid="' + data['HKAT_ID'] +
						'" class="dropdown-item text-danger delete_sheet_record '+disabled+'"><i class="fa-solid fa-ban"></i>  Delete</a></li>'
						taskAssignButtons += '</ul>' +
						'</div>';
						return taskAssignButtons;	

                }
            },
			
			{

                data: 'HKAT_TASK_SHEET_ID',
                render: function(data, type, full, meta) {
                    if (full['HKAT_TASK_SHEET_ID'] != null)
                        return full['HKAT_TASK_SHEET_ID'];
                    else
                        return '';
                }
            },
            
            {
                data: 'USR_FIRST_NAME',
				render: function(data, type, full, meta) {
                    if (full['USR_FIRST_NAME'] != null)
                        return full['USR_FIRST_NAME']+' '+full['USR_LAST_NAME'];
                    else
                        return '';
                }
            },
            {
                data: 'STATUSCODE',
				className: "text-center taskStatusCol",
				render: function(data, type, full, meta) {
				
						if( full['STATUSCODE'] != null ){
							var match = full['STATS'].split(',')
							var status_text = full['STATUSCODE'].split(',')
							for (var a in match)
							{
								var status_id = match[a];
								//if(status_id == '1'){
									
									for (var b in status_text)
									{
										var statText = status_text[b];
										var statButton = showTaskStatusChange(status_id,statText,full['HKAT_ID'],'3');
									    return statButton;
									}


								//}
								
							}
						}
						else if(full['STATUSCODE'] == null ){
								return '<a href="javascript:;" data-task_id="' + full['HKAT_TASK_ID'] +
							'" data-tasksheet_id="' + full['HKAT_TASK_SHEET_ID'] +
							'" data-sysid="' + full['HKAT_ID'] +
							'" class="dropdown-item text-primary assign_rooms" data-bs-toggle="modal" data-bs-target="#roomAssignmentModal"><i class="fa-solid fa-align-justify"></i> Assign Rooms</a>'
						}					
				    
                }
				
            },
			
			{
                data: 'SUPER_STATUSCODE',
				className: "text-center supertaskStatusCol",
				render: function(data, type, full, meta) {			
					
					if( full['SUPER_STATUSCODE'] != null){
						var match       = full['INSP_STATS'].split(',');
						var status_text = full['SUPER_STATUSCODE'].split(',');
						for (var a in match)
						{
							var status_id = match[a];
							// if(status_id == '5'){
								for (var b in status_text)
								{
									var statText = status_text[b];
									var statButton = showSuperTaskStatusChange(status_id,statText,full['HKAT_ID'],'5',full['COMPLETION_TIME']);
									return statButton;
								}
							//}
						}
					}
					else if(full['SUPER_STATUSCODE'] == null ){
							return '<a href="javascript:;" data-task_id="' + full['HKAT_TASK_ID'] +
						'" data-tasksheet_id="' + full['HKAT_TASK_SHEET_ID'] +
						'" data-sysid="' + full['HKAT_ID'] +
						'" class="dropdown-item text-primary assign_rooms" data-bs-toggle="modal" data-bs-target="#roomAssignmentModal"><i class="fa-solid fa-align-justify"></i> Assign Rooms</a>'
						}
                    
                }
            },
            {
                data: 'HKAT_INSTRUCTIONS',
				
            },
			{
                data: 'INSPECTED_DATETIME',
				render: function(data, type, full, meta) {	
				if (full['INSPECTED_DATETIME'] == null )
                        return '';
                    else if (full['STATS'] == 2 && full['INSPECTED_DATETIME'] != '1900-01-01 00:00:00.000') {
                        var INSPECTED_DATETIME = full['INSPECTED_DATETIME'].split('.');
                        return (INSPECTED_DATETIME[0]);
                    } else
                        return "";
				}
            },
			
			
           

        ],
		columnDefs: [

			{
				data: 'HKAT_INSTRUCTIONS',
				width: "10%",
				targets: 6,
				class:'word-wrap'
				},
		],
        "order": [
            [0, "asc"]
        ],
        'createdRow': function(row, data, dataIndex) {
            $(row).attr('data-sys_id', data['HKAT_ID']);
			$(row).attr('data-task_id', data['HKAT_TASK_ID']);
			$(row).addClass('taskRow' + data['HKAT_ID']);
        },
        destroy: true,
        "ordering": true,
        "searching": false,
        autowidth: true,
        responsive: true
    });
	}

	$(document).on('click', '.save-tasksheet-detail', function() {
		var task_id      = $("#TASK_ID").val();
		var overview_id  = $("#HKAT_TASK_ID").val();
		var tasksheet_no = $("#HKAT_SHEET_NO").val();
		var attendant_id = $("#HKAT_ATTENDANT_ID").val();
		var task_date    = $('input[name="TASK_ASSIGNMENT_DATE"]').val();
		var instructions = $("#HKAT_SHEET_INSTRUCTIONS").val();
		var url = '<?php echo base_url('/insertTaskAssignmentSheet') ?>';
		$.ajax({
			url: url,
			type: "post",
			data: {task_id:task_id,tasksheet_no:tasksheet_no,attendant_id:attendant_id,instructions:instructions,overview_id:overview_id,task_date:task_date},
			headers: {
				'X-Requested-With': 'XMLHttpRequest'
			},
			dataType: 'json',
			success: function(respn) {
				var response = respn['SUCCESS'];
				if(response == '-1'){
					var ERROR = respn['RESPONSE']['ERROR'];
					var mcontent = '';					
						mcontent += '<li>' + ERROR + '</li>';
				
					showModalAlert('error', mcontent);
					$('#loader_flex_bg').hide();
				}
				else if (response != '1') {
					var ERROR = respn['RESPONSE']['ERROR'];
					var mcontent = '';
					$.each(ERROR, function(ind, data) {
						mcontent += '<li>' + data + '</li>';
					});
					showModalAlert('error', mcontent);
					$('#loader_flex_bg').hide();
				} else {
					$("#HKAT_SHEET_NO").val(respn['RESPONSE']['OUTPUT']);
					$("#HKAT_SHEET_NUMBER").val(respn['RESPONSE']['OUTPUT']);					
					
					var alertText =  '<li>The task assignment sheet has been created</li>';
					showModalAlert('success', alertText);
					$('#loader_flex_bg').hide();
					$('#Taskassignment_sheet_details').dataTable().fnDraw();
				}
				    $("#HKAT_ATTENDANT_ID").val(null).trigger('change');
					$("#HKAT_SHEET_INSTRUCTIONS").val('');
			}
		});

	});


	$(document).on('click', '.delete_sheet_record', function() {
		hideModalAlerts();

		var tasksheet_id = $(this).data('tasksheet_id');
		var task_id = $(this).data('task_id');
		var sys_id = $(this).data('sysid');
		bootbox.confirm({
			message: "Are you sure you want to delete this record?",
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
						url: '<?php echo base_url('/deleteTaskAssignmentSheet') ?>',
						type: "post",
						data: {
							tasksheet_id: tasksheet_id, task_id: task_id,sys_id:sys_id
						},
						headers: {
							'X-Requested-With': 'XMLHttpRequest'
						},
						dataType: 'json',
						success: function(respn) {
							showModalAlert('warning',
							'<li>The tasksheet has been deleted</li>');
							$("#HKAT_SHEET_NO").val(respn['RESPONSE']['OUTPUT']);
							$("#HKAT_SHEET_NUMBER").val(respn['RESPONSE']['OUTPUT']);	
							$('#Taskassignment_sheet_details').dataTable().fnDraw();
						}
					});
				}
			}
		});
	});


	// function showRoomAssignment()
	// {
	// 	var task_id      = $("#HKAT_TASK_ID").val();

	// 	getTaskSheets(task_id);
	// 	getRooms();
	// 	$('#Taskassignment_room_details').DataTable({
    //     'processing': true,
    //     async: false,
    //     'serverSide': true,
    //     'serverMethod': 'post',
    //     'ajax': {
    //         'url': '<?php //echo base_url('/showTaskAssignedRooms') ?>',
    //         'data': {
    //             "HKTAO_ID": task_id
    //         }
    //     },
    //     'columns': [{
    //             data: 'HKARM_ID',
    //             'visible': false
    //         },            
           
	// 		{
    //             data: 'HKARM_ROOM_ID',
	// 			render: function(data, type, row, meta) {
	// 				if(row['RM_NO'] != '')
    //                		return row['RM_NO']+' - '+row['RM_DESC'];
	// 				else
	// 				    return '';
    //             }
				

    //         },
    //         {
    //             data: 'HKARM_CREDITS'
    //         },
			
    //         {
    //             data: 'HKARM_INSTRUCTIONS'
    //         },

	// 		{
    //             data: null,
    //             className: "text-center",
    //             "orderable": false,
    //             render: function(data, type, row, meta) {
    //                 return (
    //                     '<a href="javascript:;" data-assignroom_id="' + data['HKARM_ID'] +
    //                     '" data-task_id="' + task_id +
    //                     '" class="dropdown-item text-danger delete_room_record"><i class="fa-solid fa-ban"></i> Delete</a>'
    //                 );
    //             }
    //         },
			
           

    //     ],
    //     "order": [
    //         [0, "asc"]
    //     ],
    //     'createdRow': function(row, data, dataIndex) {
    //         $(row).attr('data-tasksheet_id', data['HKAT_ID']);
	// 		$(row).attr('data-task_id', data['HKAT_TASK_ID']);
    //     },
    //     destroy: true,
    //     "ordering": true,
    //     "searching": false,
    //     autowidth: true,
    //     responsive: true
    // });
	// }

	function getTaskSheets(task_id){

		$.ajax({
			url: '<?php echo base_url('/taskSheetList') ?>',
			type: "POST",
			data:{task_id:task_id},
			headers: {
				'X-Requested-With': 'XMLHttpRequest'
			},
			async: false,
			success: function(respn) {
				$('#HKARM_TASK_SHEET_ID').html('<option value="">Select </option>');
				$('#HKARM_TASK_SHEET_ID').append(respn);
			}
		});
	}


	function getRooms(){
		$.ajax({
			url: '<?php echo base_url('/taskRoomList') ?>',
			type: "POST",
			headers: {
				'X-Requested-With': 'XMLHttpRequest'
			},
			async: false,
			success: function(respn) {
				$('#HKARM_ROOM_ID').html('<option value="">Select </option>');
				$('#HKARM_ROOM_ID').append(respn);
			}
		});
		
	}
	


	
	$(document).on('click', '.save-taskroom-detail', function() {
		var TASK_CODE              = $("#TASK_ID").val();
		var HKAT_TASK_ID         = $("#HKAT_TASK_ID").val();
		var HKARM_TASK_SHEET_ID  = $("#HKARM_TASK_SHEET_ID").val();
		var HKARM_ROOM_ID        = $("#HKARM_ROOM_ID").val();
		var HKARM_CREDITS        = $("#HKARM_CREDITS").val();
		var HKARM_INSTRUCTIONS   = $("#HKARM_INSTRUCTIONS").val();
		var TASK_DATE            = $('input[name="TASK_ASSIGNMENT_DATE"]').val();		

		room_exists = checkRoomAlreadyAssigned(TASK_CODE,HKAT_TASK_ID,HKARM_ROOM_ID,TASK_DATE);
		
		if(room_exists == null){
			
			var url = '<?php echo base_url('/insertTaskAssignmentRoom') ?>';
			$.ajax({
				url: url,
				type: "post",
				data: {TASK_ID:TASK_CODE,HKAT_TASK_ID:HKAT_TASK_ID,HKARM_TASK_SHEET_ID:HKARM_TASK_SHEET_ID,HKARM_ROOM_ID:HKARM_ROOM_ID,HKARM_CREDITS:HKARM_CREDITS,HKARM_INSTRUCTIONS:HKARM_INSTRUCTIONS},
				headers: {
					'X-Requested-With': 'XMLHttpRequest'
				},
				dataType: 'json',
				success: function(respn) {
					var response = respn['SUCCESS'];
					if (response != '1') {
						var ERROR = respn['RESPONSE']['ERROR'];
						var mcontent = '';
						$.each(ERROR, function(ind, data) {
							mcontent += '<li>' + data + '</li>';
						});
						showModalAlert('error', mcontent);
						$('#loader_flex_bg').hide();
					} else {								
						
						$("#HKARM_ROOM_ID").val(null).trigger('change');
						$("#HKARM_CREDITS").val('');
						$("#HKARM_INSTRUCTIONS").val('');
						var alertText =  '<li>The room has been assigned</li>';
						showModalAlert('success', alertText);
						$('#loader_flex_bg').hide();
						
						$('#Taskassignment_room_details').dataTable().fnDraw();
						$('#Taskassignment_sheet_details').dataTable().fnDraw();
					}
				}
			});
		}
		else return;

	});



	function checkRoomAlreadyAssigned(TASK_CODE, HKAT_TASK_ID,HKARM_ROOM_ID,TASK_DATE) {
		$.ajax({
			url: '<?php echo base_url('/checkRoomAlreadyAssigned') ?>',
			type: 'POST',
			async: false,
			headers: {
				'X-Requested-With': 'XMLHttpRequest'
			},
			data: {
					TASK_CODE: TASK_CODE,
					HKARM_ID: HKARM_ROOM_ID,
					HKAT_TASK_ID: HKAT_TASK_ID,
					TASK_DATE: TASK_DATE,
				},
			dataType: 'json'

		}).done(function(response) {
			if(response['RESPONSE']['OUTPUT'] != '' ){
				var alertText =  '<li>Room is already assigned</li>';
				showModalAlert('warning', alertText);
				$("#HKARM_ROOM_ID").val(null).trigger('change');
				$("#HKARM_CREDITS").val('');
				$("#HKARM_INSTRUCTIONS").val('');
				ret_val = 1;
			}
			else{
				ret_val = null;
			}
		}).fail(function(jqXHR, textStatus, errorThrown) {
			ret_val = null;
		});
		return ret_val;
	}


	$(document).on('click', '.delete_room_record', function() {
		hideModalAlerts();

		var assignroom_id = $(this).data('assignroom_id');
        var taskid = $(this).data('task_id');
		rooms.splice(rooms.indexOf(assignroom_id), 1);  //deleting
		bootbox.confirm({
			message: "Are you sure you want to delete this record?",
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
						url: '<?php echo base_url('/deleteTaskAssignmentRoom') ?>',
						type: "post",
						data: {
							HKARM_ID: assignroom_id,
                            HKAT_TASK_ID: taskid,
						},
						headers: {
							'X-Requested-With': 'XMLHttpRequest'
						},
						dataType: 'json',
						success: function(respn) {
							showModalAlert('warning',
							'<li>The room assigned has been deleted</li>');								
							$('#Taskassignment_room_details').dataTable().fnDraw();
						}
					});
				}
			}
		});
	});

	$(document).on('click', '.assign_rooms', function() {
		hideModalAlerts();
		getRooms();
		$('input[name="HKARM_TASK_SHEET_ID"]').val($(this).data('tasksheet_id'));
		HKARM_TASK_SHEET_ID = $(this).data('tasksheet_id');
		var task_id      = $("#HKAT_TASK_ID").val();
		
		$('#Taskassignment_room_details').DataTable().destroy();
		$('#Taskassignment_room_details').DataTable({
		'processing': true,
		async: false,
		'serverSide': true,
		'serverMethod': 'post',
		'ajax': {
			'url': '<?php echo base_url('/showTaskAssignedRooms') ?>',
			'data': {
				"HKTAO_ID": task_id,
				"HKARM_TASK_SHEET_ID":HKARM_TASK_SHEET_ID
			}
		},
		'columns': [{
				data: 'HKARM_ID',
				'visible': false
			},            
			
			{
				data: 'HKARM_ROOM_ID',
				render: function(data, type, row, meta) {
					if(row['RM_NO'] != '')
						return row['RM_NO']+' - '+row['RM_DESC'];
					else
						return '';
				}
				

			},
			{
				data: 'HKARM_CREDITS'
			},
			
			{
				data: 'HKARM_INSTRUCTIONS'
				
			},
			

			{
				data: null,
				className: "text-center",
				"orderable": false,
				render: function(data, type, row, meta) {
					return (
						'<a href="javascript:;" data-assignroom_id="' + data['HKARM_ID'] +
						'" data-task_id="' + task_id +
						'" class="dropdown-item text-danger delete_room_record"><i class="fa-solid fa-ban"></i> Delete</a>'
					);
				}
			},
			
		

		],
		columnDefs: [
				{
				data: 'HKARM_INSTRUCTIONS',
				width: "10%",
				class:'word-wrap',
				targets:3,
				}],
		"order": [
			[0, "asc"]
		],
		'createdRow': function(row, data, dataIndex) {
			$(row).attr('data-tasksheet_id', HKARM_TASK_SHEET_ID);
			$(row).attr('data-task_id', task_id);
			$(row).attr('data-room_id', data['HKARM_ROOM_ID']);
		},
		
		destroy: true,
		"ordering": true,
		"searching": false,
		autowidth: true,
		responsive: true
		});
	});

	

	function showSuperTaskStatusChange(curStatId, curStatName, taskId, roleId, completionTime) {
		$status = '';
		var $status_name = curStatName;
		var $status_id = curStatId;
	

		if(roleId == 5){
			var $status = {
				<?php foreach ($task_status_supervisor_list as $task_status) { ?> '<?= $task_status['STATUS_ID'] ?>': {
					class: 'btn-<?= $task_status['STATUS_COLOR_CLASS'] ?>',
					title: '<?= $task_status['STATUS_CODE'] ?>'
				},
				<?php } ?>
			};
		}


		if (typeof $status[$status_id] === 'undefined') {
			return $status_name;
		}

		var $statButton = '<button type="button" class="btn btn-sm ' + $status[
				$status_id]
			.class + ' dropdown-toggle"' +
			'data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="supervisor_status">' +
			$status_name + '</button>';

		if ($status_id != '1' && $status_id != '2' && $status_id != '3' && $status_id != '4') {
			$statButton += ' <ul class="dropdown-menu">' +
				'     <li>' +
				'      <h6 class="dropdown-header">Change Task Status</h6>' +
				'     </li><li><hr class="dropdown-divider"></li>';

			$.each($status, function(statText) {
				if (statText == $status_id || statText == 1 || statText == 2 || statText == 3 || statText == 4) $statButton += '';
				else $statButton +=
					'<li><a class="dropdown-item changeTaskStatus" data-role="5" data-task-id="' + taskId + '"' +
					' data-task-new-stat="' + statText + '"' +
					' data-task-new-statName="' + $status[statText].title + '"' +
					' data-task-old-stat="' + $status_id + '"' +
					' data-task-old-statName="' + $status_name + '"' +
					' data-completion-time="' + completionTime + '"' +
					' href="javascript:void(0);">' + $status[statText].title + '</a></li>';
			});

			$statButton += '  </ul>';
		}
		//$('#Taskassignment_sheet_details').dataTable().fnDraw();

		return $statButton;
	}


	function showTaskStatusChange(curStatId, curStatName,  taskId, roleId) {
		
		$status = '';
		var $status_name = curStatName;
		var $status_id = curStatId;
		if(roleId == 3){
			var $status = {
				<?php foreach ($task_status_attendant_list as $task_status) { ?> 
					'<?= $task_status['STATUS_ID'] ?>': {
					class: 'btn-<?= $task_status['STATUS_COLOR_CLASS'] ?>',
					title: '<?= $task_status['STATUS_CODE'] ?>',
					statusId: '<?= $task_status['STATUS_ID'] ?>',
				},
				<?php } ?>
			};
		}

		if (typeof $status[$status_id] === 'undefined') {
			return $status_name;
		}

		var $statButton = '<button type="button" id="task_status_id" class="btn btn-sm ' + $status[
			$status_id]
			.class + ' dropdown-toggle"' +
			'data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" rel="'+$status[
			$status_id].statusId+'" >' +
			$status_name + '</button>';

		if ($status_id != '4' && $status_id != '5') {
			$statButton += ' <ul class="dropdown-menu">' +
				'     <li>' +
				'      <h6 class="dropdown-header">Change Task Status</h6>' +
				'     </li><li><hr class="dropdown-divider"></li>';

			$.each($status, function(statText) {
				if (statText == $status_id || statText == 4 || statText == 5) $statButton += '';
				else $statButton +=
					'<li><a class="dropdown-item changeTaskStatus" data-role="3" data-task-id="' + taskId + '"' +
					' data-task-new-stat="' + statText + '"' +
					' data-task-new-statName="' + $status[statText].title + '"' +
					' data-task-old-stat="' + $status_id + '"' +
					' data-task-old-statName="' + $status_name + '"' +
					' href="javascript:void(0);">' + $status[statText].title + '</a></li>';
			});

			$statButton += '  </ul>';
		}
		//console.log($statButton)

		return $statButton;
	}



	// Change Task Status
	$(document).on('click', '.changeTaskStatus', function() {
		var role = $(this).attr('data-role');
		var taskId = $(this).attr('data-task-id');
		var current_status = $(this).attr('data-task-old-stat');
		var current_statusName = $(this).attr('data-task-old-statName');
		var new_status = $(this).attr('data-task-new-stat');
		var new_statusName = $(this).attr('data-task-new-statName');
		var completion_time = $(this).attr('data-completion-time');		

		var task_status = $("#task_status_id").attr('rel');
	

		if(role == 3)
			var clickedCol = $('.taskRow' + taskId).find('.taskStatusCol');
	    if(role == 5)
		    var clickedCol = $('.taskRow' + taskId).find('.supertaskStatusCol');	
			
		if(role == 5 && (task_status == 1 || task_status == 3)){
			showModalAlert('error',
			`<li>The task is not completed.</li>`
		);
		return;

		}

		if (current_status == new_status) {
			alert('The task status is already ' + current_statusName);
		} else {
			bootbox.confirm({
				message: "Are you sure you want to change the status from '" +
					current_statusName +
					"' to '" + new_statusName + "' for this task?",
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
							url: '<?php echo base_url('/updateTaskStatus') ?>',
							type: "post",
							data: {
								taskId: taskId,
								role  : role,
								new_status: new_status,
								completion_time:completion_time
							},
							headers: {
								'X-Requested-With': 'XMLHttpRequest'
							},
							dataType: 'json',
							success: function(respn) {
								var response = respn['SUCCESS'];
								if (response != '1') {
									var ERROR = respn['RESPONSE']['ERROR'];
									var mcontent = '';
									$.each(ERROR, function(ind, data) {

										mcontent += '<li>' + data +
											'</li>';
									});
									showModalAlert('error', mcontent);
								} else {
									showModalAlert('success',
										`<li>The task status has been updated successfully.</li>`
									);
									if(role == 3)
									clickedCol.html(showTaskStatusChange(new_status,
										new_statusName, taskId, 3));
									if(role == 5)
									clickedCol.html(showSuperTaskStatusChange(new_status,
										new_statusName, taskId, 5));
																
									$('#Taskassignment_sheet_details').dataTable().fnDraw();
								}
							}
						});
					}
				}
			});
		}
	});



	$(document).on('click', '.view_rooms', function() {
		hideModalAlerts();
		$('input[name="HKARM_TASK_SHEET_ID"]').val($(this).data('tasksheet_id'));
		HKARM_TASK_SHEET_ID = $(this).data('tasksheet_id');
		var task_id      = $("#HKAT_TASK_ID").val();
		var task_overview_date  = $('input[name="TASK_ASSIGNMENT_DATE"]').val();
		$('#Taskassignment_room_view').DataTable().destroy();
		$('#Taskassignment_room_view').DataTable({
		'processing': true,
		async: false,
		'serverSide': true,
		'serverMethod': 'post',
		'ajax': {
			'url': '<?php echo base_url('/viewTaskAssignedRooms') ?>',
			'data': {
				"HKTAO_ID": task_id,
				"HKARM_TASK_SHEET_ID":HKARM_TASK_SHEET_ID,
				"TASK_DATE":task_overview_date
			}
		},
		'columns': [{
				data: 'HKARM_ID',
				'visible': false
			},            
			
			{
				data: 'HKARM_ROOM_ID',
				render: function(data, type, row, meta) {
					if(row['RM_NO'] != '')
						return row['RM_NO']+' - '+row['RM_DESC'];
					else
						return '';
				}
				

			},
			{
				data: 'HKARM_CREDITS'
			},
			
			{
				data: 'HKARM_INSTRUCTIONS'
			},
			{
				data: 'START_TIME'
			},
			
			{
				data: 'COMPLETE_TIME'
			},			
			{
				data: 'INSPECTED_NAME'
			},
			{
				data: 'INSPECTEDTIME'
			},
			{
				data: null
			},
			{
				data: 'TOTAL_TIME'
			},
			
		],
		columnDefs: [
				{
				data: 'HKARM_INSTRUCTIONS',
				width: "10%",
				class:'word-wrap',
				targets:3,
				},

				{
					data: 'HKATD_START_TIME',
                    width: "10%",
                    targets: 4,
					render: function(data, type, row, meta) {
							if (row['HKATD_START_TIME'] != null ) {							
								var HKATD_START_TIME = row['HKATD_START_TIME'].split(".");
								if (HKATD_START_TIME[0] == '1900-01-01 00:00:00') {
									return '';
								} else
									return HKATD_START_TIME[0];
							}else
							return '';
						
                    }
                },
                {
					data: 'HKATD_COMPLETION_TIME',
                    width: "10%",
                    targets: 5,
					render: function(data, type, row, meta) {
						if(row['COMPLETED'] == row['TOT']){
							if (row['HKATD_COMPLETION_TIME'] != null ) {							
								var HKATD_COMPLETION_TIME = row['HKATD_COMPLETION_TIME'].split(".");
								if (HKATD_COMPLETION_TIME[0] == '1900-01-01 00:00:00') {
									return '';
								} else
									return HKATD_COMPLETION_TIME[0];
							}else
							return '';
						}
						else 
						return '';
                    }
                },
                {
				data: 'HKATD_INSPECTED_DATETIME',
				targets: 7,
				render: function(data, type, row, meta) {
					if (row['HKATD_INSPECTED_DATETIME'] != null  ) {
						var HKATD_INSPECTED_DATETIME = row['HKATD_INSPECTED_DATETIME'].split(".");
						if (HKATD_INSPECTED_DATETIME[0] == '1900-01-01 00:00:00') {
							return '';
						} else
							return HKATD_INSPECTED_DATETIME[0];
					}else
					return '';
                }
			},{
			data: '',
			targets: 8,
				render: function(data, type, row, meta) {
					if(row['COMPLETED'] == row['TOT']){
						if(row['HKATD_COMPLETION_TIME'] != null){
							return '<a href="javascript:;" data-room_id="' + row['HKARM_ROOM_ID'] +
								'" data-tasksheet_id="' + row['HKAT_TASK_SHEET_ID'] +
								'" data-assigned_taskid="' + row['HKATD_ASSIGNED_TASK_ID'] +
								'" class="text-primary view_comments"><i class="fa-solid fa-eye "></i> </a>';
							}	
							else
							return '';
					}
					else
					   return '';
				}
			},
			{
			data: 'TOTAL_TIME',
			targets: 9,
				render: function(data, type, row, meta) {
					if(row['COMPLETED'] == row['TOT']){
						if(row['TOTAL_TIME'] != null){
							return toHoursAndMinutes(row['TOTAL_TIME']);
						}
						else return '';		
					}
					else return '';				
				}
			}

            ],
		"order": [
			[0, "asc"]
		],
		
		destroy: true,
		"ordering": true,
		"searching": false,
		responsive: true
		});
	});


	function getCompletedTime(){
		$.ajax({
		url: '<?php echo base_url('/getCompletedTime') ?>',
		type: "post",
		data:{task_overview_id:task_overview_id,task_overview_date:task_overview_date, task_assigned_id:task_assigned_id} ,
		headers: {
			'X-Requested-With': 'XMLHttpRequest'
		},
		async: false,
		success: function(respn) {
			if (respn > 0) {				
			    window.open('<?= base_url('/printTaskSheet') ?>', '_blank');			

			}

		}

	});
	}

$(document).on('click', '.printTaskAssignment', function() {
	var task_overview_id    =  $("#HKAT_TASK_ID").val();
	var task_overview_date  = $('input[name="TASK_ASSIGNMENT_DATE"]').val();
	var task_assigned_id       = $(this).data('sysid');
	
	$.ajax({
		url: '<?php echo base_url('/setTaskSheet') ?>',
		type: "post",
		data:{task_overview_id:task_overview_id,task_overview_date:task_overview_date, task_assigned_id:task_assigned_id} ,
		headers: {
			'X-Requested-With': 'XMLHttpRequest'
		},
		async: false,
		success: function(respn) {
			if (respn > 0) {				
			    window.open('<?= base_url('/printTaskSheet') ?>', '_blank');			

			}

		}

	});

});

function toHoursAndMinutes(totalMinutes) {
  const minutes = totalMinutes % 60;
  const hours = Math.floor(totalMinutes / 60);

  return `${padTo2Digits(hours)}:${padTo2Digits(minutes)}`;
}

function padTo2Digits(num) {
  return num.toString().padStart(2, '0');
}


$(document).on('click', '.view_comments', function() {
	
	var HKAT_TASK_ASSIGNED_ID = $(this).data('assigned_taskid');
	var HKAT_ROOM_ID          = $(this).data('room_id');
	$.ajax({
		url: '<?php echo base_url('/getTaskComments') ?>',
		type: "POST",
		data:{HKAT_TASK_ASSIGNED_ID: HKAT_TASK_ASSIGNED_ID, HKAT_ROOM_ID:HKAT_ROOM_ID},
		headers: {
			'X-Requested-With': 'XMLHttpRequest'
		},
		dataType: 'json',
		success: function(response) {
                if (response['SUCCESS'] == 200) {
                    let comments = response['RESPONSE']['OUTPUT'];                    
                    let html = '';
					if(comments.length > 0){
						for (let comment of comments) {
							var ATNA_URL =  '';
							var comment_time = `${comment.ATN_CREATED_AT}`;
							if(comment.ATNA_URL != '' && comment.ATNA_URL != null)
							ATNA_URL = '</br><img onClick="displayImagePopup("<?= base_url() ?>/${comment.ATNA_URL}")" src="${comment.ATNA_URL}" width="80" height="80"/>';
							$comment_time = comment_time.split('.')
							html += `
								<b>${comment.USER_NAME} (${comment_time[0]})</b></br>
								<span class="">${comment.ATN_NOTE}</span></br>`+ATNA_URL+`
																
							`;
						}
					}

                    if(!html)
                        html = `<b>No Comments!</b>`;
					$("#image-popup").css('z-index', '9999999');
                    $('#comment-modal .modal-body').html(html);
                    $('#comment-modal').modal('show');
                }
            }
	});

	$('#viewTaskComments').modal('show');
});


</script>

<?= $this->endSection() ?>