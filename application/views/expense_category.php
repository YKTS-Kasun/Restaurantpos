<?php
include "include/header.php";
include "include/topnavbar.php";
?>
<div id="layoutSidenav" class="d-flex flex-column flex-lg-row">
    <div id="layoutSidenav_nav" class="flex-shrink-0">
        <?php include "include/menubar.php"; ?>
    </div>

    <div id="layoutSidenav_content" class="flex-grow-1">
        <main class="p-3">

             <!-- PAGE HEADER -->
            <div class="page-header page-header-light bg-white shadow">
                <div class="container-fluid">
                    <div class="page-header-content py-3">
                        <h1 class="page-header-title font-weight-light">
                            <div class="page-header-icon">
                                <i class="fas fa-box-open"></i>
                            </div>
                            <span>Expense Type</span>
                        </h1>
                    </div>
                </div>
            </div>
            <br>

            <?php if($this->session->flashdata('msg')): ?>
                <div class="alert alert-info">
                    <?= $this->session->flashdata('msg'); ?>
                </div>
            <?php endif; ?>

            <div class="row">

                <!-- LEFT SIDE FORM -->
                <div class="col-lg-4 mb-4">
                    <div class="card shadow-sm border-0">

                        <div class="card-header bg-primary text-white">
                            <strong id="form-title">Add Expense Type</strong>
                        </div>

                        <div class="card-body">

                            <form action="<?= base_url('ExpenseCategory/save_or_update'); ?>" method="post">

                                <input type="hidden" name="id" id="edit_id">

                                <label>Expense Type*</label>
                                <input type="text" id="edit_name" name="category" class="form-control mb-3"
                                       placeholder="Enter Expense Type" required>

                                <button id="btn-add" class="btn btn-primary w-100">
                                    <i class="fa fa-plus"></i> Add
                                </button>

                                <button id="btn-update" class="btn btn-warning w-100 d-none">
                                    <i class="fa fa-edit"></i> Update
                                </button>

                            </form>

                        </div>
                    </div>
                </div>

                <!-- RIGHT SIDE TABLE -->
                <div class="col-lg-8 mb-4">
                    <div class="card shadow-sm border-0">

                        <div class="card-header" style="background:#6a0dad; color:white;">
                            <strong>Expense Type List</strong>
                        </div>

                        <div class="card-body p-0">
                            <table class="table table-striped table-bordered mb-0">
                                <thead style="background:#2f2f2f; color:white;">
                                    <tr>
                                        <th>#</th>
                                        <th>Type Name</th>
                                        <th style="width:150px;">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php $i=1; foreach($categories as $c): ?>
                                    <tr>
                                        <td><?= $i++; ?></td>
                                        <td><?= $c->category; ?></td>

                                        <td class="text-center">

                                            <!-- EDIT -->
                                            <button class="btn btn-info btn-sm"
                                                onclick="loadEdit('<?= $c->idtbl_expense_category; ?>','<?= $c->category; ?>')">
                                                <i class="fa fa-edit"></i>
                                            </button>

                                            <!-- DELETE -->
                                            <a href="<?= base_url('ExpenseCategory/delete/'.$c->idtbl_expense_category); ?>"
                                               onclick="return confirm('Delete this type?');"
                                               class="btn btn-danger btn-sm">
                                               <i class="fa fa-trash"></i>
                                            </a>

                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>

                            </table>
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
function loadEdit(id, name){

    // Set values
    document.getElementById("edit_id").value = id;
    document.getElementById("edit_name").value = name;

    // Switch to UPDATE MODE
    document.getElementById("form-title").innerHTML = "Update Expense Type";

    document.getElementById("btn-add").classList.add("d-none");
    document.getElementById("btn-update").classList.remove("d-none");
}
</script>

<?php include "include/footer.php"; ?>
