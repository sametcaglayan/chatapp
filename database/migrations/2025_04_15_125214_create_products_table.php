<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('chat_credit');
            $table->timestamps();
        });

        DB::table('products')->insert([
            ['name' => 'basic', 'chat_credit' => 20],
            ['name' => 'pro', 'chat_credit' => 30],
            ['name' => 'premium', 'chat_credit' => 40],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
