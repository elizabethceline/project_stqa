<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $admins = [
            [
                'email' => 'admin@admin.com',
                'password' => bcrypt('password'),
            ],
            [
                'email' => 'admin2@admin.com',
                'password' => bcrypt('password'),
            ]
        ];

        foreach ($admins as $admin) {
            Admin::create($admin);
        }
    }
}
