import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

const appUrl = new URL(process.env.APP_URL ?? 'http://127.0.0.1:8000');
const defaultDevServerUrl = `${appUrl.protocol}//${appUrl.hostname}:5173`;
const devServerUrl = new URL(process.env.VITE_DEV_SERVER_URL || defaultDevServerUrl);
const devServerPort = Number(devServerUrl.port || (devServerUrl.protocol === 'https:' ? 443 : 5173));
const hmrProtocol = devServerUrl.protocol === 'https:' ? 'wss' : 'ws';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/css/home.css',
                'resources/js/home.js',
                'resources/js/chatbot.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        origin: devServerUrl.origin,
        allowedHosts: [devServerUrl.hostname],
        cors: {
            origin: [appUrl.origin, devServerUrl.origin],
        },
        hmr: {
            host: devServerUrl.hostname,
            protocol: hmrProtocol,
            clientPort: devServerPort,
        },
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
