<h5 class="">{{$page_title}}</h5>
<hr>
<div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <form class="row g-3" style="max-width: 700px; width: 100%;" method="post" action="/users/update/{{$user['id']}}">
        <div class="col-md-12">
            <label for="name" class="form-label">Tài khoản</label>
            <input type="text" name="name" class="form-control mx-auto" id="name" value="{{ old('name', $user['name']) }}" required>
            {! form_error('name', '<span style="color: red;">', '</span>') !}
        </div>
        <div class="col-md-12">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control mx-auto" id="email" placeholder="name@example.com" value="{{ old('email', $user['email']) }}" required>
            {! form_error('email', '<span style="color: red;">', '</span>') !}
        </div>
        <div class="col-md-12">
            <label for="password" class="form-label">Mật khẩu</label>
            <input type="password" name="password" class="form-control mx-auto" id="password" aria-describedby="inputGroupPrepend2" required>
            {! form_error('password', '<span style="color: red;">', '</span>') !}
        </div>
        <div class="col-md-12">
            <label for="confirm_password" class="form-label">Nhập lại mật khẩu</label>
            <input type="password" name="confirm_password" class="form-control mx-auto" id="confirm_password" required>
            {! form_error('confirm_password', '<span style="color: red;">', '</span>') !}
        </div>
        <div class="col-md-12">
            <label for="validationDefault04" class="form-label">Nhóm</label>
            <select class="form-select mx-auto" name="group_id" id="validationDefault04" required>
                <option selected disabled value="">Chọn nhóm...</option>
                @foreach ($groups as $group)
                    <option value="{{$group['id']}}" {{!empty(old('group_id', $user['group_id'])) && old('group_id', $user['group_id']) == $group['id'] ? 'selected' : ''}}>{{$group['name']}}</option>
                @endforeach
            </select>
            {! form_error('group_id', '<span style="color: red;">', '</span>') !}
        </div>
        <div class="col-md-12">
            <label for="status" class="form-label">Trạng thái</label>
            <select class="form-select mx-auto" id="status" name="status">
                @foreach ($status as $key => $value)
                    <option value="{{$key}}" {{!empty(old('status', $user['status'])) && old('status', $user['status']) == $key ? 'selected' : ''}}>{{$value}}</option>
                @endforeach
            </select>
            {! form_error('status', '<span style="color: red;">', '</span>') !}
        </div>
        <div class="col-12 text-center">
            <button class="btn btn-primary" type="submit">Lưu</button>
            <button class="btn btn-danger" type="button">Hủy</button>
        </div>
    </form>
</div>

