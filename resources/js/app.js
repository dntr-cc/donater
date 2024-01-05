import './bootstrap';

import jquery from "jquery";

window.$ = window.jQuery = jquery;

import slug from 'limax/lib/limax';

window.slug = slug;

import {Modal, Toast, Button} from 'bootstrap';

window.Modal = Modal;
window.Toast = Toast;
window.Button = Button;

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

const animation = {
    x: {
        type: 'number',
            easing: 'linear',
            duration: 1000,
            from: NaN, // the point is initially skipped
            delay(ctx) {
            if (ctx.type !== 'data' || ctx.xStarted) {
                return 0;
            }
            ctx.xStarted = true;
            return ctx.index * 1000;
        }
    },
    y: {
        type: 'number',
            easing: 'linear',
            duration: 1000,
            from: (ctx) => ctx.index === 0 ? ctx.chart.scales.y.getPixelForValue(1000) : ctx.chart.getDatasetMeta(ctx.datasetIndex).data[ctx.index - 1].getProps(['y'], true).y,
            delay(ctx) {
            if (ctx.type !== 'data' || ctx.yStarted) {
                return 0;
            }
            ctx.yStarted = true;
            return ctx.index * 1000;
        }
    },
};

window.animation = animation;
