<?php include "include/header.php"; ?>
<div id="layoutAuthentication">
    <div id="layoutAuthentication_content">
        <main>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-4">
                        <div class="card shadow-lg rounded-0 mt-5">
                            <div class="card-header justify-content-center bg-transparent">
                                <img src="<?php echo base_url() ?>images/logo.png" class="img-fluid" alt="">
                            </div>

                            <div class="card-body pt-3">
                                <form action="<?php echo base_url() ?>Welcome/LoginUser" method="post" autocomplete="off">

                                    <!-- USERNAME -->
                                    <div class="form-group">
                                        <label class="small mb-1">Username</label>
                                        <input class="form-control form-control-sm rounded-0 py-3"
                                               name="username" id="username" type="text" required>
                                    </div>

                                    <!-- PASSWORD -->
                                    <div class="form-group">
                                        <label class="small mb-1">Password</label>
                                        <input class="form-control form-control-sm rounded-0 py-3"
                                               name="password" id="password" type="password" required>
                                    </div>

                                    <!-- COMPANY -->
                                    <div class="form-group">
                                        <label class="small mb-1">Company *</label>
                                        <select name="company_id" id="company_id"
                                                class="form-control form-control-sm rounded-0">
                                            <option value="">Select</option>
                                            <?php foreach ($companylist as $c): ?>
                                                <option value="<?= $c->idtbl_company ?>">
                                                    <?= $c->company ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <!-- BRANCH -->
                                    <div class="form-group">
                                        <label class="small mb-1">Company Branch *</label>
                                        <select name="branch_id" id="branch_id"
                                                class="form-control form-control-sm rounded-0">
                                            <option value="">Select</option>
                                        </select>
                                    </div>

                                    <div class="form-group mt-4 mb-0 text-right">
                                        <button type="submit" class="btn btn-dark btn-sm rounded-0 px-4">
                                            <i class="fas fa-lock mr-2"></i> Login
                                        </button>
                                    </div>

                                </form>
                            </div>

                            <div class="card-footer text-center small">
                                Copyright © Erav Technology <?= date('Y') ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include "include/footerscripts.php"; ?>

<script>
$('#company_id').change(function () {
    let company_id = $(this).val();

    $('#branch_id').html('<option value="">Loading...</option>');

    $.post("<?= base_url('Welcome/getBranchesByCompany') ?>",
        {company_id: company_id},
        function (data) {
            $('#branch_id').html(data);
        }
    );
});
</script>

<?php include "include/footer.php"; ?>
