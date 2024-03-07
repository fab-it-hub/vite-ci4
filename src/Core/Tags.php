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

class Tags
{
    public function reactRefresh(): ?string
    {
        if (!Utils::isViteDevServerRunning()) {
            return null;
        }

        $attributes = AssetTagGenerator::formatAttributes(['nonce' => Utils::cspNonce()]);
        $devUrl     = Utils::devAssetUrl('@react-refresh');

        return sprintf(
            <<<'HTML'
                <script type="module" %s>
                    import RefreshRuntime from '%s'
                    RefreshRuntime.injectIntoGlobalHook(window)
                    window.$RefreshReg$ = () => {}
                    window.$RefreshSig$ = () => (type) => type
                    window.__vite_plugin_react_preamble_installed__ = true
                </script>
                HTML,
            $attributes,
            $devUrl
        );
    }

    public function init(string ...$entryPoints): array
    {
        if (Utils::isViteDevServerRunning()) {
            $entryPoints = array_merge(['@vite/client'], $entryPoints);

            return array_map(static fn ($entrypoint) => AssetTagGenerator::generateTag($entrypoint, Utils::devAssetUrl($entrypoint)), $entryPoints);
        }

        $manifest = new Manifest();

        return $manifest->retrieveTags($entryPoints);
    }
}
