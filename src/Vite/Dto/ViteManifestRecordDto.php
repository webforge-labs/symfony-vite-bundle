<?php

declare(strict_types=1);

namespace Vite\Dto;

class ViteManifestRecordDto
{
    /**
     * @param array<int, ViteManifestCssDto> $css
     */
    public function __construct(
        public string $file,
        public string $src,
        public bool $isEntry,
        public array $css,
        public ?string $name = null,
    ) {}
}
