<?php

namespace Ghermans\MicrosoftGraphMail\Providers;

use Illuminate\Support\ServiceProvider;

class MicrosoftGraphMailServiceProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/msgraph.php' => config_path('msgraph-mail.php'),
        ], 'config');
    }
}