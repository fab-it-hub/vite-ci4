<?php

/**
 * This file is part of Codeigniter 4 with Vite.js.
 *
 * (c) 2024 Fab IT Hub <hello@fabithub.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Fabithub\ViteJs;

use Fabithub\ViteJs\Config\Services;

/**
 * @method static array   init(array ...$entryPoints)
 * @method static ?string reactRefresh()
 */
class Vite
{
    public static function __callStatic(string $method, array $arguments): array|string|null
    {
        return Services::vite()->{$method}(...$arguments);
    }
}
