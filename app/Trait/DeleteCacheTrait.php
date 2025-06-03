<?php

namespace App\Trait;

use Illuminate\Support\Facades\Cache;

trait DeleteCacheTrait
{
    public function deleteCache(string $cacheKey): bool
    {
        return Cache::forget($cacheKey);
    }
}
