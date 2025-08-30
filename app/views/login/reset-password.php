<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="boderder p-3 mt-3 mb-3 bg-light rounded shadow">
        <form class="px-3" action="/auth/submit-reset-password" method="POST">
            <div class="row mb-3 text-center">
                <img class="logo-login py-3" src="<?php echo _ASSET_DIR ?>/clients/images/coder-logo.png" alt="">
                <h2>{{$page_title}}</h2>
            </div>
            <input type="hidden" class="form-control" id="token" placeholder="" name="token" value="{{ $resetToken ?? old('token') ?? '' }}" required>
            <div class="row mb-3">
                <div class="col-sm-12">
                    <div class="form-floating">
                        <input type="password" class="form-control password-input" id="password" placeholder="" name="password" required>
                        <label for="password">Mật khẩu mới</label>
                        <i class="fa fa-eye-slash toggle-password position-absolute top-50 end-0 translate-middle-y me-3" style="cursor: pointer;" aria-hidden="true"></i>
                    </div>
                    {! form_error('password', '<span style="color: red;">', '</span>') !}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-12">
                    <div class="form-floating">
                        <input type="password" class="form-control password-input" id="confirm_password" placeholder="" name="confirm_password" required>
                        <label for="confirm_password">Nhập lại mật khẩu mới</label>
                        <i class="fa fa-eye-slash toggle-password position-absolute top-50 end-0 translate-middle-y me-3" style="cursor: pointer;" aria-hidden="true"></i>
                    </div>
                    {! form_error('confirm_password', '<span style="color: red;">', '</span>') !}
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-primary w-100 mb-2">Đặt lại mật khẩu</button>
                </div>
            </div>
        </form>
    </div>
</div>


