<?php

namespace App\Jobs\NewsOrg;

use App\Models\{Category, Source as SourceModel};
use App\Services\News\NewsOrg\{Article, Source};
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\Bus;

class SyncArticles implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public const MAX_PAGES = 4;

    public Article $articlesService;

    public Source $sourceService;

    public function handle(): void
    {
        $this->articlesService = new Article(...config('services.news-api'));
        $this->sourceService   = new Source(...config('services.news-api'));

        Category::query()
            ->each(
                fn (Category $category) => $this->sourceService
                    ->get($category->name)
                    ->each(fn (array $source) => $this->syncArticles(
                        $this->saveSource($source),
                        $category->id
                    ))
            );
    }

    public function syncArticles(SourceModel $source, int $categoryId): void
    {
        $data = $this->articlesService->get($source->name);

        if (!$data->has('articles') || !$data->has('totalResults')) {
            return;
        }

        $firstPage = collect($data['articles'])
            ->map(fn ($article) => new ImportArticle(
                $article,
                $source->id,
                $source->language,
                $categoryId
            ));

        $totalPages = ceil($data['totalResults'] / Article::PAGE_SIZE);

        $lastPage = $totalPages > self::MAX_PAGES
            ? self::MAX_PAGES
            : $totalPages;

        $pageChunks = collect(range(2, $lastPage))
            ->map(fn (int $page) => new ImportArticlesChunk(
                $page,
                $source->id,
                $source->language,
                $categoryId
            ));

        Bus::batch($firstPage->merge($pageChunks))
            ->name('Import Articles from News Org')
            ->dispatch();
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
