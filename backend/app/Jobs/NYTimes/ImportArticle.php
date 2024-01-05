<?php

namespace App\Jobs\NYTimes;

use App\Enums\Language;
use App\Models\{Article, Category};
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
        public array $data,
        public int $sourceId,
        public int $categoryId
    ) {
    }

    public function handle(): void
    {
        Article::query()
            ->updateOrCreate(
                ['title' => $this->data['abstract']],
                [
                    'url'      => $this->data['web_url'],
                    'language' => Language::EN->value,
                    'image'    => isset($this->data['multimedia'][0]['url'])
                        ? "https://www.nytimes.com/{$this->data['multimedia'][0]['url']}"
                        : null,
                    'published_at' => Carbon::createFromFormat('Y-m-d', substr($this->data['pub_date'], 0, 10)),
                    'category_id'  => $this->categoryId,
                    'source_id'    => $this->sourceId,
                ]
            );
    }
}
