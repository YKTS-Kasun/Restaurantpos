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
                            <div class="page-header-icon"><i data-feather="user"></i></div>
                            <span>User Account</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container-fluid mt-2 p-0 p-2">
                <div class="card">
                    <div class="card-body p-0 p-2">
                        <div class="row">
                            <div class="col-3">
                                <form action="<?php echo base_url() ?>User/Useraccountinsertupdate" method="post" autocomplete="off">
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Account Name*</label>
                                        <input type="text" class="form-control form-control-sm" name="accountname" id="accountname" required>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Username*</label>
                                        <input type="text" class="form-control form-control-sm" name="username" id="username" required>
                                    </div>
                                    <div class="form-group mb-1">
                                        <label class="small font-weight-bold">Password*</label>
                                        <input type="password" class="form-control form-control-sm" name="password" id="password" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="small font-weight-bold">User Type*</label>
                                        <select class="form-control form-control-sm" name="usertype" id="usertype" required>
                                            <option value="">Select</option>
                                            <?php foreach ($usertype->result() as $rowusertype) { ?>
                                            <option value="<?php echo $rowusertype->idtbl_res_user_type ?>"><?php echo $rowusertype->usertype ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

   <div class="form-group mb-1">
    <label class="small font-weight-bold">Company *</label>
    <select class="form-control form-control-sm"
            name="company_id"
            id="company_id"
            required>
        <option value="">Select Company</option>
        <?php foreach ($companylist as $c) { ?>
            <option value="<?= $c->idtbl_company ?>">
                <?= $c->company ?>
            </option>
        <?php } ?>
    </select>
</div>

<div class="form-group mb-1">
    <label class="small font-weight-bold">Company Branch *</label>
    <select class="form-control form-control-sm"
            name="branch_id"
            id="branch_id"
            required>
        <option value="">Select Branch</option>
    </select>
</div>


                                    <div class="form-group mt-2 text-right">
                                        <button type="submit" id="submitBtn" class="btn btn-primary btn-sm px-4" <?php if($addcheck==0){echo 'disabled';} ?>><i class="far fa-save"></i>&nbsp;Add</button>
                                    </div>
                                    <input type="hidden" name="recordOption" id="recordOption" value="1">
                                    <input type="hidden" name="recordID" id="recordID" value="">
                                </form>
                            </div>
                            <div class="col-9">
                                <div class="scrollbar pb-3" id="style-2">
                                    <table class="table table-bordered table-striped table-sm nowrap" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>NAME</th>
                                                <th>USERNAME</th>
                                                <th>TYPE</th>
                                                <th>LOCATION</th>
                                                <th class="text-right">&nbsp;</th>
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
$(document).ready(function() {

    var addcheck    = '<?php echo $addcheck; ?>';
    var editcheck   = '<?php echo $editcheck; ?>';
    var statuscheck = '<?php echo $statuscheck; ?>';
    var deletecheck = '<?php echo $deletecheck; ?>';

    /* =====================================================
     * DATATABLE – USER ACCOUNT LIST
     * ===================================================== */
    var table = $('#dataTable').DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "<?php echo base_url() ?>scripts/useraccountlist.php",
            type: "POST",
            data: function (d) {
                d.userID = '<?php echo $this->session->userdata("userid"); ?>';
            }
        },
        order: [[0, "desc"]],
        columns: [
            { data: "idtbl_user" },
            { data: "name" },
            { data: "username" },
            { data: "type" },
            { data: "company" },   // ✅ NEW
            { data: "branch" },    // ✅ NEW
            {
                data: null,
                className: "text-right",
                render: function (data, type, full) {

                    var button = '';

                    // EDIT
                    button += '<button class="btn btn-primary btn-sm btnEdit mr-1 ';
                    if (editcheck != 1) { button += 'd-none'; }
                    button += '" data-id="'+full.idtbl_user+'"><i class="fas fa-pen"></i></button>';

                    // STATUS
                    if (full.status == 1) {
                        button += '<a href="<?php echo base_url() ?>User/Useraccountstatus/'+full.idtbl_user+'/2" ';
                        button += 'onclick="return deactive_confirm()" ';
                        button += 'class="btn btn-success btn-sm mr-1 ';
                        if (statuscheck != 1) { button += 'd-none'; }
                        button += '"><i class="fas fa-check"></i></a>';
                    } else {
                        button += '<a href="<?php echo base_url() ?>User/Useraccountstatus/'+full.idtbl_user+'/1" ';
                        button += 'onclick="return active_confirm()" ';
                        button += 'class="btn btn-warning btn-sm mr-1 ';
                        if (statuscheck != 1) { button += 'd-none'; }
                        button += '"><i class="fas fa-times"></i></a>';
                    }

                    // DELETE
                    button += '<a href="<?php echo base_url() ?>User/Useraccountstatus/'+full.idtbl_user+'/3" ';
                    button += 'onclick="return delete_confirm()" ';
                    button += 'class="btn btn-danger btn-sm ';
                    if (deletecheck != 1) { button += 'd-none'; }
                    button += '"><i class="fas fa-trash-alt"></i></a>';

                    return button;
                }
            }
        ],
        drawCallback: function () {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    /* =====================================================
     * LOAD BRANCHES BY COMPANY (FORM)
     * ===================================================== */
    $('#company_id').change(function () {
        var company_id = $(this).val();

        $('#branch_id').html('<option value="">Loading...</option>');

        $.post(
            "<?php echo base_url('User/getBranchesByCompany'); ?>",
            { company_id: company_id },
            function (data) {
                $('#branch_id').html(data);
            }
        );
    });

    /* =====================================================
     * EDIT USER
     * ===================================================== */
    $('#dataTable tbody').on('click', '.btnEdit', function () {

        if (!confirm("Are you sure you want to edit this user?")) {
            return;
        }

        var id = $(this).data('id');

        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>User/Useraccountedit",
            data: { recordID: id },
            success: function (result) {

                var obj = JSON.parse(result);

                $('#recordID').val(obj.id);
                $('#accountname').val(obj.name);
                $('#username').val(obj.username);
                $('#usertype').val(obj.type);

                // 🔥 COMPANY + BRANCH
                $('#company_id').val(obj.company_id).trigger('change');

                setTimeout(function () {
                    $('#branch_id').val(obj.branch_id);
                }, 500);

                $('#password').removeAttr("required");
                $('#recordOption').val('2');
                $('#submitBtn').html('<i class="far fa-save"></i>&nbsp;Update');
            }
        });
    });
});

/* =====================================================
 * CONFIRMATIONS
 * ===================================================== */
function deactive_confirm() {
    return confirm("Are you sure you want to deactivate this user?");
}

function active_confirm() {
    return confirm("Are you sure you want to activate this user?");
}

function delete_confirm() {
    return confirm("Are you sure you want to remove this user?");
}
</script>

<?php include "include/footer.php"; ?>
