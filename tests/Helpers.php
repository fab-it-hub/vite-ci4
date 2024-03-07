<?php

use Config\App;
use Fabithub\ViteJs\Config\ViteJs;
use Fabithub\ViteJs\Core\AssetTagGenerator;
use Fabithub\ViteJs\Core\Manifest;
use Fabithub\ViteJs\Utils\Utils;

function setupViteConfig()
{
    $appConfig  = config(App::class);
    $viteConfig = config(ViteJs::class);

    $appConfig->indexPage   = '';
    $appConfig->uriProtocol = 'PATH_INFO';
    $appConfig->baseURL     = 'https://example.com';

    $viteConfig->publicPath = __DIR__ . DIRECTORY_SEPARATOR;
}

function tearDownViteConfig()
{
    $appConfig  = config(App::class);
    $viteConfig = config(ViteJs::class);

    $appConfig->reset();
    $appConfig->CSPEnabled  = false;
    $appConfig->indexPage   = 'index.php';
    $appConfig->uriProtocol = 'REQUEST_URI';
    $appConfig->baseURL     = 'http://localhost:8080/';

    $viteConfig->reset();
    $viteConfig->assetURL   = null;
    $viteConfig->publicPath = FCPATH;
}

function makeViteManifest(array $contents = null, string $buildDirectory = 'build', string $manifestFileName = 'manifest.json'): void
{
    config(ViteJs::class)->buildDirectory   = $buildDirectory;
    config(ViteJs::class)->manifestFileName = $manifestFileName;
    config(ViteJs::class)->publicPath       = __DIR__ . DIRECTORY_SEPARATOR;

    $manifestFile = (new Manifest())->getPath();
    $dir      = Utils::publicPath(Utils::buildDirectory('', DIRECTORY_SEPARATOR));

    if (!file_exists($dir)) {
        mkdir($dir);
    }

    $manifest = json_encode($contents ?? [
        'resources/js/app.js' => [
            'src' => 'resources/js/app.js',
            'file' => 'assets/app.versioned.js',
        ],
        'resources/js/app-with-css-import.js' => [
            'src' => 'resources/js/app-with-css-import.js',
            'file' => 'assets/app-with-css-import.versioned.js',
            'css' => [
                'assets/imported-css.versioned.css',
            ],
        ],
        'resources/css/imported-css.css' => [
            // 'src' => 'resources/css/imported-css.css',
            'file' => 'assets/imported-css.versioned.css',
        ],
        'resources/js/app-with-shared-css.js' => [
            'src' => 'resources/js/app-with-shared-css.js',
            'file' => 'assets/app-with-shared-css.versioned.js',
            'imports' => [
                '_someFile.js',
            ],
        ],
        'resources/css/app.css' => [
            'src' => 'resources/css/app.css',
            'file' => 'assets/app.versioned.css',
        ],
        '_someFile.js' => [
            'file' => 'assets/someFile.versioned.js',
            'css' => [
                'assets/shared-css.versioned.css',
            ],
        ],
        'resources/css/shared-css' => [
            'src' => 'resources/css/shared-css',
            'file' => 'assets/shared-css.versioned.css',
        ],
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

    file_put_contents($manifestFile, $manifest);
}

function cleanViteManifest($buildDirectory = 'build', $manifestFileName = 'manifest.json')
{
    config(ViteJs::class)->buildDirectory   = $buildDirectory;
    config(ViteJs::class)->manifestFileName = $manifestFileName;

    $manifest = (new Manifest())->getPath();
    $dir      = Utils::publicPath(Utils::buildDirectory('', DIRECTORY_SEPARATOR));

    if (file_exists($manifest)) {
        unlink($manifest);
    }

    if (is_dir($dir)) {
        rmdir($dir);
    }
}

function makeAsset($asset, $content)
{
    $path = Utils::publicPath(Utils::buildDirectory('assets', DIRECTORY_SEPARATOR));

    if (!file_exists($path)) {
        mkdir($path, recursive: true);
    }

    file_put_contents($path . '/' . $asset, $content);
}

function cleanAsset($asset)
{
    $path = Utils::publicPath(Utils::buildDirectory('assets', DIRECTORY_SEPARATOR));

    unlink($path . $asset);

    rmdir($path);
}


function makeViteHotFile($path = null)
{
    $path ??= Utils::publicPath('hot');
    config(ViteJs::class)->hotFile = $path;

    file_put_contents($path, 'http://localhost:3000');
}


function cleanViteHotFile($path = null)
{
    $path ??= Utils::publicPath('hot');

    if (file_exists($path)) {
        unlink($path);
    }
}


function generate_tags(array $files = [], bool $isProductionBuild = true, bool $isCspEnabled = false, string $buildDirectory = 'build', ?bool $integrity = false): string
{
    $tags = [];

    foreach ($files as $file) {
        if (AssetTagGenerator::isStylesheet($file)) {
            array_push($tags, link_tag($file, $isProductionBuild, $isCspEnabled, $buildDirectory, $integrity));
        } else {
            array_push($tags, script_tag($file, $isProductionBuild, $isCspEnabled, $buildDirectory, $integrity));
        }
    }

    return implode('', $tags);
}


function link_tag(string $url, bool $isProductionBuild = true, bool $isCspEnabled = false, string $buildDirectory = 'build', ?bool $integrity = false): string
{
    $nonce = csrf_hash();

    $tag = "<link rel=\"stylesheet\"";
    $tag .= $isProductionBuild ? " href=\"https://example.com/{$buildDirectory}/assets/{$url}\"" : " href=\"http://localhost:3000/{$url}\"";
    $tag .= $isCspEnabled ? " nonce=\"{$nonce}\"" : "";
    $tag .= $integrity ? " integrity=\"expected-app.css-integrity\"" : "";
    $tag .= " />";

    return $tag;
}


function script_tag(string $url, bool $isProductionBuild = true, bool $isCspEnabled = false, string $buildDirectory = 'build', ?bool $integrity = false): string
{
    $nonce = csrf_hash();

    $tag = "<script type=\"module\"";
    $tag .= $isProductionBuild ? " src=\"https://example.com/{$buildDirectory}/assets/{$url}\"" : " src=\"http://localhost:3000/{$url}\"";
    $tag .= $isCspEnabled ? " nonce=\"{$nonce}\"" : "";
    $tag .= $integrity ? " integrity=\"expected-app.js-integrity\"" : "";
    $tag .= "></script>";

    return $tag;
}
