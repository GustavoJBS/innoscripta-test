<?php

namespace App\Jobs\Guardian;

use App\Enums\Language;
use App\Models\Source;
use App\Services\News\Guardian\Article;
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

    public const MAX_PAGES = 10;

    public Article $articlesService;

    public function handle(): void
    {
        $this->articlesService = new Article(config('services.guardian.url'));

        $this->syncArticles($this->getGuardianSource());
    }

    private function syncArticles(Source $source): void
    {
        $data = $this->articlesService->get();

        if (!$data->has('results') || !$data->has('pages')) {
            return;
        }

        $firstPage = collect($data['results'])
            ->map(fn ($article) => new ImportArticle($article, $source->id));

        $lastPage   = $data['pages'];
        $totalPages = $lastPage > self::MAX_PAGES
            ? self::MAX_PAGES
            : $lastPage;

        $pageChunks = collect(range(2, $totalPages))
            ->map(fn (int $page) => new ImportArticlesChunk($page, $source->id));

        Bus::batch($firstPage->merge($pageChunks))
            ->name('Import Articles from The Guardian')
            ->dispatch();
    }

    private function getGuardianSource(): Source
    {
        return Source::query()->firstOrCreate(
            ['name' => 'The Guardian'],
            [
                'url'      => 'https://www.theguardian.com/',
                'country'  => 'us',
                'language' => Language::EN->value,
            ]
        );
    }
}
