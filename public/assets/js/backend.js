document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('[data-select="select2"]').forEach(function (element) {
        const placeholder = element.getAttribute('data-placeholder') || 'Select an option';
        const allowTags = element.getAttribute('data-tags') === 'true';
        
        $(element).select2({
            tags: allowTags, 
            tokenSeparators: [',', ' '],
            placeholder: placeholder,
            width: '100%',
            // minimumInputLength: 1,
        });
    });
});

document.getElementById('toggle-password').addEventListener('click', function () {
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eye-icon');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.setAttribute('stroke', 'green');
    } else {
        passwordInput.type = 'password';
        eyeIcon.setAttribute('stroke', 'currentColor');
    }
});

document.getElementById('toggle-confirm-password').addEventListener('click', function () {
    const confirmPasswordInput = document.getElementById('confirm_password');
    const confirmEyeIcon = document.getElementById('confirm-eye-icon');
    if (confirmPasswordInput.type === 'password') {
        confirmPasswordInput.type = 'text';
        confirmEyeIcon.setAttribute('stroke', 'green');
    } else {
        confirmPasswordInput.type = 'password';
        confirmEyeIcon.setAttribute('stroke', 'currentColor');
    }
});
document.getElementById('toggle-old-password').addEventListener('click', function () {
    const confirmPasswordInput = document.getElementById('old_password');
    const confirmEyeIcon = document.getElementById('confirm-eye-icon');
    if (confirmPasswordInput.type === 'password') {
        confirmPasswordInput.type = 'text';
        confirmEyeIcon.setAttribute('stroke', 'green');
    } else {
        confirmPasswordInput.type = 'password';
        confirmEyeIcon.setAttribute('stroke', 'currentColor');
    }
});

// Pilih elemen tombol dan dropdown menu
const dropdownButton = document.getElementById('mobile-dropdown-button');
const dropdownMenu = document.getElementById('mobile-dropdown-menu');

// Fungsi untuk toggle dropdown
function toggleDropdown() {
    if (dropdownMenu.classList.contains('hidden')) {
        dropdownMenu.classList.remove('hidden');
        dropdownMenu.classList.add('block');
    } else {
        dropdownMenu.classList.add('hidden');
        dropdownMenu.classList.remove('block');
    }
}

// Tambahkan event listener untuk tombol
if (dropdownButton && dropdownMenu) {
    dropdownButton.addEventListener('click', toggleDropdown);
}

// Event listener untuk klik di luar dropdown untuk menutupnya
document.addEventListener('click', (event) => {
    if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
        dropdownMenu.classList.add('hidden');
        dropdownMenu.classList.remove('block');
    }
});