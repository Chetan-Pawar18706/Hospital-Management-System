function validateForm(form) {
    const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
    for (let i = 0; i < inputs.length; i++) {
        if (inputs[i].value.trim() === '') {
            alert('Please fill all required fields.');
            inputs[i].focus();
            return false;
        }
    }
    return true;
}

function confirmDelete() {
    return confirm('Are you sure you want to delete?');
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('form.validate').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            if (!validateForm(form)) e.preventDefault();
        });
    });
    // nav toggle for small screens
    var toggle = document.querySelector('.nav-toggle');
    if (toggle) {
        toggle.addEventListener('click', function () {
            var links = document.querySelector('.nav-links');
            if (links) links.classList.toggle('open');
        });
    }
});
