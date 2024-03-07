<?php

/**
 * This file is part of Codeigniter 4 with Vite.js.
 *
 * (c) 2024 Fab IT Hub <hello@fabithub.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Fabithub\ViteJs\Core;

use Fabithub\ViteJs\Utils\Utils;

class AssetTagGenerator
{
    /**
     *
     * @param  array<string, string>  $chunk
     * @param  array<string, string>  $manifest
     *
     */
    public static function generateTag(string $src, string $url, array $chunk = [], array $manifest = []): string
    {
        $attributes = [];

        $integrity = Utils::integrity();
        if ($integrity !== false) {
            $attributes['integrity'] = $chunk[$integrity] ?? false;
        }

        return match (static::isStylesheet($url)) {
            true    => static::generateStyleTag($url, $attributes),
            default => static::generateScriptTag($url, $attributes),
        };
    }

    public static function isStylesheet(string $entrypoint): bool
    {
        $styleExtensions = ['css', 'less', 'sass', 'scss', 'styl', 'stylus', 'pcss', 'postcss'];

        return in_array(pathinfo($entrypoint, PATHINFO_EXTENSION), $styleExtensions, true);
    }

    protected static function generateStyleTag(string $url, array $additionalAttributes = []): string
    {
        $attributes = ['rel' => 'stylesheet', 'href' => $url, 'nonce' => Utils::cspNonce()];

        return '<link ' . static::formatAttributes(array_merge($attributes, $additionalAttributes)) . ' />';
    }

    protected static function generateScriptTag(string $url, array $additionalAttributes = []): string
    {
        $attributes = ['type' => 'module', 'src' => $url, 'nonce' => Utils::cspNonce()];

        return '<script ' . static::formatAttributes(array_merge($attributes, $additionalAttributes)) . '></script>';
    }

    public static function formatAttributes(array $attributes): string
    {
        $filteredAttributes = array_filter($attributes, static fn ($value) => !in_array($value, [false, null], true));

        return implode(' ', array_map(static fn ($key, $value) => $key . ($value === true ? '' : '="' . $value . '"'), array_keys($filteredAttributes), $filteredAttributes));
    }
}
