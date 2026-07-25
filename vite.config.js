import { defineConfig } from 'vite';

export default defineConfig({
    // Copied verbatim into public/ on build; also lets Vite resolve the
    // absolute /fonts/... urls in the scss at build time.
    publicDir: 'static',
    build: {
        // public/ is the webroot: generated css plus the copied static/ files.
        outDir: 'public',
        // Keep the tracked public/.gitkeep so the webroot exists on fresh checkouts.
        emptyOutDir: false,
        rollupOptions: {
            input: 'scss/style.scss',
            output: {
                assetFileNames: 'css/[name][extname]',
            },
        },
    },
});
