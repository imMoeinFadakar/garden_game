<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Carbon;

class DeleteExpiredTokens extends Command
{
    protected $signature = 'tokens:delete-expired';
    protected $description = 'Delete expired Sanctum tokens from the database';

    public function handle()
    {
        $count = PersonalAccessToken::whereNotNull('expires_at')
            ->where('expires_at', '<', Carbon::now())
            ->delete();

        $this->info("✅ Deleted {$count} expired tokens.");
    }
}
