<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="boderder p-3 mt-3 mb-3 bg-light rounded shadow">
        <form class="px-3" action="/auth/do-login" method="POST">
            <div class="row mb-3 text-center">
                <img class="logo-login py-3" src="<?php echo _ASSET_DIR ?>/clients/images/coder-logo.png" alt="">
                <h2>{{$page_title}}</h2>
            </div>
            
            <div class="row mb-3">
                <div class="col-sm-12 ">
                    <div class="form-floating ">
                        <input type="email" class="form-control" id="email" placeholder="" name="email" value="{{ old('email') }}" required>
                        <label for="email">Email</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-12">
                    <div class="form-floating position-relative">
                        <input type="password" class="form-control password-input" id="password" placeholder="" name="password" required>
                        <label for="password">Mật khẩu</label>
                        <i class="fa fa-eye-slash toggle-password position-absolute top-50 end-0 translate-middle-y me-3" style="cursor: pointer;" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-sm-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember_me" name="remember_me">
                        <label class="form-check-label" for="remember_me">
                            Duy trì đăng nhập
                        </label>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="text-end">
                        <a href="<?php echo _WEB_ROOT . '/auth/forget-password' ?>" title="Quên mật khẩu">Quên mật khẩu</a>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-primary w-100 mb-2">Đăng nhập</button>
                    <p>Bạn chưa có tài khoản? <a href="<?php echo _WEB_ROOT . '/auth/register' ?>" >Đăng ký ngay!!</a></p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-12">
                    <p class="text-center">Hoặc đăng nhập bằng</p>
                    <div class="d-flex justify-content-center align-items-center">
                        <a href="<?php echo _WEB_ROOT . '/auth/login/facebook' ?>" class="btn btn-primary me-2" title="facebook"><i class="fa fa-facebook-official"></i></a>
                        <a href="<?php echo _WEB_ROOT . '/auth/login/google' ?>" class="btn btn-danger me-2" title="google"><i class="fa fa-google"></i></a>
                        <a href="<?php echo _WEB_ROOT . '/auth/login/github' ?>" class="btn btn-dark" title="github"><i class="fa fa-github"></i></a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
