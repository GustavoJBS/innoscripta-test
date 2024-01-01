<?php

namespace App\Console\Commands;

use App\Jobs\SyncDataFromNewsOrg;
use Illuminate\Console\Command;

class SyncDataFromNewsApis extends Command
{
    protected $signature = 'app:sync-source-articles-from-apis';

    protected $description = 'Sync source articles from api to local database.';

    public function handle(): void
    {
        SyncDataFromNewsOrg::dispatch();
    }
}
