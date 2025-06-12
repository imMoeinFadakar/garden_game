<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateUserHasParentSeeder extends Seeder
{
    public function run()
    {
        // همه کاربران رو مقداردهی اولیه کن با رشته 'false'
        DB::table('users')->update(['has_parent' => 'false']);

        // فقط کاربرانی که در user_referrals به‌عنوان invented_user هستند رو مقداردهی کن به 'true'
        DB::table('users')
            ->whereIn('id', function ($query) {
                $query->select('invented_user')
                    ->from('user_referrals');
            })
            ->update(['has_parent' => 'true']);

        $this->command->info('ستون has_parent با مقادیر enum "true"/"false" به‌روزرسانی شد.');
    }
}


