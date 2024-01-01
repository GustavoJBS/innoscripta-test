<?php

namespace App\Services\News\Guardian;

use App\Services\News\Client;
use Illuminate\Support\Collection;

class Article extends Client
{
    public function get(int $page = 1): Collection
    {
        $query = [
            'api-key' => config('services.guardian.key'),
            'page' => $page,
        ];

        return $this->api->get('search', $query)->collect('response');
    }
}
