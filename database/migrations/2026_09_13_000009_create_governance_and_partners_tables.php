<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('governance_bodies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('level')->default('national'); // national, zonal, regional, district, ward, branch
            $table->integer('order_number')->default(0);
            $table->timestamps();
        });

        Schema::create('leaders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('governance_body_id')->nullable()->constrained('governance_bodies')->nullOnDelete();
            $table->string('name');
            $table->string('position');
            $table->string('photo_path')->nullable();
            $table->text('biography')->nullable();
            $table->text('qualifications')->nullable();
            $table->text('sector_experience')->nullable();
            $table->text('responsibilities')->nullable();
            $table->string('official_office_contact')->nullable(); // info@tevda.or.tz or official line
            $table->string('term_period')->nullable();
            $table->boolean('is_founding_leader')->default(false);
            $table->string('founding_position')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('order_number')->default(0);
            $table->timestamps();

            $table->index('is_founding_leader');
            $table->index('is_active');
        });

        Schema::create('committees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('mandate')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('committee_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('committee_id')->constrained('committees')->cascadeOnDelete();
            $table->string('name');
            $table->string('position_in_committee');
            $table->foreignId('leader_id')->nullable()->constrained('leaders')->nullOnDelete();
            $table->foreignId('member_id')->nullable()->constrained('members')->nullOnDelete();
            $table->date('appointed_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('category', [
                'government_and_authorities',
                'financial_institutions',
                'ev_companies',
                'colleges_and_research',
                'development_organisations',
                'employers_and_logistics',
                'investors',
                'other'
            ])->default('other');
            $table->string('logo_path')->nullable();
            $table->text('description')->nullable();
            $table->string('website_url')->nullable();
            $table->string('collaboration_area')->nullable();
            $table->enum('status', ['active', 'strategic_partner', 'past'])->default('active');
            $table->date('start_date')->nullable();
            $table->boolean('is_featured')->default(true);
            $table->integer('order_number')->default(0);
            $table->timestamps();

            $table->index('status');
            $table->index('category');
        });

        Schema::create('partnership_enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('organisation_name');
            $table->string('category')->nullable();
            $table->string('contact_person');
            $table->string('email');
            $table->string('phone');
            $table->string('collaboration_interests')->nullable();
            $table->text('message');
            $table->enum('status', ['new', 'under_review', 'contacted', 'accepted', 'archived'])->default('new');
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partnership_enquiries');
        Schema::dropIfExists('partners');
        Schema::dropIfExists('committee_members');
        Schema::dropIfExists('committees');
        Schema::dropIfExists('leaders');
        Schema::dropIfExists('governance_bodies');
    }
};
