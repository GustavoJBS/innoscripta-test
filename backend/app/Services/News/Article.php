<?php

namespace App\Services\News;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class Article
{
    private PendingRequest $api;

    public function __construct()
    {
        $this->api = Http::baseUrl(config('services.news-api.url'))
            ->withToken(config('services.news-api.key'))	
            ->acceptJson()
            ->asJson();
    }

    public function get(): mixed
    {
        return $this->api
            ->get('everything', [
                'sources' => 'globo',
            ])->json();
    }
}
