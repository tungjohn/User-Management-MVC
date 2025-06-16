<h3>{{$page_title}}</h3>
<hr>
<div class="row mb-3">
    <div class="col-6">
        <a href="#" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Thêm mới</a>
    </div>
</div>

    <form action="/users/" method="get" class="mb-3">
        <div class="row">
            <div class="col-4">
                <input type="text" name="keyword" id="" class="form-control" placeholder="Từ khóa..." value="{{!empty($dataFields['keyword']) ? $dataFields['keyword'] : ''}}">
            </div>
            <div class="col-3">
                <select name="group" id="" class="form-select">
                    <option value="0">Tất cả nhóm</option>
                    @foreach($groups as $group)
                        <option value="{{$group['id']}}" {{!empty($dataFields['group']) && $dataFields['group'] == $group['id'] ? 'selected' : ''}}>{{$group['name']}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-3">
                <select name="status" id="" class="form-select">
                    <option value="0">Tất cả trạng thái</option>
                    @foreach($status as $key => $value)
                        <option value="{{$key}}" {{!empty($dataFields['status']) && $dataFields['status'] == $key ? 'selected' : ''}}>{{$value}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-2 d-grid">
                <button type="submit" class="btn btn-primary">Tìm kiếm <i class="fa fa-search" aria-hidden="true"></i></button>
            </div>
        </div>
        
        
    </form>

<div class="">
    <table class="table table-responsive table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th scope="col"><input type="checkbox" name="" id="user-all" class="check-all"></th>
                <th scope="col">#</th>
                <th scope="col">Họ Tên</th>
                <th scope="col">Email</th>
                <th scope="col">Nhóm</th>
                <th scope="col">Trạng thái</th>
                <th scope="col">Thời gian tạo</th>
                <th scope="col">Tác vụ</th>
            </tr>
        </thead>
        <tbody>
            @php
                $stt = 0;
            @endphp
            @foreach($users as $user)
            @php
                $stt++;
            @endphp
            <tr>
                <th scope="col"><input type="checkbox" name="" id="user-1" class="check-item" value="{{$user['id']}}"></th>
                <td>{{$stt}}</td>
                <td>{{$user['name']}}</td>
                <td>{{$user['email']}}</td>
                <td>
                    @if($user['group_id'] == 1)
                        <span class="badge bg-light text-dark"><i class="fa fa-star text-warning" aria-hidden="true"></i><i class="fa fa-star text-warning" aria-hidden="true"></i> Admin</span>
                    @elseif($user['group_id'] == 2)
                        <span class="badge bg-light text-dark"><i class="fa fa-star text-warning" aria-hidden="true"></i> Mod</span>
                    @else
                        <span class="badge bg-light text-dark">Member</span>
                    @endif
                </td>
                <td>{! $user['status'] == 1 ? '<span class="badge bg-success">Kích hoạt</span>' : '<span class="badge bg-danger">Chưa kích hoạt</span>' !}</td>
                <td>{! !empty($user['created_at']) ? $user['created_at'] : '' !}</td>
                <td>
                    <a href="{{_WEB_ROOT . '/users/edit/' . $user['id']}}" class="btn btn-warning btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Sửa</a>
                    <a href="{{_WEB_ROOT . '/users/delete/' . $user['id']}}" class="btn btn-danger btn-sm delete-row"><i class="fa fa-trash-o" aria-hidden="true"></i> Xóa</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="row">
        <div class="col-6">
            <form action="{{ _WEB_ROOT . '/users/delete/' }}" method="post">
                <input type="hidden" name="ids" id="ids" value="">
                <button type="submit" class="btn btn-danger" id="delete-checked" data-ids="" disabled>Xóa đã chọn (<span id="number-checked">0</span>)</button>
            </form>
        </div>
        <div class="col-6">
            {! $users->links() !}
        </div>
    </div>
</div>
<script type="text/javascript">
    // sau khi render xong view thì sẽ chạy đoạn này
    document.addEventListener('DOMContentLoaded', function() {
        let actionAlert = '{! $action ?? "" !}';
        if (actionAlert != '') {
            let icon = '{! $icon ?? "" !}';
            let message = '{! $message ?? "" !}';
            
            if (icon != '' && message != '') {
                Swal.fire({
                    icon: icon,
                    title: actionAlert,
                    text: message,
                    confirmButtonText: 'OK'
                });
            }
        }
    });
    
</script>

