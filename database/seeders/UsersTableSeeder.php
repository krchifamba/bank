<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sampleUsers = [
            [
                'first_name' => 'Gendo',
                'last_name' => 'Ikari',
                'address' => 'Tokyo-3, Japan',
                'date_of_birth' => '1967-04-29',
                'email' => config('app.fake_admin_email'),
                'password' => Hash::make('password'),
                'is_admin' => true,
            ],
            [
                'first_name' => 'Shinji',
                'last_name' => 'Ikari',
                'address' => 'Tokyo-3, Japan',
                'date_of_birth' => '2001-06-06',
                'email' => 'ikarishinji@testmail.com',
                'password' => Hash::make('password'),
                'is_admin' => false,
            ],
            [
                'first_name' => 'Misato',
                'last_name' => 'Katsuragi',
                'address' => 'Tokyo-3',
                'date_of_birth' => '1986-05-08',
                'email' => 'misato@nerv.com',
                'password' => Hash::make('password123'),
                'is_admin' => false,

            ],
            [
                'first_name' => 'Rei',
                'last_name' => 'Ayanami',
                'address' => 'Tokyo-3',
                'date_of_birth' => '2001-03-30',
                'email' => 'rei@nerv.com',
                'password' => Hash::make('password123'),
                'is_admin' => false,

            ],
            [
                'first_name' => 'Asuka',
                'last_name' => 'Langley',
                'address' => 'Tokyo-3',
                'date_of_birth' => '2001-12-04',
                'email' => 'asuka@nerv.com',
                'password' => Hash::make('password123'),
                'is_admin' => false,
            ],
            [
                'first_name' => 'Toji',
                'last_name' => 'Suzuhara',
                'address' => 'Tokyo-3',
                'date_of_birth' => '2001-12-04',
                'email' => 'toji@nerv.com',
                'password' => Hash::make('password123'),
                'is_admin' => false,
            ],
        ];
        foreach ($sampleUsers as $user) {
            DB::table('users')->insert([
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'address' => $user['address'],
                'date_of_birth' => $user['date_of_birth'],
                'email' => $user['email'],
                'password' => $user['password'],
                'is_admin' => $user['is_admin'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
