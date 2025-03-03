<?php

namespace Saeedvir\LaravelAdvancedAttributes;

use Illuminate\Support\ServiceProvider;

class LaravelAdvancedAttributesServiceProvider extends ServiceProvider
{
    /**
     * Get config path.
     */
    private string $config_path = __DIR__.'/../config/laravel-advanced-attributes.php';

    /**
     * Get config name.
     */
    private string $config_name = 'laravel-advanced-attributes';

    /**
     * Get migration path.
     */
    private string $migration_path = __DIR__.'/../migrations/';

    /**
     * Register files.
     *
     * @return void
     */
    public function register()
    {
        $this->loadMigrationsFrom($this->migration_path);
        $this->mergeConfigFrom($this->config_path, $this->config_name);
        $this->publishPackageFiles();
    }

    /**
     * Load package files.
     *
     * @return void
     */
    private function publishPackageFiles()
    {
        // Publish config
        $this->publishes([
            $this->config_path => config_path("$this->config_name.php"),
        ], 'laravel-advanced-attributes-config');

        // Publish migrations
        $this->publishes([
            $this->migration_path => database_path('migrations'),
        ], 'laravel-advanced-attributes-migrations');
    }
}
