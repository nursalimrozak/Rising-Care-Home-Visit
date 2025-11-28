<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ActivityLog;
use Carbon\Carbon;

class PruneActivityLogs extends Command
{
    protected $signature = 'activity-log:prune';
    protected $description = 'Delete activity logs older than 7 days';

    public function handle()
    {
        $count = ActivityLog::where('created_at', '<', Carbon::now()->subDays(7))->delete();
        $this->info("Deleted {$count} old activity logs.");
    }
}
