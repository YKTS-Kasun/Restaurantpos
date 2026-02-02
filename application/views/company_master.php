<?php include "include/header.php"; ?>
<?php include "include/topnavbar.php"; ?>

<div id="layoutSidenav">
<div id="layoutSidenav_nav">
<?php include "include/menubar.php"; ?>
</div>

<div id="layoutSidenav_content">
<main class="container-fluid mt-3">

<div class="row">

<!-- ================= COMPANY MASTER ================= -->
<div class="col-md-6">
<div class="card">
<div class="card-header font-weight-bold d-flex justify-content-between">
    <span>Company Master</span>
    <button class="btn btn-primary btn-sm" id="btnAddCompany">
        <i class="fas fa-plus"></i> Add
    </button>
</div>

<div class="card-body p-0 p-2">
<table class="table table-bordered table-striped table-sm nowrap" id="companyTable">
    <thead>
        <tr>
            <th>#</th>
            <th>Company</th>
            <th>Code</th>
            <th class="text-right">Action</th>
        </tr>
    </thead>
</table>
</div>
</div>
</div>

<!-- ================= BRANCH MASTER ================= -->
<div class="col-md-6">
<div class="card">
<div class="card-header font-weight-bold d-flex justify-content-between">
    <span>Company Branch Master</span>
    <button class="btn btn-success btn-sm" id="btnAddBranch">
        <i class="fas fa-plus"></i> Add
    </button>
</div>

<div class="card-body p-0 p-2">
<table class="table table-bordered table-striped table-sm nowrap" id="branchTable">
    <thead>
        <tr>
            <th>#</th>
            <th>Company</th>
            <th>Branch</th>
            <th>Code</th>
            <th class="text-right">Action</th>
        </tr>
    </thead>
</table>
</div>
</div>
</div>

</div>
</main>
</div>
</div>

<!-- ================= COMPANY MODAL ================= -->
<div class="modal fade" id="companyModal" data-bs-backdrop="static">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">
<div class="modal-header">
    <h5 class="modal-title">Company</h5>
    <buttonclass="btn-close" data-bs-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
<form id="companyForm">
    <input type="hidden" id="company_recordID">
    <input type="hidden" id="company_recordOption" value="1">

    <div class="form-group">
        <label>Company *</label>
        <input type="text" id="company_name" class="form-control form-control-sm" required>
    </div>

    <div class="form-group">
        <label>Code</label>
        <input type="text" id="company_code" class="form-control form-control-sm">
    </div>

    <div class="text-right">
        <button class="btn btn-primary btn-sm" id="saveCompany">
            <i class="fas fa-save"></i> Save
        </button>
    </div>
</form>
</div>
</div>
</div>
</div>

<!-- ================= BRANCH MODAL ================= -->
<div class="modal fade" id="branchModal" data-bs-backdrop="static">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">
<div class="modal-header">
    <h5 class="modal-title">Branch</h5>
    <buttonclass="btn-close" data-bs-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
<form id="branchForm">
    <input type="hidden" id="branch_recordID">
    <input type="hidden" id="branch_recordOption" value="1">

    <div class="form-group">
        <label>Company *</label>
        <select id="branch_company" class="form-control form-control-sm" required>
            <option value="">Select</option>
            <?php foreach ($companylist as $c) { ?>
                <option value="<?= $c->idtbl_company ?>"><?= $c->company ?></option>
            <?php } ?>
        </select>
    </div>

    <div class="form-group">
        <label>Branch *</label>
        <input type="text" id="branch_name" class="form-control form-control-sm" required>
    </div>

    <div class="form-group">
        <label>Code</label>
        <input type="text" id="branch_code" class="form-control form-control-sm">
    </div>

    <div class="text-right">
        <button class="btn btn-success btn-sm" id="saveBranch">
            <i class="fas fa-save"></i> Save
        </button>
    </div>
</form>
</div>
</div>
</div>
</div>

<?php include "include/footerscripts.php"; ?>

<script>
$(document).ready(function(){

/* ================= COMPANY TABLE ================= */
var companyTable = $('#companyTable').DataTable({
    processing:true,
    serverSide:true,
    ajax:{
        url:"<?= base_url('scripts/companylist.php') ?>",
        type:"POST"
    },
    columns:[
        {data:"idtbl_company"},
        {data:"company"},
        {data:"code"},
        {
            data:null,
            className:"text-right",
            render:function(data){
                return `
                <button class="btn btn-sm btn-primary btnEditCompany"
                    data-id="${data.idtbl_company}"
                    data-name="${data.company}"
                    data-code="${data.code}">
                    <i class="fas fa-pen"></i>
                </button>`;
            }
        }
    ]
});

/* ================= BRANCH TABLE ================= */
var branchTable = $('#branchTable').DataTable({
    processing:true,
    serverSide:true,
    ajax:{
        url:"<?= base_url('scripts/companybranchlist.php') ?>",
        type:"POST"
    },
    columns:[
        {data:"idtbl_company_branch"},
        {data:"company"},
        {data:"branch"},
        {data:"code"},
        {
            data:null,
            className:"text-right",
            render:function(data){
                return `
                <button class="btn btn-sm btn-success btnEditBranch"
                    data-id="${data.idtbl_company_branch}"
                    data-company="${data.tbl_company_idtbl_company}"
                    data-branch="${data.branch}"
                    data-code="${data.code}">
                    <i class="fas fa-pen"></i>
                </button>`;
            }
        }
    ]
});

/* ================= COMPANY ================= */
$('#btnAddCompany').click(function(){
    $('#companyForm')[0].reset();
    $('#company_recordOption').val(1);
    $('#companyModal').modal('show');
});

$('#companyTable').on('click','.btnEditCompany',function(){
    $('#company_recordID').val($(this).data('id'));
    $('#company_name').val($(this).data('name'));
    $('#company_code').val($(this).data('code'));
    $('#company_recordOption').val(2);
    $('#companyModal').modal('show');
});

$('#saveCompany').click(function(e){
    e.preventDefault();
    $.post("<?= base_url('Company/companySave') ?>",{
        recordID:$('#company_recordID').val(),
        recordOption:$('#company_recordOption').val(),
        company:$('#company_name').val(),
        code:$('#company_code').val()
    },function(){
        $('#companyModal').modal('hide');
        companyTable.ajax.reload();
    });
});

/* ================= BRANCH ================= */
$('#btnAddBranch').click(function(){
    $('#branchForm')[0].reset();
    $('#branch_recordOption').val(1);
    $('#branchModal').modal('show');
});

$('#branchTable').on('click','.btnEditBranch',function(){
    $('#branch_recordID').val($(this).data('id'));
    $('#branch_company').val($(this).data('company'));
    $('#branch_name').val($(this).data('branch'));
    $('#branch_code').val($(this).data('code'));
    $('#branch_recordOption').val(2);
    $('#branchModal').modal('show');
});

$('#saveBranch').click(function(e){
    e.preventDefault();
    $.post("<?= base_url('Company/branchSave') ?>",{
        recordID:$('#branch_recordID').val(),
        recordOption:$('#branch_recordOption').val(),
        company_id:$('#branch_company').val(),
        branch:$('#branch_name').val(),
        code:$('#branch_code').val()
    },function(){
        $('#branchModal').modal('hide');
        branchTable.ajax.reload();
    });
});

});
</script>

<?php include "include/footer.php"; ?>
