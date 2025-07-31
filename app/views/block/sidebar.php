<aside>
    <h3>Menu</h3>
    
    <ul class="nav flex-column">
        <li>
            <span id="user-menu">{{$userInfo['name']}}</span>
            <ul id="user-sub-menu" class="sub-menu" style="display: none;">
                <li id="logout">
                    <a href="{{_WEB_ROOT}}/auth/logout" id="logout-btn" class="btn btn-danger btn-sm"><i class="fa fa-sign-out fa-sm" aria-hidden="true"></i> Đăng xuất</a>
                </li>
            </ul>
        </li>
        <hr>
        <li><a href="{{_WEB_ROOT}}">Trang chủ</a></li>
        <li><a href="{{_WEB_ROOT}}/users/">Quản lý người dùng</a></li>
    </ul>
</aside>
<script>
document.getElementById('user-menu').addEventListener('click', function() {
    var submenu = document.getElementById('user-sub-menu');
    if (submenu.style.display === 'none' || submenu.style.display === '') {
        submenu.style.display = 'block';
    } else {
        submenu.style.display = 'none';
    }
});

document.getElementById('logout-btn').addEventListener('click', function(e) {
    e.preventDefault();
    Swal.fire({
        title: "Bạn có chắc chắn muốn đăng xuất tài khoản?",
        text: "Toàn bộ thông tin đăng nhập sẽ bị xóa!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Đăng xuất"
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirect to the delete URL
            window.location.href = this.href;
        }
    });
});
</script>