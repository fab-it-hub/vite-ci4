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

use CodeIgniter\Config\BaseConfig;

class ViteJs extends BaseConfig
{
    public ?string $hotFile = null;
    public ?string $assetURL = null;
    public string $publicPath = FCPATH;
    public string $buildDirectory = 'build';
    public string|false $integrity = 'integrity';
    public string $manifestFileName = 'manifest.json';
}
