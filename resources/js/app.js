import './bootstrap';

import jquery from "jquery";

window.$ = window.jQuery = jquery;

import {Toast} from 'bootstrap';

window.Toast = Toast;

import '../sass/app.scss'

window.copyContent = async function copyContent(text) {
    try {
        await navigator.clipboard.writeText(text);
    } catch (err) {
        alert('Помилка копіювання');
    }
}
window.toast = function toast(text, selector) {
    let toast = $('#toast');
    let toastBootstrap = window.Toast.getOrCreateInstance(toast)
    selector.click(() => {
        $('#toast .toastText').text(text);
        toastBootstrap.show();
        setInterval(() => {
            $('closeToast').click();
        }, 500);
    });
}
