<?php

dataset('dev-entrypoints', [
    'jsOnly' => [['resources/js/app.js'], fn () => generate_tags([
        '@vite/client', 'resources/js/app.js'
    ], false)],
    'css&Js' => [['resources/css/app.css', 'resources/js/app.js'], fn () => generate_tags([
        '@vite/client', 'resources/css/app.css', 'resources/js/app.js'
    ], false)],
    'cssImport' => [['resources/js/app-with-css-import.js'], fn () => generate_tags([
        '@vite/client', 'resources/js/app-with-css-import.js'
    ], false)],
    'sharedCssImport' => [['resources/js/app-with-shared-css.js'], fn () => generate_tags([
        '@vite/client', 'resources/js/app-with-shared-css.js'
    ], false)],
]);

dataset('dev-entrypoints-csp', [
    'jsOnly' => [['resources/js/app.js'], fn () => generate_tags([
        '@vite/client', 'resources/js/app.js'
    ], false, true)],
    'css&Js' => [['resources/css/app.css', 'resources/js/app.js'], fn () => generate_tags([
        '@vite/client', 'resources/css/app.css', 'resources/js/app.js'
    ], false, true)],
    'cssImport' => [['resources/js/app-with-css-import.js'], fn () => generate_tags([
        '@vite/client', 'resources/js/app-with-css-import.js'
    ], false, true)],
    'sharedCssImport' => [['resources/js/app-with-shared-css.js'], fn () => generate_tags([
        '@vite/client', 'resources/js/app-with-shared-css.js'
    ], false, true)],
]);
