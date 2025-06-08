<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CleanupApplicationFiles extends Command
{
    protected $signature = 'applications:cleanup {--days=30 : Delete files older than X days}';
    protected $description = 'Clean up old application files from storage';

    public function handle()
    {
        $days = $this->option('days');
        $cutoffDate = Carbon::now()->subDays($days);
        
        $this->info("Cleaning up application files older than {$days} days...");
        
        $files = Storage::disk('public')->files('bewerbungen');
        $deletedCount = 0;
        
        foreach ($files as $file) {
            $lastModified = Carbon::createFromTimestamp(Storage::disk('public')->lastModified($file));
            
            if ($lastModified->lt($cutoffDate)) {
                Storage::disk('public')->delete($file);
                $deletedCount++;
                $this->line("Deleted: {$file}");
            }
        }
        
        $this->info("Cleanup completed. Deleted {$deletedCount} files.");
        
        return 0;
    }
} 