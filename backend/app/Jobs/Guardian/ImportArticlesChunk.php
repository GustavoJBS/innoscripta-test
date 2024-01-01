<?php

namespace App\Jobs\Guardian;

use App\Services\News\Guardian\Article;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportArticlesChunk implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Article $articlesService;

    public function __construct(
        public int $page,
        public int $sourceId
    ) {
    }

    public function handle(): void
    {
        $this->articlesService = new Article(config('services.guardian.url'));

        $data = $this->articlesService->get($this->page);

        if (! $data->has('results')) {
            return;
        }

        $articleJobs = collect($data['results'])
            ->map(fn ($article) => new ImportArticle($article, $this->sourceId));

        $this->batch()->add($articleJobs);
    }
}
