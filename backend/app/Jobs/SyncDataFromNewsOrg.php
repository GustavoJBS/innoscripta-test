<?php

namespace App\Jobs;

use App\Models\Article as ArticleModel;
use App\Models\Category;
use App\Models\Source as SourceModel;
use App\Services\News\NewsOrg\Article;
use App\Services\News\NewsOrg\Source;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncDataFromNewsOrg implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public Article $articlesService;

    public Source $sourceService;

    public function handle(): void
    {
        $this->articlesService = new Article(...config('services.news-api'));
        $this->sourceService = new Source(...config('services.news-api'));

        Category::query()
            ->each(
                fn (Category $category) => $this->sourceService
                    ->get($category->name)
                    ->each(fn (array $source) => $this->syncArticlesFromSource(
                        $this->saveSource($source),
                        $category->id
                    ))
            );
    }

    public function syncArticlesFromSource(SourceModel $source, int $categoryId): void
    {
        $this->articlesService
            ->get($source->name)
            ->each(fn (array $article) => $this->saveArticle([
                'title' => $article['title'],
                'url' => $article['url'],
                'image' => optional($article)['urlToImage'],
                'content' => $article['content'],
                'description' => $article['description'],
                'published_at' => Carbon::createFromFormat('Y-m-d', substr($article['publishedAt'], 0, 10)),
                'language' => $source->language,
                'category_id' => $categoryId,
                'source_id' => $source->id,
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

    private function saveSource(array $source): SourceModel
    {
        return SourceModel::query()
            ->updateOrCreate(
                ['name' => $source['name']],
                [...collect($source)->only(['url', 'country', 'description', 'language'])]
            );
    }
}
