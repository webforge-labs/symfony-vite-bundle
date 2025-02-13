<?php

declare(strict_types=1);

namespace Vite\Dto;

use Webmozart\Assert\Assert;

final class ViteRenderedLinkTagDto extends ViteRenderedTagDto
{
    public function __construct(
        public string $href,
        public string $rel = 'stylesheet',
    ) {
        Assert::stringNotEmpty($this->href);
        Assert::stringNotEmpty($this->rel);
    }

    public function html(): string
    {
        return sprintf('<link rel="%s" href="%s">', $this->rel, htmlentities($this->href));
    }
}
