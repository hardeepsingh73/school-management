// Import jQuery (if needed for legacy plugins)
import $ from 'jquery';
window.$ = window.jQuery = $; // Make it globally available

// Bootstrap JS (auto-includes Popper.js)
import 'bootstrap';
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

// SweetAlert2
import Swal from 'sweetalert2';
window.Swal = Swal;
// FullCalendar                 
import { Calendar } from '@fullcalendar/core';
window.Calendar = Calendar;
import dayGridPlugin from '@fullcalendar/daygrid';
window.dayGridPlugin = dayGridPlugin;

document.addEventListener('DOMContentLoaded', function () {
    $(function () {
        // Sidebar toggle functionality
        $('#sidebarToggle').on('click', function () {
            $('#sidebar').toggleClass('d-none');
        });

        // Delete confirmation with SweetAlert2
        $('.delete-entry').on('click', function (e) {
            e.preventDefault();
            const $form = $(this).closest('form');

            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it'
            }).then((result) => {
                if (result.isConfirmed) {
                    $form.trigger('submit');
                }
            });
        });

        // Clear logs confirmation
        $('.clearLogs').on('submit', function (e) {
            e.preventDefault();
            const $form = $(this);

            Swal.fire({
                icon: 'warning',
                title: 'Clear All Logs?',
                text: 'Are you sure you want to clear all logs? This action cannot be undone.',
                showCancelButton: true,
                confirmButtonText: 'Yes, clear logs',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
            }).then((result) => {
                if (result.isConfirmed) {
                    $form.off('submit').trigger('submit');
                }
            });
        });
        // Toggle password visibility for both fields
        $('.toggle-password').on('click', function () {
            const $input = $(this).closest('.input-group').find('input');
            const $icon = $(this).find('i');

            if ($input.attr('type') === 'password') {
                $input.attr('type', 'text');
                $icon.removeClass('bi-eye').addClass('bi-eye-slash');
            } else {
                $input.attr('type', 'password');
                $icon.removeClass('bi-eye-slash').addClass('bi-eye');
            }
        });
    });
});