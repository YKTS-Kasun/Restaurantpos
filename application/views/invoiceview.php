<?php 
include "include/header.php"; 

include "include/topnavbar.php"; 
?>

<style>
    content-display {
        display: none;
    }
</style>


<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <?php include "include/menubar.php"; ?>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="page-header page-header-light bg-white shadow">
                <div class="container-fluid">
                    <div class="page-header-content py-3">
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <h1 class="page-header-title">
                                    <div class="page-header-icon"><i class="fas fa-file-alt"></i></div>
                                    <span>Invoice View</span>
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
            	<div class="card">
            		<div class="card-body">
                        <form id="searchform">
                            <div class="row">
                                <div class="col-2">
                                    <label class="small font-weight-bold text-dark">From Date*</label>
                                    <input type="date" class="form-control form-control-sm" name="fromdate" id="fromdate" value="<?php echo date('Y-m-d') ?>" required>
                                </div>
                                <div class="col-2">
                                    <label class="small font-weight-bold text-dark">To Date*</label>
                                    <input type="date" class="form-control form-control-sm" name="todate" id="todate" readonly>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">&nbsp;</label><br>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="todayinvoice" checked>
                                        <label class="custom-control-label" for="todayinvoice">Today invoices</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">&nbsp;</label><br>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="unpaidinvoice">
                                        <label class="custom-control-label" for="unpaidinvoice">Unpaid Invoices</label>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <label class="small font-weight-bold text-dark">&nbsp;</label><br>
                                    <button type="button" class="btn btn-primary btn-sm px-4" id="searchbtn"><i class="fas fa-search mr-2"></i>Search Invoice</button>
                                    <input type="submit" id="hidesubmit" class="d-none">
                                </div>
                            </div>
                        </form>
            			<div class="row">
            				<div class="col-12">
                                <hr>
            					<div class="scrollbar pb-3" id="style-2">
            						<table class="table table-striped table-bordered table-sm nowrap" id="invoicetable"
            							style="width:100%">
            							<thead class="table-success">
            								<tr>
            									<th>#</th>
            									<th>Invoice Date</th>
                                                <th>Invoice No.</th>
                                                <th>Gross Total</th>
            									<th>Discount</th>
            									<th>Net Total</th>
                                                <th>Status</th>
            									<th>Action</th>
            								</tr>
            							</thead>
            							<tbody>
            							</tbody>
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
<div class="modal fade" id="pordereditmodal" data-backdrop="static" data-keyboard="false" tabindex="-1"
	aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel">Add Discount</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col">
                    <form action="<?php echo base_url() ?>Invoiceview/Invoiceupdate" method="post" autocomplete="off">
                        <div class="form-row mb-1">
								<div class="col">
									<label class="small font-weight-bold text-dark">Total</label>
									<input type="text" id="total" name="total"
										class="form-control form-control-sm" readonly>
                                        <input type="hidden" id="hiddeninvId" name="hiddeninvId"
										class="form-control form-control-sm" readonly>
								</div>
							</div>
							<div class="form-row mb-1">
								<div class="col">
									<label class="small font-weight-bold text-dark">Discount</label>
									<div class="input-group">
										<input type="number" class="form-control form-control-sm col-8"
											name="discount" id="discount">
										<input type="text" value="%" class="form-control form-control-sm col-4"
											readonly>
									</div>
								</div>
							</div>
                            <div class="form-row mb-1">
								<div class="col">
									<label class="small font-weight-bold text-dark">Discount Amount</label>
										<input type="number" class="form-control form-control-sm"
											name="discountamount" id="discountamount" readonly>
								</div>
							</div>
                            <div class="form-row mb-1">
								<div class="col">
									<label class="small font-weight-bold text-dark">Net Total</label>
									<input type="text" id="nettotal" name="nettotal"
										class="form-control form-control-sm">
								</div>
							</div>
                            <div class="form-group mt-2 text-right">
                                        <button type="submit" id="submitBtn" class="btn btn-primary btn-sm px-4"
                                         <?php if($addcheck==0){echo 'disabled';} ?>><i class="far fa-save"></i>&nbsp;Update Invoice</button>
                                    </div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Modal -->
<div class="modal fade" id="viewInvoicelist" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalScrollableTitle">View Invoice List</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div id="viewdata"></div>
    </div>
  </div>
</div>
<?php include "include/footerscripts.php"; ?>
<script type="text/javascript">
    $(document).ready(function () {
        dataloadtable();

        $('#todayinvoice').change(function() {
            if(this.checked) {$('#todate').prop('readonly', true).prop('required', false);}
            else{$('#todate').prop('readonly', false).prop('required', true);}
        });

        $('#searchbtn').click(function(){
            if (!$("#searchform")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidesubmit").click();
            } else {   
                dataloadtable();
            }
        });

        $(document).on("click", ".btnviewList", function () {
            var id = $(this).attr('id');
            $('#viewInvoicelist').modal('show');
            // alert(id);
            $.ajax({
                type: "POST",
                data: {
                    recordID: id
                },
                url: '<?php echo base_url() ?>Invoiceview/Getinvoicedetails',
                success: function (result) { //alert(result);

                    $('#viewdata').html(result);
                    $('#tblInvoicelist').DataTable({
                        "ordering": false,
                        dom: "<'row'<'col-sm-5'B><'col-sm-2'l><'col-sm-5'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                        responsive: true,
                        lengthMenu: [
                            [10, 25, 50, -1],
                            [10, 25, 50, 'All'],
                        ],
                        "buttons": [{
                                extend: 'csv',
                                className: 'btn btn-success btn-sm',
                                title: 'Invoice List',
                                text: '<i class="fas fa-file-csv mr-2"></i> CSV',
                            },
                            {
                                extend: 'pdf',
                                className: 'btn btn-danger btn-sm',
                                title: 'Invoice List',
                                text: '<i class="fas fa-file-pdf mr-2"></i> PDF',
                            },
                            {
                                extend: 'print',
                                title: 'Invoice List',
                                className: 'btn btn-primary btn-sm',
                                text: '<i class="fas fa-print mr-2"></i> Print',
                                customize: function (win) {
                                    $(win.document.body).find('table')
                                        .addClass('compact')
                                        .css('font-size', 'inherit');
                                },
                            },
                        ],
                        footerCallback: function (row, data, start, end, display) {
                            var api = this.api();

                            // Remove the formatting to get integer data for summation
                            var intVal = function (i) {
                                return typeof i === 'string' ?
                                    i.replace(/[\$,]/g, '') * 1 :
                                    typeof i === 'number' ?
                                    i : 0;
                            };

                            // Total over all pages
                            total = api
                                .column(3)
                                .data()
                                .reduce(function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0);

                            // Total over this page
                            pageTotal = api
                                .column(3, {
                                    page: 'current'
                                })
                                .data()
                                .reduce(function (a, b) {
                                    return parseFloat(intVal(a) + intVal(b)).toFixed(2);
                                }, 0);

                            // Update footer
                            $(api.column(3).footer()).html(
                                // pageTotal=parseFloat(pageTotal).toFixed(2);
                                'Rs. ' + pageTotal
                            );

                        },
                        drawCallback: function (settings) {
                            $('[data-toggle="tooltip"]').tooltip();
                        }
                    });;


                }
            });
        });

        $('#invoicetable tbody').on('click', '.btnEdit', function () {
            var r = confirm("Are you sure you want to edit this?");
            if (r == true) {
                var id = $(this).attr('id');
                $.ajax({
                    type: "POST",
                    data: {
                        recordID: id
                    },
                    url: '<?php echo base_url() ?>Invoiceview/Invoiceedit',
                    success: function (result) {
                            var obj = JSON.parse(result);

                            $('#hiddeninvId').val(obj.id);
                            $('#total').val(obj.nettotal);
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX request error:', error);
                    }
                });
            }
        });

        $('#discount').on('input', function() {
            var discount = parseFloat($(this).val());
            var total = parseFloat($('#total').val());

            if (!isNaN(discount) && !isNaN(total)) {
                var discountAmount = total * (discount / 100);
                var netTotal = total - discountAmount;

                $('#discountamount').val(discountAmount.toFixed(2));
                $('#nettotal').val(netTotal.toFixed(2));
            }
        });

    });

    function dataloadtable(){
        var addcheck='<?php echo $addcheck; ?>';
        var editcheck='<?php echo $editcheck; ?>';
        var statuscheck='<?php echo $statuscheck; ?>';
        var deletecheck='<?php echo $deletecheck; ?>';
        let today = new Date().toISOString().slice(0, 10);

        var fromdate = $('#fromdate').val();
        var todate = $('#todate').val();
        if ($('#todayinvoice').is(":checked")){var viewtoday=1;}
        else{var viewtoday=0;}
        if ($('#unpaidinvoice').is(":checked")){var unpaidinvoice=1;}
        else{var unpaidinvoice=0;}

        $('#invoicetable').DataTable({
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "scripts/invoiceviewlist.php",
                type: "POST", // you can use GET
                data: function(d) {
                    d.fromdate = fromdate,
                    d.todate = todate,
                    d.viewtoday = viewtoday,
                    d.unpaidinvoice = unpaidinvoice
                }
            },
            "order": [
                [0, "desc"]
            ],
            "columns": [{
                        "data": null,
                        "render": function(data, type, full, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        "data": "invdate"
                    },
                    {
                        "data": "idtbl_invoice",
                        "render": function(data, type, full) {
                            if (full['invtype'] == 1) {
                                return "INV-" + data;
                            } else {
                                return "INV-" + data;
                            }
                        }
                    },
                    {
                        "targets": -1,
                        "className": 'text-left',
                        "data": null,
                        "render": function(data, type, full) {
                            return addCommas(parseFloat(full['grosstotal']).toFixed(2));
                        }
                    },
                    {
                        "targets": -1,
                        "className": 'text-left',
                        "data": null,
                        "render": function(data, type, full) {
                            return addCommas(parseFloat(full['discount']).toFixed(2));
                        }
                    },
                    {
                        "targets": -1,
                        "className": 'text-left',
                        "data": null,
                        "render": function(data, type, full) {
                            return addCommas(parseFloat(full['nettotal']).toFixed(2));
                        }
                    },
                    {
                        "targets": -1,
                        "className": 'text-left',
                        "data": null,
                        "render": function(data, type, full) {
                            if (full['status'] == 3) {
                                return 'Cancelled Invoice';
                            } else {
                                return '';
                            }
                        }
                    },
                    {
                        "targets": -1,
                        "className": 'text-right',
                        "data": null,
                        "render": function(data, type, full) {
                            var button = '';
                                button+='<button class="btn btn-info btn-sm btnEdit mr-1 ';if(editcheck!=1){button+='d-none';}button+='" id="'+full['idtbl_invoice']+'" data-toggle="modal" data-target="#pordereditmodal"><i class="fas fa-pen"></i></button>';
                                button+='<button class="btn btn-secondary btn-sm btnviewList mr-1 ';if(editcheck!=1){button+='d-none';}button+='" id="'+full['idtbl_invoice']+'"><i class="fas fa-list"></i></button>';
                                button += '<a href="<?php echo base_url() ?>Directsale/Getcreditprintbill/' + full['idtbl_invoice'] + '" target="_blank" class="btn btn-dark btn-sm mr-1"><i class="fas fa-print"></i></a>';
                                button+='<a href="<?php echo base_url() ?>Invoiceview/Invoicestatus/'+full['idtbl_invoice']+'/3" onclick="return delete_confirm()" target="_self" class="btn btn-danger btn-sm ';if(deletecheck!=1){button+='d-none';}button+='"><i class="fas fa-trash-alt"></i></a>';
                            return button;
                        }
                    }


            ],

            dom: "<'row'<'col-sm-4'B><'col-sm-3'l><'col-sm-5'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            responsive: true,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, 'All'],
                ],
            buttons: [
                {
                    extend: 'csv',
                    className: 'btn btn-success btn-sm',
                    filename: 'Invoice Details' + today,
                    text: '<i class="fas fa-file-csv mr-2"></i> CSV',
                    footer: true,
                    title: 'City Lounge',
                    messageTop: 'Stock Report'
                },
                {
                    extend: 'excel',
                    className: 'btn btn-info btn-sm',
                    filename: 'Invoice Details' + today,
                    text: '<i class="fas fa-file-excel mr-2"></i> EXCEL',
                    footer: true,
                    title: 'City Lounge',
                    messageTop: 'Invoice Details'
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-danger btn-sm',
                    filename: 'Invoice Details' + today,
                    text: '<i class="fas fa-file-pdf mr-2"></i> PDF',
                    footer: true,
                    title: 'City Lounge',
                    messageTop: {
                        text: 'Invoice Details',
                        fontSize: 20,
                        bold: true,
                        alignment: 'center'
                    },
                    customize: function (doc) {
                        doc.styles.title = {
                            bold: 60,
                            color: '#2F5233',
                            fontSize: '30',
                            alignment: 'center',
                        }
                    }
                },
                {
                    extend: 'print',
                    className: 'btn btn-primary btn-sm',
                    filename: 'Material Stock Report' + today,
                    text: '<i class="fas fa-print mr-2"></i> PRINT',
                    footer: true,
                    title: 'City Lounge',
                    messageTop: 'Invoice Details',
                    customize: function (doc) {
                        doc.styles.title = {
                            color: 'black',
                            fontSize: '30',
                            alignment: 'center',
                        }
                    }
                }
            ],

                drawCallback: function (settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                },
                createdRow: function(row, data, dataIndex) {
                    if (data.status == 3) {
                        $(row).css({
                            'background-color': 'red',
                            'color': 'white',
                            'font-weight': 'bold'
                        });
                    }
                }
        });
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

    function delete_confirm() {
        return confirm("Are you sure you want to cancel this Invoice?");
    }
</script>

<?php include "include/footer.php"; ?>