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
                            <span>Order Process</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="col-12 d-none d-sm-block">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="activeorders-tab" data-toggle="tab" data-target="#activeorders" type="button" role="tab" aria-controls="activeorders" aria-selected="true">Active Orders</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="completeorders-tab" data-toggle="tab" data-target="#completeorders" type="button" role="tab" aria-controls="completeorders" aria-selected="false">Complete Orders</button>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active py-4 px-2" id="activeorders" role="tabpanel" aria-labelledby="activeorders-tab">
                                    <div class="scrollbar pb-3" id="style-2">
                                        <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Date</th>
                                                    <th>Order No</th>
                                                    <th>Table No</th>
                                                    <th>Item</th>
                                                    <th>Potion</th>
                                                    <th>Time</th>
                                                    <th>Start</th>
                                                    <th>End</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade py-4 px-2" id="completeorders" role="tabpanel" aria-labelledby="completeorders-tab">
                                    <div class="scrollbar pb-3" id="style-2">
                                        <table class="table table-bordered table-striped table-sm nowrap w-100" id="dataTablecomplete">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Date</th>
                                                    <th>Order No</th>
                                                    <th>Table No</th>
                                                    <th>Item</th>
                                                    <th>Potion</th>
                                                    <th>Time</th>
                                                    <th>Start</th>
                                                    <th>End</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 d-block d-sm-none">
                            <h6 class="title-style small my-3"><span>Active orders</span></h6>
                            <ul class="list-group">
                                <?php foreach($activeorderlist->result() as $rowdatalist){ ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center p-2">
                                    <?php echo $rowdatalist->order_identifier ?>
                                    <button type="button" class="btn btn-link btn-sm btnkotorderview" id="<?php echo $rowdatalist->idtbl_res_kot_detail ?>"><i class="fas fa-question-circle"></i></button>
                                </li>
                                <?php } ?>
                            </ul>
                            <h6 class="title-style small my-3"><span>Complete orders</span></h6>
                            <ul class="list-group" id="divcompleteorders">
                                <?php foreach($completeorderlist->result() as $rowdatalistcomplete){ ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center p-2 list-group-item-success">
                                    <?php echo $rowdatalistcomplete->order_identifier ?>
                                    <button type="button" class="btn btn-link btn-sm btnkotorderview" id="<?php echo $rowdatalistcomplete->idtbl_res_kot_detail ?>"><i class="fas fa-question-circle"></i></button>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>                  
                </div>
            </div>
		</main>
		<?php include "include/footerbar.php"; ?>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="modalvieworder" data-bs-backdrop="static" data-keyboard="false" tabindex="-1"
	aria-labelledby="modalvieworderLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalvieworderLabel">Order Information</h5>
				<button type="button"class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="vieworder"></div>
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
            responsive: true,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, 'All'],
            ],
            ajax: {
                url: "<?php echo base_url() ?>scripts/vieworderlist.php",
                type: "POST", // you can use GET
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
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        if(full['orderid'] > 0) {
                            return 'WEB/' + full['date'] + '/' + full['orderid'];
                        } else {
                            return 'TBL/' + full['date'] + '/' + full['tableid'];
                        }
                    }
                },
                {
                    "data": "table"
                },
                {
                    "data": "itemname"
                },
                {
                    "data": "qty"
                },
                { 
                    "data": null, 
                    "render": function (data, type, row) {
                    let start = new Date(row.startdatetime);
                    let currentTime = new Date();
                    let timeGapInMinutes = Math.floor((currentTime - start) / 60000);

                        if (timeGapInMinutes < 60) {
                            return `${timeGapInMinutes} min`;
                        } else {
                            let hours = Math.floor(timeGapInMinutes / 60);
                            let minutes = timeGapInMinutes % 60;
                            return `${hours} hour${hours !== 1 ? 's' : ''} ${minutes} min`;
                        }
                    }
                },
                {
                    "data": "startdatetime"
                },
                {
                    "data": "enddatetime"
                },
            ],
            "rowCallback": function(row, data, index) {
                if (data.completestatus == 1) {
                    $(row).css({
                        'background-color': 'green',
                        'color': 'white',
                        'font-weight': 'bold'
                    });
                }
            },
            "drawCallback": function(settings) {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
        $('#dataTablecomplete').DataTable({
            "destroy": true,
            "processing": true,
            "serverSide": true,
            responsive: true,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, 'All'],
            ],
            ajax: {
                url: "<?php echo base_url() ?>scripts/viecimpleteorderlist.php",
                type: "POST", // you can use GET
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
                    "className": 'text-right',
                    "data": null,
                    "render": function(data, type, full) {
                        if(full['orderid'] > 0) {
                            return 'WEB/' + full['date'] + '/' + full['orderid'];
                        } else {
                            return 'TBL/' + full['date'] + '/' + full['tableid'];
                        }
                    }
                },
                {
                    "data": "table"
                },
                {
                    "data": "itemname"
                },
                {
                    "data": "qty"
                },
                { 
                    "data": null, 
                    "render": function (data, type, row) {
                    let start = new Date(row.startdatetime);
                    let currentTime = new Date();
                    let timeGapInMinutes = Math.floor((currentTime - start) / 60000);

                        if (timeGapInMinutes < 60) {
                            return `${timeGapInMinutes} min`;
                        } else {
                            let hours = Math.floor(timeGapInMinutes / 60);
                            let minutes = timeGapInMinutes % 60;
                            return `${hours} hour${hours !== 1 ? 's' : ''} ${minutes} min`;
                        }
                    }
                },
                {
                    "data": "startdatetime"
                },
                {
                    "data": "enddatetime"
                },
            ],
            "rowCallback": function(row, data, index) {
                if (data.completestatus == 1) {
                    $(row).css({
                        'background-color': 'green',
                        'color': 'white',
                        'font-weight': 'bold'
                    });
                }
            },
            "drawCallback": function(settings) {
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

        $('.btnkotorderview').click(function(){
            var kotID=$(this).attr('id');

            $.ajax({
                type: "POST",
                data: {
                    recordID: kotID
                },
                url: '<?php echo base_url() ?>Vieworder/Viewkotorderinfo',
                success: function(result) { //alert(result);
                    $('#vieworder').html(result);
                    $('#modalvieworder').modal('show');
                }
            });  
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
