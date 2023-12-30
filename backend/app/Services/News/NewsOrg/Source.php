<?php

namespace App\Services\News\NewsOrg;

use App\Services\News\Client;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class Source extends Client
{
    public function get(): mixed
    {
        return $this->api
            ->get('top-headlines/sources')
            ->json();
    }
}
