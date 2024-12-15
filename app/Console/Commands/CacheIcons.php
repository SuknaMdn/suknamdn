<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CacheIcons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'icons:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache the icons';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Add logic to cache the icons here
        // For example, you might copy files to a specific directory
        $iconSourcePath = resource_path('icons');
        $iconCachePath = storage_path('app/icons/cache');

        if (!is_dir($iconCachePath)) {
            mkdir($iconCachePath, 0755, true);
        }

        foreach (glob("$iconSourcePath/*.*") as $file) {
            copy($file, $iconCachePath . '/' . basename($file));
        }

        $this->info('Icons cached successfully.');

        return 0;
    }
}
