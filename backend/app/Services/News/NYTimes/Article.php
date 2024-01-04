<?php

namespace App\Services\News\NYTimes;

use App\Services\News\Client;
use Illuminate\Support\Collection;

class Article extends Client
{
    public const PAGE_SIZE = 10;

    public function get(string $category, int $page = 0): Collection
    {
        $query = [
            'api-key' => config('services.ny-times.key'),
            'fq'      => $category,
            'page'    => $page,
            'sort'    => 'newest',
        ];

        return $this->api
            ->get('articlesearch.json', $query)
            ->collect('response');
    }
}
