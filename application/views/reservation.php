<?php 
include "include/header.php";  
include "include/topnavbar.php"; 
?>
<div id="layoutSidenav">

<div id="layoutSidenav_nav">
        <?php include "include/menubar.php"; ?>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="page-header page-header-light bg-white shadow">
                <div class="container-fluid">
                    <div class="page-header-content py-3">
                        <h1 class="page-header-title font-weight-light">
                            <div class="page-header-icon"><i class="fas fa-suitcase"></i></div>
                            <span>Reservation</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
            	<div class="card">
            		<div class="card-body p-0 p-2">
            			<div class="row">
                        <div class="col-3">
            					<form action="<?php echo base_url() ?>Reservation/Booking" method="post"
            						autocomplete="off" enctype="multipart/form-data">
            						<div class="form-group mb-1">
            							<label class="small font-weight-bold">Name*</label>
            							<input type="text" class="form-control form-control-sm" name="name" id="name"
            								required>
            						</div>
            						<div class="form-group mb-1">
            							<label class="small font-weight-bold">Date*</label>
            							<input type="date" class="form-control form-control-sm" name="date_res" id="date_res" placeholder="Select date for booking" required>
            						</div>
            						<div class="form-group mb-1">
            							<label class="small font-weight-bold">Time</label>
            							<input type="time" class="form-control form-control-sm" name="time" id="time" placeholder="Select time for booking" required>
            						</div>
            						<div class="form-group mb-1">
            							<label class="small font-weight-bold">No of Guest</label><br>
            							<div class="input-group">
                                            <input type="number" class="form-control form-control-sm" placeholder="Adults" min="1" name="guest" id="guest" required>
                                            <input type="number" class="form-control form-control-sm" placeholder="Child" min="1" name="child" id="child">
                                        </div>
            						</div>
            						<div class="form-group mb-1">
            							<label class="small font-weight-bold">Category</label><br>
                                            <select name="category" id="category" class="form-control form-control-sm select_custom">
                                                <option value="">Select Category</option>
                                                <?php foreach($tablecategory->result() as $rowrescategorylist){ ?>
                                                    <option value="<?php echo $rowrescategorylist->idtbl_res_reservation_category ?>"><?php echo $rowrescategorylist->reservation_category ?></option>
                                                <?php } ?>
                                            </select>
            						</div>
            						<div class="form-group mb-1">
            							<label class="small font-weight-bold">Table*</label>
            							<select name="restable" id="restable" class="form-control form-control-sm select_custom">
                                            <option value="">Select Table</option>
                                        </select>
            						</div>
            						<div class="form-group mb-1">
            							<label class="small font-weight-bold">Phone Number*</label>
            							<input type="text" class="form-control form-control-sm" name="phone" id="phone"
            								required>
            						</div>
                                    <div class="form-group mb-1">
            							<label class="small font-weight-bold">Email*</label>
            							<input type="email" class="form-control form-control-sm" name="email" id="email"
            								required>
            						</div>
            						<div class="form-group mt-2 text-right">
            							<button type="submit" id="submitBtn" class="btn btn-primary btn-sm px-4"
            								<?php if($addcheck==0){echo 'disabled';} ?>><i
            									class="far fa-save"></i>&nbsp;Add</button>
            						</div>
            						<input type="hidden" name="recordOption" id="recordOption" value="1">
            						<input type="hidden" name="recordID" id="recordID" value="">
            					</form>
            				</div>
            				<div class="col-9">
            					<div class="scrollbar pb-3" id="style-2">
            						<table class="table table-bordered table-striped table-sm nowrap"
            							id="tblmachinetype">
            							<thead>
            								<tr>
            									<th>#</th>
            									<th>Date</th>
            									<th>Time</th>
            									<th>Customer</th>
            									<th>Category</th>
            									<th>Adult</th>
            									<th>Child</th>
            									<th class="text-right">Actions</th>
            								</tr>
            							</thead>
            						</table>
            					</div>
            				</div>
            			</div>
            		</div>
            	</div>
            </div>
		</main>


<!-- reject modal -->
        <div class="modal fade" id="rejectmodel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
		aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalCenterTitle">Reject Reservation</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-12">
						<form method="post" action="<?php echo base_url()?>Reservation/Reservationreject"
							autocomplete="off">
                            <div class="col-12">
									<label class="small font-weight-bold">Reject Reason*</label><br>
									<input type="text"  class="form-control form-control-sm" id="reason" name="reason" required>
								</div>
							<div class="row">
								<div class="col-12" style="align-items: center;">
									<div class="form-group mt-2">
										<input type="hidden" name="rejectID" id="rejectID" value="">
										<button type="submit" name="Btnsubmit" id="Btnsubmit"
											class="btn btn-primary btn-m "><i class="fas fa-save"></i>&nbsp;Reject</button>
									</div>
								</div>
							</div>
						</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

		<?php include "include/footerbar.php"; ?>
    </div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>
    $(document).ready(function() {
        var addcheck='<?php echo $addcheck; ?>';
        var editcheck='<?php echo $editcheck; ?>';
        var statuscheck='<?php echo $statuscheck; ?>';
        var deletecheck='<?php echo $deletecheck; ?>';

        $('#tblmachinetype').DataTable({
            "destroy": true,
            "processing": true,
            "serverSide": true,
            dom: "<'row'<'col-sm-5'B><'col-sm-2'l><'col-sm-5'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            responsive: true,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, 'All'],
            ],
            "buttons": [
                { extend: 'csv', className: 'btn btn-success btn-sm', title: 'Reservations Information', text: '<i class="fas fa-file-csv mr-2"></i> CSV', },
                { extend: 'pdf', className: 'btn btn-danger btn-sm', title: 'Reservations Information', text: '<i class="fas fa-file-pdf mr-2"></i> PDF', },
                { 
                    extend: 'print', 
                    title: 'Reservations Information',
                    className: 'btn btn-primary btn-sm', 
                    text: '<i class="fas fa-print mr-2"></i> Print',
                    customize: function ( win ) {
                        $(win.document.body).find( 'table' )
                            .addClass( 'compact' )
                            .css( 'font-size', 'inherit' );
                    }, 
                },
                // 'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            
            ajax: {
                url: "<?php echo base_url() ?>scripts/reservationlist.php",
                type: "POST", // you can use GET
                // data: function(d) {}
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_res_reservation"
                },
                {
                    "data": "resdate"
                },
				{
                    "data": "restime"
                },
				{
                    "data": "name"
                },
                {
                    "data": "reservation_category"
                },
				{
                    "data": "adult"
                },
                {
                    "data": "child"
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button='';
                        
                        if(full['confirmstatus']==1){
                            button+='<button class="btn btn-danger btn-sm btnEdit mr-1 ';if(editcheck!=1){button+='d-none';}button+='" id="'+full['idtbl_res_reservation']+'"><i class="fas fa-times"></i></button>';
                        } else if(full['rejectstatus']==1){
                            button+='<a href="<?php echo base_url() ?>Reservation/Reservationaccept/'+full['idtbl_res_reservation']+'" onclick="return accept_confirm()" target="_self" class="btn btn-info btn-sm mr-1 ';if(statuscheck!=1){button+='d-none';}button+='"><i class="fas fa-check"></i></a>';
                        }
                        else{
                            button+='<button class="btn btn-danger btn-sm btnEdit mr-1 ';if(editcheck!=1){button+='d-none';}button+='" id="'+full['idtbl_res_reservation']+'"><i class="fas fa-times"></i></button>';
                            button+='<a href="<?php echo base_url() ?>Reservation/Reservationaccept/'+full['idtbl_res_reservation']+'" onclick="return accept_confirm()" target="_self" class="btn btn-info btn-sm mr-1 ';if(statuscheck!=1){button+='d-none';}button+='"><i class="fas fa-check"></i></a>';
                        }


                        if(full['status']==1){
                            button+='<a href="<?php echo base_url() ?>Reservation/Reservationstatus/'+full['idtbl_res_reservation']+'/2" onclick="return deactive_confirm()" target="_self" class="btn btn-success btn-sm mr-1 ';if(statuscheck!=1){button+='d-none';}button+='"><i class="fas fa-check"></i></a>';
                        }else{
                            button+='<a href="<?php echo base_url() ?>Reservation/Reservationstatus/'+full['idtbl_res_reservation']+'/1" onclick="return active_confirm()" target="_self" class="btn btn-warning btn-sm mr-1 ';if(statuscheck!=1){button+='d-none';}button+='"><i class="fas fa-times"></i></a>';
                        }
                        button+='<a href="<?php echo base_url() ?>Reservation/Reservationstatus/'+full['idtbl_res_reservation']+'/3" onclick="return delete_confirm()" target="_self" class="btn btn-danger btn-sm ';if(deletecheck!=1){button+='d-none';}button+='"><i class="fas fa-trash-alt"></i></a>';
                        
                        return button;
                    }
                }
            ],
            drawCallback: function(settings) {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
        $('#tblmachinetype tbody').on('click', '.btnEdit', function() {
           
                var id = $(this).attr('id');
                $.ajax({
                    type: "POST",
                    data: {
                        recordID: id
                    },
                    url: '<?php echo base_url() ?>Reservation/Reservationedit',
                    success: function(result) { //alert(result);
                        var obj = JSON.parse(result);
                        $('#rejectID').val(obj.id);
                        $('#rejectmodel').modal('show');
                    }
                });
            
        });

        $('#category').change(function(){
				loadtablelist();
			});
    });

    function loadtablelist(){
			var resdate = $('#date_res').val();
			var restime = $('#time').val();
			var rescategory = $('#category').val();

			if(resdate!='' && restime!='' && rescategory!=''){
				$.ajax({
                    type: "POST",
                    data: {
                        resdate: resdate,
                        restime: restime,
                        rescategory: rescategory
                    },
                    url: '<?php echo base_url() ?>Reservation/Tablelist',
                    success: function(result) { //alert(result);
                        var objfirst = JSON.parse(result);

						var html = '';
						html += '<option value="">Select Table</option>';
						$.each(objfirst, function(i, item) {
							//alert(objfirst[i].id);
							html += '<option value="' + objfirst[i].idtbl_res_reservation_table + '">';
							html += objfirst[i].table;
							html += '</option>';
						});

						$('#restable').empty().append(html);
                    }
                });
			}
		}

    function deactive_confirm() {
        return confirm("Are you sure you want to deactive this?");
    }

    function accept_confirm() {
        return confirm("Are you sure you want to Accept this?");
    }


    function active_confirm() {
        return confirm("Are you sure you want to active this?");
    }

    function delete_confirm() {
        return confirm("Are you sure you want to remove this?");
    }
</script>
<?php include "include/footer.php"; ?>
