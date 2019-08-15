<?php
namespace Cankod\Theme;


use Cankod\Theme\Commands\ThemeGeneratorCommand;
use Illuminate\Support\Facades\View;

class ServiceProvider extends \Illuminate\Support\ServiceProvider {


    public function boot()
    {
        $this->publishes([__DIR__ . '/Config/theme.php'   => config_path('theme.php')], 'theme');

    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ .'/Config/theme.php', 'laravel-theme');

        $this->commands(ThemeGeneratorCommand::class);

        $this->app->bind('theme', function()
        {
            return new Theme();
        });

        View::addLocation(resource_path(config('theme.theme_path').'/'.config('theme.current_theme').'/views'));

    }
}
