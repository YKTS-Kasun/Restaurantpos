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
                                    <div class="page-header-icon"><i class="fas fa-file"></i></div>
                                    <span>Cancel Sale Report</span>
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body">

                        <div class="row">
                            <div class="col-12">
                                <form id="searchReport">
                                    <div class="form-row">
                                        <div class="col-2">
                                            <label class="small font-weight-bold text-dark">From Date</label>
                                            <input type="date" class="form-control form-control-sm" name="formdate" id="formdate" required>
                                        </div>
                                        <div class="col-2">
                                            <label class="small font-weight-bold text-dark">To Date</label>
                                            <input type="date" class="form-control form-control-sm" name="todate" id="todate" required>
                                        </div>
                                        <div class="col-2">
                                            <label class="small font-weight-bold text-dark">&nbsp;</label><br>
                                            <button type="button" id="btnformsearch" class="btn btn-outline-primary btn-sm ml-auto px-5">Search</button>
                                        </div>
                                        <input type="submit" id="hidesubmitbtn" class="d-none">
                                    </div>
                                </form>
                            </div>
                            <div class="col-12">
                                <hr class="border-dark">
                                <div class="scrollbar pb-3" id="style-2">
                                    <table class="table table-striped table-bordered table-sm nowrap" id="cashsaleTable"
                                    	style="width:100%">
                                    	<thead>
                                    		<tr>
                                    			<th>#</th>
                                                <th>DATE</th>
                                    			<th>INVOICE NO</th>
                                                <th>REMARK</th>
                                                <th>USER</th>                       			
                                                <th>AMOUNT</th>                              			
                                    		</tr>
                                    	</thead>
                                    	<tbody>
                                    	</tbody>
                                    	<!-- <tfoot>
                                    		<tr>
                                    			<th colspan="5" class="text-right">Total:</th>
                                    			<th class="text-right"></th>
                                    		</tr>
                                    	</tfoot> -->
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


<script type="text/javascript">
    let today = new Date().toISOString().slice(0, 10)
    $(document).ready(function () {
        $('#btnformsearch').click(function(){
            if (!$("#searchReport")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#hidesubmitbtn").click();
            } else {
                var formdate = $('#formdate').val();
                var todate = $('#todate').val();

                $('#cashsaleTable').DataTable({
                    "destroy": true,
                    "processing": true,
                    "serverSide": true,
                    ajax: {
                        url: "scripts/rptcancelsale.php",
                        type: "POST", // you can use GET
                        "data": function (d) {
                            // d.cashier = cashier,
                            d.formdate = formdate,
                            d.todate = todate
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
                            "targets": -1,
                            "className": '',
                            "data": null,
                            "render": function(data, type, full) {
                                return 'INV/DT-'+full['idtbl_invoice'];
                            }
                        },
                        {
                            "targets": -1,
                            "className": '',
                            "data": null,
                            "render": function(data, type, full) {
                                return '';
                            }
                        },
                        {
                            "data": "name"
                        },
                        { 
                            "targets": -1,
                            "className": 'text-right',
                            "data": null,
                            "render": function(data, type, full) {
                                return addCommas(parseFloat(full['nettotal']).toFixed(2));
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
                            filename: 'Cashier Sales Report' + today,
                            text: '<i class="fas fa-file-csv mr-2"></i> CSV',
                            footer: true,
                            title: 'City Lounge Bar & Restaurant',
                            messageTop: 'Cashier Sales Report'
                        },
                        {
                            extend: 'excel',
                            className: 'btn btn-info btn-sm',
                            filename: 'Cashier Sales Report' + today,
                            text: '<i class="fas fa-file-excel mr-2"></i> EXCEL',
                            footer: true,
                            title: 'City Lounge Bar & Restaurant',
                            messageTop: 'Cashier Sales Report'
                        },
                        {
                            extend: 'pdf',
                            className: 'btn btn-danger btn-sm',
                            filename: 'Cashier Sales Report' + today,
                            text: '<i class="fas fa-file-pdf mr-2"></i> PDF',
                            footer: true,
                            title: 'City Lounge Bar & Restaurant',
                            messageTop: {
                                text: 'Cashier Sales Report',
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
                            filename: 'Cash Sales Report' + today,
                            text: '<i class="fas fa-print mr-2"></i> PRINT',
                            footer: true,
                            title: 'Gryffindor Restaurant',
                            messageTop: 'Cash Sales Report',
                            customize: function (doc) {
                                doc.styles.title = {
                                    color: 'black',
                                    fontSize: '30',
                                    alignment: 'center',
                                }
                            }
                        }
                    ],
                    // "footerCallback": function (row, data, start, end, display) {
                    //     var api = this.api(),
                    //         data;

                    //     // Remove the formatting to get integer data for summation
                    //     var intVal = function (i) {
                    //         return typeof i === 'string' ?
                    //             i.replace(/[\$,]/g, '') * 1 :
                    //             typeof i === 'number' ?
                    //             i : 0;
                    //     };

                    //     // Total over all pages
                    //     totalAmount = api
                    //         .column(5)
                    //         .data()
                    //         .reduce(function (a, b) {
                    //             return intVal(a) + intVal(b);
                    //         }, 0);

                    //     // Update footer
                    //     $(api.column(5).footer()).html(
                    //         addCommas(parseFloat(totalAmount).toFixed(2))
                    //     );
                    // },
                    drawCallback: function (settings) {
                        $('[data-toggle="tooltip"]').tooltip();
                    }
                });
            }
        });
    });

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
</script>

<?php include "include/footer.php"; ?>