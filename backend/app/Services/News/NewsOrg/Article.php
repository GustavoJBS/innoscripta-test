<?php

namespace App\Services\News\NewsOrg;

use App\Services\News\Client;
use Illuminate\Support\Collection;

class Article extends Client
{
    public const PAGE_SIZE = 100;

    public function get(string $source, int $page = 1): Collection
    {
        $query = [
            'sources'  => $source,
            'page'     => $page,
            'pageSize' => self::PAGE_SIZE,
        ];

        return $this->api->get('top-headlines', $query)->collect();
    }
}
