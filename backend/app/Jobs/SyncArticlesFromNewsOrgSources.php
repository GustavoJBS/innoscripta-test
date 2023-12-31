<?php

namespace App\Jobs;

use App\Models\Article as ArticleModel;
use App\Models\Category;
use App\Models\Source;
use App\Services\News\NewsOrg\Article;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncArticlesFromNewsOrgSources implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Source $source,
        public Category $category
    ) {
    }

    public function handle(): void
    {
        $articlesService = new Article(...config('services.news-api'));

        $articlesService
            ->get($this->source->name)
            ->each(fn (array $article) => $this->saveArticle([
                'title' => $article['title'],
                'url' => $article['url'],
                'image' => optional($article)['urlToImage'],
                'content' => $article['content'],
                'description' => $article['description'],
                'published_at' => Carbon::createFromFormat('Y-m-d H:i:s', $article['publishedAt']),
                'language' => $this->source->language,
                'category_id' => $this->category->id,
                'source_id' => $this->source->id,
            ]));
    }

    private function saveArticle(array $article): ArticleModel
    {
        return ArticleModel::query()
            ->updateOrCreate(
                ['title' => $article['title']],
                [...collect($article)->except('title')]
            );
    }
}
