<?php

namespace Tests\Features;

use Config\App;
use Fabithub\ViteJs\Vite;

uses()->group('dev-server-hmr');

describe('When development server is running', function () {
    beforeEach(function () {
        makeViteHotFile();
    });

    it('should return correct tags', function (array $entryPoints, string $linkOrScriptTag) {
        expect(implode('', Vite::init(...$entryPoints)))->toStartWith($linkOrScriptTag);
    })->with('dev-entrypoints');

    it('should return correct tags with csp enabled', function (array $entryPoints, string $linkOrScriptTag) {
        config(App::class)->CSPEnabled = true;

        expect(implode('', Vite::init(...$entryPoints)))->toStartWith($linkOrScriptTag);
    })->with('dev-entrypoints-csp');

    it('should return react refresh script without nonce attribute', function () {
        expect(Vite::reactRefresh())->not->toContain('nonce');
    });

    it('should return react refresh script with nonce attribute', function () {
        $nonce = csrf_hash();
        config(App::class)->CSPEnabled = true;

        expect(Vite::reactRefresh())->toContain("nonce=\"{$nonce}\"");
    });
});
