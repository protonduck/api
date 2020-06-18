const mix = require('laravel-mix');

/**
 * Frontend
 */

mix
    .setResourceRoot('frontend/resources')
    .setPublicPath('frontend/web')
    .js('frontend/resources/js/app.js', 'frontend/web/js')
    .sass('frontend/resources/sass/app.scss', 'frontend/web/css')
    .sass('frontend/resources/sass/site.scss', 'frontend/web/css');

