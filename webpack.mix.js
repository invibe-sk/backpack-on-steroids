let mix = require('laravel-mix');

mix.js('resources/js/app.js', '/vendor/backpack-on-steroids')
    .setPublicPath('public')
    .vue().version();
