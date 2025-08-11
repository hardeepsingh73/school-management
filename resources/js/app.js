// Import jQuery and make it global
import $ from 'jquery';
window.$ = window.jQuery = $;

// Bootstrap JS (requires Popper.js)
import 'bootstrap';
import * as Popper from '@popperjs/core';

// SweetAlert2
import Swal from 'sweetalert2';
window.Swal = Swal;

document.addEventListener('DOMContentLoaded', function () {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('d-none');
        });
    }
});
