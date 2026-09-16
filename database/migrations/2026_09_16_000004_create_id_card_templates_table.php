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
        Schema::create('id_card_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('card_type')->default('standard'); // standard, commercial, executive, membership
            $table->string('front_background_image_path')->nullable();
            $table->string('back_background_image_path')->nullable();
            $table->string('orientation')->default('landscape'); // landscape (CR80)
            $table->longText('placeholders_config')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::table('membership_cards', function (Blueprint $table) {
            $table->foreignId('template_id')->nullable()->after('member_id')->constrained('id_card_templates')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('membership_cards', function (Blueprint $table) {
            $table->dropForeign(['template_id']);
            $table->dropColumn('template_id');
        });

        Schema::dropIfExists('id_card_templates');
    }
};
