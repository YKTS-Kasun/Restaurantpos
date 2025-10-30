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
                            <div class="page-header-icon"><i class="fas fa-money-bill"></i></div>
                            <span>Transactions</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <form id="search">
                        	<div class="row">
                        		<div class="col-3">
                        			<label class="small font-weight-bold text-dark">Type*</label>
                        			<div class="input-group input-group-sm">
                        				<select class="form-control form-control-sm" name="report_type"
                        					id="report_type">
                        					<option value="0">Select</option>
                        					<option value="1">Order</option>
                        					<option value="2">Reserveation</option>
                        				</select>
                        			</div>
                        		</div>
                        		<div class="col-2" id="hidesumbit">&nbsp;<br>
                        			<button type="submit" class="btn btn-info mb-2"><span id="boot-icon"
                        					class="bi bi-search" style="font-size: 15px;">&nbsp;Search</span></button>
                        		</div>
                        	</div>
                        </form>
                        <br><br>
                            <div class="col-12">
                                <div class="scrollbar pb-3" id="style-2">
                                    <table class="table table-bordered table-striped table-sm nowrap" id="tblmachinetype">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Payment Status</th>
                                                <th>Sub Total</th>
												<th>Discount</th>
                                                <th>Net Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                         				<tr>
                                         	<th style="text-align:right" colspan="2">Total:</th>
                         					<th></th>
                                             <th></th>
                                             <th></th>
                         					
                         				</tr>
                         			</tfoot>
                                    </table>
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

        $("#search").submit(function (event) {
			event.preventDefault();

            $('#tblmachinetype').DataTable({
				"destroy": true,
				"processing": true,
				"serverSide": true,
				scrollY: 350,
				ajax: {
					url: "<?php echo base_url() ?>scripts/transactionlist.php",
					type: "POST", // you can use GET
					"data": function (d) {
						return $.extend({}, d, {
							"search_report_type": $("#report_type").val()
						});
					}
				},
                "order": [
					[0, "desc"]
				],

                dom: "<'row'<'col-sm-5'B><'col-sm-2'l><'col-sm-5'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            responsive: true,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, 'All'],
            ],
            "buttons": [
                { extend: 'csv', className: 'btn btn-success btn-sm', title: 'Transactionss Information', text: '<i class="fas fa-file-csv mr-2"></i> CSV', },
                { extend: 'pdf', className: 'btn btn-danger btn-sm', title: 'Transactionss Information', text: '<i class="fas fa-file-pdf mr-2"></i> PDF', },
                { 
                    extend: 'print', 
                    title: 'Transactionss Information',
                    className: 'btn btn-primary btn-sm', 
                    text: '<i class="fas fa-print mr-2"></i> Print',
                    customize: function ( win ) {
                        $(win.document.body).find( 'table' )
                            .addClass( 'compact' )
                            .css( 'font-size', 'inherit' );
                    }, 
                },
            ],
            "columns": [
                {
                    "data": "idtbl_res_transaction"
                },
                {
						"targets": -1,
						"className": 'text-left',
						"data": null,
						"render": function (data, type, full) {
							var text = '';

							if (full['paycomplete'] == 1) {
								text += '<label class="font-weight">Payment Completed</label>';
							} else {
								text +=
									'<label class="font-weight">Payment Not Completed</label>';
							}

							return text;
						}
					},
                {
                    "data": "subtotal",
                    "className": 'text-right',
                    render: $.fn.dataTable.render.number(',', '.', 2, '')
                },
				{
                    "data": "discount",
                    "className": 'text-right',
                    render: $.fn.dataTable.render.number(',', '.', 2, '')
                },
                {
                    "data": "nettotal",
                    "className": 'text-right',
                    render: $.fn.dataTable.render.number(',', '.', 2, '')
                }
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
                rejection = api
            .column(2 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

        // Total over this page
        rejection_pageTotal = api
            .column(2, { page: 'current'} )
            .data()
            .reduce( function (a, b) {
                return parseFloat(intVal(a) + intVal(b)).toFixed();
            }, 0 );

        // Update footer
        $( api.column( 2).footer() ).html(
            // pageTotal=parseFloat(pageTotal).toFixed(2);
            rejection_pageTotal
        );


        discount = api
            .column(3 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

        // Total over this page
        discount_pageTotal = api
            .column(3, { page: 'current'} )
            .data()
            .reduce( function (a, b) {
                return parseFloat(intVal(a) + intVal(b)).toFixed();
            }, 0 );

        // Update footer
        $( api.column( 3).footer() ).html(
            // pageTotal=parseFloat(pageTotal).toFixed(2);
            discount_pageTotal
        );

        nettotal = api
            .column(4 )
            .data()
            .reduce( function (a, b) {
                return intVal(a) + intVal(b);
            }, 0 );

        // Total over this page
        nettotal_pageTotal = api
            .column(4, { page: 'current'} )
            .data()
            .reduce( function (a, b) {
                return parseFloat(intVal(a) + intVal(b)).toFixed();
            }, 0 );

        // Update footer
        $( api.column( 4).footer() ).html(
            // pageTotal=parseFloat(pageTotal).toFixed(2);
            nettotal_pageTotal
        );

            },
            drawCallback: function(settings) {
                $('[data-toggle="tooltip"]').tooltip();
            }


        });
    });
       


    });

</script>
<?php include "include/footer.php"; ?>
