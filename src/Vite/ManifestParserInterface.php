<?php

declare(strict_types=1);

namespace Vite;

interface ManifestParserInterface
{
    public function getParsedManifest(): \Vite\Dto\ViteManifestDto;
}
