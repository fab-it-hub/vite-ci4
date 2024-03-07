<?php

use CodeIgniter\CodingStandard\CodeIgniter4;
use Nexus\CsConfig\Factory;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__ . '/src/')
    ->exclude(__DIR__ . '/tests/');

$options = [
    'finder'    => $finder,
    'cacheFile' => 'build/.php-cs-fixer.cache'
];

$overrideRules = [
    'binary_operator_spaces'            => false,
    "not_operator_with_successor_space" => false
];

return Factory::create(new CodeIgniter4, $overrideRules, $options)->forLibrary(
    'Codeigniter 4 with Vite.js',
    'Fab IT Hub',
    'hello@fabithub.com',
    2024
);
