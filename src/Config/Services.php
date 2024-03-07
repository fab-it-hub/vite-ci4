<?php

/**
 * This file is part of Codeigniter 4 with Vite.js.
 *
 * (c) 2024 Fab IT Hub <hello@fabithub.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Fabithub\ViteJs\Config;

use CodeIgniter\Config\BaseService;
use Fabithub\ViteJs\Core\Tags;

class Services extends BaseService
{
    public static function vite(bool $getShared = true): ?Tags
    {
        if ($getShared) {
            return static::getSharedInstance('vite');
        }

        return new Tags();
    }
}
