<?php

namespace App\Services\News\NewsOrg;

use App\Services\News\Client;

class Article extends Client
{
    public function get(string $sources): mixed
    {
        $results = collect();
        $query = [
            'sources' => $sources,
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
