<?php

namespace App\Services\News;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class Client
{
    protected PendingRequest $api;

    public function __construct(string $url, ?string $key = null)
    {
        $this->api = Http::baseUrl($url)
            ->acceptJson()
            ->asJson();

        if ($key) {
            $this->api->withToken($key);
        }
    }
}
