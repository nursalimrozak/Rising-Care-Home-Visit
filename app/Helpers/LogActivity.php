<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class LogActivity
{
    public static function record($action, $description, $subject = null)
    {
        $log = [
            'user_id' => Auth::id(),
            'action' => $action,
            'description' => $description,
            'ip_address' => Request::ip(),
            'user_agent' => Request::header('User-Agent'),
        ];

        if ($subject) {
            $log['subject_type'] = get_class($subject);
            $log['subject_id'] = $subject->id;
        }

        ActivityLog::create($log);
    }
}
