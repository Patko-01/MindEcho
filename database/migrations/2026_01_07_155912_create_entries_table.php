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
        Schema::create('entries', function (Blueprint $table) {
            // make entry_id a primary-foreign key (unsigned big integer) referencing ai_questions.question_id
            $table->unsignedBigInteger('entry_id');
            $table->primary('entry_id');
            $table->foreign('entry_id')->references('question_id')->on('ai_questions')->onDelete('cascade');

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('entry_title');
            $table->string('tag');
            $table->text('content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entries');
    }
};
