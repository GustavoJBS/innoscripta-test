<?php

namespace App\Console\Commands;

use App\Jobs\Guardian\SyncArticles as SyncArticlesGuardian;
use App\Jobs\NewsOrg\SyncArticles as SyncArticlesNewsOrg;
use App\Jobs\NYTimes\SyncArticles as SyncArticlesNYTimes;
use Illuminate\Console\Command;

class SyncArticlesFromNewsApis extends Command
{
    protected $signature = 'app:sync-source-articles-from-apis';

    protected $description = 'Sync articles from from apis to local database.';

    public function handle(): void
    {
        SyncArticlesGuardian::dispatch();
        SyncArticlesNewsOrg::dispatch();
        SyncArticlesNYTimes::dispatch();

        $this->info('Sync Article Jobs emmitted successfully.');
    }
}
