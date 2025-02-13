<?php

declare(strict_types=1);

namespace Vite\Dto;

use Webmozart\Assert\Assert;

final class ViteRenderedScriptTagDto extends ViteRenderedTagDto
{
    public function __construct(
        public string $src,
        public string $type,
    ) {
        Assert::stringNotEmpty($this->type);
        Assert::stringNotEmpty($this->src);
    }

    public function html(): string
    {
        return sprintf('<script src="%s" type="%s"></script>', htmlentities($this->src), $this->type);
    }
}
