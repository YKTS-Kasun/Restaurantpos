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
                            <div class="page-header-icon"><i class="fas fa-users"></i></div>
                            <span>Supplier</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-4">
                                <form action="<?php echo base_url() ?>Supplier/Supplierinsertupdate" method="post" autocomplete="off">
                                    <div class="form-row mb-1">
                                        <div class="col-7">
                                            <label class="small font-weight-bold text-dark">Supplier Name*</label>
                                            <input type="text" class="form-control form-control-sm" name="suppliername"
                                                id="suppliername" required>
                                        </div>
                                        <div class="col-5">
                                            <label class="small font-weight-bold text-dark">Supplier Code*</label>
                                            <input type="text" class="form-control form-control-sm" name="suppliercode" id="suppliercode" readonly required>
                                        </div>                                
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold text-dark">Address*</label>
                                        <textarea name="address" id="address" class="form-control form-control-sm"></textarea>
                                    </div>
                                    <div class="form-row mb-1">
                                        <div class="col">
                                        <label class="small font-weight-bold text-dark">Email address*</label>
                                            <input type="email" class="form-control form-control-sm" name="email" id="email">
                                        </div>
                                        <div class="col">
                                        <label class="small font-weight-bold text-dark">Primary Contact*</label>
                                            <input type="tel" class="form-control form-control-sm" name="primarycontact"
                                                id="primarycontact" required>
                                        </div>
                                    </div>
                                    <div class="form-row mb-1">
                                        <div class="col">
                                        <label class="small font-weight-bold text-dark">Secondary Contact</label>
                                            <input type="tel" class="form-control form-control-sm" name="secondarycontact"
                                                id="secondarycontact">
                                        </div>
                                        <div class="col">
                                        <label class="small font-weight-bold text-dark">Material Category*</label>
                                            <select class="form-control form-control-sm" name="category[]" id="category" multiple required>
                                                <?php foreach($materialcategory->result() as $rowmaterialcategory){ ?>
                                                <option value="<?php echo $rowmaterialcategory->idtbl_res_material_category ?>"><?php echo $rowmaterialcategory->category ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row mb-1">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Remark</label>
                                            <textarea name="remark" id="remark" class="form-control form-control-sm"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group mt-2 text-right">
                                        <button type="submit" id="submitBtn" class="btn btn-primary btn-sm px-4" <?php if($addcheck==0){echo 'disabled';} ?>><i class="far fa-save"></i>&nbsp;Add</button>
                                    </div>
                                    <input type="hidden" name="recordOption" id="recordOption" value="1" required>
                                    <input type="hidden" name="recordID" id="recordID" value="">
                                </form>
                            </div>
                            <div class="col-8">
                                <div class="scrollbar pb-3" id="style-2">
                                    <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Supplier</th>
                                                <th>Code</th>
                                                <th>Contact 1</th>
                                                <th>Contact 2</th>
                                                <th>Email</th>
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
        $('#category').select2();
        $('#materialinfo').select2();

        $.ajax({
        	url: '<?php echo base_url(); ?>Supplier/Getsuppliercode',
        	dataType: 'json',
        	success: function (response) {
        		var supplierCount = response.supplierCount;
        		var supplierCode = 'SUPCODE-' + supplierCount;
        		$('#suppliercode').val(supplierCode);
        	},
        	error: function (xhr, status, error) {
        		console.error('Error fetching supplier count:', error);
        	}
        });


        var addcheck='<?php echo $addcheck; ?>';
        var editcheck='<?php echo $editcheck; ?>';
        var statuscheck='<?php echo $statuscheck; ?>';
        var deletecheck='<?php echo $deletecheck; ?>';

        $('#dataTable').DataTable({
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
                { extend: 'csv', className: 'btn btn-success btn-sm', title: 'Supplier Information', text: '<i class="fas fa-file-csv mr-2"></i> CSV', },
                { extend: 'pdf', className: 'btn btn-danger btn-sm', title: 'Supplier Information', text: '<i class="fas fa-file-pdf mr-2"></i> PDF', },
                { 
                    extend: 'print', 
                    title: 'Supplier Information',
                    className: 'btn btn-primary btn-sm', 
                    text: '<i class="fas fa-print mr-2"></i> Print',
                    customize: function ( win ) {
                        $(win.document.body).find( 'table' )
                            .addClass( 'compact' )
                            .css( 'font-size', 'inherit' );
                    }, 
                },
                // 'csv', 'pdf', 'print'
            ],
            ajax: {
                url: "<?php echo base_url() ?>scripts/supplierlist.php",
                type: "POST", // you can use GET
                // data: function(d) {}
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_supplier"
                },
                {
                    "data": "suppliername"
                },
                {
                    "data": "suppliercode"
                },
                {
                    "data": "primarycontactno"
                },
                {
                    "data": "secondarycontactno"
                },
                {
                    "data": "email"
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button='';
                        button+='<button class="btn btn-primary btn-sm btnEdit mr-1 ';if(editcheck!=1){button+='d-none';}button+='" id="'+full['idtbl_supplier']+'"><i class="fas fa-pen"></i></button>';
                        if(full['status']==1){
                            button+='<a href="<?php echo base_url() ?>Supplier/Supplierstatus/'+full['idtbl_supplier']+'/2" onclick="return deactive_confirm()" target="_self" class="btn btn-success btn-sm mr-1 ';if(statuscheck!=1){button+='d-none';}button+='"><i class="fas fa-check"></i></a>';
                        }else{
                            button+='<a href="<?php echo base_url() ?>Supplier/Supplierstatus/'+full['idtbl_supplier']+'/1" onclick="return active_confirm()" target="_self" class="btn btn-warning btn-sm mr-1 ';if(statuscheck!=1){button+='d-none';}button+='"><i class="fas fa-times"></i></a>';
                        }
                        button+='<a href="<?php echo base_url() ?>Supplier/Supplierstatus/'+full['idtbl_supplier']+'/3" onclick="return delete_confirm()" target="_self" class="btn btn-danger btn-sm ';if(deletecheck!=1){button+='d-none';}button+='"><i class="fas fa-trash-alt"></i></a>';
                        
                        return button;
                    }
                }
            ],
            drawCallback: function(settings) {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
        $('#dataTable tbody').on('click', '.btnEdit', function() {
            var r = confirm("Are you sure, You want to Edit this ? ");
            if (r == true) {
                var id = $(this).attr('id');
                $.ajax({
                    type: "POST",
                    data: {
                        recordID: id
                    },
                    url: '<?php echo base_url() ?>Supplier/Supplieredit',
                    success: function(result) { //alert(result);
                        var obj = JSON.parse(result);
                        $('#recordID').val(obj.id);
                        $('#suppliername').val(obj.suppliername);                       
                        $('#suppliercode').val(obj.suppliercode);                       
                        $('#address').val(obj.address);                       
                        $('#country').val(obj.country);                       
                        $('#email').val(obj.email);                       
                        $('#primarycontact').val(obj.primarycontactno);                       
                        $('#secondarycontact').val(obj.secondarycontactno);
                        $('#remark').val(obj.remark);
                        
                        var categorylist = obj.categorylist;
                        var categorylistoption = [];
                        $.each(categorylist, function(i, item) {
                            categorylistoption.push(categorylist[i].categorylistID);
                        });

                        $('#category').val(categorylistoption);
                        $('#category').trigger('change');

                        // var materiallist = obj.materiallist;
                        // var materiallistoption = [];
                        // $.each(materiallist, function(i, item) {
                        //     materiallistoption.push(materiallist[i].materiallistID);
                        // });

                        // $('#materialinfo').val(materiallistoption);
                        // $('#materialinfo').trigger('change');                        

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
