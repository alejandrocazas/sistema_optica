import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    // === AGREGA ESTO ===
    server: {
        host: '0.0.0.0',
        hmr: {
            host: '192.168.1.9', // <--- ¡AQUÍ PON TU IP EXACTA!
        },
    },
});