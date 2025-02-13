<?php

declare(strict_types=1);

namespace Vite\Dto;

abstract class ViteRenderedTagDto
{
    abstract public function html(): string;
}
