import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import toast from "toastr";

window.toast = toast;

document.addEventListener('livewire:init', () => {
    Livewire.on('notify', ({ type, message }) => {
        toast[type] ? toast[type](message) : toast.info(message);
    });
});
