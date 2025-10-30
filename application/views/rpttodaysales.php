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
                                    <div class="page-header-icon"><i class="fas fa-cart-arrow-down"></i></div>
                                    <span>Today Sales Report</span>
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
                                <div class="form-row">
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Item Name</label>
                                        <select class="form-control form-control-sm" name="itemname" id="itemname" required>
                                            <option value="">Select</option>
                                            <?php foreach ($item->result() as $row) { ?>
                                                <option value="<?php echo $row->idtbl_res_item ?>"><?php echo $row->itemname ?></option>
                                            <?php } ?>
                                        </select>                                   
                                    </div>
                                    <div class="col-2"><br>
                                        <button type="button" id="searchButton" class="btn btn-info mb-2"><span id="boot-icon" class="bi bi-search" style="font-size: 15px;">&nbsp;Search</span></button>
                                    </div>
                                </div>6
                            </div>
                        </div>
                        <div class="row">  
                            <div class="col-12">
                                <hr class="border-dark">
                                <div class="scrollbar pb-3" id="style-2">
                                    <table class="table table-striped table-bordered table-sm nowrap" id="dataTable" style="width:100%">
                                        <thead>
                                            <tr>                                   		
                                                <th>INVOICE NO</th>
                                                <!-- <th>ITEM NAME</th> -->
                                                <th>INVOICE DATE</th>
                                                <th>DISCOUNT</th>
                                                <th>GROSS TOTAL</th>
                                                <th>NET TOTAL</th>
                                                <th>ORDER ID</th>
                                                <th>TABLE ID</th>
                                                <th class="text-right">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot>
                                        </tfoot>
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

<!-- Details view model -->
<div class="modal fade" id="tableModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="tblData" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ITEM NAME</th>
                                <th>QUANTITY</th>
                                <th>SALE PRICE</th>
                                <th>TOTAL</th>
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

<?php include "include/footerscripts.php"; ?>


<script type="text/javascript">
$(document).ready(function () {
    $('#itemname').select2();

    // Initialize DataTable
    var table = $('#dataTable').DataTable({
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
            { extend: 'csv', className: 'btn btn-success btn-sm', title: 'Todaysales Information', text: '<i class="fas fa-file-csv mr-2"></i> CSV', },
            { extend: 'pdf', className: 'btn btn-danger btn-sm', title: 'Todaysales Information', text: '<i class="fas fa-file-pdf mr-2"></i> PDF', },
            { 
                extend: 'print', 
                title: 'Todaysales Information',
                className: 'btn btn-primary btn-sm', 
                text: '<i class="fas fa-print mr-2"></i> Print',
                customize: function ( win ) {
                    $(win.document.body).find( 'table' )
                        .addClass( 'compact' )
                        .css( 'font-size', 'inherit' );
                }, 
            },
        ],
        ajax: {
            url: "<?php echo base_url() ?>scripts/rpttodaysaleslist.php",
            type: "POST", // you can use GET
        },
        "order": [[ 0, "desc" ]],
        "columns": [
            { "data": "idtbl_invoice" },
            // { "data": "itemname" },
            { "data": "invdate" },
            { "data": "discount" },
            { "data": "grosstotal" },
            { "data": "nettotal" },        
            { "data": "orderid" },
            { "data": "tableid" },
            { 
                "targets": -1,
                "className": 'text-right',
                "data": null,
                "render": function(data, type, full) {
                    var button = '';
                    button += '<button class="btn btn-info btn-sm btnSales mr-1';
                    button += '" id="' + full['idtbl_invoice'] + '"><i class="fas fa-eye"></i></button>';
                    return button;
                }
            }      
        ],
        drawCallback: function(settings) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    // Add event listener for the search button
    $('#searchButton').on('click', function() {
        // Get the selected item name
        var itemName = $('#itemname').val();

        // Reload the DataTable with a filter on the selected item name
        table.column(1).search(itemName).draw();
    });

    $('#dataTable').on('click', '.btnSales', function() {
        var id = $(this).attr('id');
        $('#tableModal').modal('show');
        $('#tblData').DataTable({
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
                { extend: 'csv', className: 'btn btn-success btn-sm', title: 'Todaysales Information', text: '<i class="fas fa-file-csv mr-2"></i> CSV', },
                { extend: 'pdf', className: 'btn btn-danger btn-sm', title: 'Todaysales Information', text: '<i class="fas fa-file-pdf mr-2"></i> PDF', },
                { 
                    extend: 'print', 
                    title: 'Todaysales Information',
                    className: 'btn btn-primary btn-sm', 
                    text: '<i class="fas fa-print mr-2"></i> Print',
                    customize: function ( win ) {
                        $(win.document.body).find( 'table' )
                            .addClass( 'compact' )
                            .css( 'font-size', 'inherit' );
                    }, 
                },
            ],
            ajax: {
                url: "<?php echo base_url() ?>scripts/rpttodaysalemodallist.php",
                type: "POST", // you can use GET
                "data": function(data){
                    data.row_id = id; // Pass selected row's ID to server
                },
            },
            "order": [
                [0, "desc"]
            ],
            "columns": [
                { "data": "idtbl_invoice_detail" },
                { "data": "itemname" },
                { "data": "qty" },
                { "data": "saleprice" },
                { "data": "total" },
            ],
            drawCallback: function(settings) {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
    }); 
});
</script>


<?php include "include/footer.php"; ?>
