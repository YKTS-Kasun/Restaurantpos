<?php 
include "include/header.php";  
include "include/topnavbar.php"; 
?>

<style>
#dataTable {
    width: 100% !important;
}
#dataTable th, #dataTable td {
    white-space: nowrap;
    vertical-align: middle;
}
</style>

<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <?php include "include/menubar.php"; ?>
    </div>

    <div id="layoutSidenav_content">
        <main>

            <!-- PAGE HEADER -->
            <div class="page-header page-header-light bg-white shadow">
                <div class="container-fluid">
                    <div class="page-header-content py-3">
                        <h1 class="page-header-title font-weight-light">
                            <div class="page-header-icon">
                                <i class="fas fa-box-open"></i>
                            </div>
                            <span>Stock Receive</span>
                        </h1>
                    </div>
                </div>
            </div>

            <!-- CONTENT -->
            <div class="container-fluid mt-2 p-2">
                <div class="card">
                    <div class="card-body p-2">

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm w-100"
                                   id="dataTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Transfer No</th>
                                        <th>From</th>
                                        <th>Items</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

        </main>
        <?php include "include/footerbar.php"; ?>
    </div>
</div>

<!-- ===============================
     RECEIVE CONFIRM MODAL
================================ -->
<div class="modal fade" id="receiveModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-box"></i> Receive Stock
                </h5>
                <button class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body" id="receiveModalContent">
                Loading...
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary btn-sm" data-dismiss="modal">
                    Cancel
                </button>
                <button class="btn btn-success btn-sm" id="btnConfirmReceive">
                    <i class="fas fa-check"></i> Confirm Receive
                </button>
            </div>

        </div>
    </div>
</div>

<?php include "include/footerscripts.php"; ?>

<script>
let receiveTransferID = 0;

$(document).ready(function(){

$('#dataTable').DataTable({
    processing: true,
    serverSide: true,
    order:[[1,'desc']],
    ajax:{
        url:"<?= base_url() ?>scripts/stockreceivelist.php",
        type:"POST"
    },
    columns:[
        {
            data:null,
            render:(d,t,r,m)=>m.row + m.settings._iDisplayStart + 1
        },
        { data:'transfer_date' },
        { data:'transfer_no' },
        { data:'from_loc' },
        {
            data:null,
            render:function(d){
                return '<span class="badge badge-info">Items</span>';
            }
        },
        {
            data:null,
            orderable:false,
            className:'text-center',
            render:function(d){
                return `
                    <button class="btn btn-sm btn-success"
                            onclick="openReceive(${d.idtbl_stock_transfer})">
                        <i class="fas fa-download"></i> Receive
                    </button>`;
            }
        }
    ]
});


});

/* ===============================
   OPEN RECEIVE MODAL
================================ */
function openReceive(id)
{
    receiveTransferID = id;
    $('#receiveModal').modal('show');
    $('#receiveModalContent').html('Loading...');

    $.post("<?= base_url() ?>StockReceive/view_modal",{id:id},function(res){
        $('#receiveModalContent').html(res);
    });
}


/* ===============================
   CONFIRM RECEIVE
================================ */
$('#btnConfirmReceive').click(function(){

    if(!receiveTransferID) return;

    $.post("<?= base_url() ?>StockReceive/receive/"+receiveTransferID, function(res){

        let r = JSON.parse(res);

        if(r.status === 1){
            $('#receiveModal').modal('hide');
            $('#dataTable').DataTable().ajax.reload(null,false);
        }else{
            alert(r.message);
        }

    });
});
</script>

<?php include "include/footer.php"; ?>
