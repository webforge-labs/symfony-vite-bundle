<?php

declare(strict_types=1);


use PHPUnit\Framework\TestCase;
use Vite\Dto\ViteManifestCssDto;
use Vite\Dto\ViteManifestDto;
use Vite\Dto\ViteManifestRecordDto;
use Vite\Dto\ViteRenderedLinkTagDto;
use Vite\Dto\ViteRenderedScriptTagDto;
use Vite\Exception\OnlyViteDevServerIsRunningException;
use Vite\ManifestParserInterface;
use Vite\Service;

/**
 * @covers \Vite\Service
 */
class ServiceTest extends TestCase
{
    private Service $viteService;

    private ManifestParserInterface&\PHPUnit\Framework\MockObject\MockObject $parser;

    protected function setUp(): void
    {
        $this->parser = $this->createMock(ManifestParserInterface::class);

        $this->viteService = new Service(
            $this->parser,
            'https://web.local.dev:3030',
            ''
        );
    }

    public function testRendersAllJavascriptDependenciesAsTagWhenInProd(): void
    {
        $this->parser->method('getParsedManifest')->willReturn(
            new ViteManifestDto([
                    "assets/app.js" => new ViteManifestRecordDto(
                        file: "app.2baa1153.js",
                        src: "assets/app.js",
                        isEntry: true,
                        css: [
                            new ViteManifestCssDto(
                                "app.076763a6.css"
                            )
                        ]
                    )
                ]
            )
        );

        $tags = $this->viteService->generateTags('js', 'app');

        self::assertCount(1, $tags);
        $app = $tags[0];
        self::assertInstanceOf(ViteRenderedScriptTagDto::class, $app);
        self::assertSame('/build/app.2baa1153.js', $app->src);
        self::assertSame('module', $app->type);

        $tags = $this->viteService->generateTags('css', 'app');

        self::assertCount(1, $tags);
        $app = $tags[0];
        self::assertInstanceOf(ViteRenderedLinkTagDto::class, $app);
        self::assertSame('/build/app.076763a6.css', $app->href);
        self::assertSame('stylesheet', $app->rel);
    }

    public function testUsesTheCdnUrlInfront(): void
    {
        $cdnViteService = new Service(
            $this->parser,
            'https://web.local.dev:3030',
            'https://cdnb.yaymemories.com'
        );

        $this->parser->method('getParsedManifest')->willReturn(
            new ViteManifestDto([
                    "assets/app.js" => new ViteManifestRecordDto(
                        file: "app.2baa1153.js",
                        src: "assets/app.js",
                        isEntry: true,
                        css: [
                            new ViteManifestCssDto(
                                "app.076763a6.css"
                            )
                        ]
                    )
                ]
            )
        );

        $tags = $cdnViteService->generateTags('js', 'app');

        self::assertCount(1, $tags);
        $app = $tags[0];
        self::assertInstanceOf(ViteRenderedScriptTagDto::class, $app);
        self::assertSame('https://cdnb.yaymemories.com/build/app.2baa1153.js', $app->src);
        self::assertSame('module', $app->type);

        $tags = $cdnViteService->generateTags('css', 'app');

        self::assertCount(1, $tags);
        $app = $tags[0];
        self::assertInstanceOf(ViteRenderedLinkTagDto::class, $app);
        self::assertSame('https://cdnb.yaymemories.com/build/app.076763a6.css', $app->href);
        self::assertSame('stylesheet', $app->rel);
    }

    public function testRendersJustTheViteServeTagWhenNotInProd(): void
    {
        $this->parser->method('getParsedManifest')->willThrowException(
            new OnlyViteDevServerIsRunningException('no manifest is written, when vite serve is running')
        );

        $tags = $this->viteService->generateTags('js', 'app');

        self::assertCount(2, $tags);
        $server = $tags[0];
        self::assertInstanceOf(ViteRenderedScriptTagDto::class, $server);
        self::assertSame(
            'https://web.local.dev:3030/build/@vite/client',
            $server->src
        );
        self::assertSame(
            'module',
            $server->type
        );

        $app = $tags[1];
        self::assertInstanceOf(ViteRenderedScriptTagDto::class, $app);
        self::assertSame(
            'https://web.local.dev:3030/build/assets/app.js',
            $app->src
        );
        self::assertSame(
            'module',
            $app->type
        );

        self::assertCount(0, $this->viteService->generateTags('css', 'app'));
    }
}
