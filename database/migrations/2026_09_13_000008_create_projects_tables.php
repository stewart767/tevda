<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('project_categories');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('summary');
            $table->longText('description');
            $table->string('location')->default('Tanzania');
            $table->json('target_regions')->nullable();
            $table->integer('target_beneficiaries_count')->default(50);
            $table->enum('funding_status', [
                'proposal_under_development',
                'seeking_partners',
                'funding_approved',
                'partially_funded',
                'to_be_confirmed'
            ])->default('proposal_under_development');
            $table->enum('project_status', [
                'proposal_under_development',
                'open_for_applications',
                'applications_closed',
                'shortlisting_in_progress',
                'active',
                'completed',
                'paused'
            ])->default('proposal_under_development');
            $table->decimal('budget_amount', 14, 2)->nullable();
            $table->string('currency', 10)->default('TZS');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('application_open_date')->nullable();
            $table->date('application_close_date')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('partner_organisations')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();

            $table->index('slug');
            $table->index('project_status');
            $table->index('funding_status');
        });

        Schema::create('project_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->string('application_number')->unique();
            $table->text('statement_of_need')->nullable();
            $table->string('preferred_vehicle_type')->nullable();
            $table->string('operating_zone_or_route')->nullable();
            $table->json('supporting_documents')->nullable();
            $table->enum('status', [
                'applied',
                'under_review',
                'shortlisted',
                'approved',
                'asset_allocated',
                'rejected'
            ])->default('applied');
            $table->text('reviewer_notes')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->unique(['project_id', 'member_id']);
            $table->index('status');
        });

        Schema::create('beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->foreignId('application_id')->nullable()->constrained('project_applications')->nullOnDelete();
            $table->string('beneficiary_code')->unique();
            $table->string('asset_type')->nullable(); // e.g. Electric Three-Wheeler
            $table->string('asset_registration_or_serial')->nullable();
            $table->date('allocation_date')->nullable();
            $table->boolean('training_completed')->default(false);
            $table->enum('status', ['active', 'monitoring', 'completed', 'defaulted', 'transferred'])->default('active');
            $table->text('monitoring_notes')->nullable();
            $table->timestamps();

            $table->index('beneficiary_code');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beneficiaries');
        Schema::dropIfExists('project_applications');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('project_categories');
    }
};
