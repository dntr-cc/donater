import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path'

export default defineConfig({
    plugins: [
        laravel([
            'resources/js/app.js',
            'resources/js/tabs.js',
            'resources/js/tinymce.js',
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
