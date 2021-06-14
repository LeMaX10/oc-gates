<?php
declare(strict_types=1);

namespace LeMaX10\Gates;

use Auth;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Support\Facades\Gate;
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
            'name' => 'lemax10.gates::lang.plugin.name',
            'description' => 'lemax10.gates::lang.plugin.description',
            'author' => 'Vladimir Pyankov (aka lemax10)',
        ];
    }

    public function register()
    {
        $this->registerRequestAndGatesResolvers();
    }

    public function boot()
    {
        $this->registerPolicies();
    }

    private function registerRequestAndGatesResolvers(): void
    {
        $this->app['request']->setUserResolver(function () {
            return Auth::user();
        });

        $this->app->singleton(GateContract::class, function ($app) {
            return new \Illuminate\Auth\Access\Gate(
                $app,
                $this->app['request']->getUserResolver()
            );
        });
    }

    /**
     * Register gate policies.
     */
    private function registerPolicies(): void
    {
        foreach ($this->policies as $modelNamespace => $policyNamespace) {
            Gate::policy($modelNamespace, $policyNamespace);
        }
    }
}
