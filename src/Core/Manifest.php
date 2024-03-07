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

use Fabithub\ViteJs\Config\ViteJs;
use Fabithub\ViteJs\Exceptions\FileNotFound;
use Fabithub\ViteJs\Exceptions\ManifestNotFound;
use Fabithub\ViteJs\Utils\Utils;

class Manifest
{
    /**
     * @var list<array|string>
     */
    protected array $manifests = [];

    protected array $tags = [];
    protected array $preloads = [];

    protected function manifestFile(): string
    {
        return config(ViteJs::class)->manifestFileName;
    }

    public function getPath(): string
    {
        return Utils::publicPath(Utils::buildDirectory($this->manifestFile(), DIRECTORY_SEPARATOR));
    }

    protected function manifest(): array
    {
        $path = $this->getPath();

        if (!isset($this->manifests[$path])) {
            if (!is_file($path)) {
                throw new ManifestNotFound("Vite manifest not found at: {$path}");
            }

            $this->manifests[$path] = json_decode(file_get_contents($path), true);
        }

        return $this->manifests[$path];
    }

    protected function chunk(array $manifest, string $file): array
    {
        if (!isset($manifest[$file])) {
            throw new FileNotFound("Unable to locate file in Vite manifest: {$file}.");
        }

        return $manifest[$file];
    }

    public function retrieveTags(array $entrypoints): array
    {
        $manifest = $this->manifest();

        foreach ($entrypoints as $entrypoint) {
            $chunk = $this->chunk($manifest, $entrypoint);
            $this->processChunk($chunk, $manifest);

            foreach ($chunk['imports'] ?? [] as $import) {
                $this->processImport($import, $manifest);
            }
        }

        $preloads = array_map(static fn ($src) => AssetTagGenerator::generateTag(...$src), $this->preloads);

        return $this->sort(array_unique(array_merge($preloads, $this->tags)));
    }

    protected function processChunk(array $chunk, array $manifest): void
    {
        $this->preloads[] = [$chunk['src'], Utils::liveAssetUrl($chunk['file']), $chunk, $manifest];
        $this->tags[] = AssetTagGenerator::generateTag($chunk['src'], Utils::liveAssetUrl($chunk['file']), $chunk, $manifest);

        $this->processCss($chunk['css'] ?? [], $manifest);
    }

    protected function processImport(string $import, array $manifest): void
    {
        $this->preloads[] = [$import, Utils::liveAssetUrl($manifest[$import]['file']), $manifest[$import], $manifest];
        $this->tags[] = AssetTagGenerator::generateTag($import, Utils::liveAssetUrl($manifest[$import]['file']), $manifest[$import], $manifest);

        $this->processCss($manifest[$import]['css'] ?? [], $manifest);
    }

    protected function processCss(array $cssEntries, array $manifest): void
    {
        foreach ($cssEntries as $css) {
            $partialManifest = array_filter($manifest, static fn ($value) => $value['file'] === $css, ARRAY_FILTER_USE_BOTH);

            $fileSrc = array_keys($partialManifest)[0];
            $fileUrl = array_values($partialManifest)[0];

            $attributes = [$fileSrc, Utils::liveAssetUrl($css), $fileUrl, $manifest];

            $this->preloads[] = $attributes;
            $this->tags[] = AssetTagGenerator::generateTag(...$attributes);
        }
    }

    protected function sort(array $array): array
    {
        usort($array, static function ($a, $b) {
            $aIsLink = str_starts_with($a, '<link ');
            $bIsLink = str_starts_with($b, '<link ');

            if ($aIsLink !== $bIsLink) {
                return $aIsLink ? -1 : 1;
            }

            return 0;
        });

        return $array;
    }
}
