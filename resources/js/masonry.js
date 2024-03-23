import Masonry from 'masonry-layout';

window.Masonry = Masonry;

document.querySelectorAll('.grid').forEach(element => {
    new window.Masonry(element, {
        itemSelector: '.grid-item',
    });
});

