<?php

/**
 * This file is part of Codeigniter 4 with Vite.js.
 *
 * (c) 2024 Fab IT Hub <hello@fabithub.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Fabithub\ViteJs\Utils;

use Config\App;
use Fabithub\ViteJs\Config\ViteJs;

class Utils
{
    public static function cspNonce(): ?string
    {
        return config(App::class)->CSPEnabled ? csrf_hash() : null;
    }

    public static function integrity(): string|false
    {
        return config(ViteJs::class)->integrity;
    }

    public static function hotFile(): string
    {
        return config(ViteJs::class)->hotFile ?? static::publicPath('hot');
    }

    public static function isViteDevServerRunning(): bool
    {
        return is_file(static::hotFile());
    }

    public static function devAssetUrl(string $filename): string
    {
        return static::slashes(file_get_contents(static::hotFile())) . $filename;
    }

    public static function liveAssetUrl(string $filename): string
    {
        return static::slashes(config(ViteJs::class)->assetURL ?? config(App::class)->baseURL) . static::buildDirectory($filename);
    }

    public static function slashes(string $path, string $tailing = '/'): string
    {
        return rtrim($path, "\n\r\t\v\0\\/") . $tailing;
    }

    public static function publicPath(string $path): string
    {
        $publicPath = config(ViteJs::class)->publicPath;

        if (!empty($publicPath)) {
            $publicPath = static::slashes($publicPath, DIRECTORY_SEPARATOR);
        }

        return $publicPath . $path;
    }

    public static function buildDirectory(string $path, string $tailing = '/'): string
    {
        $buildDirectory = config(ViteJs::class)->buildDirectory;

        if (!empty($buildDirectory)) {
            $buildDirectory = static::slashes($buildDirectory, $tailing);
        }

        return $buildDirectory . $path;
    }
}
