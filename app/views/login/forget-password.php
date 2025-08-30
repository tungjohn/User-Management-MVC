<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="boderder p-3 mt-3 mb-3 bg-light rounded shadow">
        <form class="px-3" action="/auth/send-token-reset-password" method="POST">
            <div class="row mb-3 text-center">
                <img class="logo-login py-3" src="<?php echo _ASSET_DIR ?>/clients/images/coder-logo.png" alt="">
                <h2>{{$page_title}}</h2>
            </div>
            
            <div class="row mb-3">
                <div class="col-sm-12 ">
                    <div class="form-floating">
                        <input type="email" class="form-control" id="email" placeholder="" name="email" value="{{ old('email') }}" required>
                        <label for="email">Email</label>
                    </div>
                    {! form_error('email', '<span style="color: red;">', '</span>') !}
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-primary w-100 mb-2">Yêu cầu đặt lại mật khẩu</button>
                    <a class="btn btn-success w-100 mb-2 back" href="<?php echo _WEB_ROOT . '/auth/login' ?>">Quay lại</a>
                </div>
            </div>
        </form>
        @endif
    </div>
</div>


