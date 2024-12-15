<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearIconCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'icons:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the icon cache';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Add logic to clear the icon cache here
        // For example, you might delete files from a specific directory
        $iconCachePath = storage_path('app/icons/cache');

        if (is_dir($iconCachePath)) {
            array_map('unlink', glob("$iconCachePath/*.*"));
            $this->info('Icon cache cleared successfully.');
        } else {
            $this->info('No icon cache found.');
        }

        return 0;
    }
}
