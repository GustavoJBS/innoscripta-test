<?php

namespace App\Services\News\Guardian;

use App\Services\News\Client;
use Illuminate\Support\Collection;

class Article extends Client
{
    public const MAX_RESULTS = 200;

    public function get(): Collection
    {
        $results = collect();
        $query = [
            'api-key' => config('services.guardian.key'),
            'page' => 1,
            'pageSize' => 50,
        ];

        while (true) {
            $result = $this->api->get('search', $query);

            if (! $result->successful()) {
                break;
            }

            $results->push(...$result->json('response.results'));

            if ($results->count() === $result->json('response.total') || $results->count() >= self::MAX_RESULTS) {
                break;
            }

            $query['page']++;
        }

        return $results;
    }
}
