<?php

namespace App\Services\News;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class Client
{
    private PendingRequest $api;

    public function __construct(string $baseUrl)
    {
        $this->api = Http::baseUrl($baseUrl)
            ->acceptJson()
            ->asJson();
    }

    public function setBaseUrl(string $baseUrl): void
    {
        $this->api->baseUrl($baseUrl);
    }
}