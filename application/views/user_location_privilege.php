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

            <!-- ================= PAGE HEADER ================= -->
            <div class="page-header page-header-light bg-white shadow">
                <div class="container-fluid">
                    <div class="page-header-content py-3">
                        <h1 class="page-header-title font-weight-light">
                            <div class="page-header-icon">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <span>User Branch Access</span>
                        </h1>
                    </div>
                </div>
            </div>

            <!-- ================= CONTENT ================= -->
            <div class="container-fluid mt-1">
                <div class="row">

                    <!-- =====================================================
                        LEFT PANEL : USER LOCATION ACCESS
                    ====================================================== -->
                    <div class="col-lg-4">
                        <div class="card shadow-sm">
                            <div class="card-header bg-light">
                                <strong>
                                    <i class="fas fa-map-marker-alt mr-2"></i>
                                    User Location Access
                                </strong>
                            </div>

                            <div class="card-body">

                                <!-- FORM START -->
                                <form id="locationForm" autocomplete="off">

                                    <!-- USER -->
                                    <div class="form-group">
                                        <label class="small font-weight-bold">User *</label>
                                        <select class="form-control form-control-sm" name="user_id" id="user_id" required>
                                            <option value="">Select User</option>
                                            <?php foreach($userlist->result() as $u){ ?>
                                                <option value="<?= $u->idtbl_res_user ?>">
                                                    <?= $u->name ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <!-- COMPANY -->
<div class="form-group mb-1">
    <label class="small font-weight-bold">Company *</label>
<select class="form-control form-control-sm"
        name="company_id[]"
        id="company_id"
        multiple
        required>
    <option value=""></option>

    <?php foreach ($companylist->result() as $c) { ?>
        <option value="<?= $c->idtbl_company ?>">
            <?= $c->company ?>
        </option>
    <?php } ?>
</select>

</div>

                                    <!-- BRANCH -->
<div class="form-group mb-1">
    <label class="small font-weight-bold">
        Branch
        <span class="text-muted">(Empty = Head Office)</span>
    </label>

    <select class="form-control form-control-sm"
            name="branch_id[]"
            id="branch_id"
            multiple>

        <!-- branches loaded by AJAX -->
    </select>
</div>

                                    <!-- STATUS -->
                                    <div class="form-group">
                                        <label class="small font-weight-bold">Status</label>
                                        <select class="form-control form-control-sm" name="status" id="status">
                                            <option value="1">Active</option>
                                            <option value="2">Inactive</option>
                                        </select>
                                    </div>

                                    <!-- HIDDEN -->
                                    <input type="hidden" name="recordOption" id="recordOption" value="1">
                                    <input type="hidden" name="recordID" id="recordID" value="">

                                    <!-- ACTION -->
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-primary btn-sm px-4">
                                            <i class="fas fa-save mr-1"></i> Assign Access
                                        </button>
                                    </div>

                                </form>
                                <!-- FORM END -->

                            </div>
                        </div>
                    </div>

                    <!-- =====================================================
                        RIGHT PANEL : INFO ONLY (NO MENU PRIVILEGE HERE)
                    ====================================================== -->
                    <div class="col-lg-8">
                        <div class="card shadow-sm">
                            <div class="card-header bg-light">
                                <strong>
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Information
                                </strong>
                            </div>

                            <div class="card-body">
                                <div class="alert alert-info small mb-0">
                                    This screen is used to assign <b>Company / Branch access</b> to users.<br>
                                    <b>Head Office</b> users are identified by keeping Branch empty.
                                </div>
                            </div>
                        </div>
                         <div class="card shadow-sm mt-3">
    <div class="card-header bg-light">
        <strong>
            <i class="fas fa-list mr-2"></i>
            Assigned User Location Access
        </strong>
    </div>

    <div class="card-body p-2">
        <div class="table-responsive">
            <table class="table table-bordered table-sm table-striped nowrap w-100"
                   id="locationTable">
                <thead class="bg-light">
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Company</th>
                        <th>Branch</th>
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

<script>


/* =============================
   EDIT RECORD
============================= */
$(document).on('click', '.btnEdit', function () {

    let id = $(this).data('id');

    $.post(
        "<?= base_url('Userlocation/Userlocationedit') ?>",
        { recordID: id },
        function (res) {

            let r = JSON.parse(res);

            $('#recordID').val(r.id);
            $('#user_id').val(r.user_id);
            $('#company_id').val(r.company_id).trigger('change');

            // branch load delay fix
            setTimeout(function () {
                $('#branch_id').val(r.branch_id);
            }, 300);

            $('#status').val(r.status);

            $('#recordOption').val(2);

            // button text
            $('#locationForm button[type=submit]')
                .html('<i class="fas fa-save mr-1"></i> Update Access');
        }
    );
});

$(document).ready(function () {

    $('#company_id').select2({
        placeholder: "Select Company",
        width: '100%'
    });

  $('#branch_id').select2({
    placeholder: "Select Branches (Empty = Head Office)",
    closeOnSelect: false,
    width: '100%'
    });

});

    /* =============================
   LOCATION ACCESS LIST
============================= */
let locationTable = $('#locationTable').DataTable({
    processing: true,
    serverSide: true,
    responsive: false,
    autoWidth: false,
    scrollX: true,

    ajax: {
        url: "<?= base_url('scripts/userlocationlist.php') ?>",
        type: "POST"
    },

    order: [[0, 'desc']],

    columns: [
        {
            data: 'id',
            render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        },
        { data: 'user' },
        { data: 'company' },
        { data: 'branch' },
        {
            data: 'status',
            className: 'text-center',
            render: function (d) {
                return d == 1
                    ? '<span class="badge badge-success">Active</span>'
                    : '<span class="badge badge-secondary">Inactive</span>';
            }
        },
{
    data: 'id',
    className: 'text-center',
    orderable: false,
    render: function (id, type, row) {

        let statusBtn = '';

        // ===== ACTIVE → INACTIVE =====
        if (row.status == 1) {
            statusBtn = `
                <button class="btn btn-sm btn-secondary btnStatus"
                        data-id="${id}"
                        data-status="2"
                        title="Inactivate">
                    <i class="fas fa-ban"></i>
                </button>`;
        }
        // ===== INACTIVE → ACTIVE =====
        else if (row.status == 2) {
            statusBtn = `
                <button class="btn btn-sm btn-success btnStatus"
                        data-id="${id}"
                        data-status="1"
                        title="Activate">
                    <i class="fas fa-check"></i>
                </button>`;
        }else {
            statusBtn = `
                <button class="btn btn-sm btn-success btnStatus"
                        data-id="${id}"
                        data-status="1"
                        title="Activate">
                    <i class="fas fa-check"></i>
                </button>`;
        }

        return `
            <button class="btn btn-sm btn-warning btnEdit"
                    data-id="${id}"
                    title="Edit">
                <i class="fas fa-edit"></i>
            </button>

            ${statusBtn}

            <button class="btn btn-sm btn-danger btnDelete"
                    data-id="${id}"
                    title="Remove">
                <i class="fas fa-trash"></i>
            </button>
        `;
    }
}


    ]
});

$(document).on('click', '.btnStatus', function () {

    let id     = $(this).data('id');
    let status = $(this).data('status');

    if (!confirm('Are you sure you want to change status?')) return;

    window.location.href =
        "<?= base_url('Userlocation/Userlocationstatus/') ?>" + id + '/' + status;
});

$(document).on('click', '.btnDelete', function () {

    let id = $(this).data('id');

    if (!confirm('Are you sure you want to remove this access?')) return;

    window.location.href =
        "<?= base_url('Userlocation/Userlocationstatus/') ?>" + id + '/3';
});

/* =============================
   LOAD BRANCHES BY COMPANY
============================= */
$('#company_id').on('change', function () {

    let company_ids = $(this).val(); // ARRAY
    $('#branch_id').empty().trigger('change');

    if (!company_ids || company_ids.length === 0) {
        return;
    }

    $.ajax({
        url: "<?= base_url('Userlocation/getBranchesByCompany') ?>",
        type: "POST",
        data: { company_id: company_ids },
        success: function (res) {
            $('#branch_id').html(res).trigger('change');
        }
    });
});


$('#locationForm').submit(function(e){
    e.preventDefault();

    $.post(
        "<?= base_url('Userlocation/Userlocationinsertupdate') ?>",
        $(this).serialize(),
        function(){
            locationTable.ajax.reload(null, false);

            // reset form
            $('#locationForm')[0].reset();
            $('#recordOption').val(1);
            $('#recordID').val('');
            $('#branch_id').html('<option value="">Head Office</option>');

            $('#locationForm button[type=submit]')
                .html('<i class="fas fa-save mr-1"></i> Assign Access');
        }
    );
});

</script>

<?php include "include/footer.php"; ?>
