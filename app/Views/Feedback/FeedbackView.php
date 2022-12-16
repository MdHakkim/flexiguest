<?= $this->extend("Layout/AppView") ?>
<?= $this->section("contentRender") ?>

<!-- Content wrapper -->
<div class="content-wrapper">
	<!-- Content -->

	<div class="container-xxl flex-grow-1 container-p-y">
		<h4 class="breadcrumb-wrapper py-3 mb-4"><span class="text-muted fw-light">General</span> Feedback</h4>

		<!-- DataTable with Buttons -->
		<div class="card">
			<!-- <h5 class="card-header">Responsive Datatable</h5> -->
			<div class="container-fluid p-3">
				<table id="dataTable_view" class="table table-striped">
					<thead>
						<tr>
							<th>ID</th>
							<th>Feedback By</th>
							<th>Ratings</th>
							<th>Description</th>
							<th>Module</th>
							<th>Reservation</th>
							<th>Room No</th>
							<th>Created At</th>
							<!-- <th>Action</th> -->
						</tr>
					</thead>

				</table>
			</div>
		</div>

		<!--/ Multilingual -->
	</div>
	<!-- / Content -->

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
				'url': '<?php echo base_url('/getFeedbackList') ?>'
			},
			'columns': [{
					data: 'FB_ID'
				},
				{
					data: null,
					render: function(data, type, row, meta) {
						return `${data['CUST_FIRST_NAME'] || ''} ${data['CUST_LAST_NAME'] || ''}`;
					}
				},
				{
					data: 'FB_RATINGS'
				},
				{
					data: 'FB_DESCRIPTION'
				},
				{
					data: 'FB_MODULE'
				},
				{
					data: 'FB_RESERVATION'
				},
				{
					data: 'FB_ROOM'
				},
				{
					data: 'FB_CREATE_DT'
				},
				//   { data: 'MAINT_PREFERRED_TIME' },
				//  { data: 'MAINT_STATUS' },
				// { data: 'MAINT_ATTACHMENT' },
				// {
				//   data: null,
				//   className: "text-center",
				//   "orderable": false,
				//   render: function(data, type, row, meta) {
				//     return (
				//       '<div class="d-inline-block">' +
				//       '<a href="javascript:;" class="btn btn-sm btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></a>' +
				//       '<ul class="dropdown-menu dropdown-menu-end">' +
				//       '<li><a href="javascript:;" data_sysid="' + data['MAINT_ID'] + '" class="dropdown-item editWindow">Edit</a></li>' +
				//       '<div class="dropdown-divider"></div>' +
				//       '<li><a href="javascript:;" data_sysid="' + data['MAINT_ID'] + '" class="dropdown-item text-danger delete-record">Delete</a></li>' +
				//       '</ul>' +
				//       '</div>'
				//     );
				//   }
				// },
			],
			autowidth: true

		});

		$('#MAINT_PREFERRED_DT').datepicker({
			format: 'd-M-yyyy',
			autoclose: true
		});

	});
</script>
<script src="<?php //echo base_url('assets/js/bootstrap.bundle.js')
				?>"></script>
<script src="<?php //echo base_url('assets/js/bootstrap-select.js')
				?>"></script>
<?= $this->endSection() ?>