import { defineConfig } from 'vite';
import { readFile } from 'node:fs/promises';

export default defineConfig({
    // Copied verbatim into public/ on build; also lets Vite resolve the
    // absolute /fonts/... urls in the scss at build time.
    publicDir: 'static',
    plugins: [
        {
            // jQuery is npm-managed but served as a plain script from the
            // webroot (see head.php), not bundled with the site scripts.
            name: 'copy-jquery',
            async generateBundle() {
                for (const file of ['jquery.min.js', 'jquery.min.map']) {
                    this.emitFile({
                        type: 'asset',
                        fileName: `js/${file}`,
                        source: await readFile(`node_modules/jquery/dist/${file}`, 'utf8'),
                    });
                }
            },
        },
    ],
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
