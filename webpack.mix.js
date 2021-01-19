const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.sass('resources/scss/app.scss', 'public/css')
    .js('resources/js/app.js', 'public/js')
    .js('resources/js/liveSearch.js', 'public/js')
    .js('resources/js/addRemoveField.js', 'public/js')
    .copy('node_modules/bootstrap-icons', 'public/bootstraps-icons');