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

    /**
     * Register all Ai module bindings.
     *
     * MediaIntelligenceInterface is bound here so the Gif module can resolve it
     * via the container without knowing about this module's internals.
     *
     * If the Ai module is disabled, the binding simply doesn't exist — callers
     * guard with app()->bound(MediaIntelligenceInterface::class).
     */
    public function register(): void
    {
        parent::register();

        // Focused services (registered as singletons for efficiency)
        $this->app->singleton(VisionAnalysisService::class);
        $this->app->singleton(EmbeddingService::class);
        $this->app->singleton(SemanticSearchService::class, function ($app) {
            return new SemanticSearchService($app->make(EmbeddingService::class));
        });

        // The cross-module contract binding — core of the architecture
        $this->app->bind(
            MediaIntelligenceInterface::class,
            fn ($app) => new MediaIntelligenceService(
                $app->make(VisionAnalysisService::class),
                $app->make(EmbeddingService::class),
            )
        );
    }
}
