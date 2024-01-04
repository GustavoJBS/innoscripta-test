<?php

namespace App\Jobs\NewsOrg;

use App\Services\News\NewsOrg\Article;
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
        public string $language,
        public int $categoryId
    ) {
    }

    public function handle(): void
    {
        $this->articlesService = new Article(config('services.news-api.url'));

        $data = $this->articlesService->get($this->page);

        if (!$data->has('articles')) {
            return;
        }

        $articleJobs = collect($data['articles'])
            ->map(fn (array $article) => new ImportArticle(
                $article,
                $this->sourceId,
                $this->language,
                $this->categoryId
            ));

        $this->batch()->add($articleJobs);
    }
}
