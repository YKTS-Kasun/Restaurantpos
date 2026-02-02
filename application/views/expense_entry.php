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

            <h4 class="mb-4">Expense Entry</h4>

            <?php if($this->session->flashdata('msg')): ?>
                <div class="alert alert-info"><?= $this->session->flashdata('msg'); ?></div>
            <?php endif; ?>

            <div class="row">

                <!-- LEFT FORM -->
                <div class="col-lg-4 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-primary text-white">
                            <strong id="form-title">Add Expense</strong>
                        </div>

                        <div class="card-body">
                            <form action="<?= base_url('ExpenseEntry/save_or_update'); ?>" method="post">

                                <input type="hidden" name="id" id="exp_id">

                                <div class="form-group">
                                    <label>Category *</label>
                                    <select name="categoryid" id="exp_category" class="form-control" required>
                                        <option value="">Select</option>
                                        <?php foreach($categories as $c): ?>
                                            <option value="<?= $c->idtbl_expense_category; ?>"><?= $c->category; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Amount *</label>
                                    <input type="number" step="0.01" id="exp_amount" name="amount" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label>Date *</label>
                                    <input type="date" id="exp_date" name="expdate" value="<?= date('Y-m-d'); ?>" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea id="exp_desc" name="description" rows="2" class="form-control"></textarea>
                                </div>

                                <button id="btn-save" class="btn btn-primary w-100">
                                    <i class="fa fa-plus"></i> Save Expense
                                </button>

                                <button id="btn-update" class="btn btn-warning w-100 d-none">
                                    <i class="fa fa-edit"></i> Update Expense
                                </button>

                            </form>
                        </div>
                    </div>
                </div>

                <!-- RIGHT TABLE -->
                <div class="col-lg-8 mb-4">

                    <div class="card shadow-sm border-0 mb-3">
                        <div class="card-body">
                            <form method="get" action="<?= base_url('ExpenseEntry'); ?>">
                                <div class="form-row">

                                    <div class="col-md-3">
                                        <label>From Date</label>
                                        <input type="date" name="date_from" value="<?= $date_from; ?>" class="form-control">
                                    </div>

                                    <div class="col-md-3">
                                        <label>To Date</label>
                                        <input type="date" name="date_to" value="<?= $date_to; ?>" class="form-control">
                                    </div>

                                    <div class="col-md-3">
                                        <label>Category</label>
                                        <select name="category" class="form-control">
                                            <option value="">All</option>
                                            <?php foreach ($categories as $c): ?>
                                                <option value="<?= $c->category; ?>" <?= ($f_category==$c->category?'selected':''); ?>>
                                                    <?= $c->category; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                </div>

                                <button class="btn btn-success mt-3">Filter</button>
                                <button type="button" onclick="downloadRangePDF()" class="btn btn-danger mt-3">
                                    PDF
                                </button>

                            </form>
                        </div>
                    </div>

                    <!-- GROUPED TABLE -->
                    <?php
                    $grouped = [];
                    foreach ($expenses as $e) {
                        $grouped[$e->expdate][] = $e;
                    }

                    $dates = array_keys($grouped);
                    $per_page = 7;
                    $page = isset($_GET['p']) ? $_GET['p'] : 1;
                    $total_pages = ceil(count($dates) / $per_page);
                    $start = ($page - 1) * $per_page;

                    $dates_to_show = array_slice($dates, $start, $per_page);
                    ?>

                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                        <tr>
                            <th>Date</th>
                            <th>Location</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th class="text-right">Amount</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody>

                        <?php foreach ($dates_to_show as $date): ?>
                            <tr class="table-primary toggle-btn" data-target="row-<?= $date ?>" style="cursor:pointer;">
                                <td colspan="5">
                                    <span class="arrow">►</span> 
                                    <b><?= $date ?></b>
                                    <small class="text-muted">(<?= count($grouped[$date]) ?> items)</small>
                                </td>
                                <td>
                                    <a href="<?= base_url('ExpenseEntry/pdf/'.$date) ?>" target="_blank" class="btn btn-danger btn-sm">PDF</a>
                                </td>
                            </tr>

                            <?php $day_total = 0;
                            foreach ($grouped[$date] as $item):
                                $day_total += $item->amount;
                                ?>
                                <tr class="collapse-row row-<?= $date ?>" style="display:none;">
                                    <td><?= $item->expdate ?></td>
                                    <td><?= $item->branch_name ?? 'Head Office' ?></td>
                                    <td><?= $item->category ?></td>
                                    <td><?= $item->description ?></td>
                                    <td class="text-right"><?= number_format($item->amount,2) ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm"
                                            onclick="loadEdit('<?= $item->idtbl_expense ?>', '<?= $item->categoryid ?>', '<?= $item->amount ?>', '<?= $item->expdate ?>', `<?= $item->description ?>`)">
                                            Edit
                                        </button>
                                        <a href="ExpenseEntry/delete/<?= $item->idtbl_expense ?>" onclick="return confirm('Delete?');" class="btn btn-danger btn-sm">
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                            <tr class="collapse-row row-<?= $date ?>" style="display:none;background:#f0f0f0;">
                                <td colspan="4" class="text-right"><b>Total</b></td>
                                <td class="text-right"><b><?= number_format($day_total,2) ?></b></td>
                                <td></td>
                            </tr>

                        <?php endforeach; ?>

                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <nav>
                        <ul class="pagination">
                            <?php if ($page > 1): ?>
                                <li class="page-item"><a class="page-link" href="?p=<?= $page-1 ?>">Previous</a></li>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?= ($page == $i ? 'active' : '') ?>">
                                    <a class="page-link" href="?p=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($page < $total_pages): ?>
                                <li class="page-item"><a class="page-link" href="?p=<?= $page+1 ?>">Next</a></li>
                            <?php endif; ?>
                        </ul>
                    </nav>

                </div>
            </div>

        </main>

        <?php include "include/footerbar.php"; ?>
    </div>
</div>

<?php include "include/footerscripts.php"; ?>

<script>
function loadEdit(id, category, amount, date, desc) {
    document.getElementById("exp_id").value = id;
    document.getElementById("exp_category").value = category;
    document.getElementById("exp_amount").value = amount;
    document.getElementById("exp_date").value = date;
    document.getElementById("exp_desc").value = desc;

    document.getElementById("form-title").innerHTML = "Update Manual Expense";
    document.getElementById("btn-save").classList.add("d-none");
    document.getElementById("btn-update").classList.remove("d-none");
}

$(document).on("click", ".toggle-btn", function () {
    let target = $(this).data("target");

    // Toggle collapse rows
    $("." + target).slideToggle(200);

    // Rotate arrow
    $(this).toggleClass("open");
});
function downloadRangePDF() {
    let from = document.querySelector('input[name="date_from"]').value;
    let to   = document.querySelector('input[name="date_to"]').value;
    let cat  = document.querySelector('select[name="category"]').value;

    if(from === "" || to === ""){
        alert("Please select both From Date and To Date!");
        return;
    }

    window.open(
        "<?= base_url('ExpenseEntry/pdf_range?from=') ?>" + from + "&to=" + to + "&cat=" + cat,
        "_blank"
    );
}
</script>

<style>
.toggle-btn .arrow {
    display: inline-block;
    transition: transform 0.2s ease;
}
.toggle-btn.open .arrow {
    transform: rotate(90deg);
}
</style>
<?php include "include/footer.php"; ?>
