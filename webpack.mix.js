const mix = require('laravel-mix');

mix.disableNotifications();
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.sass('resources/sass/app.scss', 'public/css')
.js([

    // app.js General Script
    "resources/js/app.js",

    // View correspondent scripts
    "resources/js/app/settings/user/datatables_users.js",
    "resources/js/app/dailyReports.js",

], "public/js/app.js")
.extract([
    'datatables.net-dt',
    'quill',
    '@fortawesome/fontawesome-free',
    '@lottiefiles/lottie-player'
])
