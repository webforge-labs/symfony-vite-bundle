<?php

declare(strict_types=1);

namespace Vite;

use CuyZ\Valinor\Mapper\MappingError;
use Vite\Dto\ViteManifestDto;
use Vite\Exception\OnlyViteDevServerIsRunningException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ManifestParser implements ManifestParserInterface
{
    public function __construct(
        private HttpClientInterface $internalNginx
    ) {
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getParsedManifest(): ViteManifestDto
    {
        $response = $this->internalNginx->request('GET', '/build/.vite/manifest.json', [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'timeout' => 3
        ]);

        if ($response->getStatusCode() === 200) {
            return $this->parse($response->getContent());
        } elseif ($response->getStatusCode() === 404) {
            throw new OnlyViteDevServerIsRunningException('manifest.json returned 404, so only vite serve is running');
        } else {
            throw new \RuntimeException('Cannot talk to nginx and get the manifest.json from vite: ' . $response->getStatusCode());
        }
    }

    private function parse(string $json): ViteManifestDto
    {
        $manifest = (new \CuyZ\Valinor\MapperBuilder())
            ->mapper()
            ->map(
                ViteManifestDto::class,
                \CuyZ\Valinor\Mapper\Source\Source::json($json)
            );

        return $manifest;
    }
}
