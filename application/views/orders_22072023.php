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
                            <div class="page-header-icon"><i class="fas fa-map-marker-alt"></i></div>
                            <span>Orders</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                      
                           
                            <div class="col-12">
                                <div class="scrollbar pb-3" id="style-2">
                                    <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Date</th>
												<th>Customer</th>
                                                <th>Total</th>
                                                <th>Discount</th>
                                                <th>Net Total</th>
                                                <th>Pay status</th>
                                                <th>Process status</th>
                                                <th>Delivery status</th>
                                                <th class="text-right">Actions</th>
                                            </tr>
                                        </thead>
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
                { extend: 'csv', className: 'btn btn-success btn-sm', title: 'Orderss Information', text: '<i class="fas fa-file-csv mr-2"></i> CSV', },
                { extend: 'pdf', className: 'btn btn-danger btn-sm', title: 'Orderss Information', text: '<i class="fas fa-file-pdf mr-2"></i> PDF', },
                { 
                    extend: 'print', 
                    title: 'Orderss Information',
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
                url: "<?php echo base_url() ?>scripts/orderslist.php",
                type: "POST", // you can use GET
                // data: function(d) {}
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_res_order"
                },
                {
                    "data": "orderdate"
                },
                {
                    "targets": [ 2 ],
                    "render": function ( data, type, row ) {
                   return  row.firstname +','+ row.lastname +'' ;
                      }  
                },
				{
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        return addCommas(parseFloat(full['total']).toFixed(2));
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        return addCommas(parseFloat(full['discount']).toFixed(2));
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        return addCommas(parseFloat(full['nettotal']).toFixed(2));
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-center',
                    "data": null,
                    "render": function(data, type, full) {
                        if(full['paystatus']==0){return '<span class="text-danger"><i class="fas fa-money-bill-alt mr-2"></i>Cash on delivery</span>';}
                        else{return '<span class="text-success"><i class="fas fa-money-bill-alt mr-2"></i>Payment complete</span>';}
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-center',
                    "data": null,
                    "render": function(data, type, full) {
                        if(full['processstatus']==null){return '<span class="text-danger"><i class="fas fa-exchange-alt mr-2"></i>Please transfer to kitchen</span>';}
                        else if(full['enddatetime']!=null){return '<span class="text-dark"><i class="fas fa-truck mr-2"></i>Your request on the way</span>';}
                        else if(full['processstatus']==0){return '<span class="text-dark"><i class="fas fa-spinner mr-2"></i>Not start your request</span>';}
                        else if(full['processstatus']==1){return '<span class="text-success"><i class="fas fa-hourglass-start mr-2"></i>Start your request</span>';}
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-center',
                    "data": null,
                    "render": function(data, type, full) {
                        if(full['paystatus']==1){return '<span class="text-success"><i class="fas fa-clipboard-list-check mr-2"></i>Order Complete</span>';}
                        else{return '';}
                    }
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        var button='';
                        if(full['acceptstatus']==1){
                            button+='<a href="<?php echo base_url() ?>Orders/Acceptpaystatus/'+full['idtbl_res_order']+'/2" onclick="return reject_confirm()" target="_self" class="btn btn-success btn-sm mr-1 ';if(statuscheck!=1){button+='d-none';}button+='"><i class="fas fa-check"></i></a>';
                        }
                        else{
                            button+='<a href="<?php echo base_url() ?>Orders/Acceptpaystatus/'+full['idtbl_res_order']+'/1" onclick="return accept_confirm()" target="_self" class="btn btn-warning btn-sm mr-1 ';if(statuscheck!=1){button+='d-none';}button+='"><i class="fas fa-times"></i></a>';
                        }

                        if(full['paystatus']==0){
                            button+='<a href="<?php echo base_url() ?>Orders/Acceptpaystatus/'+full['idtbl_res_order']+'/3" onclick="return pay_confirm()" target="_self" class="btn btn-danger btn-sm mr-1 ';if(statuscheck!=1){button+='d-none';}button+='"><i class="fas fa-times"></i></a>';
                        }
                        if(full['idtbl_res_kot']==null){
                            button+='<button class="btn btn-primary btn-sm btnTransfer mr-1 ';if(addcheck!=1){button+='d-none';}button+='" id="'+full['idtbl_res_order']+'"><i class="fas fa-exchange-alt"></i></button>';
                        }
                        
                        return button;
                    }
                }
            ],
            drawCallback: function(settings) {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });

        $('#dataTable tbody').on('click', '.btnTransfer', function() {
            var r = confirm("Are you sure to transfer this order to the kitchen?");
            if (r == true) {
                var id = $(this).attr('id');
                $.ajax({
                    type: "POST",
                    data: {
                        recordID: id
                    },
                    url: '<?php echo base_url() ?>Orders/Ordertransfertokitchen',
                    success: function(result) { //alert(result);
                        var obj = JSON.parse(result);
                        action(obj.action);
                        $('#dataTable').DataTable().ajax.reload( null, false );
                    }
                });  
            }          
        });
    });

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
                '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
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

    function addCommas(nStr){
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

    function reject_confirm() {
        return confirm("Are you sure you want to Reject this?");
    }

    function accept_confirm() {
        return confirm("Are you sure you want to Accept this?");
    }

    function pay_confirm() {
        return confirm("Are you sure you want to Complete Payment ?");
    }

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
