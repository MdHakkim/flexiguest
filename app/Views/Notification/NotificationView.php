<?= $this->extend("Layout/AppView") ?>
<?= $this->section("contentRender") ?>
<?= $this->include('Layout/ErrorReport') ?>
<?= $this->include('Layout/SuccessReport') ?>
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
	.disabledDiv{
		pointer-events: none;
   		 opacity: 0.4;
	}
</style>

<!-- Content wrapper -->
<div class="content-wrapper">
	<!-- Content -->

	<div class="container-xxl flex-grow-1 container-p-y">
		<h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">Notifications /</span> Notifications</h4>

		<!-- DataTable with Buttons -->
		<div class="card">
			<!-- <h5 class="card-header">Responsive Datatable</h5> -->
			<div class="container-fluid table-responsive" style="padding: 16px 16px 6px 16px">

				<form class="search-form mb-2" method="POST">
					<div class="border rounded p-3 mb-3">
						<div class="row g-3">
							<div class="col-md-6">
								<label class="form-label"><b>Notification Type</b></label>
								<select name="notification_type[]" class="select2 form-select" multiple>
								</select>
							</div>

							<div class="col-md-6">
								<label class="form-label"><b>Department</b></label>
								<select name="notification_department[]" class="select2 form-select" multiple>
								</select>
							</div>

							<div class="col-md-6">
								<label class="form-label"><b>Reservation</b></label>
								<select name="notification_reservation_id[]" class="select2 form-select" multiple>
								</select>
							</div>

							<div class="col-md-6">
                                <label class="form-label"><b>Notification Text</b></label>
                                <textarea name="notification_text" class="form-control" placeholder="text..."></textarea>
                            </div>

							<div class="col-md-12 text-end">
								<button type="button" class="btn btn-primary submit-search-form">
									<i class='bx bx-search'></i> Search
								</button>

								<button type="button" class="btn btn-secondary clear-search-form">Clear</button>
							</div>
						</div>
					</div>
				</form>

				<table id="dataTable_view" class="dt-responsive table table-striped display nowrap" style="width:100%">
					<thead>
						<tr>
							<th>View</th>
							<th>Notification ID</th>
							<th class="all">Notification Type</th>
							<th class="all">Department</th>
							<th>To User</th>
							<th class="all">Reservation</th>
							<th>Guest</th>
							<th class="all">Message</th>
							<th>URL</th>
							<th class="all">Resolved By</th>
							<th class="all">Date & Time</th>
							<th class="all">Status</th>
							<th class="all">Action</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>

		<!--/ Multilingual -->
	</div>
	<!-- / Content -->

	<!-- Modal Window -->

	<div class="modal fade" id="popModalWindow" data-backdrop="static" data-keyboard="false" aria-lableledby="popModalWindowlable">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="popModalWindowlabel">Add Notification</h4>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-lable="Close"></button>
				</div>
				<div class="modal-body">
					<form id="submitForm" class="needs-validation" novalidate>
						<div class="row g-3">
							<input type="hidden" name="NOTIFICATION_ID" id="NOTIFICATION_ID" class="form-control" />
							<input type="hidden" name="NOTIFICATION_OLD_TYPE" id="NOTIFICATION_OLD_TYPE" class="form-control" />

							<div class="border rounded p-3">
								<div class="col-md-12">
									<div class="row mb-3">
										<label for="html5-text-input" class="col-form-label"><b>NOTIFICATION TYPE
												*</b></label>
										<div class="col-md-12 mb-3 notification_type">
										</div>

										<div class="col-md-6 Department">
											<label for="html5-text-input" class="col-form-label"><b>ENTIRE DEPARTMENT</b></label>
											<select id="NOTIFICATION_DEPARTMENT" name="NOTIFICATION_DEPARTMENT[]" class="select2 form-select form-select-lg" data-allow-clear="true" multiple>
											</select>
										</div>

										<div class="col-md-6 Users">
											<label for="html5-text-input" class="col-form-label"><b>SPECIFIC USER
												</b></label>
											<select id="NOTIFICATION_TO_ID" name="NOTIFICATION_TO_ID[]" class="select2 form-select form-select-lg" data-allow-clear="true" multiple>
											</select>
										</div>
									</div>

									<div class="row mb-3">
										<div class="col-md-6 mb-3 Reservation">
											<label for="html5-text-input" class="col-form-label"><b>ENTIRE RESERVATION
												</b></label>
											<select id="NOTIFICATION_RESERVATION_ID" name="NOTIFICATION_RESERVATION_ID[]" class="select2 form-select form-select-lg" data-allow-clear="true" multiple>

											</select>
										</div>

										<div class="col-md-6 Guest">
											<label for="html5-text-input" class="col-form-label"><b>SPECIFIC GUEST NAME
												</b></label>
											<select id="NOTIFICATION_GUEST_ID" name="NOTIFICATION_GUEST_ID[]" class="select2 form-select form-select-lg" data-allow-clear="true" multiple>

											</select>
										</div>
										<div class="col-md-6 Guest">
											<label for="html5-text-input" class="col-form-label"><b>URL
												</b></label>
											<input type="text" id="NOTIFICATION_URL" name="NOTIFICATION_URL" class="form-control" placeholder="">
										</div>
									</div>
									<div class="row mb-3">
										<div class="col-md-6">
											<label for="html5-text-input" class="col-form-label"><b>NOTIFICATION DATE & TIME
												</b></label>
											<input type="text" id="NOTIFICATION_DATE_TIME" name="NOTIFICATION_DATE_TIME" class="form-control" placeholder="YYYY-MM-DD HH:MM">

										</div>
										<div class="col-md-3">
											<label for="html5-text-input" class="col-form-label"><b></b></label><br><br>
											<label class="switch">

												<input id="NOTIFICATION_SEND_NOW" value="1" name="NOTIFICATION_SEND_NOW" type="checkbox" class="switch-input" />
												<span class="switch-toggle-slider">
													<span class="switch-on">
														<i class="bx bx-check"></i>
													</span>
													<span class="switch-off">
														<i class="bx bx-x"></i>
													</span>
												</span>
												<span class="switch-label"><b>Send Now</b></span>
											</label>
										</div>

									</div>
									<div class="row mb-3">

									</div>
									<div class="row mb-3">
										<label for="html5-text-input" class="col-form-label"><b>Message
												*</b></label>
										<!-- Full Editor -->
										<div class="col-12">
											<div class="card">
												<h6 class="card-header">Add your message</h6>
												<div class="card-body">
													<textarea name="NOTIFICATION_TEXT" class="d-none"></textarea>
													<div id="full-editor">

													</div>
												</div>
											</div>
										</div>
										<!-- /Full Editor -->
									</div>
								</div>
							</div>

						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<button type="button" id="submitBtn" onClick="submitForm('submitForm')" class="btn btn-primary">Save</button>
				</div>
			</div>
		</div>
	</div>

	<!-- /Modal window -->

	<!-- Modal -->
	<div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalCenterTitle"></h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col mb-3 ">
							<span class="showDetails"></span>

						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
						Close
					</button>
				</div>
			</div>
		</div>
	</div>
	<div class="content-backdrop fade"></div>
</div>

<!-- Content wrapper -->
<script>
	var search_form_data = null;
	var compAgntMode = '';
	var linkMode = '';

	$(document).ready(function() {
		notificationTypeList();
		departmentList();
		usersList();
		guestList();
		reservationList();

		$("#NOTIFICATION_DATE_TIME").flatpickr({
			enableTime: true,
			dateFormat: 'Y-m-d H:i',
			minDate: "today",
			static: true
		});



		linkMode = 'EX';

		var dt_notification_table = $('#dataTable_view'),
			select2 = $('.select2'),
			userView = '',
			statusObj = {
				1: {
					title: 'Seen',
					class: 'bg-label-success'
				},
				0: {
					title: 'Unseen',
					class: 'bg-label-secondary'
				}
			};

		if (dt_notification_table.length) {
			var dt_notification = dt_notification_table.DataTable({
				'processing': true,
				'serverSide': true,
				'serverMethod': 'post',
				'ajax': {
					'url': '<?php echo base_url('/NotificationList') ?>',
					'type': 'POST',
					'data': function(d) {
						var form_data = $('form.search-form').serializeArray();
						$(form_data).each(function(i, field) {
							d[field.name] = field.value;
						});

						d['notification_type[]'] = $('.search-form [name="notification_type[]"]').val();
						d['notification_department[]'] = $('.search-form [name="notification_department[]"]').val();
						d['notification_reservation_id[]'] = $('.search-form [name="notification_reservation_id[]"]').val();
					},
				},
				columns: [{
						data: ''
					},
					{
						data: 'NOTIFICATION_ID',
						visible: false
					},
					{
						data: 'NOTIF_TY_DESC'
					},
					{
						data: 'NOTIFICATION_DEPARTMENT',

						render: function(data, type, full, meta) {
							if (full['NOTIFICATION_DEPARTMENT'] != '') {
								return '<a href="javascript:;" onclick="viewAll(\'Departments\',' + full['NOTIFICATION_ID'] + ')" title="View Department" id="viewDept" rel="">' + full['NOTIFICATION_DEPARTMENT'] + '<br><span class="btn btn-sm btn-label-info">View</span></br></a>';
							} else return '';
						}
					},
					{
						data: 'NOTIFICATION_TO_ID',
						render: function(data, type, full, meta) {
							if (full['NOTIFICATION_TO_ID'] != '') {
								return '<a href="javascript:;" onclick="viewAll(\'Users\',' + full['NOTIFICATION_ID'] + ')" title="View Users" id="viewDept" rel="">' + full['NOTIFICATION_TO_ID'] + '<br><span class="btn btn-sm btn-label-info">View</span></br></a>';
							} else return '';
						}
					},
					{
						data: 'RSV_ID',
						render: function(data, type, full, meta) {
							if (full['RSV_ID'] != '') {
								return full['RSV_ID'];
							} else return '';
						}
					},
					{
						data: 'NOTIFICATION_GUEST_ID',
						render: function(data, type, full, meta) {
							if (full['NOTIFICATION_GUEST_ID'] != '') {
								dots = '';
								NOTIFICATION_GUEST_ID = full['NOTIFICATION_GUEST_ID'];
								if(NOTIFICATION_GUEST_ID.length>20)
								var dots = '...<p><span class="btn btn-sm btn-label-info">View</span></p>';
								return '<a href="javascript:;" onclick="viewAll(\'Guests\',' + full['NOTIFICATION_ID'] + ')" title="View Guests"  rel="">' + full['NOTIFICATION_GUEST_ID'] + '<br><span class="btn btn-sm btn-label-info">View</span></a>';
							} else return '';
						}

					},
					{
						data: 'NOTIFICATION_TEXT',
						render: function(data, type, full, meta) {
							if (full['NOTIFICATION_TEXT'] != '') {
								dots = '';
								NOTIFICATION_TEXT = full['NOTIFICATION_TEXT'];
								if(NOTIFICATION_TEXT.length>20)
								var dots = '...<p><span class="btn btn-sm btn-label-info">View</span></p>';
								return '<a href="javascript:;" onclick="viewAll(\'Messages\',' + full['NOTIFICATION_ID'] + ')" title="View Message" rel="">' + NOTIFICATION_TEXT.substr(0, 20)+ dots+'</a>';
							} else return '';
						}
					},
					{
						data: 'NOTIFICATION_URL'
					},
					{
						data: 'RSV_TRACE_RESOLVED_BY',
						render: function(data, type, full, meta) {
							return (full['RSV_TRACE_RESOLVED_BY'] != 0 ) ? full['RSV_TRACE_RESOLVED_BY'] : ''; 
						}
					},
					{
						data: 'NOTIFICATION_DATE_TIME'
					},
					{
						data: 'NOTIFICATION_READ_STATUS',
						
					},
					{
						data: null,
						className: "text-center",
						"orderable": false,
						render: function(data, type, full, meta) {
							var disabledDiv = "disabledDiv";
							if (full["NOTIFICATION_READ_STATUS"] == 0) {
								var disabledDiv  = "";
							}
								var resvListButtons =
									'<div class="d-inline-block flxy_option_view dropend '+disabledDiv+'" >' +
									'<a href="javascript:;" class="btn btn-sm btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></a>' +
									'<ul class="dropdown-menu dropdown-menu-end">' +									
									'<li><a href="javascript:;" data_sysid="' + full['NOTIFICATION_ID'] +
									'" data-row-ind="' + meta.row + '"  class="dropdown-item viewNotification text-success"><i class="fa-solid fa-align-justify"></i> View</a></li><div class="dropdown-divider"></div>' +
									'<li><a href="javascript:;" data_sysid="' + full['NOTIFICATION_ID'] +
									'" class="dropdown-item text-danger delete-record"><i class="fas fa-trash"></i> Delete</a></li>';

								//console.log("metaObj", meta);

								resvListButtons += '</ul>' +
									'</div>';

								return resvListButtons;
							
						}
					}
				],
				columnDefs: [{
						width: "7%",
						className: 'control',
						responsivePriority: 1,
						orderable: false,
						targets: 0,
						searchable: false,
						render: function(data, type, full, meta) {
							return '';
						}
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
					{
						targets: 10,
						width: "10%",
						render: function(data, type, full, meta) {
							if (full['NOTIFICATION_DATE_TIME'] != '') {
								var NOTIFICATION_DATE_TIME = full['NOTIFICATION_DATE_TIME'].split(".");
								if (NOTIFICATION_DATE_TIME[0] == '1900-01-01 00:00:00') {
									return '';
								} else
									return NOTIFICATION_DATE_TIME[0];
							}
						}

					},
					{
						targets: 11,
						width: "10%",
						render: function(data, type, full, meta) {
							// if (full["RSV_TRACE_RESOLVED_BY"] != null) {
							// 	return '';
							// } else {
								var $status = full['NOTIFICATION_READ_STATUS'];
								return '<span class="badge ' + statusObj[$status].class + '">' + statusObj[$status].title + '</span>';
							//}
						}
					}
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


	// Show Add Notification

	function addForm() {
		$('#submitForm').not('[type="radio"],[type="checkbox"]').val('').prop('checked', false).prop('selected', false);
		$('.select2').val(null).trigger('change');
		$("#NOTIFICATION_ID").val('');
		$("#NOTIFICATION_DATE_TIME").val('');
		$("#full-editor .ql-editor").html('');
		$('#submitBtn').removeClass('btn-success').addClass('btn-primary').text('Save');
		$('#popModalWindowlabel').html('Add Notification');
		$('#popModalWindow').modal('show');
	}

	// Delete Notification

	$(document).on('click', '.viewNotification', function() {
		//alert($(this).data('row-ind'))
		var rowInd = $(this).data('row-ind');
		$("td.control").eq(rowInd).click();
	});

	$(document).on('click', '.delete-record', function() {
		hideModalAlerts();
		$('.dtr-bs-modal').modal('hide');

		var sysid = $(this).attr('data_sysid');
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
						url: '<?php echo base_url('/deleteNotification') ?>',
						type: "post",
						data: {
							sysid: sysid
						},
						headers: {
							'X-Requested-With': 'XMLHttpRequest'
						},
						dataType: 'json',
						success: function(respn) {
							showModalAlert('warning',
								'<li>The Notification has been deleted</li>');
							$('#dataTable_view').dataTable().fnDraw();
						}
					});
				}
			}
		});
	});

	// Show Edit Notification 

	$(document).on('click', '.editNotification', function() {
		$('#popModalWindowlabel').html('Edit Notification');
		$('.dtr-bs-modal').modal('hide');
		var sysid = $(this).attr('data_sysid');
		$('#popModalWindowlabel').html('Edit Notification');
		$("#DEPT_CODE").prop("readonly", true);
		$('#popModalWindow').modal('show');

		var url = '<?php echo base_url('/editNotification') ?>';
		$.ajax({
			url: url,
			type: "post",
			headers: {
				'X-Requested-With': 'XMLHttpRequest'
			},
			data: {
				sysid: sysid
			},
			dataType: 'json',
			success: function(respn) {
				$(respn).each(function(inx, data) {
					$.each(data, function(fields, datavals) {
						var field = $.trim(fields); //fields.trim();
						var dataval = $.trim(datavals); //datavals.trim();                   

						if (field == "NOTIFICATION_DEPARTMENT") {
							if (dataval != '') {
								$("#submitForm select[name='NOTIFICATION_DEPARTMENT[]']").val(JSON.parse(dataval));
								$("#submitForm select[name='NOTIFICATION_DEPARTMENT[]']").trigger('change');
							}
						} else if (field == "NOTIFICATION_TO_ID") {
							if (dataval != '') {
								$("#submitForm select[name='NOTIFICATION_TO_ID[]']").val(JSON.parse(dataval));
								$("#submitForm select[name='NOTIFICATION_TO_ID[]']").trigger('change');
							}
						} else if (field == "NOTIFICATION_RESERVATION_ID") {
							if (dataval != '') {
								$("#submitForm select[name='NOTIFICATION_RESERVATION_ID[]']").val(JSON.parse(dataval));
								$("#submitForm select[name='NOTIFICATION_RESERVATION_ID[]']").trigger('change');
							}
						} else if (field == "NOTIFICATION_GUEST_ID") {
							if (dataval != '') {
								$("#submitForm select[name='NOTIFICATION_GUEST_ID[]']").val(JSON.parse(dataval));
								$("#submitForm select[name='NOTIFICATION_GUEST_ID[]']").trigger('change');
							}
						} else if ($('#' + field).attr('type') == 'checkbox') {
							$('#' + field).prop('checked', dataval == 1 ? true : false);
						} else if ($("input[name=" + field + "]").attr('type') == 'radio') {
							$('#' + field + '_' + dataval).prop('checked', true);
							$('#' + field + '_' + dataval).click();
							$('#NOTIFICATION_OLD_TYPE').val(dataval)
						} else if (field == "NOTIFICATION_TEXT") {
							$("#full-editor .ql-editor").html(dataval)
						} else {
							$('#' + field).val(dataval)
						}


					});
				});
				$('#submitBtn').removeClass('btn-primary').addClass('btn-success').text('Update');
			}
		});
	});


	// Add New or Edit Department submit Function

	function submitForm(id) {

		hideModalAlerts();
		$('#loader_flex_bg').show();
		$(`#${id} textarea[name='NOTIFICATION_TEXT']`).val($("#full-editor .ql-editor").html());
		if ($("#full-editor .ql-editor").html() == "<p><br></p>")
			$(`#${id} textarea[name='NOTIFICATION_TEXT']`).val('');

		var formSerialization = $('#' + id).serializeArray();

		var url = '<?php echo base_url('/insertNotification') ?>';
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
					var alertText = $('#NOTIFICATION_ID').val() == '' ? '<li>The notification has been created</li>' : '<li>The notification has been updated</li>';
					showModalAlert('success', alertText);
					$('#popModalWindow').modal('hide');
					$('#loader_flex_bg').hide();
					$('#dataTable_view').dataTable().fnDraw();
				}
			}
		});
	}

	function notificationTypeList() {
		$.ajax({
			url: '<?php echo base_url('/notificationTypeList') ?>',
			type: "GET",
			headers: {
				'X-Requested-With': 'XMLHttpRequest'
			},
			async: false,
			success: function(respn) {
				respn = JSON.parse(respn);

				$('.notification_type').html(respn.option1);
				$('.search-form [name="notification_type[]"]').html(respn.option2);
			}
		});

	}


	function departmentList() {
		$.ajax({
			url: '<?php echo base_url('/allDepartmentList') ?>',
			type: "get",
			headers: {
				'X-Requested-With': 'XMLHttpRequest'
			},
			async: false,
			success: function(respn) {
				$('#NOTIFICATION_DEPARTMENT').html(respn);
				$('.search-form [name="notification_department[]"').html(respn);
			}
		});
	}

	function usersList() {
		$.ajax({
			url: '<?php echo base_url('/usersList') ?>',
			type: "GET",
			headers: {
				'X-Requested-With': 'XMLHttpRequest'
			},
			async: false,
			success: function(respn) {
				$('#NOTIFICATION_TO_ID').html(respn);
			}
		});
	}

	$("#submitForm [name='NOTIFICATION_DEPARTMENT[]'], .search-form [name='notification_department[]']").change(function() {
		let name = $(this).attr('name');
		let department_ids = $(this).val();
		if (department_ids.length) {
			if (department_ids.includes('all')) {
				if (name == 'notification_department[]') {
					$(".search-form [name='notification_department[]']> option").prop("selected", true);
					$(".search-form [name='notification_department[]'] > option").first().prop("selected", false);
					$(".search-form [name='notification_department[]']").trigger("change");
				} else {

					$("#submitForm [name='NOTIFICATION_DEPARTMENT[]']> option").prop("selected", true);
					$("#submitForm [name='NOTIFICATION_DEPARTMENT[]'] > option").first().prop("selected", false);
					$("#submitForm [name='NOTIFICATION_DEPARTMENT[]']").trigger("change");
				}
				return;
			}

			if (name == 'NOTIFICATION_DEPARTMENT[]') {
				$.ajax({
					url: '<?= base_url('/usersByDepartmentList') ?>',
					type: "post",
					data: {
						department_ids: department_ids
					},
					dataType: 'json',
					success: function(response) {
						$("#submitForm select[name='NOTIFICATION_TO_ID[]']").html(response);
						$("#submitForm select[name='NOTIFICATION_TO_ID[]']").trigger('change');
					}
				});
			}
		}
	});

	$("#submitForm [name='NOTIFICATION_TO_ID[]']").change(function() {
		let user_ids = $(this).val();

		if (user_ids.length && user_ids.includes('all')) {
			$("#submitForm [name='NOTIFICATION_TO_ID[]'] > option").prop("selected", true);
			$("#submitForm [name='NOTIFICATION_TO_ID[]'] > option").first().prop("selected", false);
			$("#submitForm [name='NOTIFICATION_TO_ID[]']").trigger("change");
		}
	});

	$("#submitForm [name='NOTIFICATION_RESERVATION_ID[]']").change(function() {

		let reservation_ids = $(this).val();
		if (reservation_ids.length) {
			if (reservation_ids.includes('all')) {
				$("#submitForm [name='NOTIFICATION_RESERVATION_ID[]']> option").prop("selected", true);
				$("#submitForm [name='NOTIFICATION_RESERVATION_ID[]'] > option").first().prop("selected", false);
				$("#submitForm [name='NOTIFICATION_RESERVATION_ID[]']").trigger("change");
				return;
			}

			$.ajax({
				url: '<?= base_url('/guestByReservation') ?>',
				type: "post",
				data: {
					reservation_ids: reservation_ids
				},
				dataType: 'json',
				success: function(response) {
					if (response) {
						let guests = response;
						let html = '';
						for (let guest of guests) {
							html += `
                            <option value="${guest.CUST_ID}">${guest.FULLNAME} </option>
                        `;
						}
						$("#submitForm select[name='NOTIFICATION_GUEST_ID[]']").html(html);
						$("#submitForm select[name='NOTIFICATION_GUEST_ID[]']").trigger('change');
					} else {

					}
				}
			});
		}
	});

	function reservationList() {
		$.ajax({
			url: '<?php echo base_url('/reservationList') ?>',
			type: "GET",
			headers: {
				'X-Requested-With': 'XMLHttpRequest'
			},
			async: false,
			// dataType:'json',
			success: function(respn) {
				$('#NOTIFICATION_RESERVATION_ID').html(respn);
				$('.search-form [name="notification_reservation_id[]"]').html(respn);
			}
		});
	}

	function guestList() {
		$.ajax({
			url: '<?php echo base_url('/getCustomers') ?>',
			type: "GET",
			headers: {
				'X-Requested-With': 'XMLHttpRequest'
			},
			async: false,
			success: function(respn) {
				$('#NOTIFICATION_GUEST_ID').html(respn);
			}
		});
	}


	$(document).on('click', '[name=NOTIFICATION_TYPE]', function() {
		if ($(this).val() === '4') {
			$(".Reservation").show();
			$(".Department").show();
			$(".Users").hide();
			$(".Guest").hide();
			$('#NOTIFICATION_RESERVATION_ID').val(null).trigger('change');
		} else if ($(this).val() === '3') {
			$(".Department").hide();
			$(".Users").hide();
			$(".Reservation").show();
			$(".Guest").show();
			guestList();
		} else if ($(this).val() === '1' || $(this).val() === '2') {
			$(".Department").show();
			$(".Users").show();
			$(".Reservation").hide();
			$(".Guest").hide();

		}
	});


	$(document).on('change', '#NOTIFICATION_RESERVATION_ID', function() {
		var resvId = $(this).val();
		if (document.querySelector('input[name="NOTIFICATION_TYPE"]:checked').value === 'Traces') {
			fillCustomerSelect(resvId);
		}

	});

	function fillCustomerSelect(resvId) {
		$.ajax({
			url: '<?php echo base_url('/showReservationCustomers') ?>',
			async: false,
			type: "post",
			headers: {
				'X-Requested-With': 'XMLHttpRequest'
			},
			data: {
				sysid: resvId
			},
			dataType: 'json',
			success: function(respn) {
				var customerSelect = $('#NOTIFICATION_GUEST_ID');
				customerSelect.empty().trigger("change");

				$(respn).each(function(inx, data) {
					var newOption = new Option(data.text, data.id, false, false);
					customerSelect.append(newOption);
				});
				customerSelect.val(respn.length == 1 ? respn[0].id : null).trigger('change');
				// if only 1 guest, select one by default
			}
		});
	}

	function viewAll(field, notificationId) {
		$(".showDetails").html('');
		$.ajax({
			url: '<?php echo base_url('/viewAllNotificationDetails') ?>',
			async: false,
			type: "post",
			headers: {
				'X-Requested-With': 'XMLHttpRequest'
			},
			data: {
				notificationId: notificationId,
				type: field
			},
			success: function(respn) {
				$("#modalCenterTitle").html(field);
				$(".showDetails").html(respn);


			}
		});
		$("#modalCenter").modal('show')
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
</script>

<?= $this->endSection() ?>