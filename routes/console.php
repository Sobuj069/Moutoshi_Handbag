<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('app:sync-storage', function () {
    $this->info('Starting storage synchronization for cPanel/Hosting...');
    $source = storage_path('app/public');
    $destUploads = public_path('uploads');
    $destStorage = public_path('storage');

    if (!is_dir($source)) {
        $this->warn("Source directory {$source} does not exist.");
        return 1;
    }

    try {
        Artisan::call('storage:link');
        $this->line(trim(Artisan::output()));
    } catch (\Throwable $e) {
        $this->warn("Symlink attempt skipped: " . $e->getMessage());
    }

    $copiedCount = 0;
    $copyRecursive = function ($src, $dst) use (&$copyRecursive, &$copiedCount) {
        $dir = @opendir($src);
        if (!$dir) return;
        @mkdir($dst, 0755, true);
        while (false !== ($file = readdir($dir))) {
            if ($file != '.' && $file != '..') {
                if (is_dir($src . '/' . $file)) {
                    $copyRecursive($src . '/' . $file, $dst . '/' . $file);
                } else {
                    if (@copy($src . '/' . $file, $dst . '/' . $file)) {
                        $copiedCount++;
                    }
                }
            }
        }
        closedir($dir);
    };

    $copyRecursive($source, $destUploads);
    $this->info("Copied storage files to public/uploads.");

    if (!is_link($destStorage)) {
        $copyRecursive($source, $destStorage);
        $this->info("Copied storage files to public/storage.");
    }

    $this->info("✓ Storage synchronization completed! Total files processed: {$copiedCount}");
    return 0;
})->purpose('Synchronize storage files directly to public uploads/storage directories for cPanel hosting compatibility');

