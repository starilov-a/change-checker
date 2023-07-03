<?php

namespace App\Providers;

use App\Actions\AddSiteAction;
use App\Actions\ScanSiteAction;
use App\Contracts\AddSiteContract;
use App\Contracts\ScanSiteContract;
use Illuminate\Support\ServiceProvider;

class ActionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AddSiteContract::class, AddSiteAction::class);
        $this->app->bind(ScanSiteContract::class, ScanSiteAction::class);
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
