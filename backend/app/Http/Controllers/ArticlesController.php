<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\{JsonResponse, Response};

class ArticlesController extends Controller
{
    public function get(): Response|JsonResponse
    {
        $preference = auth()->user()->preference;

        $articles = Article::query()
            ->whereIn('language', $preference->languages)
            ->orWhereIn('source_id', $preference->sources)
            ->orWhereIn('category_id', $preference->categories)
            ->paginate(10);

        return response()->json([
            'status'   => true,
            'message'  => 'Articles Fetched Successfully.',
            'articles' => $articles,
        ], Response::HTTP_OK);
    }
}
