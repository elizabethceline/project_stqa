<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $customers = [
            [
                'name' => 'customer',
                'email' => 'user@user.com',
                'password' => bcrypt('password'),
                'bio' => 'They watch you from the shelf while you sleep 👀. Are you dreaming of them, they wonder, in that wistful mood books are prone to at night when they’re bored and there’s nothing else to do but tease the cat.?',
            ],
            [
                'name' => 'customer2',
                'email' => 'user2@user.com',
                'password' => bcrypt('password'),
                'bio' => 'Hi :)! Long time no see ❤️',
            ]
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
