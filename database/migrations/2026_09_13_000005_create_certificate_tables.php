<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificate_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('certificate_type', [
                'membership',
                'training_completion',
                'participation',
                'professional_certification',
                'professional_development',
                'recognition_appreciation',
                'other'
            ])->default('membership');
            $table->string('background_image_path')->nullable();
            $table->enum('orientation', ['landscape', 'portrait'])->default('landscape');
            $table->text('template_html')->nullable();
            $table->json('placeholders_config')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('certificate_number')->unique();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->foreignId('template_id')->nullable()->constrained('certificate_templates')->nullOnDelete();
            $table->enum('certificate_type', [
                'membership',
                'training_completion',
                'participation',
                'professional_certification',
                'professional_development',
                'recognition_appreciation',
                'other'
            ])->default('membership');
            $table->string('title');
            $table->string('recipient_name');
            $table->unsignedBigInteger('course_id')->nullable(); // Foreign key added later if needed or standalone
            $table->string('course_name')->nullable();
            $table->string('grade')->nullable();
            $table->date('issue_date');
            $table->date('expiry_date')->nullable();
            $table->string('authorized_person_name')->default('Dr. Charles Mwansasu');
            $table->string('authorized_person_title')->default('Founding Chairperson / National Chairperson');
            $table->string('signature_image_path')->nullable();
            $table->string('qr_code_path')->nullable();
            $table->string('pdf_path')->nullable();
            $table->enum('status', ['valid', 'expired', 'revoked', 'replaced'])->default('valid');
            $table->text('revocation_reason')->nullable();
            $table->foreignId('revoked_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('revoked_at')->nullable();
            $table->timestamps();

            $table->index('certificate_number');
            $table->index('status');
            $table->index('certificate_type');
        });

        Schema::create('certificate_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certificate_id')->constrained('certificates')->cascadeOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('verified_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificate_verifications');
        Schema::dropIfExists('certificates');
        Schema::dropIfExists('certificate_templates');
    }
};
