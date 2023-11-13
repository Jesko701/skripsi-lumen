<?php

namespace App\Events;
use Illuminate\Queue\SerializesModels;

class JobCategoryProcessed extends Event
{
    use SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
}
