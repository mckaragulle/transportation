import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import livewire from '@defstudio/vite-livewire-plugin'; // <-- import

export default defineConfig({
    plugins: [
        laravel(
            {
                input: ['resources/js/app.js'],
                refresh: true,
            }
        ),
        livewire({
            refresh: ['resources/js/app.js'],
        }),
    ],
    server: {
        // https: {
        //     key: './localhost-key.pem',
        //     cert: './localhost.pem',
        // },
        https: false,
        hmr: {
            host: 'localhost',
        },
    },
});
