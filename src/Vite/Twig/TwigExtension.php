<?php
declare(strict_types=1);

namespace Vite\Twig;

use Vite\Service;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigExtension extends AbstractExtension
{
    public function __construct(
        private readonly Service $viteService,
    )
    {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'vite_entry_link_tags', [$this, 'entryLinkTags'], [
                    'is_safe' => ['html' => true],
                    'needs_environment' => true
                ]
            ),
            new TwigFunction(
                'vite_entry_script_tags', [$this, 'entryScriptTags'], [
                    'is_safe' => ['html' => true],
                    'needs_environment' => true
                ]
            ),
        ];
    }

    public function entryLinkTags(\Twig\Environment $twig, string $entryPointName): string
    {
        return $this->viteService->renderTags('css', $entryPointName);
    }

    public function entryScriptTags(\Twig\Environment $twig, string $entryPointName): string
    {
        return $this->viteService->renderTags('js', $entryPointName);
    }
}
