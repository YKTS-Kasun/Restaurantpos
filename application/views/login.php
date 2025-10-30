<?php include "include/header.php"; ?>
<div id="layoutAuthentication">
    <div id="layoutAuthentication_content">
        <main>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-4">
                        <div class="card shadow-lg rounded-0 mt-5">
                            <div class="card-header justify-content-center bg-transparent"><img src="<?php echo base_url() ?>images/logo.png" class="img-fluid" alt=""></div>
                            <div class="card-body pt-3">
                                <form action="<?php echo base_url() ?>Welcome/LoginUser" method="post" autocomplete="off">
                                    <div class="form-group"><label class="small mb-1" for="inputEmailAddress">Username</label><input class="form-control form-control-sm rounded-0 py-3" id="username" name="username" type="text" placeholder="username" autofocus /></div>
                                    <div class="form-group"><label class="small mb-1" for="inputPassword">Password</label><input class="form-control form-control-sm rounded-0 py-3" id="password" name="password" type="password" placeholder="****" /></div>
                                    <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0"><button type="submit" class="btn btn-dark rounded-0 btn-sm ml-auto px-4"><i class="fas fa-lock mr-2"></i>Login</button></div>
                                </form>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-12 small text-center">Copyright &copy; Erav Technology <?php echo date('Y') ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<?php include "include/footerscripts.php"; ?>
<script type="text/javascript">
    $(document).ready(function () {
        $('#username').keyboard();
        $('#password').keyboard();
    });
</script>
<?php include "include/footer.php"; ?>
