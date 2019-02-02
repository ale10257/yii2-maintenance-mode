<?php
namespace ale10257\statusApplication;

use yii\base\BootstrapInterface;

class CheckStatus implements BootstrapInterface
{
    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param \WebApplication $app the application currently running
     */
    public function bootstrap($app)
    {
        $appStatusMode = $app->appStatusMode;
        $appStatusMode->check();
    }
}