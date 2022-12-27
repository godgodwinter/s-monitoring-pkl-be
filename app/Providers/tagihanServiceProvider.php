<?php

namespace App\Providers;

use App\Services\Impl\TagihanServiceImpl;
use App\Services\tagihanService;
use Illuminate\Support\ServiceProvider;

class tagihanServiceProvider extends ServiceProvider
{
    public array $singletons = [
        tagihanService::class => TagihanServiceImpl::class,
    ];
    public function provides(): array
    {
        return [
            tagihanService::class,
        ];
    }
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
