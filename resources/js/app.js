import './bootstrap';

import Alpine from 'alpinejs';
import Sortable from 'sortablejs';
import Swal from 'sweetalert2';

function alertMessage(type = 'success', message = 'สำเร็จ', title = '') {
    Swal.fire({
        icon: type,        // 'success', 'error', 'warning', 'info', 'question'
        title: title || null,
        text: message,
        confirmButtonText: 'ตกลง',
        timer: 5000,
        timerProgressBar: true,
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
    });
}

window.Sortable = Sortable
window.Alpine = Alpine;
window.Swal = Swal;

window.alertMessage = alertMessage

Alpine.start();
