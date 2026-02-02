import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/css/tenant.css',
                'resources/js/tenant.js',
            ],
            refresh: [
                'resources/views/**/*.blade.php',
                'resources/views/tenant/**/*.blade.php',
                'resources/js/**/*.js',
                'resources/css/**/*.css',
                'config/**/*.php',
                'routes/**/*.php',
                'routes/tenant.php',
            ],
        }),
    ],
    server: {
        host: 'localhost',
        port: 5173,
        hmr: {
            host: 'localhost',
            port: 5173,
        },
    },
});
