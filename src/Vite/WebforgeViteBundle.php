<?php

declare(strict_types=1);

namespace Vite;

use Vite\DependencyInjection\WebforgeViteExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class WebforgeViteBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new WebforgeViteExtension();
    }
}
