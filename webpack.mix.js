let mix = require('laravel-mix');

mix.sass('scss/style.scss', 'public/css/style.css')
    .sourceMaps(true, 'source-map');