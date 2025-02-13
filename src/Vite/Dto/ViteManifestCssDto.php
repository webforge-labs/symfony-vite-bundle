<?php

declare(strict_types=1);

namespace Vite\Dto;

class ViteManifestCssDto
{
    public function __construct(
        public string $file
    ) {}
}
