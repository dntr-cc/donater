import './bootstrap';

import jquery from "jquery";

window.$ = window.jQuery = jquery;

import slug from 'limax/lib/limax';

window.slug = slug;

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
