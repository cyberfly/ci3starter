<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card-group mb-0">
            <div class="card p-4">
                <div class="card-body">
                    <?php echo form_open("auth/login");?>
                    <h1>Login</h1>
                    <p class="text-muted">Sign In to your account</p>
                    <div class="input-group mb-3">
                                <span class="input-group-addon"><i class="icon-user"></i>
                                </span>
                        <?php echo form_input($identity);?>
                    </div>
                    <div class="input-group mb-4">
                                <span class="input-group-addon"><i class="icon-lock"></i>
                                </span>
                        <?php echo form_input($password);?>
                    </div>

                    <div class="input-group mb-4 hide">
                        <?php echo lang('login_remember_label', 'remember');?>
                        <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"');?>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <button type="submit" class="btn btn-primary px-4">Login</button>
                        </div>
                        <div class="col-6 text-right">
                            <a href="forgot_password" class="btn btn-link px-0"><?php echo lang('login_forgot_password');?></a>
                        </div>
                    </div>
                    <?php echo form_close();?>
                </div>
            </div>
            <div class="card text-white bg-primary py-5 d-md-down-none" style="width:44%">
                <div class="card-body text-center">
                    <div>
                        <h2>Sign up</h2>
                        <p>Dont have any account yet? Please contact the administrator.</p>
                        <button type="button" class="btn btn-primary active mt-3">Contact Now!</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>