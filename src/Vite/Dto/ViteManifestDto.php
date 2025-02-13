<?php

declare(strict_types=1);

namespace Vite\Dto;

class ViteManifestDto
{
    /**
     * @param array<string, ViteManifestRecordDto> $records
     */
    public function __construct(
        public array $records
    ) {}
}
