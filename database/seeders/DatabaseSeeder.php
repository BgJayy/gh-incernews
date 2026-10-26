<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $password = env('ADMIN_PASSWORD');

        if (! is_string($password) || $password === '') {
            if (app()->isProduction()) {
                throw new RuntimeException('ADMIN_PASSWORD wajib diisi sebelum seed di production.');
            }

            $password = 'ganti-sandi-ini';
        }

        User::query()->updateOrCreate(
            ['email' => filled(env('ADMIN_EMAIL')) ? env('ADMIN_EMAIL') : 'redaksi@incernews.test'],
            [
                'name' => 'Redaksi Incer News',
                'password' => $password,
            ],
        );

        $this->call(IncerNewsSeeder::class);
    }
}
