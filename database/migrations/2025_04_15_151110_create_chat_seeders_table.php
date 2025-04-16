<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chat_seeders', function (Blueprint $table) {
            $table->id();
            $table->string('user_message');
            $table->string('bot_response');
            $table->timestamps();
        });

        DB::table('chat_seeders')->insert([
            ['user_message' => 'Hello, how are you?', 'bot_response' => 'I am fine, thank you!'],
            ['user_message' => 'Hi', 'bot_response' => 'I am fine, thank you!'],
            ['user_message' => 'What is your name?', 'bot_response' => 'I am a chatbot.'],
            ['user_message' => 'Tell me a joke.', 'bot_response' => 'Why did the chicken cross the road? To get to the other side!'],
            ['user_message' => 'What is the weather like?', 'bot_response' => 'It is sunny today.'],
            ['user_message' => 'Goodbye!', 'bot_response' => 'Goodbye! Have a great day!'],
            ['user_message' => 'Can you help me?', 'bot_response' => 'Of course! What do you need help with?'],
            ['user_message' => 'What is your favorite color?', 'bot_response' => 'I like all colors!'],
            ['user_message' => 'Do you have any hobbies?', 'bot_response' => 'I enjoy chatting with people!'],
            ['user_message' => 'What is your favorite food?', 'bot_response' => 'I don\'t eat, but I hear pizza is great!'],
            ['user_message' => 'Tell me a fun fact.', 'bot_response' => 'Did you know honey never spoils?'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_seeders');
    }
};
