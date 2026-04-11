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
                'resources/js/cart.js',
                'resources/js/catalog.js',
                'resources/js/produk-gallery.js',
                'resources/js/checkout.js',
                'resources/js/print.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],

});
