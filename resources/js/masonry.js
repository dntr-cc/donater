import Masonry from 'masonry-layout';
import imagesLoaded from 'imagesloaded';

imagesLoaded('.masonry-grid', () => {
    document.querySelectorAll('.masonry-grid').forEach(element => {
        new Masonry(element, {
            itemSelector: '.masonry-grid-item',
        });
    });
});
