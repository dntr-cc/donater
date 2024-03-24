import './bootstrap';
import jquery from "jquery";
window.$ = window.jQuery = jquery;

import slug from 'limax/lib/limax';
window.slug = slug;

import {Modal, Toast, Button} from 'bootstrap';
window.Modal = Modal;
window.Toast = Toast;
window.Button = Button;

import { Dropdown, Collapse, initMDB } from 'mdb-ui-kit';
initMDB({ Dropdown, Collapse });

window.copyContent = async function copyContent(text) {
    try {
        await navigator.clipboard.writeText(text);
    } catch (err) {
        alert('Помилка копіювання');
    }
}
window.toast = function toast(text, selector, bgClass = 'text-bg-success') {
    let toast = $('#toast');
    toast.removeClass('text-bg-success')
        .removeClass('text-bg-warning')
        .removeClass('text-bg-secondary')
        .removeClass('text-bg-info')
        .removeClass('text-bg-danger')
        .removeClass('text-bg-primary')
        .addClass(bgClass);
    let toastBootstrap = window.Toast.getOrCreateInstance(toast)
    selector.click(() => {
        $('#toast .toastText').text(text);
        toastBootstrap.show();
        setInterval(() => {
            $('closeToast').click();
        }, 200);
    });
}

window.isValidUrl = urlString => {
    try {
        return Boolean(new URL(urlString));
    } catch (e) {
        return false;
    }
}

window.document.querySelectorAll('.arrow-control').forEach(el => el.addEventListener('click', event => {
    let button = $(event.target.closest('.arrow-control'));
    console.log(button);
    let state = button.attr('data-state');
    if (state === 'down') {
        button.attr('data-state', 'up');
        button.html('<i class="bi bi-arrow-up"></i>');
    } else {
        button.attr('data-state', 'down');
        button.html('<i class="bi bi-arrow-down"></i>');
    }
}));

window.document.querySelectorAll('.copy-text').forEach(el => el.addEventListener('click', event => {
    let button = $(event.target.closest('.copy-text'));
    let text = button.attr('data-text');
    window.copyContent(text);
    toast(button.attr('data-message') + ' "' + text + '" скопійовано' , button);
    button.click();
}));

import '../sass/app.scss'
