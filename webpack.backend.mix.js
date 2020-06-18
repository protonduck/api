const mix = require('laravel-mix');

/**
 * Backend
 */

mix
    .setResourceRoot('backend/web')
    .setPublicPath('backend/web')
    .js('backend/web/resources/js/app.js', 'backend/web/js')
    .sass('backend/web/resources/sass/app.scss', 'backend/web/css')
    .sass('backend/web/resources/sass/site.scss', 'backend/web/css');
