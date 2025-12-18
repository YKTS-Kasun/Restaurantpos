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

        <h4 class="mb-3">Edit Expense</h4>

        <?php if ($this->session->flashdata('msg')): ?>
            <div class="alert alert-info"><?= $this->session->flashdata('msg'); ?></div>
        <?php endif; ?>

        <?php if(!$expense): ?>
            <div class="alert alert-danger">Expense record not found.</div>
        <?php else: ?>

        <div class="card shadow-sm">
            <div class="card-header">
                Update Manual Expense
            </div>

            <div class="card-body">

                <form method="post" action="<?= base_url('Expense/expense_update/'.$expense->idtbl_expense); ?>">

                    <div class="form-row">
                        <div class="col-md-4">
                            <label>Category</label>
                            <select name="categoryid" class="form-control" required>
                                <?php foreach($categories as $c): ?>
                                    <option value="<?= $c->idtbl_expense_category; ?>"
                                        <?= ($c->idtbl_expense_category == $expense->categoryid ? 'selected' : ''); ?>>
                                        <?= $c->category; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label>Amount</label>
                            <input type="number" name="amount" step="0.01" class="form-control"
                                   value="<?= $expense->amount; ?>" required>
                        </div>

                        <div class="col-md-4">
                            <label>Date</label>
                            <input type="date" name="expdate" class="form-control"
                                   value="<?= $expense->expdate; ?>" required>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <label>Description</label>
                        <textarea name="description" rows="3" class="form-control"><?= $expense->description; ?></textarea>
                    </div>

                    <button class="btn btn-primary">Update</button>
                    <a href="<?= base_url('Expense/expense'); ?>" class="btn btn-secondary">Cancel</a>

                </form>

            </div>
        </div>

        <?php endif; ?>

        </main>

        <?php include "include/footerbar.php"; ?>
    </div>
</div>

<?php include "include/footerscripts.php"; ?>
<?php include "include/footer.php"; ?>
