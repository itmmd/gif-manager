<?php

namespace Modules\Ai\Providers;

use Modules\Ai\Services\EmbeddingService;
use Modules\Ai\Services\MediaIntelligenceService;
use Modules\Ai\Services\SemanticSearchService;
use Modules\Ai\Services\VisionAnalysisService;
use Modules\Core\Contracts\MediaIntelligenceInterface;
use Nwidart\Modules\Support\ModuleServiceProvider;

class AiServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'Ai';

    protected string $nameLower = 'ai';

    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

    public function register(): void
    {
        parent::register();

        $this->app->singleton(VisionAnalysisService::class);
        $this->app->singleton(EmbeddingService::class);
        $this->app->singleton(SemanticSearchService::class, fn ($app) =>
            new SemanticSearchService($app->make(EmbeddingService::class))
        );

        $this->app->bind(
            MediaIntelligenceInterface::class,
            fn ($app) => new MediaIntelligenceService(
                $app->make(VisionAnalysisService::class),
                $app->make(EmbeddingService::class),
            )
        );
    }
}
