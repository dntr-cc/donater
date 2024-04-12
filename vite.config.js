import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path'

export default defineConfig({
    plugins: [
        laravel([
            'resources/js/app.js',
            'resources/js/tabs.js',
            'resources/js/chartjs.js',
            'resources/js/tinymce.js',
            'resources/js/pickerjs.js',
            'resources/js/masonry.js',
            'resources/js/swiper.js',
            'resources/sass/timeline.scss',
            'resources/sass/pickerjs.scss',
            'resources/sass/swiper.scss',
            'resources/sass/app.scss',
        ]),
    ],
    resolve: {
        alias: {
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
            '~bootstrap-icons': path.resolve(__dirname,'node_modules/bootstrap-icons'),
            'tinymce/plugins/link': path.resolve(__dirname, 'node_modules/tinymce/plugins/link/plugin.js'),
            'tinymce/plugins/table': path.resolve(__dirname, 'node_modules/tinymce/plugins/table/plugin.js'),
            'tinymce/plugins/media': path.resolve(__dirname, 'node_modules/tinymce/plugins/media/plugin.js'),
            'mdb-ui-kit': path.resolve(__dirname, 'node_modules/mdb-ui-kit'),
            'pickerjs': path.resolve(__dirname, 'node_modules/pickerjs'),
            'masonry': path.resolve(__dirname, 'node_modules/masonry-layout'),
            'imagesloaded': path.resolve(__dirname, 'node_modules/imagesloaded'),
            'swiper': path.resolve(__dirname, 'node_modules/swiper'),
        }
    },
    server: {
        host: true, // needed for the Docker Container port mapping to work
        strictPort: true,
        watch: {
            usePolling: true
        }
    }
});
