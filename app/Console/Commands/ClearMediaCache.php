<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ClearMediaCache extends Command
{
    protected $signature = 'blogger:clear-media';
    protected $description = 'Purge all cached proxied media files from local storage';

    public function handle(): int
    {
        $mediaDir = storage_path('app/public/blogger_media');

        if (File::exists($mediaDir)) {
            File::cleanDirectory($mediaDir);
            $this->info("Successfully cleaned media cache directory: {$mediaDir}");
        } else {
            $this->info("Media cache directory does not exist yet.");
        }

        return self::SUCCESS;
    }
}
