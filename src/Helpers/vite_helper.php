<?php

/**
 * This file is part of Codeigniter 4 with Vite.js.
 *
 * (c) 2024 Fab IT Hub <hello@fabithub.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

use Fabithub\ViteJs\Vite;

if (!function_exists('react_refresh')) {
    function react_refresh(): ?string
    {
        return Vite::reactRefresh();
    }
}

if (!function_exists('vite_styles')) {
    function vite_styles(string ...$entrypoints): string
    {
        $styles = Vite::init(...$entrypoints);

        return implode('', array_filter($styles, static fn ($value) => str_starts_with($value, '<link')));
    }
}

if (!function_exists('vite_scripts')) {
    function vite_scripts(string ...$entrypoints): string
    {
        $scripts = Vite::init(...$entrypoints);

        return implode('', array_filter($scripts, static fn ($value) => !str_starts_with($value, '<link')));
    }
}
