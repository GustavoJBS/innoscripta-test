<?php

namespace App\Jobs\NYTimes;

use App\Enums\Language;
use App\Models\{Category, Source};
use App\Services\News\NYTimes\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\{ShouldBeUnique, ShouldQueue};
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};
use Illuminate\Support\Facades\Bus;

class SyncArticles implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public const MAX_PAGES = 5;

    public Article $articlesService;

    public function handle(): void
    {
        $this->articlesService = new Article(config('services.ny-times.url'));

        $source = $this->getNYTimesSource();

        Category::query()
            ->each(
                fn (Category $category) => $this->syncArticles(
                    $source->id,
                    $category->id,
                    $category->title
                )
            );
    }

    public function syncArticles(int $sourceId, int $categoryId, string $categoryTitle): void
    {
        $data = $this->articlesService->get($categoryTitle);

        if (!isset($data['response']['docs'])) {
            return;
        }

        $firstPage = collect($data['response']['docs'])
            ->map(fn (array $article) => new ImportArticle($article, $sourceId, $categoryId));

        $totalPages = ceil($data['response']['meta']['hits'] / Article::PAGE_SIZE);

        $lastPage = $totalPages > self::MAX_PAGES
            ? self::MAX_PAGES
            : $totalPages;

        $pageChunks = collect(range(1, $lastPage))
            ->map(fn (int $page) => new ImportArticlesChunk($page, $sourceId, $categoryId, $categoryTitle));

        Bus::batch($firstPage->merge($pageChunks))
            ->name('Import Articles from The New York Times')
            ->dispatch();
    }

    private function getNYTimesSource(): Source
    {
        return Source::query()->firstOrCreate(
            ['name' => 'The New York Times'],
            [
                'url'      => 'https://www.nytimes.com',
                'country'  => 'us',
                'language' => Language::EN->value,
            ]
        );
    }
}
