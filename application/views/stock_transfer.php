<?php 
include "include/header.php";  
include "include/topnavbar.php"; 
?>

<?php
$company_id  = $this->session->userdata('company_id');
$branch_id   = $this->session->userdata('branch_id');
$branch_name = $this->session->userdata('branch_name');
?>
<style>

#dataTable {
    width: 100% !important;
}

/* Common cells */
#dataTable th,
#dataTable td {
    white-space: nowrap;
    vertical-align: middle;
}

/* ---------- Column widths (DESKTOP) ---------- */

/* # */
#dataTable th:nth-child(1),
#dataTable td:nth-child(1) {
    min-width: 60px;
    text-align: center;
}

/* Date */
#dataTable th:nth-child(2),
#dataTable td:nth-child(2) {
    min-width: 110px;
}

/* Transfer No */
#dataTable th:nth-child(3),
#dataTable td:nth-child(3) {
    min-width: 180px;
}

/* To */
#dataTable th:nth-child(4),
#dataTable td:nth-child(4) {
    min-width: 140px;
}

/* Status */
#dataTable th:nth-child(5),
#dataTable td:nth-child(5) {
    min-width: 110px;
    text-align: center;
}

/* 🔥 Actions */
#dataTable th:nth-child(6),
#dataTable td:nth-child(6) {
    min-width: 200px;
    text-align: center;
}

/* ---------- Action buttons ---------- */

.action-buttons {
    display: inline-flex;
    gap: 6px;
}

.action-buttons .btn {
    padding: 4px 6px;
}

/* ---------- Mobile behavior ---------- */
@media (max-width: 768px) {
    #dataTable th,
    #dataTable td {
        font-size: 13px;
    }
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
                                <i class="fas fa-exchange-alt"></i>
                            </div>
                            <span>Stock Transfer</span>
                        </h1>
                    </div>
                </div>
            </div>

            <!-- CONTENT -->
            <div class="container-fluid mt-2 p-2">
                <div class="card">
                    <div class="card-body p-2">

                        <!-- CREATE BUTTON -->
                        <div class="row">
                            <div class="col-12 text-right">
<button id="btnOpenTransfer"
        class="btn btn-primary btn-sm"
        <?php if(isset($addcheck) && $addcheck==0){ echo 'disabled'; } ?>>
    <i class="fas fa-plus mr-2"></i>Create Stock Transfer
</button>


                                <hr>
                            </div>
                        </div>

                        <!-- LIST TABLE -->
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-sm nowrap w-100"
                                        id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Date</th>
                                                <th>Transfer No</th>
                                               <th>From</th>
                                                <th>To</th>
                                                <th>Status</th>
                                                <th class="text-center">Action</th>
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
    <!-- ===============================
     VIEW STOCK TRANSFER MODAL
================================ -->
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exchange-alt mr-2"></i>
                    Stock Transfer Details
                </h5>
<button type="button"
        class="close"
        data-dismiss="modal"
        aria-label="Close">
    <span aria-hidden="true">&times;</span>
</button>


            </div>

            <div class="modal-body" id="viewModalContent">
                <div class="text-center text-muted p-3">
                    Loading...
                </div>
            </div>

        </div>
    </div>
</div>

<!-- ===========================================================
    CREATE STOCK TRANSFER MODAL
=========================================================== -->
<div class="modal fade" id="transferModal" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Create Stock Transfer</h5>
<button type="button"
        class="close"
        data-dismiss="modal"
        aria-label="Close">
    <span aria-hidden="true">&times;</span>
</button>


            </div>

            <div class="modal-body">
                <div class="row">

                    <!-- LEFT FORM -->
                    <div class="col-lg-4">
                        <form id="transferForm" autocomplete="off">

                            <div class="form-group">
    <label class="small font-weight-bold">From Location</label>

<input type="text"
 class="form-control form-control-sm"
 value="<?= $branch_id ? 'Branch - '.$branch_name : 'Head Office' ?>"
 readonly>

<input type="hidden" id="from_company_id" value="<?= $company_id ?>">
<input type="hidden" id="from_branch_id" value="<?= $branch_id ?>">

</div>

                            <div class="form-group">
    <label class="small font-weight-bold">To Location *</label>

<select class="form-control form-control-sm" id="to_location" required>
    <option value="">Select Location</option>

    <?php foreach($branchlist as $b){ ?>
        <?php if($b->idtbl_company_branch != $branch_id){ ?>
            <option value="<?= $company_id ?>|<?= $b->idtbl_company_branch ?>">
                <?= $b->branch ?>
            </option>
        <?php } ?>
    <?php } ?>

    <!-- HEAD OFFICE -->
    <?php if($branch_id): ?>
        <option value="<?= $company_id ?>|">Head Office</option>
    <?php endif; ?>
</select>

</div>

                            <div class="form-group">
                                <label class="small font-weight-bold">Material *</label>
                                <select class="form-control form-control-sm" id="material">
    <option value="">Select Material</option>
    <?php foreach($materiallist as $row){ ?>
        <option value="<?= $row->material_id ?>"
        data-stock="<?= $row->qty ?>">
    <?= $row->material ?> (Stock: <?= $row->qty ?>)
</option>
    <?php } ?>
</select>

                            </div>
<div class="form-group">
    <label class="small font-weight-bold">Available Qty</label>
    <input type="text"
           class="form-control form-control-sm"
           id="available_qty"
           readonly>
</div>

                            <div class="form-group">
                                <label class="small font-weight-bold">Qty *</label>
                                <input type="number" class="form-control form-control-sm"
                                       id="qty" min="1">
                            </div>

                            <div class="text-right">
                                <button type="button" id="btnAdd"
                                        class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Add
                                </button>
                            </div>

                        </form>
                    </div>

                    <!-- RIGHT TABLE -->
                    <div class="col-lg-8">
                        <table class="table table-bordered table-sm" id="itemTable">
                            <thead>
<tr>
    <th>Material</th>
    <th class="text-center">Qty</th>
    <th class="d-none">MaterialID</th>
    <th class="text-center">Action</th>
</tr>
</thead>

                            <tbody></tbody>
                        </table>

                        <div class="form-group">
                            <label class="small font-weight-bold">Remark</label>
                            <textarea id="remark" class="form-control form-control-sm"></textarea>
                        </div>

                        <div class="text-right">
                            <button class="btn btn-outline-primary btn-sm"
                                    id="btnSave">
                                <i class="fas fa-save"></i> Create Transfer
                            </button>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
    <input type="hidden" id="transfer_id" value="">
        <input type="hidden" id="edit_row_index" value="">
</div>



<script>
    
$(document).ready(function(){

    /* ===========================
       DATATABLE
    =========================== */
$('#dataTable').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
scrollX: true,
autoWidth: true,


ajax:{
    url:"<?= base_url() ?>scripts/stocktransferlist.php",
    type:"POST",
    data:function(d){
        d.company_id = "<?= $company_id ?>";
        d.branch_id  = "<?= $branch_id ?>"; // NULL if HO
    }
},

   order:[[1,'desc']], // transfer_date

        columnDefs: [
        { targets: 0,   className:'text-center' },   // #
        { targets: 1, },                            // Date
        { targets: 2,  },                            // Transfer No
        {targets: 3, },
        { targets: 4, },                            // To
        { targets: 5,  className:'text-center' },   // Status
        {
            targets: 6,
            orderable: false,
            className: 'text-center'       // ✅ FIXED WIDTH (no jump)
        }
    ],


    columns:[
          {
        data: null,
        render: (d, t, r, m) => m.row + m.settings._iDisplayStart + 1
    },
    { data:'transfer_date' },
    { data:'transfer_no' },

    // ✅ FROM
    {
        data:'from_loc',
        render: d => d ? d : '-'
    },

    // ✅ TO
    {
        data:'to_loc',
        render: d => d ? d : '-'
    },

{
    data:'status',
    className:'text-center',
    render:function(d){
        if(d === 'PENDING'){
            return '<span class="badge badge-warning">Pending</span>';
        }
        if(d === 'APPROVED'){
            return '<span class="badge badge-success">Approved</span>';
        }
        if(d === 'REJECTED'){
            return '<span class="badge badge-danger">Rejected</span>';
        }
        if(d === 'RECEIVED'){
            return '<span class="badge badge-primary">Received</span>';
        }
        return d;
    }
},
        {
            data: null,
            render: function (d) {

                let btn = `<div class="btn-group btn-group-sm" role="group">`;

                /* 👁 VIEW – always */
                btn += `
                    <button class="btn btn-info"
                            title="View"
                            onclick="viewTransfer(${d.idtbl_stock_transfer})">
                        <i class="fas fa-eye"></i>
                    </button>
                `;

                /* 🚚 DISPATCH REPORT (Approved + Received only) */
if (d.status === 'APPROVED' || d.status === 'RECEIVED') {
    btn += `
        <a href="<?= base_url() ?>StockTransfer/dispatch_report_pdf/${d.idtbl_stock_transfer}"
           class="btn btn-secondary"
           title="Dispatch Report"
           target="_blank">
            <i class="fas fa-truck"></i>
        </a>
    `;
}

              
                /* ✏ EDIT (only Pending) */
                if (d.status === 'PENDING') {
                    btn += `
                       <button class="btn btn-warning"
        title="Edit"
        onclick="editTransfer(${d.idtbl_stock_transfer})">
    <i class="fas fa-edit"></i>
</button>

                    `;
                }

                /* 🗑 DELETE (only Pending) */
                if (d.status === 'PENDING') {
                    btn += `
                        <a href="<?= base_url() ?>StockTransfer/delete/${d.idtbl_stock_transfer}"
                           onclick="return confirm('Delete this transfer?')"
                           class="btn btn-danger"
                           title="Delete">
                           <i class="fas fa-trash"></i>
                        </a>
                    `;
                }

                btn += `</div>`;
                return btn;
            }
        }
    ]
});

$(document).on('click','.btnEditItem',function(){

    let row = $(this).closest('tr');

    let matID   = row.find('td:eq(2)').text();
    let matText = row.find('td:eq(0)').text();
    let qty     = row.find('td:eq(1)').text();
    let index   = row.index();

    if ($('#material option[value="'+matID+'"]').length === 0) {
        $('#material').append(
            `<option value="${matID}" selected>${matText}</option>`
        );
    }

    $('#material').val(matID).trigger('change');
    $('#qty').val(qty);

    $('#edit_row_index').val(index);
    $('#btnAdd').html('<i class="fas fa-sync"></i> Update');
});


$('#material').on('change', function () {

    let material_id = $(this).val();
    let company_id = $('#from_company_id').val();
   let branch_id = $('#from_branch_id').val();
if(branch_id === '') {
    branch_id = null;
}



    if(material_id === ''){
        $('#available_qty').val('');
        return;
    }

$.post("<?= base_url() ?>StockTransfer/get_available_qty", {
    material_id: material_id,
    company_id: company_id,
    branch_id: branch_id
}, function (res) {
    let r = JSON.parse(res);
    $('#available_qty').val(r.status ? r.qty : 0);
});

});

    /* ===========================
       ADD ITEM
    =========================== */
 $('#btnAdd').click(function(){

    let matText = $('#material option:selected').text();
    let matID   = $('#material').val();
    let qty     = parseFloat($('#qty').val());
   
    let editIndex = $('#edit_row_index').val();
    let available = parseFloat($('#available_qty').val()) || 0;

if(qty > available){
    alert('Transfer qty exceeds available stock');
    return;
}

    if(matID === ''){
        alert('Select material');
        return;
    }

    if(isNaN(qty) || qty <= 0){
        alert('Enter valid quantity');
        return;
    }


    /* ===============================
       UPDATE EXISTING ROW
    =============================== */
    if(editIndex !== ''){

        let row = $('#itemTable tbody tr').eq(editIndex);

        row.find('td:eq(0)').text(matText);
        row.find('td:eq(1)').text(qty);
        row.find('td:eq(2)').text(matID);

        // reset state
        $('#edit_row_index').val('');
        $('#btnAdd').html('<i class="fas fa-plus"></i> Add');

    } 
    /* ===============================
       ADD NEW ROW
    =============================== */
    else {

        // prevent duplicate
        let exists = false;
        $('#itemTable tbody tr').each(function(){
            if($(this).find('td:eq(2)').text() == matID){
                exists = true;
            }
        });

        if(exists){
            alert('This material already added');
            return;
        }

        $('#itemTable tbody').append(`
        <tr>
            <td>${matText}</td>
            <td class="text-center">${qty}</td>
            <td class="d-none">${matID}</td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-warning btnEditItem">
                    <i class="fas fa-edit"></i>
                </button>
                <button type="button" class="btn btn-sm btn-danger btnRemove">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
        `);
    }

// clear form
$('#material').val('');
$('#qty').val('');
$('#available_qty').val('');

});

    /* ===========================
       SAVE TRANSFER
    =========================== */
   $('#btnSave').click(function(){

    if($('#to_location').val() === ''){
    alert('Select destination location');
    return;
}

    let items=[];
    $('#itemTable tbody tr').each(function(){
        items.push({
            material_id: $(this).find('td:eq(2)').text(),
            qty: $(this).find('td:eq(1)').text()
        });
    });

    if(items.length === 0){
        alert('Add at least one item');
        return;
    }

    let transfer_id = $('#transfer_id').val();

    let url = transfer_id
        ? "<?= base_url() ?>StockTransfer/update"
        : "<?= base_url() ?>StockTransfer/save";

$('#btnSave').prop('disabled', true);

$.post(url,{
    transfer_id: transfer_id,
    from_company_id: $('#from_company_id').val(),
    from_branch_id: $('#from_branch_id').val(),
    to_location: $('#to_location').val(),
    remark: $('#remark').val(),
    items: items
},function(res){

        let r;
try {
    r = JSON.parse(res);
} catch(e) {
    alert('Unexpected server response');
    $('#btnSave').prop('disabled', false);
    return;
}


if(r.status == 1){

  $('#transferModal').modal('hide');

    // reset form
    $('#itemTable tbody').empty();
    $('#remark').val('');
    $('#transfer_id').val('');
    $('#btnSave').html('<i class="fas fa-save"></i> Create Transfer');

    $('#dataTable').DataTable().ajax.reload(null,false);
}
else{
            alert(r.message);
        }
         $('#btnSave').prop('disabled', false);
    });
});

});

function viewTransfer(id){

$('#viewModal').modal('show');

    $('#viewModalContent').html('<div class="text-center p-3">Loading...</div>');

    $.post(
        "<?= base_url() ?>StockTransfer/view_modal",
        { id: id },
        function(res){
            $('#viewModalContent').html(res);
        }
    );
}

$(document).on('click','.btnRemove',function(){
    $(this).closest('tr').remove();

    // 🔥 reset edit state
    $('#edit_row_index').val('');
    $('#btnAdd').html('<i class="fas fa-plus"></i> Add');
});


function editTransfer(id)
{
    $.get("<?= base_url() ?>StockTransfer/edit/" + id, function(res){

        let r;
try {
    r = JSON.parse(res);
} catch(e) {
    alert('Invalid server response');
    return;
}


        if(r.status !== 1){
            alert('This transfer cannot be edited');
            return;
        }

        // 🔑 EDIT MODE
        $('#transfer_id').val(id);

        // 👉 TO LOCATION FIX
let toVal = r.header.to_company_id + '|' + (r.header.to_branch_id ?? '');
$('#to_location').val(toVal).trigger('change');


        // remark
        $('#remark').val(r.header.remark ?? '');

        // reset form state
        $('#material').val('');
        $('#qty').val('');
        $('#edit_row_index').val('');
        $('#btnAdd').html('<i class="fas fa-plus"></i> Add');
        $('#available_qty').val('');
        // reset table
        $('#itemTable tbody').empty();

        // fill items (WITH EDIT + DELETE)
        r.items.forEach(function(it){
            $('#itemTable tbody').append(`
                <tr>
                    <td>${it.material}</td>
                    <td class="text-center">${it.qty}</td>
                    <td class="d-none">${it.material_id}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-warning btnEditItem">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger btnRemove">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `);
        });

        // change save button
        $('#btnSave').html('<i class="fas fa-save"></i> Update Transfer');

$('#transferModal').modal({
    backdrop: 'static',
    keyboard: false
});

    });
}

$('#btnOpenTransfer').on('click', function () {
    $('#viewModal').modal('hide');
    $('#transferModal').modal({
        backdrop: 'static',
        keyboard: false
    });
});
;
</script>

<?php include "include/footer.php"; ?>
