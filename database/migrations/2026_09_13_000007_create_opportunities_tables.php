<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('opportunity_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        Schema::create('opportunities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('opportunity_categories');
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('provider_name');
            $table->string('provider_logo')->nullable();
            $table->text('summary');
            $table->longText('description');
            $table->text('eligibility_criteria');
            $table->string('location')->default('Tanzania / Nationwide');
            $table->date('deadline')->nullable();
            $table->enum('application_type', ['internal', 'external'])->default('internal');
            $table->string('external_url')->nullable();
            $table->json('required_documents')->nullable();
            $table->string('contact_info')->nullable();
            $table->enum('status', ['draft', 'published', 'closed', 'archived'])->default('draft');
            $table->boolean('is_verified')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->foreignId('published_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index('slug');
            $table->index('status');
            $table->index('deadline');
        });

        Schema::create('opportunity_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opportunity_id')->constrained('opportunities')->cascadeOnDelete();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->string('application_number')->unique();
            $table->text('cover_letter')->nullable();
            $table->json('application_data')->nullable();
            $table->string('resume_path')->nullable();
            $table->json('supporting_documents')->nullable();
            $table->enum('status', ['submitted', 'under_review', 'shortlisted', 'accepted', 'rejected'])->default('submitted');
            $table->text('admin_feedback')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->unique(['opportunity_id', 'member_id']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('opportunity_applications');
        Schema::dropIfExists('opportunities');
        Schema::dropIfExists('opportunity_categories');
    }
};
