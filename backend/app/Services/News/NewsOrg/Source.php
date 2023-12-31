<?php

namespace App\Services\News\NewsOrg;

use App\Services\News\Client;
use Illuminate\Support\Collection;

class Source extends Client
{
    public function get(?string $category = null): Collection
    {
        return $this->api
            ->get('top-headlines/sources', [
                'category' => $category,
            ])->collect('sources');
    }
}
