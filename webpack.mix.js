let mix = require('laravel-mix');

mix.sass('app/stylesheet/virtuagora.scss', 'public/assets/css')
    .js('app/components/main.js', 'public/assets/js/virtuagora.js')
    .setPublicPath('public/')
    .webpackConfig({ devtool: "inline-source-map" });