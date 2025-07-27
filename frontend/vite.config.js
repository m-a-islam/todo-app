// In frontend/vite.config.js
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
    plugins: [vue()],
    server: {
        proxy: {
            // Proxy any request starting with /api to your backend
            '/api': {
                target: 'http://localhost:8080', // This is the Nginx container's public port
                changeOrigin: true,
                rewrite: (path) => path.replace(/^\/api/, ''), // Optional: remove /api prefix
            },
        },
    },
})