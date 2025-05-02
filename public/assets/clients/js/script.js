// Hiển thị/ ẩn mật khẩu
document.querySelectorAll('.toggle-password').forEach(function (toggleIcon) {
    toggleIcon.addEventListener('click', function () {
        const input = this.closest('.form-floating').querySelector('.password-input');
        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);
        
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });
});
