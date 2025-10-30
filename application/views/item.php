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
                            <div class="page-header-icon"><i class="fas fa-shopping-basket"></i></div>
                            <span>Item</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
            	<div class="card">
            		<div class="card-body p-0 p-2">
            			<div class="row">
            				<div class="col-3">
            					<form action="<?php echo base_url() ?>Item/Iteminsertupdate" method="post"
            						autocomplete="off" enctype="multipart/form-data">
                                    <div class="form-group mb-1">
            							<label class="small font-weight-bold"> Item Type*</label>
            							<select class="form-control form-control-sm" name="type" id="type"
            								required>
            								<option value="">Select Item Type</option>
                                            <option value="1">Restaurent Item</option>
            								<option value="2">Coffee Shop Item</option>
            							</select>
            						</div>
            						<div class="form-group mb-1">
            							<label class="small font-weight-bold"> Item Category*</label>
            							<select class="form-control form-control-sm" name="category" id="category"
            								required>
            								<option value="">Select</option>
            								<?php foreach($categorylist->result() as $rowcategorylist){ ?>
            								<option value="<?php echo $rowcategorylist->idtbl_res_item_category ?>">
            									<?php echo $rowcategorylist->categoryname?></option>
            								<?php } ?>
            							</select>
            						</div>

            						<div class="form-group mb-1">
            							<label class="small font-weight-bold">Item Name*</label>
            							<input type="text" class="form-control form-control-sm" name="name" id="name"
            								required>
            						</div>
            						<div class="form-group mb-1">
            							<label class="small font-weight-bold">Code*</label>
            							<input type="text" class="form-control form-control-sm" name="code" id="code"
            								required>
            						</div>
            						<div class="form-group mb-1">
            							<label class="small font-weight-bold">CSR</label>
            							<input type="text" class="form-control form-control-sm" name="csr" id="csr">
            						</div>
            						<div class="form-group mb-1">
            							<label class="small font-weight-bold">Short Discription*</label><br>
            							<textarea name="shortdesc" class="form-control form-control-sm" id="shortdesc"
            								cols="10" rows="2" required></textarea>
            						</div>
            						<div class="form-group mb-1">
            							<label class="small font-weight-bold">Discription*</label><br>
            							<textarea name="discription" class="form-control form-control-sm"
            								id="discription" cols="10" rows="4" required></textarea>
            						</div>
            						<div class="form-group mb-1">
            							<label class="small font-weight-bold">Price*</label>
            							<input type="number" step="any" class="form-control form-control-sm"
            								name="price" id="price" required>
            						</div>
            						<label class="small font-weight-bold">Image*</label>
            						<input type="file" id="image" name="image">
                                    <span class="text-danger font-weight-bold">Image Dimension should be 6000*4000 pixels</span>
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
            									<th>Name</th>
                                                <th>Type</th>
            									<th>Category</th>
            									<th>Code</th>
            									<th>Price</th>
            									<th>CSR</th>
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
                { extend: 'csv', className: 'btn btn-success btn-sm', title: 'Items Information', text: '<i class="fas fa-file-csv mr-2"></i> CSV', },
                { extend: 'pdf', className: 'btn btn-danger btn-sm', title: 'Items Information', text: '<i class="fas fa-file-pdf mr-2"></i> PDF', },
                { 
                    extend: 'print', 
                    title: 'Items Information',
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
                url: "<?php echo base_url() ?>scripts/itemlist.php",
                type: "POST", // you can use GET
                // data: function(d) {}
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_res_item"
                },
                {
                    "data": "itemname"
                },
                {
                    "data": "itemtype",
                    "render": function(data, type, row) {
                        if (data == 1) {
                            return "Restaurant Item";
                        } else if (data == 2) {
                            return "Coffee Shop Item";
                        } else {
                            return "Unknown Item Type";
                        }
                    }
                },
				{
                    "data": "categoryname"
                },
                {
                    "data": "code"
                },
                {
                    "data": "price"
                },
                {
                    "data": "csr"
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button='';
                        button+='<button class="btn btn-primary btn-sm btnEdit mr-1 ';if(editcheck!=1){button+='d-none';}button+='" id="'+full['idtbl_res_item']+'"><i class="fas fa-pen"></i></button>';
                        if(full['status']==1){
                            button+='<a href="<?php echo base_url() ?>Item/Itemstatus/'+full['idtbl_res_item']+'/2" onclick="return deactive_confirm()" target="_self" class="btn btn-success btn-sm mr-1 ';if(statuscheck!=1){button+='d-none';}button+='"><i class="fas fa-check"></i></a>';
                        }else{
                            button+='<a href="<?php echo base_url() ?>Item/Itemstatus/'+full['idtbl_res_item']+'/1" onclick="return active_confirm()" target="_self" class="btn btn-warning btn-sm mr-1 ';if(statuscheck!=1){button+='d-none';}button+='"><i class="fas fa-times"></i></a>';
                        }
                        button+='<a href="<?php echo base_url() ?>Item/Itemstatus/'+full['idtbl_res_item']+'/3" onclick="return delete_confirm()" target="_self" class="btn btn-danger btn-sm ';if(deletecheck!=1){button+='d-none';}button+='"><i class="fas fa-trash-alt"></i></a>';
                        
                        return button;
                    }
                }
            ],
            drawCallback: function(settings) {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
        $('#tblmachinetype tbody').on('click', '.btnEdit', function() {
            var r = confirm("Are you sure, You want to Edit this ? ");
            if (r == true) {
                var id = $(this).attr('id');
                $.ajax({
                    type: "POST",
                    data: {
                        recordID: id
                    },
                    url: '<?php echo base_url() ?>Item/Itemedit',
                    success: function(result) { //alert(result);
                        var obj = JSON.parse(result);
                        $('#recordID').val(obj.id);
                        $('#name').val(obj.itemname);
                        $('#type').val(obj.type);  
						$('#code').val(obj.code); 
                        $('#shortdesc').val(obj.shortdesc); 
                        $('#discription').val(obj.desc); 
                        $('#price').val(obj.price); 
                        $('#csr').val(obj.csr); 
                        $('#category').val(obj.categoryid); 
                         
                        $('#recordOption').val('2');
                        $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');
                    }
                });
            }
        });
    });

    function deactive_confirm() {
        return confirm("Are you sure you want to deactive this?");
    }

    function active_confirm() {
        return confirm("Are you sure you want to active this?");
    }

    function delete_confirm() {
        return confirm("Are you sure you want to remove this?");
    }
</script>
<?php include "include/footer.php"; ?>
