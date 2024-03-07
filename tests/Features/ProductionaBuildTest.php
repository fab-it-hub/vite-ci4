<?php

namespace Tests\Features;

use Config\App;
use Fabithub\ViteJs\Config\ViteJs;
use Fabithub\ViteJs\Utils\Utils;
use Fabithub\ViteJs\Vite;

uses()->group('production-build');

describe('When running in production build', function () {
    beforeEach(function () {
        makeViteManifest();
    });


    it('should return correct link | script tags', function (array $entryPoints, string $linkOrScriptTag) {
        expect(implode('', Vite::init(...$entryPoints)))->toStartWith($linkOrScriptTag);
    })->with('prod-entrypoints');


    it('should return correct tags with csp enabled', function (array $entryPoints, string $linkOrScriptTag) {
        config(App::class)->CSPEnabled = true;

        expect(implode('', Vite::init(...$entryPoints)))->toStartWith($linkOrScriptTag);
    })->with('prod-entrypoints-csp');


    it('should return tags with integrity attribute', function (array $integrateManifest, array $entryPoints, string $linkOrScriptTag, string $buildDir) {
        makeViteManifest($integrateManifest, $buildDir);
        expect(implode('', Vite::init(...$entryPoints)))->toStartWith($linkOrScriptTag);
        cleanViteManifest($buildDir);
    })->with('prod-entrypoints-integrity');


    it('should return valid url with assets url', function () {
        $buildDir = substr(md5(rand()), 0, 10);
        config(ViteJs::class)->assetURL = 'https://cdn.app.com/';

        makeViteManifest([
            'resources/images/profile.png' => [
                'src' => 'resources/images/profile.png',
                'file' => 'assets/profile.versioned.png',
            ],
        ], $buildDir);

        expect(Utils::liveAssetUrl('assets/profile.versioned.png'))
            ->toBe("https://cdn.app.com/{$buildDir}/assets/profile.versioned.png");

        cleanViteManifest($buildDir);
    });


    it('should return valid url with base url', function () {
        $buildDir = substr(md5(rand()), 0, 10);
        config(App::class)->baseURL = 'https://cdn.example.com/';

        makeViteManifest([
            'resources/images/profile.png' => [
                'src' => 'resources/images/profile.png',
                'file' => 'assets/profile.versioned.png',
            ],
        ], $buildDir);

        expect(Utils::liveAssetUrl('assets/profile.versioned.png'))
            ->toBe("https://cdn.example.com/{$buildDir}/assets/profile.versioned.png");

        cleanViteManifest($buildDir);
    });


    it('should return valid url with default config', function () {
        $buildDir = substr(md5(rand()), 0, 10);

        makeViteManifest([
            'resources/images/profile.png' => [
                'src' => 'resources/images/profile.png',
                'file' => 'assets/profile.versioned.png',
            ],
        ], $buildDir);

        expect(Utils::liveAssetUrl('assets/profile.versioned.png'))
            ->toBe("https://example.com/{$buildDir}/assets/profile.versioned.png");

        cleanViteManifest($buildDir);
    });
});
