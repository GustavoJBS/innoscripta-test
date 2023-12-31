<?php

namespace App\Services\News\NewsOrg;

use App\Services\News\Client;

class Source extends Client
{
    public function get(): mixed
    {
        return $this->api
            ->get('top-headlines/sources')
            ->json();
    }
}
