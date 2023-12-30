<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class News
{
    private PendingRequest $api;

    public function __construct()
    {
        $this->api = Http::baseUrl(config('services.news-api.url'))
            ->withToken(config('services.news-api.key'))	
            ->acceptJson()
            ->asJson();
    }
}
