<?php

namespace App\Services\News;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class Client
{
    protected PendingRequest $api;

    public function __construct(string $baseUrl, string $apiKey)
    {
        $this->api = Http::baseUrl($baseUrl)
            ->withToken($apiKey)
            ->acceptJson()
            ->asJson();
    }
}
