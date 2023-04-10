<?php

namespace App\Providers;

use App\Actions\AddSiteAction;
use App\Contracts\AddSiteContract;
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
