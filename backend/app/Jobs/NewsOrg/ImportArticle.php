<?php

namespace App\Jobs\NewsOrg;

use App\Models\Article;
use Carbon\Carbon;
use Illuminate\Bus\{Batchable, Queueable};
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\{InteractsWithQueue, SerializesModels};

class ImportArticle implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public array $article,
        public int $sourceId,
        public string $language,
        public int $categoryId,
    ) {
    }

    public function handle(): void
    {
        Article::query()
            ->updateOrCreate(
                ['title' => $this->article['title']],
                [
                    'url'          => $this->article['url'],
                    'image'        => optional($this->article)['urlToImage'],
                    'content'      => $this->article['content'],
                    'description'  => $this->article['description'],
                    'published_at' => Carbon::createFromFormat('Y-m-d', substr($this->article['publishedAt'], 0, 10)),
                    'language'     => $this->language,
                    'category_id'  => $this->categoryId,
                    'source_id'    => $this->sourceId,
                ]
            );
    }
}
