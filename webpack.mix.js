const mix = require('laravel-mix');


// mix.js('resources/js/app.js', 'public/js')
//     .sass('resources/sass/app.scss', 'public/css');


    mix.styles([
        'resources/plantilla/css/style.css',
        'resources/plantilla/css/font-awesome.min.css',
        'resources/plantilla/css/simple-line-icons.min.css',
    ], 'public/css/plantilla.css')
    .scripts([
        'resources/plantilla/js/jquery.min.js',
        'resources/plantilla/js/popper.min.js',
        'resources/plantilla/js/bootstrap.min.js',
        'resources/plantilla/js/Chart.min.js',
        'resources/plantilla/js/pace.min.js',
        'resources/plantilla/js/template.js',    
    ], 'public/js/plantilla.js')
    .js(['resources/js/app.js'],'public/js/app.js');