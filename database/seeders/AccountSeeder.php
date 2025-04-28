<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (DB::table('users')->get() as $user) {
            DB::table('accounts')->insert([
                'user_id' => $user->id,
                'number' => $this->generateAccountNumber(),
                'type' => 'savings',
                'balance' => 10000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('accounts')->insert([
                'user_id' => $user->id,
                'number' => $this->generateAccountNumber(),
                'type' => 'checking',
                'balance' => 10000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        
    }

    private function generateAccountNumber()
    {
        // Generates a random 10-digit account number
        return mt_rand(1000000000, 9999999999);
    }
    
}
