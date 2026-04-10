import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/produk.css',
                'resources/js/produk.js',
                'resources/js/catalog.js',
                'resources/js/cart.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],

});
