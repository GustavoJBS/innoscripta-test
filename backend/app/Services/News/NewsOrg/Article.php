<?php

namespace App\Services\News\NewsOrg;

use App\Services\News\Client;
use Illuminate\Support\Collection;

class Article extends Client
{
    public function get(string $source): Collection
    {
        $results = collect();
        $query = [
            'sources' => $source,
            'page' => 1,
        ];

        while (true) {
            $result = $this->api->get('top-headlines', $query);

            if (! $result->successful()) {
                break;
            }

            $results->push(...$result->json('articles'));

            if ($results->count() === $result->json('totalResults')) {
                break;
            }

            $query['page']++;
        }

        return $results;
    }
}
