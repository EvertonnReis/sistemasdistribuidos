<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug');
            $table->text('content')->nullable();
            $table->string('video_url')->nullable();
            $table->integer('duration_minutes')->default(0);
            $table->integer('order')->default(0);
            $table->boolean('is_free')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['course_id', 'order']);
            $table->index('slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
