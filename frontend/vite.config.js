import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import path from 'path'
import ViteImages from 'vite-plugin-vue-images'

export default defineConfig({
  plugins: [
    vue(),
    ViteImages({
      dirs: ['src/assets/images'], // Garante auto-import de imagens
      extensions: ['jpg', 'jpeg', 'png', 'svg', 'webp'],
      customResolvers: [],
      customSearchRegex: '([a-zA-Z0-9_\\-]+)',
    }),
  ],
  server: {
    host: '0.0.0.0', // Permite acesso externo (ideal para Docker)
    port: 5173,
    strictPort: true,
    watch: {
      usePolling: true, // Essencial para hot reload funcionar no Docker
    },
  },
  resolve: {
    alias: {
      '@': path.resolve(__dirname, './src'),
    },
    extensions: ['.mjs', '.js', '.ts', '.jsx', '.tsx', '.json', '.vue'],
  },
  build: {
    chunkSizeWarningLimit: 2000, // Aumenta o limite para avisos de chunks grandes
  },
})
