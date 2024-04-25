<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call(CustomerSeeder::class);
        $this->call(InvoicesSeeder::class);

        // \App\Models\User::factory(10)->create();
        // db::table('users')->insert(
        //     [
        //         'name' => 'Quang Trần',
        //         'email' => 'quangtran@gmail.com',
        //         'password' => Hash::make(123456),
        //         'created_at' => date('Y-m-d H:i:s'),
        //         'updated_at' => date('Y-m-d H:i:s'),
        //     ],
        //     [
        //         'name' => 'Quang ',
        //         'email' => 'quang@gmail.com',
        //         'password' => Hash::make(123456),
        //         'created_at' => date('Y-m-d H:i:s'),
        //         'updated_at' => date('Y-m-d H:i:s'),
        //     ]

        // );
    }
}
