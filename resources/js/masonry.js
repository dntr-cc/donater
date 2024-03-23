import Masonry from 'masonry-layout';
import imagesLoaded from 'imagesloaded';

window.Masonry = Masonry;
window.Masonry = imagesLoaded;
document.querySelectorAll('.grid').forEach(element => {
    imagesLoaded('.grid', () => {
        new window.Masonry(element, {
            itemSelector: '.grid-item',
        });
    });
});
