<?php

namespace App\Services\News;

use App\Enums\News;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class Article
{
    private PendingRequest $api;

    public function __construct()
    {
        $this->api = Http::acceptJson()->asJson();
    }

    public function get(?string $sources = null): mixed
    {
        $query = [];

        if ($sources) {
            $query['sources'] = $sources;
        }

        return $this->api
            ->get('everything', $query)
            ->json();
    }
}
