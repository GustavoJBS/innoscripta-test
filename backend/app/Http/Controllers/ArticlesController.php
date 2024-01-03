<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Http\{JsonResponse, Response};

class ArticlesController extends Controller
{
    public function get(): Response|JsonResponse
    {
        $articlesQuery = Article::query()
            ->when(
                $this->containsAnyFilter(),
                fn (Builder $query) => $query->filter(request('filter'))
            )->when(
                !$this->containsAnyFilter(),
                fn (Builder $query) => $query->filterByPreference()
            )->orderBy('published_at', 'desc')->paginate(10);

        return response()->json([
            'status'   => true,
            'message'  => 'Articles Fetched Successfully.',
            'articles' => $articlesQuery,
        ], Response::HTTP_OK);
    }

    private function containsAnyFilter(): bool
    {
        return request('filter.language')
            || request('filter.source')
            || request('filter.category');
    }
}
