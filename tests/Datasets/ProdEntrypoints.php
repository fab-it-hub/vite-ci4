<?php

dataset('prod-entrypoints', [
    'jsOnly' => [['resources/js/app.js'], fn () => generate_tags(['app.versioned.js'])],
    'css&Js' => [['resources/css/app.css', 'resources/js/app.js'], fn () => generate_tags([
        'app.versioned.css', 'app.versioned.js'
    ])],
    'cssImport' => [['resources/js/app-with-css-import.js'], fn () => generate_tags([
        'imported-css.versioned.css', 'app-with-css-import.versioned.js'
    ])],
    'sharedCssImport' => [['resources/js/app-with-shared-css.js'], fn () => generate_tags([
        'shared-css.versioned.css', 'app-with-shared-css.versioned.js'
    ])],
]);

dataset('prod-entrypoints-csp', [
    'jsOnly' => [['resources/js/app.js'], fn () => generate_tags(['app.versioned.js'], true, true)],
    'css&Js' => [['resources/css/app.css', 'resources/js/app.js'], fn () => generate_tags([
        'app.versioned.css', 'app.versioned.js'
    ], true, true)],
    'cssImport' => [['resources/js/app-with-css-import.js'], fn () => generate_tags([
        'imported-css.versioned.css', 'app-with-css-import.versioned.js'
    ], true, true)],
    'sharedCssImport' => [['resources/js/app-with-shared-css.js'], fn () => generate_tags([
        'shared-css.versioned.css', 'app-with-shared-css.versioned.js'
    ], true, true)],
]);

$randomDir = substr(md5(rand()), 0, 10);
$integrateManifest = [
    'resources/js/app.js' => [
        'src' => 'resources/js/app.js',
        'file' => 'assets/app.versioned.js',
        'integrity' => 'expected-app.js-integrity',
    ],
    'resources/js/app-with-css-import.js' => [
        'src' => 'resources/js/app-with-css-import.js',
        'file' => 'assets/app-with-css-import.versioned.js',
        'integrity' => 'expected-app.js-integrity',
        'css' => [
            'assets/imported-css.versioned.css',
        ],
    ],
    'resources/css/imported-css.css' => [
        // 'src' => 'resources/css/imported-css.css',
        'file' => 'assets/imported-css.versioned.css',
        'integrity' => 'expected-app.css-integrity',
    ],
    'resources/js/app-with-shared-css.js' => [
        'src' => 'resources/js/app-with-shared-css.js',
        'file' => 'assets/app-with-shared-css.versioned.js',
        'integrity' => 'expected-app.js-integrity',
        'imports' => [
            '_someFile.js',
        ],
    ],
    'resources/css/app.css' => [
        'src' => 'resources/css/app.css',
        'file' => 'assets/app.versioned.css',
        'integrity' => 'expected-app.css-integrity',
    ],
    '_someFile.js' => [
        'file' => 'assets/someFile.versioned.js',
        'integrity' => 'expected-app.js-integrity',
        'css' => [
            'assets/shared-css.versioned.css',
        ],
    ],
    'resources/css/shared-css' => [
        'src' => 'resources/css/shared-css',
        'file' => 'assets/shared-css.versioned.css',
        'integrity' => 'expected-app.css-integrity',
    ],
];

dataset('prod-entrypoints-integrity', [
    'jsOnly' => [$integrateManifest, ['resources/js/app.js'], fn () => generate_tags([
        'app.versioned.js'
    ], true, false, $randomDir, true), $randomDir],
    'css&Js' => [$integrateManifest, ['resources/css/app.css', 'resources/js/app.js'], fn () => generate_tags([
        'app.versioned.css', 'app.versioned.js'
    ], true, false, $randomDir, true), $randomDir],
    'cssImport' => [$integrateManifest, ['resources/js/app-with-css-import.js'], fn () => generate_tags([
        'imported-css.versioned.css', 'app-with-css-import.versioned.js'
    ], true, false, $randomDir, true), $randomDir],
    'sharedCssImport' => [$integrateManifest, ['resources/js/app-with-shared-css.js'], fn () => generate_tags([
        'shared-css.versioned.css', 'app-with-shared-css.versioned.js'
    ], true, false, $randomDir, true), $randomDir],
]);

$assetsManifest = [
    'resources/images/profile.png' => [
        'src' => 'resources/images/profile.png',
        'file' => 'assets/profile.versioned.png',
    ],
];

dataset('prod-entrypoints-assets', [
    'baseURL' => [$assetsManifest, 'https://cdn.app.com', fn () => generate_tags([
        'app.versioned.js'
    ], true, false, $randomDir, true), $randomDir],
    'assetURL' => [$assetsManifest, 'https://cdn.app.com', fn () => generate_tags([
        'app.versioned.css', 'app.versioned.js'
    ], true, false, $randomDir, true), $randomDir],
    'null' => [$assetsManifest, 'resources/images/profile.png', fn () => generate_tags([
        'imported-css.versioned.css', 'app-with-css-import.versioned.js'
    ], true, false, $randomDir, true), $randomDir],
]);
