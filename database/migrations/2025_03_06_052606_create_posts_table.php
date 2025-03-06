<?php

use App\Enums\PostStatus;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title', 60);
            $table->longText('body');
            $table->enum('status', array_column(PostStatus::cases(), 'value'))->default(PostStatus::Draft->value);
            $table->date('scheduled_at')->nullable();
            $table->date('published_at')->nullable();
            $table->string('slug');
            $table->foreignUuid('author_id')->references('id')->on('users')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
