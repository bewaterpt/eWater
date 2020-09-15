const mix = require('laravel-mix');

// require('laravel-mix-polyfill');

// mix.disableNotifications();
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

    // Utility Scripts
    "resources/js/app/utility/tinymce.js",

    // View correspondent scripts
    "resources/js/app/settings/users/datatables_users.js",
    "resources/js/app/settings/teams/teams.js",
    "resources/js/app/settings/teams/datatables_teams.js",
    "resources/js/app/settings/permissions/update.js",
    "resources/js/app/components/multiselect_listbox.js",
    "resources/js/app/components/tooltip.js",
    "resources/js/app/daily_reports/dailyReports.js",
    "resources/js/app/daily_reports/datatables_reports.js",
    "resources/js/app/utility/fixes.js",
], "public/js/app.js").babel([
    // app.js General Script
    "resources/js/app.js",

    // Utility Scripts
    "resources/js/app/utility/tinymce.js",

    // View correspondent scripts
    "resources/js/app/settings/users/datatables_users.js",
    "resources/js/app/settings/teams/teams.js",
    "resources/js/app/settings/teams/datatables_teams.js",
    "resources/js/app/settings/permissions/update.js",
    "resources/js/app/components/multiselect_listbox.js",
    "resources/js/app/components/tooltip.js",
    "resources/js/app/daily_reports/dailyReports.js",
    "resources/js/app/daily_reports/datatables_reports.js",
    "resources/js/app/utility/fixes.js",
], "public/js/app.es5.js")
.extract([
    'datatables.net',
    'datatables.net-dt',
    'datatables.net-bs4',
    // 'bootstrap-colorpicker',
    '@fortawesome/fontawesome-free',
    'bootstrap',
    // 'remixicon',
]);
// .polyfill({
//     enabled: true,
//     useBuiltIns: "usage",
//     targets: {"firefox": "50", "ie": 11}
// });

