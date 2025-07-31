<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; 
use Carbon\Carbon;

trait Loggable
{
    // Boot the trait and listen to model events
    public static function bootLoggable()
    {
        static::created(function ($model) {
            $model->logActivity('created');
        });

        static::updated(function ($model) {
            $model->logActivity('updated');
        });

        static::deleted(function ($model) {
            $model->logActivity('deleted');
        });
    }

    // Function to store logs
    public function logActivity($action)
    {
        $this->deleteOldLogs();
        
        DB::table('logs')->insert([
            'model_type' => get_class($this),
            'user_id' => Auth::id(),
            'model_id' => $this->id,
            'action' => $action,
            'details' => json_encode($this->getChanges()),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function deleteOldLogs()
    {
        // Get retention period from the config file or default to 3 days
        $retentionDays = config('constant.log_retention_days');

        // Define the cutoff date (X days ago)
        $cutoffDate = Carbon::now()->subDays($retentionDays);

        DB::table('logs')->where('created_at', '<', $cutoffDate )->delete();
    }
}
