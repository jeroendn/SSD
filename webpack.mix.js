let mix = require('laravel-mix');

mix.sass('css/style.scss', 'css/style.css')
    .sourceMaps(true, 'source-map');