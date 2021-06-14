<?php

declare(strict_types=1);

namespace LeMaX10\Gates;

use Auth;
use BackendAuth;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use October\Rain\Auth\Models\User;
use System\Classes\PluginBase;

/**
 * Gates Plugin Information File.
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name' => 'Gates Policy',
            'description' => 'Support Laravel Gates Policies',
            'author' => 'Vladimir Pyankov (aka lemax10)',
        ];
    }

    public function register()
    {
        $this->registerRequestAndGatesResolvers();
    }

    private function registerRequestAndGatesResolvers(): void
    {
        $this->app['request']->setUserResolver(static function (): ?User {
            if (\App::runningInBackend()) {
                return BackendAuth::user();
            }

            return Auth::user();
        });

        $this->app->singleton(GateContract::class, function ($app) {
            return new \Illuminate\Auth\Access\Gate(
                $app,
                $this->app['request']->getUserResolver()
            );
        });
    }
}
