<?php

namespace App\Console\Commands;

use App\Jobs\SyncArticlesFromNewsOrgSources;
use App\Models\Category;
use App\Models\Source as SourceModel;
use App\Services\News\NewsOrg\Source;
use Illuminate\Console\Command;

class SyncSourceArticlesFromApis extends Command
{
    protected $signature = 'app:sync-source-articles-from-apis';

    protected $description = 'Sync source articles from api to local database.';

    public function handle(): void
    {
        $this->syncSourceArticlesFromNewsOrgApi();
    }

    private function syncSourceArticlesFromNewsOrgApi(): void
    {
        $sourceService = new Source(...config('services.news-api'));

        Category::query()
            ->each(fn (Category $category) => $sourceService
                ->get($category->name)
                ->each(fn (array $source) => SyncArticlesFromNewsOrgSources::dispatch(
                    $this->saveSource($source),
                    $category
                ))
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
