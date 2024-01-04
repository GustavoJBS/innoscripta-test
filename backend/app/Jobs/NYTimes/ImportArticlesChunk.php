<?php

namespace App\Jobs\NYTimes;

use App\Services\News\NYTimes\Article;
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

    public function __construct(
        public int $page,
        public int $sourceId,
        public int $categoryId,
        public string $categoryTitle
    ) {
    }

    public function handle(): void
    {
        $this->articlesService = new Article(config('services.ny-times.url'));

        $data = $this->articlesService->get($this->categoryTitle, $this->page);

        if (!$data->has('docs')) {
            return;
        }

        $articleJobs = collect($data['docs'])
            ->map(fn (array $article) => new ImportArticle(
                $article,
                $this->sourceId,
                $this->categoryId
            ));

        $this->batch()->add($articleJobs);
    }
}
