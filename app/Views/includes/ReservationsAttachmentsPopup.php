<!-- Reservation Attachments  -->
<?= $this->include('Layout/image_modal') ?>
<style>
    #image-popup{
		z-index : 1200 !important;
	}
</style>
<div class="modal fade" id="resvAttachmentsModal" data-backdrop="static" data-keyboard="false"
    aria-labelledby="popModalWindowlabel">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="resvAttachmentsModallabel">Attachments of Reservation</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">                
                <!-- <form method="POST" enctype="multipart/form-data" id="submit-doc-form"> -->
                <form id="submit-doc-form" class="needs-validation" novalidate method="POST" enctype="multipart/form-data">
                     <div class="row mb-2">
                     <p>Click to upload <b>(Image, PDF or Word Documents. Max 5MB each)</b></p>
                    <div class="col-md-4">
                        <input type="hidden" id="resv_id" value="">
                        <input name="reservation_file[]" type="file" multiple id="reservation_file" class="form-control"/>
                    </div>
                    <div class="col-md-4">
                    
                    <button type="button" id="submitBtn" onClick="submitResvDocForm()" class="btn btn-primary">
                    Upload & Save</button>
                    </div>
                    </div>
                </form>
                <table id="Resv_attachment" class="table table-striped mt-2">
                    <thead>
                        <tr>
                            <th>Sl No.</th>
                            <th>Image / Document</th>
                            <!-- <th>Action</th> -->
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" id="closeResvAttchBtn" class="btn btn-secondary" data-bs-dismiss="modal"
                    data-bs-target="#optionWindow" data-bs-toggle="modal">Close</button>
                
            </div>
        </div>
    </div>
</div>
<!-- Reservation Attachments  -->

<script>
var mainUrl = '<?php echo base_url(); ?>';

$(document).on('click', '.resv-attachments', function() {
    $('#optionWindow').modal('hide');
    var resvId = $(this).attr('data_sysid');
    var resvNo = $(this).attr('data_resv_no');
    $("#resv_id").val(resvId);

    $('#resvAttachmentsModal').modal('show');
    $('#resvAttachmentsModallabel').html('Attachments of Reservation ' + resvNo);
    attachments(resvId)

    
});

function attachments(resvId){
var  i = 1;
compAgntMode = 'COMPANY';
$('#Resv_attachment').DataTable().destroy();
$('#Resv_attachment').DataTable({
    'processing': true,
    'serverSide': true,
    'serverMethod': 'post',
    'ajax': {
        'url': '<?php echo base_url('/getReservationAttachments')?>',
        'data':{resvId:resvId}
    },
    'columns': [{
        data: 'RESV_ATTACH_ID',
            render: function(data, type, full, meta) {
            return i++;
         }
        },
       
        {
            data: null,
            render: function(data, type, full, meta) {
                var RESV_ATTACH_FILE_TYPE = full['RESV_ATTACH_FILE_TYPE'].split('/');
                if(RESV_ATTACH_FILE_TYPE[0] == 'image' ){
                    return (
                            `
                                <img onClick='displayImagePopup("${full['RESV_ATTACH_FILE_NAME']}")' src='${full['RESV_ATTACH_FILE_NAME']}' width='50' height='50' style="cursor:pointer"/>
                            `
                        );
                }
                else if(RESV_ATTACH_FILE_TYPE[0] == 'application' ){
                    return (
                            `
                                <a href='${full['RESV_ATTACH_FILE_NAME']}' style="cursor:pointer" target="_blank"/><img src="assets/img/download.jpg" width="50px"></a>
                            `
                        );
                    }
                }
            
        },
       
        // {
        //     data: null,
        //     className: "text-center",
        //     "orderable": false,
        //     render: function(data, type, row, meta) {

        //         return (
        //             '<div class="d-inline-block">' +
        //             '<a href="javascript:;" class="btn btn-sm btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="bx bx-dots-vertical-rounded"></i></a>' +
        //             '<ul class="dropdown-menu dropdown-menu-end">' +
                    
        //             '<li><a href="javascript:;" data_sysid="' + data['RESV_ATTACH_ID'] +
        //             '" class="dropdown-item text-danger delete-record"><i class="fas fa-trash"></i> Delete</a></li>' +
                    
        //             '</ul>' +
        //             '</div>'
        //         );
        //     }
        // },
    ],
    autowidth: true

});

}


    // Add New or Edit Rate Class submit Function
    function submitResvDocForm() {
        hideModalAlerts();
        var form_id = "#submit-doc-form";
        var fd = new FormData($(`${form_id}`)[0]);
        fd.delete('reservation_file[]');
        var resvId = $("#resv_id").val();

        resvFiles = $(`${form_id} input[name='reservation_file[]']`)[0].files;
        if( resvFiles.length == 0){
            var mcontent = '';   
            mcontent += '<li>Please select files</li>';
            showModalAlert('error', mcontent);
            return;
        }
        for (let i = 0; i < resvFiles.length; i++)
            fd.append('reservation_file[]', resvFiles[i]);
            fd.append('resvId',resvId);

        $.ajax({
            url: '<?= base_url('uploadReservationFiles') ?>',  
            headers: {
            'X-Requested-With': 'XMLHttpRequest'
            }, 
            type: "post",
            data: fd,
             processData: false,
            contentType: false,
            dataType: 'json',
           
            success: function(response) {

                if (response['SUCCESS'] == -402) {
                  
                    var mcontent = '';  
                    mcontent += '<li>Invalid File</li>';
                    showModalAlert('error', mcontent);
                }
                else if (response['SUCCESS'] != 500) {

                    var alertText = response['RESPONSE']['REPORT_RES']['msg'];

                    showModalAlert('error', alertText);
                } else {
                    var alertText = response['RESPONSE']['REPORT_RES']['msg'];

                    showModalAlert('success', alertText);

                    $('#popModalWindow').modal('hide');
                    $('#Resv_attachment').dataTable().fnDraw();
                    $("#reservation_file").val('');
                }
             }
        });
    }
</script>