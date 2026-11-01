<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_engines', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('slug', 150)->unique();
            $table->string('developer_company', 150)->nullable();
            $table->unsignedSmallInteger('release_year')->nullable();
            $table->string('license_model', 150)->nullable();
            $table->json('primary_languages')->nullable();
            $table->json('key_technologies')->nullable();
            $table->text('ai_capabilities')->nullable();
            $table->text('description')->nullable();
            $table->string('official_url')->nullable();
            $table->string('logo_url')->nullable();
            $table->timestamps();
        });

        Schema::create('technologies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 180);
            $table->string('slug', 180)->unique();
            $table->string('category', 50)->default('rendering')->index();
            $table->string('vendor', 150)->nullable();
            $table->unsignedSmallInteger('introduced_year')->nullable();
            $table->text('summary')->nullable();
            $table->longText('technical_deep_dive')->nullable();
            $table->text('ai_integration_notes')->nullable();
            $table->timestamps();
        });

        Schema::create('ai_systems', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('slug', 200)->unique();
            $table->string('paradigm', 60)->default('decision_making')->index();
            $table->string('invented_by_or_origin', 200)->nullable();
            $table->string('first_notable_game', 180)->nullable();
            $table->unsignedSmallInteger('first_year')->nullable();
            $table->string('complexity_level', 120)->nullable();
            $table->text('concept_summary');
            $table->longText('how_it_works');
            $table->text('pros_and_cons')->nullable();
            $table->text('pseudocode_or_diagram')->nullable();
            $table->timestamps();
        });

        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('original_title')->nullable();
            $table->string('slug')->unique();
            $table->unsignedSmallInteger('release_year')->index();
            $table->string('developer_studio', 200)->index();
            $table->string('publisher', 200)->nullable();
            $table->string('game_director')->nullable()->index();
            $table->string('lead_ai_programmer')->nullable();
            $table->string('composer')->nullable();
            $table->string('country', 100)->default('ABD');
            $table->foreignId('game_engine_id')->nullable()->constrained('game_engines')->nullOnDelete();
            $table->json('platforms')->nullable();
            $table->json('genres')->nullable();
            $table->json('moods')->nullable();
            $table->string('perspective', 120)->nullable();
            $table->json('multiplayer_modes')->nullable();
            $table->unsignedSmallInteger('main_story_hours')->default(20);
            $table->unsignedSmallInteger('completionist_hours')->default(50);
            $table->unsignedTinyInteger('metacritic_score')->nullable();
            $table->decimal('rating', 3, 1)->default(9.0)->index();
            $table->longText('synopsis');
            $table->longText('technical_overview')->nullable();
            $table->longText('ai_architecture_summary')->nullable();
            $table->text('cultural_impact')->nullable();
            $table->text('why_play')->nullable();
            $table->string('poster_url')->nullable();
            $table->string('backdrop_url')->nullable();
            $table->string('trailer_url')->nullable();
            $table->json('pc_min_specs')->nullable();
            $table->json('pc_rec_specs')->nullable();
            $table->boolean('is_featured_daily')->default(false)->index();
            $table->string('status', 20)->default('approved')->index();
            $table->foreignId('submitted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('game_technology', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained('games')->cascadeOnDelete();
            $table->foreignId('technology_id')->constrained('technologies')->cascadeOnDelete();
            $table->text('implementation_notes')->nullable();
            $table->timestamps();

            $table->unique(['game_id', 'technology_id']);
        });

        Schema::create('game_ai_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained('games')->cascadeOnDelete();
            $table->foreignId('ai_system_id')->constrained('ai_systems')->cascadeOnDelete();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('ai_role_in_game', 200);
            $table->longText('architecture_breakdown');
            $table->longText('perception_and_sensors')->nullable();
            $table->longText('decision_loop_details')->nullable();
            $table->text('player_experience_impact')->nullable();
            $table->text('limitations_and_tricks')->nullable();
            $table->unsignedTinyInteger('innovation_score')->default(90);
            $table->unsignedTinyInteger('complexity_score')->default(90);
            $table->string('video_evidence_url')->nullable();
            $table->string('status', 20)->default('approved')->index();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('game_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained('games')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('video_type', 40)->default('gameplay_sample')->index();
            $table->string('video_url');
            $table->string('thumbnail_url')->nullable();
            $table->string('duration_label', 30)->default('05:00');
            $table->string('resolution_tag', 40)->default('4K 60FPS');
            $table->text('description')->nullable();
            $table->string('technical_highlights')->nullable();
            $table->boolean('is_featured')->default(false)->index();
            $table->string('status', 20)->default('approved')->index();
            $table->timestamps();
        });

        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('primary_role', 40)->default('director')->index();
            $table->unsignedSmallInteger('birth_year')->nullable();
            $table->string('nationality', 100)->nullable();
            $table->string('studio_affiliation', 150)->nullable();
            $table->text('bio')->nullable();
            $table->string('photo_url')->nullable();
            $table->string('signature_style')->nullable();
            $table->json('notable_achievements')->nullable();
            $table->timestamps();
        });

        Schema::create('game_person', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained('games')->cascadeOnDelete();
            $table->foreignId('person_id')->constrained('people')->cascadeOnDelete();
            $table->string('credit_type', 40)->default('director')->index();
            $table->string('character_name')->nullable();
            $table->unsignedSmallInteger('credit_order')->default(1);
            $table->timestamps();

            $table->unique(['game_id', 'person_id', 'credit_type']);
        });

        Schema::create('game_awards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained('games')->cascadeOnDelete();
            $table->foreignId('person_id')->nullable()->constrained('people')->nullOnDelete();
            $table->string('ceremony_name', 150);
            $table->string('ceremony_slug', 150)->index();
            $table->unsignedSmallInteger('year')->index();
            $table->string('category', 200);
            $table->string('recipient_name', 200)->nullable();
            $table->string('result', 30)->default('winner')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained('games')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('author_name', 150);
            $table->string('author_role', 150)->default('Oyuncu & Eleştirmen');
            $table->decimal('rating', 3, 1)->default(9.0);
            $table->decimal('gameplay_rating', 3, 1)->default(9.0);
            $table->decimal('ai_quality_rating', 3, 1)->default(9.0);
            $table->decimal('optimization_rating', 3, 1)->default(9.0);
            $table->string('played_platform', 80)->default('PC');
            $table->unsignedSmallInteger('hours_played')->default(25);
            $table->string('title');
            $table->longText('body');
            $table->boolean('contains_spoilers')->default(false);
            $table->boolean('is_editorial')->default(false);
            $table->timestamps();
        });

        Schema::create('review_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('review_id')->constrained('reviews')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'review_id']);
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_id')->constrained('reviews')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('body');
            $table->timestamps();
        });

        Schema::create('game_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('game_id')->constrained('games')->cascadeOnDelete();
            $table->string('play_status', 30)->default('playing')->index();
            $table->string('platform', 80)->default('PC');
            $table->unsignedSmallInteger('hours_played')->default(0);
            $table->date('completed_on')->nullable();
            $table->decimal('personal_rating', 3, 1)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'game_id']);
        });

        Schema::create('watchlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('game_id')->constrained('games')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'game_id']);
        });

        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('curator_name', 150)->nullable();
            $table->text('description')->nullable();
            $table->string('accent_color', 40)->default('emerald');
            $table->boolean('is_public')->default(true);
            $table->timestamps();
        });

        Schema::create('collection_game', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained('collections')->cascadeOnDelete();
            $table->foreignId('game_id')->constrained('games')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['collection_id', 'game_id']);
        });

        Schema::create('interviews', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('subtitle')->nullable();
            $table->string('interviewee_name', 150);
            $table->string('interviewee_role', 50)->default('director');
            $table->foreignId('person_id')->nullable()->constrained('people')->nullOnDelete();
            $table->foreignId('game_id')->nullable()->constrained('games')->nullOnDelete();
            $table->string('interviewer_name', 150)->default('GamePL Editör Masası');
            $table->string('source_publication', 150)->nullable();
            $table->unsignedSmallInteger('interview_year')->default(2025);
            $table->text('featured_quote')->nullable();
            $table->text('excerpt')->nullable();
            $table->longText('body');
            $table->string('cover_url')->nullable();
            $table->string('status', 20)->default('approved')->index();
            $table->foreignId('submitted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('subtitle')->nullable();
            $table->string('category', 80)->default('ai_deep_dive')->index();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('author_name', 150);
            $table->string('author_title', 150)->default('Oyun Teknolojisi Yazarı');
            $table->foreignId('game_id')->nullable()->constrained('games')->nullOnDelete();
            $table->foreignId('person_id')->nullable()->constrained('people')->nullOnDelete();
            $table->unsignedSmallInteger('reading_time_minutes')->default(7);
            $table->text('excerpt')->nullable();
            $table->text('pull_quote')->nullable();
            $table->longText('body');
            $table->string('cover_url')->nullable();
            $table->boolean('is_featured')->default(false)->index();
            $table->string('status', 20)->default('approved')->index();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('user_favorite_games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('game_id')->constrained('games')->cascadeOnDelete();
            $table->unsignedTinyInteger('rank')->default(1);
            $table->timestamps();

            $table->unique(['user_id', 'game_id']);
        });

        Schema::create('user_follows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('follower_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('following_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['follower_id', 'following_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_follows');
        Schema::dropIfExists('user_favorite_games');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('interviews');
        Schema::dropIfExists('collection_game');
        Schema::dropIfExists('collections');
        Schema::dropIfExists('watchlists');
        Schema::dropIfExists('game_logs');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('review_likes');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('game_awards');
        Schema::dropIfExists('game_person');
        Schema::dropIfExists('people');
        Schema::dropIfExists('game_videos');
        Schema::dropIfExists('game_ai_analyses');
        Schema::dropIfExists('game_technology');
        Schema::dropIfExists('games');
        Schema::dropIfExists('ai_systems');
        Schema::dropIfExists('technologies');
        Schema::dropIfExists('game_engines');
    }
};
