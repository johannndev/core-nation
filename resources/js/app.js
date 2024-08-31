import './bootstrap';

import Alpine from 'alpinejs';

import 'flowbite';


window.Alpine = Alpine;

Alpine.start();

import { Modal } from 'flowbite';


// set the modal menu element
const $targetEl = document.getElementById('modalManual');

// options with default values
const options = {
    placement: 'center-center',
    backdrop: 'static ',
    backdropClasses:
        'bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40',
    closable: true,
    onHide: () => {
        console.log('modal is hidden');
        stopScan();
    },
    onShow: () => {
        
        console.log('modal is shown');
        
        // showAlert();
    },
    onToggle: () => {
        console.log('modal has been toggled');
    },
};

// instance options object
const instanceOptions = {
  id: 'modalManual',
  override: true
};

const modal = new Modal($targetEl, options, instanceOptions);

// modal.show();

// Event listener untuk membuka modal

document.querySelectorAll('#openModalButton').forEach(button => {
    button.addEventListener('click', function() {
        modal.show();
    });
});


// Event listener untuk menutup modal
document.getElementById('closeModalButton').addEventListener('click', () => {
    modal.hide();
});



window.myAppFunction = function() {
    alert("Hello from app.js!");
};

function starScanButton(id) {
    modal.show();
    startScan(id);
    
}

window.starScanButton = starScanButton;
