<?php

namespace App\Listeners;

use App\Events\JobCategoryProcessed;
use Illuminate\Queue\Listener;
use Illuminate\Support\Facades\Log;

class JobCategoryProcessedListener extends Listener
{
    public function handle(JobCategoryProcessed $event)
    {
        $data = $event->data;
        Log::info('JobCategoryProcessedListener dijalankan');
        return response()->json([
            'message' => 'Data berhasil ditemukan',
            'data' => $data['data'],
            'total_data' => $data['total_data']
        ], 200);
    }
}
