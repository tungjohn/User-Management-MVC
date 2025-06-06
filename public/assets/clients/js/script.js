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

// check all checkboxes
const checkAll = document.querySelectorAll('.check-all');
const checkboxes = document.querySelectorAll('.check-item');
const deleteChecked = document.querySelector('#delete-checked');
var numChecked = 0;

if (checkAll.length > 0 && checkboxes.length > 0) {
    // check all checkboxes when the "check all" checkbox is clicked
    checkAll[0].addEventListener('click', function () {
        checkboxes.forEach(function (checkbox) {
            checkbox.checked = checkAll[0].checked;
            const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
            deleteChecked.disabled = !anyChecked;
        });
        // update the count of checked items
        
        numChecked = checkAll[0].checked ? checkboxes.length : 0;
        document.querySelector('#number-checked').textContent = numChecked;
    });

    // update "check all" checkbox when any individual checkbox is clicked
    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('click', function () {
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            checkAll[0].checked = allChecked;
        });
    });

    if (deleteChecked) {
        // enable/disable delete button based on checked items
        checkboxes.forEach(function (checkbox) {
            checkbox.addEventListener('click', function () {
                const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
                deleteChecked.disabled = !anyChecked;
                // update the count of checked items
                numChecked = Array.from(checkboxes).filter(cb => cb.checked).length;
                document.querySelector('#number-checked').textContent = numChecked;
            });
        });

        
    }
}

function toggleDeleteAll() {
    const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
    
    deleteChecked.disabled = !anyChecked;
    
    // update the count of checked items
    numChecked = anyChecked ? Array.from(checkboxes).filter(cb => cb.checked).length : 0;
    document.querySelector('#number-checked').textContent = numChecked;
}