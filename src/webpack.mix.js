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

mix.css('resources/css/common.css', 'public/css')
   .css('resources/css/login.css', 'public/css')
   .css('resources/css/profile.css', 'public/css')
   .css('resources/css/register.css', 'public/css')
   .css('resources/css/santize.css', 'public/css');

mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        //
    ]);
