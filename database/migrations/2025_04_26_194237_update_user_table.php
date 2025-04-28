<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

return new class extends Migration
{
    public function up()
    {
        // Check if there are users in the database
        $firstUser = DB::table('users')->first();
        
        // If users exist, set the first user as admin
        if ($firstUser) {
            DB::table('users')
                ->where('id', $firstUser->id)
                ->update(['is_admin' => true]); // Set the first user as admin
        }
    }
    
    public function down()
    {
        // No need to reverse this migration
    }
    
    
};
