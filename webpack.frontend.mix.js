const mix = require('laravel-mix');

/**
 * Frontend
 */

mix
    .setResourceRoot('frontend/web')
    .setPublicPath('frontend/web')
    .js('frontend/web/resources/js/app.js', 'frontend/web/js')
    .sass('frontend/web/resources/sass/app.scss', 'frontend/web/css')
    .sass('frontend/web/resources/sass/site.scss', 'frontend/web/css');

