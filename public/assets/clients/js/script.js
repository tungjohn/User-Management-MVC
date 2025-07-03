// toggle the visibility of password fields
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
const checkAll = document.querySelector('.check-all');
const checkboxes = document.querySelectorAll('.check-item');
const deleteChecked = document.querySelector('#delete-checked');
let ids = [];

if (checkAll && checkboxes.length > 0) {
    // check all checkboxes when the "check all" checkbox is clicked
    checkAll.addEventListener('click', function () {
        checkboxes.forEach(function (checkbox) {
            checkbox.checked = checkAll.checked;
            toggleDeleteAll();
        });
        // update the count of checked items
        document.querySelector('#number-checked').textContent = numChecked();

        // push or remove the checkbox value from the ids array
        if (checkAll.checked) {
            ids = Array.from(checkboxes).map(cb => cb.value);
        } else {
            ids = [];
        }

        // Update the hidden input with the selected IDs
        document.querySelector('#ids').value = ids.join(',');
    });

    // update "check all" checkbox when any individual checkbox is clicked
    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('click', function () {
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            checkAll.checked = allChecked;

            // push or remove the checkbox value from the ids array
            if (checkbox.checked && !ids.includes(checkbox.value)) {
                ids.push(checkbox.value);
            } else if (!checkbox.checked) {
                ids = ids.filter(id => id !== checkbox.value);
            }
            
            // Update the hidden input with the selected IDs
            document.querySelector('#ids').value = ids.join(','); 
        });
    });

    if (deleteChecked) {
        // enable/disable delete button based on checked items
        checkboxes.forEach(function (checkbox) {
            checkbox.addEventListener('click', function () {
                toggleDeleteAll();
                // update the count of checked items
                document.querySelector('#number-checked').textContent = numChecked();
            });
        });
    }
}

// Enable or disable the delete button based on checked items
function toggleDeleteAll() {
    const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
    deleteChecked.disabled = !anyChecked;
}

// Count the number of checked checkboxes
function numChecked() {
    return Array.from(checkboxes).filter(cb => cb.checked).length;
}

// Show the modal when the delete button is clicked
if (deleteChecked) {
    deleteChecked.addEventListener('click', function (e) {
        e.preventDefault();
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                e.target.closest('form').submit();
            }
        });
    });
}

// delete row user
const deleteButton = document.querySelector('.delete-row');
if (deleteButton) {
    deleteButton.forEach(function (link) {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to the delete URL
                    window.location.href = this.href;
                }
            });
        });
    });
}


// cancel button
const cancelButton = document.querySelector('.cancel-button');
if (cancelButton) {
    cancelButton.addEventListener('click', function (e) {
        e.preventDefault();
        Swal.fire({
            title: "Are you sure?",
            text: "Do you really want to exit? All data will not be saved!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Confirm"
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to the back link
                window.history.back();
            }
        });
    });
}

