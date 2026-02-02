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
                            <div class="page-header-icon"><i class="fas fa-utensils"></i></div>
                            <span>Customer Order Status</span>
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
												<th>Order No</th>
                                                <th>Item</th>
                                                <th>Potion</th>
                                                <th>Status</th>
                                                <th>Note</th>
                                                <!-- <th class="text-right">Actions</th> -->
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
            // dom: "<'row'<'col-sm-5'B><'col-sm-2'l><'col-sm-5'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            responsive: true,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, 'All'],
            ],
            // "buttons": [
            //     { extend: 'csv', className: 'btn btn-success btn-sm', title: 'Orderss Information', text: '<i class="fas fa-file-csv mr-2"></i> CSV', },
            //     { extend: 'pdf', className: 'btn btn-danger btn-sm', title: 'Orderss Information', text: '<i class="fas fa-file-pdf mr-2"></i> PDF', },
            //     { 
            //         extend: 'print', 
            //         title: 'Orderss Information',
            //         className: 'btn btn-primary btn-sm', 
            //         text: '<i class="fas fa-print mr-2"></i> Print',
            //         customize: function ( win ) {
            //             $(win.document.body).find( 'table' )
            //                 .addClass( 'compact' )
            //                 .css( 'font-size', 'inherit' );
            //         }, 
            //     },
            //     // 'copy', 'csv', 'excel', 'pdf', 'print'
            // ],
            
            ajax: {
                url: "<?php echo base_url() ?>scripts/customerstatuslist.php",
                type: "POST", // you can use GET
                // data: function(d) {}
            },
            "order": [[ 0, "desc" ]],
            "columns": [
                {
                    "data": "idtbl_res_kot_detail"
                },
                {
                    "data": "date"
                },
                {
                    "targets": -1,
                    "className": 'text-left',
                    "data": null,
                    "render": function(data, type, full) {
                        if (full['orderid'] > 0) {
                            return 'WEB/' + full['date'] + '/' + full['orderid'];
                        } else if (full['tableid'] > 0) {
                            return 'TBL/' + full['date'] + '/' + full['tableid'];
                        } else {
                            return 'WCO/' + full['date'] + '/' + full['idtbl_res_kot'];
                        }
                    }
                },
                {
                    "data": "itemname"
                },
                {
                    "data": "qty"
                },
                {
                    "data": null,
                    "render": function(data, type, row) {
                        if (!row.startdatetime && !row.enddatetime) {
                            return 'Pending';
                        } else if (row.startdatetime && !row.enddatetime) {
                            return 'Processing';
                        } else if (row.startdatetime && row.enddatetime) {
                            return 'Complete';
                        } else {
                            return 'Unknown';
                        }
                    }
                },
                {
                    "data": "notes"
                },
            ],
            "rowCallback": function(row, data, index) {
                if (!data.startdatetime && !data.enddatetime) {
                    $(row).css({
                        'background-color': 'green',
                        'color': 'white',
                        'font-weight': 'bold'
                    });
                } else if (data.startdatetime && !data.enddatetime) {
                    $(row).css({
                        'background-color': 'orange',
                        'color': 'white',
                        'font-weight': 'bold'
                    });
                } else if (data.startdatetime && data.enddatetime) {
                    $(row).css({
                        'background-color': 'red',
                        'color': 'white',
                        'font-weight': 'bold'
                    });
                }
            },
            drawCallback: function(settings) {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });

        $('#dataTable tbody').on('click', '.btnStart', function() {
            var r = confirm("Are you sure to start this order?");
            if (r == true) {
                var id = $(this).attr('id');
                $.ajax({
                    type: "POST",
                    data: {
                        recordID: id
                    },
                    url: '<?php echo base_url() ?>Kitchen/Kitchenorderstart',
                    success: function(result) { //alert(result);
                        var obj = JSON.parse(result);
                        action(obj.action);
                        $('#dataTable').DataTable().ajax.reload( null, false );
                    }
                });  
            }          
        });
        $('#dataTable tbody').on('click', '.btnEnd', function() {
            var r = confirm("Are you sure to end this order?");
            if (r == true) {
                var id = $(this).attr('id');
                $.ajax({
                    type: "POST",
                    data: {
                        recordID: id
                    },
                    url: '<?php echo base_url() ?>Kitchen/Kitchenorderend',
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
