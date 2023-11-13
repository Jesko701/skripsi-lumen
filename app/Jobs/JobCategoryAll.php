<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class JobCategoryAll implements ShouldQueue
{
    use SerializesModels;
    protected $articleCategory;
    protected $articleCount;
    public function __construct()
    {
        Log::info("start job article category");
        $this->articleCategory = DB::select("SELECT * FROM article_category");
        $this->articleCount = DB::select("SELECT COUNT(*) AS TOTAL_DATA FROM article_category");
        Log::info("selesai job article category");
    }

    public function handle()
    {
        $queueTable = 'jobs';
        // Insert data pekerjaan ke dalam tabel antrian
        $dataJson = ['data' => $this->articleCategory, 'totalData' => $this->articleCount];
        DB::table($queueTable)->insert([
            'queue' => 'tes', // Nama antrian, sesuaikan dengan konfigurasi Anda
            'payload' => json_encode($dataJson),
            'attempts' => 0,
            'reserved_at' => null,
            'available_at' => Carbon::now()->getTimestamp(),
            'created_at' => Carbon::now()->getTimestamp(),
        ]);
    }
}
