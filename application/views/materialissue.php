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
                            <div class="page-header-icon"><i class="fas fa-share-square"></i></div>
                            <span>Material Issue</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-12 text-right">
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#staticBackdrop" <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-plus mr-2"></i>Issue Materials</button>
                                <hr>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="scrollbar pb-3" id="style-2">
                                    <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Material</th>
                                                <th>Quantity</th>
                                                <th>Date</th>
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
<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-keyboard="false" tabindex="-1"
	aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel">Material Issue to Kitchen</h5>
				<button type="button"class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
                        <form id="createorderform" autocomplete="off">
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Date*</label>
                                    <input type="date" class="form-control form-control-sm" placeholder="" name="issuedate" id="issuedate" value="<?php echo date('Y-m-d') ?>" required>
                                </div>
                            </div>
                            <div class="form-row mb-1">
                                <div class="col">
                                <label class="small font-weight-bold text-dark">Material*</label>
                                    <select class="form-control form-control-sm" name="material" id="material" required>
                                        <option value="">Select</option>
                                        <?php foreach($materiallist->result() as $rowmateriallist){ ?>
                                        <option value="<?php echo $rowmateriallist->idtbl_res_material_info ?>"><?php echo $rowmateriallist->material.'/'.$rowmateriallist->materialinfocode ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row mb-1">
                                <div class="col">
                                <label class="small font-weight-bold text-dark">Batch No</label>
                                <input type="text" id="batchno" name="batchno" class="form-control form-control-sm" readonly>
                                </div>
                            </div>
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Qty*</label>
                                    <input type="text" id="newqty" name="newqty" class="form-control form-control-sm" <?php if($editcheck==0){echo 'readonly';} ?> required>
                                </div>
                            </div>
                            <div class="form-group mt-3 text-right">
                                <button type="button" id="formsubmit" class="btn btn-primary btn-sm px-4" <?php if($addcheck==0){echo 'disabled';} ?>><i class="fas fa-plus"></i>&nbsp;Add to list</button>
                                <input name="submitBtn" type="submit" value="Save" id="submitBtn" class="d-none">
                            </div>
                            <input type="hidden" name="refillprice" id="refillprice" value="">
                        </form>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-8 col-xl-8">
                        <div class="pb-3" id="style-3">
                            <table class="table table-striped table-bordered table-sm small" id="tableorder">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th class="d-none">ProductID</th>
                                        <th class="text-center">Qty</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="form-group mt-2">
                            <button type="button" id="btncreateorder"
                                class="btn btn-outline-primary btn-sm fa-pull-right"><i
                                    class="fas fa-save"></i>&nbsp;Issue to Kitchen</button>
                        </div>
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>
<!-- Modal -->
<div class="modal fade" id="viewmodal" data-bs-backdrop="static" data-keyboard="false" tabindex="-1"
	aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel">View Good Recieve Note</h5>
				<button type="button"class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <h4 class="text-right">GRF/GRN-0000<label id="grncode"></label></h4>
                <div id="viewhtml"></div>
			</div>
		</div>
	</div>
</div>
<?php include "include/footerscripts.php"; ?>
<script>

    $(document).ready(function() {
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
                { extend: 'csv', className: 'btn btn-success btn-sm', title: 'Material Issue Information', text: '<i class="fas fa-file-csv mr-2"></i> CSV', },
                { extend: 'pdf', className: 'btn btn-danger btn-sm', title: 'Material Issue Information', text: '<i class="fas fa-file-pdf mr-2"></i> PDF', },
                { 
                    extend: 'print', 
                    title: 'Material Issue Information',
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
                url: "<?php echo base_url() ?>scripts/materialissuelist.php",
                type: "POST", // you can use GET
                // data: function(d) {}
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_res_kitchen_order"
                },
                {
                    "data": "material"
                },
                {
                    "data": "qty"
                },
                {
                    "data": "date"
                }
            ],
            drawCallback: function(settings) {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
  
        $("#formsubmit").click(function () {
            if (!$("#createorderform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#submitBtn").click();
            } else {
                var productID = $('#material').val();
                var product = $("#material option:selected").text();
                var newqty = parseFloat($('#newqty').val());

                $('#tableorder > tbody:last').append('<tr class="pointer"><td>' + product + '</td><td class="d-none">' + productID + '</td><td class="text-center">' + newqty + '</td></tr>');

                $('#product').val('');
                $('#newqty').val('');
            }
        });
        $('#tableorder').on('click', 'tr', function () {
            var r = confirm("Are you sure, You want to remove this product ? ");
            if (r == true) {
                $(this).closest('tr').remove();
            }
        });
        $('#material').change(function(){
            var materialID = $(this).val();

            $.ajax({
                type: "POST",
                data: {
                    recordID: materialID,
                },
                url: 'Materialissue/Getbatchno',
                success: function (result) {
                    var obj = JSON.parse(result);
                    if (obj && obj.length > 0) {
                        var batchno = obj[0].batchno;
                        $('#batchno').val(batchno);
                    } else {
                        $('#batchno').val('No batch number found');
                    }
                },
                error: function (xhr, status, error) {
                    console.log(error);
                }
            });
        });



        $('#btncreateorder').click(function () { //alert('IN');
            $('#btncreateorder').prop('disabled', true).html('<i class="fas fa-circle-notch fa-spin mr-2"></i> Create Good Receive Note')
            var tbody = $("#tableorder tbody");

            if (tbody.children().length > 0) {
                jsonObj = [];
                $("#tableorder tbody tr").each(function () {
                    item = {}
                    $(this).find('td').each(function (col_idx) {
                        item["col_" + (col_idx + 1)] = $(this).text();
                    });
                    jsonObj.push(item);
                });
                console.log(jsonObj);

                var issuedate = $('#issuedate').val();
                var batchno = $('#batchno').val();
                // alert(orderdate);
                $.ajax({
                    type: "POST",
                    data: {
                        tableData: jsonObj,
                        issuedate: issuedate,
                        batchno: batchno,
                    },
                    url: 'Materialissue/Materialinsertupdate',
                    success: function (result) { //alert(result);
                        // console.log(result);
                        var obj = JSON.parse(result);
                        if(obj.status==1){
                            $('#modalgrnadd').modal('hide');
                            setTimeout(window.location.reload(), 3000);
                        }
                        action(obj.action);
                    }
                });
            }

        });
    });

    function deactive_confirm() {
        return confirm("Are you sure you want to deactive this?");
    }

    function active_confirm() {
        return confirm("Are you sure you want to approve this good receive note?");
    }

    function delete_confirm() {
        return confirm("Are you sure you want to reject this good receive note?");
    }

    function addCommas(nStr) {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }

    function action(data) { //alert(data);
        var obj = JSON.parse(data);
        $.notify({
            // options
            icon: obj.icon,
            title: obj.title,
            message: obj.message,
            url: obj.url,
            target: obj.target
        }, {
            // settings
            element: 'body',
            position: null,
            type: obj.type,
            allow_dismiss: true,
            newest_on_top: false,
            showProgressbar: false,
            placement: {
                from: "top",
                align: "center"
            },
            offset: 100,
            spacing: 10,
            z_index: 1031,
            delay: 5000,
            timer: 1000,
            url_target: '_blank',
            mouse_over: null,
            animate: {
                enter: 'animated fadeInDown',
                exit: 'animated fadeOutUp'
            },
            onShow: null,
            onShown: null,
            onClose: null,
            onClosed: null,
            icon_type: 'class',
            template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                '<button type="button" aria-hidden="true"class="btn-close" data-notify="dismiss">×</button>' +
                '<span data-notify="icon"></span> ' +
                '<span data-notify="title">{1}</span> ' +
                '<span data-notify="message">{2}</span>' +
                '<div class="progress" data-notify="progressbar">' +
                '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
                '</div>' +
                '<a href="{3}" target="{4}" data-notify="url"></a>' +
                '</div>'
        });
    }

    function getbatchno(){
        var supplierID = $('#supplier').val();

        $.ajax({
            type: "POST",
            data: {
                recordID: supplierID
            },
            url: 'Goodreceive/Getbatchnoaccosupplier',
            success: function (result) { //alert(result);
                // console.log(result);
                $('#batchno').val(result);
            }
        });
    }
</script>
<?php include "include/footer.php"; ?>
