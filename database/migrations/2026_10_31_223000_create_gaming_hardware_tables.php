<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gaming_hardware', function (Blueprint $table) {
            $table->id();
            $table->string('name', 180);
            $table->string('slug', 180)->unique();
            $table->string('category', 50)->default('monitor')->index(); // monitor, console, gpu_ai, vr_headset, peripherals
            $table->string('manufacturer', 120);
            $table->unsignedSmallInteger('release_year')->default(2024);
            $table->string('price_tier', 100)->default('Amiral Gemisi (Flagship)');
            $table->string('badge_label', 140)->nullable();
            $table->decimal('rating', 3, 1)->default(9.5);
            $table->json('key_specs')->nullable();
            $table->text('short_intro');
            $table->longText('technical_deep_dive');
            $table->text('ai_and_game_synergy')->nullable();
            $table->text('pros')->nullable();
            $table->text('cons')->nullable();
            $table->string('recommended_for', 250)->nullable();
            $table->string('image_url')->nullable();
            $table->string('showcase_video_url')->nullable();
            $table->boolean('is_featured')->default(true)->index();
            $table->string('status', 20)->default('approved')->index();
            $table->foreignId('submitted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('game_gaming_hardware', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained('games')->cascadeOnDelete();
            $table->foreignId('gaming_hardware_id')->constrained('gaming_hardware')->cascadeOnDelete();
            $table->string('experience_note', 250)->nullable();
            $table->timestamps();

            $table->unique(['game_id', 'gaming_hardware_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_gaming_hardware');
        Schema::dropIfExists('gaming_hardware');
    }
};
