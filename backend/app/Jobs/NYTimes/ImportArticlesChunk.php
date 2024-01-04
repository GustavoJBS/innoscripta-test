<?php

namespace App\Jobs\NYTimes;

use App\Services\News\NYTimes\Article;
use Carbon\Carbon;
use Illuminate\Bus\{Batchable, Queueable};
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};

class ImportArticlesChunk implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $tries = 0;

    public function __construct(
        public int $page,
        public int $sourceId,
        public int $categoryId,
        public string $categoryTitle
    ) {
    }

    public function handle()
    {
        $this->articlesService = new Article(config('services.ny-times.url'));

        $data = $this->articlesService->get($this->categoryTitle, $this->page);

        if (
            isset($data['fault']['detail']['errorcode'])
                && $data['fault']['detail']['errorcode'] === Article::RATE_LIMITED_IDENTIFIER
        ) {
            return $this->release(60);
        }

        if (!isset($data['response']['docs'])) {
            return;
        }

        $articleJobs = collect($data['response']['docs'])
            ->map(fn (array $article) => new ImportArticle(
                $article,
                $this->sourceId,
                $this->categoryId
            ));

        $this->batch()->add($articleJobs);
    }

    public function retryUntil(): Carbon
    {
        return now()->addMinutes(30);
    }
}
