<?php

namespace App\Services;

use App\Services\UtilService;

class ReplyService
{
    protected $utilService;

    public function __construct(UtilService $utilService)
    {
        $this->utilService = $utilService;
    }
}
