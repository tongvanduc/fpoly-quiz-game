let mix = require('laravel-mix');

mix
    .js([
        'resources/js/bootstrap.js', //require files
        'resources/js/app.js',
    ], 'dist/main.js')
    .version()
// .js('resources/js/bootstrap.js', 'dist')
// .js('resources/js/app.js', 'dist');
