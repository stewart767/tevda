<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_programmes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('code')->unique();
            $table->text('description');
            $table->text('objective')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('order_number')->default(0);
            $table->timestamps();
        });

        Schema::create('training_courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('programme_id')->constrained('training_programmes')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->integer('duration_hours')->default(8);
            $table->decimal('pass_mark_percentage', 5, 2)->default(70.00);
            $table->foreignId('certificate_template_id')->nullable()->constrained('certificate_templates')->nullOnDelete();
            $table->decimal('fee_amount', 12, 2)->default(0.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('training_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('training_courses')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('order_number')->default(1);
            $table->integer('duration_hours')->default(2);
            $table->timestamps();
        });

        Schema::create('training_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('training_courses')->cascadeOnDelete();
            $table->string('session_code')->unique();
            $table->string('trainer_name')->nullable();
            $table->string('location');
            $table->string('venue')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->integer('capacity')->default(30);
            $table->decimal('fee_amount', 12, 2)->default(0.00);
            $table->enum('status', ['upcoming', 'in_progress', 'completed', 'cancelled'])->default('upcoming');
            $table->timestamps();

            $table->index('session_code');
            $table->index('status');
        });

        Schema::create('training_enrolments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('training_sessions')->cascadeOnDelete();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->nullOnDelete();
            $table->enum('status', ['registered', 'confirmed', 'attended', 'completed', 'failed', 'cancelled'])->default('registered');
            $table->timestamp('registered_at')->useCurrent();
            $table->timestamps();

            $table->unique(['session_id', 'member_id']);
        });

        Schema::create('training_attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrolment_id')->constrained('training_enrolments')->cascadeOnDelete();
            $table->foreignId('session_id')->constrained('training_sessions')->cascadeOnDelete();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->date('attendance_date');
            $table->enum('status', ['present', 'absent', 'excused'])->default('present');
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('method', ['manual', 'qr_scan'])->default('manual');
            $table->timestamps();

            $table->unique(['session_id', 'member_id', 'attendance_date']);
        });

        Schema::create('training_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('training_courses')->cascadeOnDelete();
            $table->foreignId('session_id')->nullable()->constrained('training_sessions')->nullOnDelete();
            $table->string('title');
            $table->decimal('total_marks', 6, 2)->default(100.00);
            $table->decimal('passing_marks', 6, 2)->default(70.00);
            $table->json('questions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('training_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrolment_id')->constrained('training_enrolments')->cascadeOnDelete();
            $table->foreignId('assessment_id')->constrained('training_assessments')->cascadeOnDelete();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->decimal('score', 6, 2);
            $table->decimal('percentage', 5, 2);
            $table->enum('status', ['pass', 'fail'])->default('pass');
            $table->foreignId('evaluated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('certificate_id')->nullable()->constrained('certificates')->nullOnDelete();
            $table->timestamp('evaluated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_results');
        Schema::dropIfExists('training_assessments');
        Schema::dropIfExists('training_attendance');
        Schema::dropIfExists('training_enrolments');
        Schema::dropIfExists('training_sessions');
        Schema::dropIfExists('training_modules');
        Schema::dropIfExists('training_courses');
        Schema::dropIfExists('training_programmes');
    }
};
