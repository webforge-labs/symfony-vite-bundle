<?php

declare(strict_types=1);

namespace Vite;

use Vite\Dto\ViteRenderedLinkTagDto;
use Vite\Dto\ViteRenderedScriptTagDto;
use Vite\Dto\ViteRenderedTagDto;
use Vite\Exception\OnlyViteDevServerIsRunningException;

final class Service
{
    private string $base;

    public function __construct(
        private ManifestParserInterface $manifestParser,
        private string $devServerOrigin,
        private string $cdnUrl
    ) {
        $this->base = '/build/';
        $this->cdnUrl = rtrim($this->cdnUrl, '/');
    }

    /**
     * @return array<int, ViteRenderedTagDto>
     */
    public function generateTags(string $type, string $entryPointName): array
    {
        $js = [];
        $css = [];

        try {
            $manifest = $this->manifestParser->getParsedManifest();
            $url = $this->cdnUrl . $this->base;

            foreach ($manifest->records as $record) {
                $js[] = new ViteRenderedScriptTagDto(
                    $url . $record->file,
                    'module'
                );

                foreach ($record->css as $cssRecord) {
                    $css[] = new ViteRenderedLinkTagDto(
                        $url . $cssRecord->file,
                    );
                }
            }
        } catch (OnlyViteDevServerIsRunningException $e) {
            $js[] = new ViteRenderedScriptTagDto(
                $this->devServerOrigin . $this->base . '@vite/client',
                'module'
            );

            $js[] = new ViteRenderedScriptTagDto(
                $this->devServerOrigin . $this->base . 'assets/' . $entryPointName . '.js',
                'module'
            );
        }

        if ($type === 'css') {
            return $css;
        } elseif ($type === 'js') {
            return $js;
        } else {
            throw new \InvalidArgumentException('type can only be js|css');
        }
    }

    public function renderTags(string $type, string $entryPointName): string
    {
        return implode(
            "\n        ",
            array_map(
                function (ViteRenderedTagDto $tag) {
                    return $tag->html();
                },
                $this->generateTags($type, $entryPointName)
            )
        );
    }
}
