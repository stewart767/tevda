<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->enum('category', [
                'announcements',
                'training',
                'opportunities',
                'events',
                'articles',
                'media_statements'
            ])->default('announcements');
            $table->string('featured_image')->nullable();
            $table->text('summary');
            $table->longText('content');
            $table->string('author_name')->default('TEVDA Secretariat');
            $table->date('publication_date');
            $table->boolean('is_published')->default(true);
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->timestamps();

            $table->index('slug');
            $table->index('category');
            $table->index('is_published');
            $table->index('publication_date');
        });

        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->date('event_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('venue');
            $table->string('region')->default('Dar es Salaam');
            $table->text('description');
            $table->string('speaker')->nullable();
            $table->integer('capacity')->nullable();
            $table->string('poster_image')->nullable();
            $table->string('registration_link')->nullable();
            $table->enum('status', ['upcoming', 'ongoing', 'completed', 'cancelled'])->default('upcoming');
            $table->boolean('is_featured')->default(false);
            $table->timestamps();

            $table->index('slug');
            $table->index('event_date');
            $table->index('status');
        });

        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->enum('category', [
                'constitution_and_governance',
                'membership',
                'training',
                'projects',
                'policies',
                'reports',
                'forms'
            ])->default('reports');
            $table->string('version')->default('1.0');
            $table->date('effective_date')->nullable();
            $table->string('approving_authority')->default('TEVDA National Council');
            $table->string('file_path');
            $table->unsignedBigInteger('file_size')->default(0);
            $table->string('file_type', 20)->default('pdf');
            $table->enum('visibility', ['public', 'members_only'])->default('public');
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('downloads_count')->default(0);
            $table->timestamps();

            $table->index('slug');
            $table->index('category');
            $table->index('visibility');
        });

        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('phone');
            $table->string('email');
            $table->string('region')->nullable();
            $table->string('district')->nullable();
            $table->enum('reason', [
                'membership',
                'training',
                'opportunities',
                'partnerships',
                'projects',
                'branches',
                'complaints',
                'general_enquiry'
            ])->default('general_enquiry');
            $table->text('message');
            $table->boolean('consent_given')->default(true);
            $table->enum('status', ['new', 'read', 'replied', 'archived'])->default('new');
            $table->text('reply_notes')->nullable();
            $table->foreignId('handled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('handled_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('reason');
        });

        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('complaint_number')->unique();
            $table->enum('category', [
                'fraud',
                'misconduct',
                'discrimination',
                'unsafe_practices',
                'misuse_of_name',
                'other'
            ])->default('fraud');
            $table->text('description');
            $table->string('evidence_file_path')->nullable();
            $table->string('reporter_name')->nullable();
            $table->string('reporter_phone')->nullable();
            $table->string('reporter_email')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->enum('priority', ['normal', 'medium', 'high', 'urgent'])->default('normal');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', [
                'submitted',
                'under_review',
                'assigned',
                'investigation',
                'resolved',
                'closed'
            ])->default('submitted');
            $table->text('internal_notes')->nullable();
            $table->text('resolution_summary')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();

            $table->index('complaint_number');
            $table->index('status');
            $table->index('category');
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('category')->default('General');
            $table->string('question');
            $table->text('answer');
            $table->integer('order_number')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('group')->default('general'); // site, contact, fees, certificates, sms, email, security
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, textarea, boolean, json, file
            $table->string('description')->nullable();
            $table->boolean('is_public')->default(false);
            $table->timestamps();

            $table->index(['group', 'key']);
        });

        Schema::create('notifications_custom', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('message');
            $table->string('link')->nullable();
            $table->enum('type', ['info', 'success', 'warning', 'danger'])->default('info');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'is_read']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications_custom');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('complaints');
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('resources');
        Schema::dropIfExists('events');
        Schema::dropIfExists('news');
    }
};
