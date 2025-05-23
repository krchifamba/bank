<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['deposit', 'withdrawal', 'transfer']);
            $table->decimal('amount', 15, 2);
            $table->decimal('spread_amount', 15, 2);
            $table->text('from_account_number');
            $table->text('to_account_number');
            $table->text('description')->nullable();
            $table->timestamp('transaction_date')->nullable(); 
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
