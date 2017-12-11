let mix = require('laravel-mix');
// let WebpackRTLPlugin = require('webpack-rtl-plugin');

mix.sass('resources/assets/coreui-static/scss/style.scss', 'public/css/coreui.css')
    .sass('resources/assets/coreui-static/scss/reports.scss', 'public/css/reports.css')
    .sass('resources/assets/coreui-static/scss/ordering.scss', 'public/css/ordering.css')
    .sass('resources/assets/coreui-static/scss/ordermonth.scss', 'public/css/ordermonth.css')
    .sass('resources/assets/coreui-static/scss/orderlunches.scss', 'public/css/orderlunches.css')
    .js([
        'resources/assets/coreui-static/js/before.js',
        'resources/assets/coreui-static/js/app.js',
        'resources/assets/coreui-static/js/after.js'
    ], 'public/js/coreui.js');

if(mix.inProduction){
    mix.version();
}