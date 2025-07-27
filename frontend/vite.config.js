import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vueDevTools from 'vite-plugin-vue-devtools'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [
    vue(),
    vueDevTools(),
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    }
  },

  // THIS IS THE CRITICAL BLOCK WE ARE ADDING FOR DOCKER
  server: {
    // This tells Vite to listen on all network interfaces inside the
    // container, which is required for Docker's port mapping to work.
    host: true,

    // This sets up the dev server to proxy API requests.
    // It's how your Vue app talks to your Symfony backend.
    proxy: {
      // Any request from your Vue app starting with '/api'
      // will be forwarded to your backend container.
      '/api': {
        // The target is the Nginx container, which Docker knows by
        // its service name 'webserver' on port 80.
        target: 'http://webserver:80',

        // This is required for the proxy to work correctly.
        changeOrigin: true,
      }
    }
  },
  test: {
    // Enable the DOM API for testing components
    environment: 'jsdom',
    // Make test utils like 'describe' and 'it' available globally
    globals: true,
  }
})
