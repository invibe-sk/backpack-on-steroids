<?php

namespace Invibe\BackpackOnSteroids\Console\Commands;

use Illuminate\Console\Command;

/**
 * Class BackpackSteroids
 * @author Adam Ondrejkovic
 * @package Invibe\BackpackOnSteroids\Console\Commands
 */
class BackpackSteroids extends Command
{
    /** @var string $signature */
    protected $signature = 'backpack:steroids';

    /** @var string $description */
    protected $description = 'Inject steroids to backpack';

    /**
     * @author Adam Ondrejkovic
     */
    public function handle()
    {
        $this->comment('Injecting steroids to backpack');
        $this->comment('_______________________');

        $this->publishConfiguration();
        $this->publishFileManagerAssets();
        $this->publishMigrations();
        $this->publishViews();
        $this->publishAssets();

        $this->comment("_______________________");
        $this->comment('Backpack is pumped now!');
    }

    /**
     * @author Adam Ondrejkovic
     */
    private function publishConfiguration()
    {
        $this->line('Publishing configuration...');

        $params = [
            '--provider' => "Invibe\BackpackOnSteroids\Providers\BackpackOnSteroidsServiceProviders",
            '--tag' => "config"
        ];

        $this->call('vendor:publish', $params);

        $this->comment('Published configuration');
    }

    /**
     * @author Adam Ondrejkovic
     */
    private function publishViews()
    {
        $this->line('Publishing views...');

        $params = [
            '--provider' => "Invibe\BackpackOnSteroids\Providers\BackpackOnSteroidsServiceProviders",
            '--tag' => "views"
        ];

        $this->call('vendor:publish', $params);

        $this->comment('Published configuration');
    }

    /**
     * @author Adam Ondrejkovic
     */
    private function publishAssets()
    {
        $this->line('Publishing assets...');

        $params = [
            '--provider' => "Invibe\BackpackOnSteroids\Providers\BackpackOnSteroidsServiceProviders",
            '--tag' => "assets"
        ];

        $this->call('vendor:publish', $params);

        $this->comment('Published assets');
    }

    /**
     * @author Adam Ondrejkovic
     */
    private function publishMigrations()
    {
        $this->line('Publishing migrations...');

        $params = [
            '--provider' => "Invibe\BackpackOnSteroids\Providers\BackpackOnSteroidsServiceProviders",
            '--tag' => "migrations"
        ];

        $this->call('vendor:publish', $params);

        $params = [
            '--provider' => "Spatie\MediaLibrary\MediaLibraryServiceProvider",
            '--tag' => "migrations"
        ];

        $this->call('vendor:publish', $params);

        $this->comment('Published migrations');
    }

    /**
     * @author Adam Ondrejkovic
     */
    public function publishFileManagerAssets()
    {
        $this->line('Publishing file manager assets...');

        $params = [
            '--tag' => 'fm-assets'
        ];

        $this->call('vendor:publish', $params);

        $this->comment('Published file manager assets');
    }
}
