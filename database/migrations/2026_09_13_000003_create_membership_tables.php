<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('eligibility_criteria');
            $table->json('required_documents')->nullable();
            $table->decimal('registration_fee', 12, 2)->default(0.00);
            $table->decimal('annual_fee', 12, 2)->default(0.00);
            $table->string('fee_status_note')->default('Fee subject to official TEVDA confirmation');
            $table->boolean('is_active')->default(true);
            $table->integer('order_number')->default(0);
            $table->timestamps();
        });

        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('membership_categories');
            $table->foreignId('region_id')->nullable()->constrained('regions')->nullOnDelete();
            $table->foreignId('district_id')->nullable()->constrained('districts')->nullOnDelete();
            $table->foreignId('ward_id')->nullable()->constrained('wards')->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            
            $table->string('membership_number')->nullable()->unique();
            $table->string('full_name');
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('phone');
            $table->string('email');
            $table->string('physical_address')->nullable();
            
            // Identification
            $table->string('nida_number')->nullable();
            $table->string('driving_licence_number')->nullable();
            $table->string('licence_class')->nullable();
            $table->date('licence_expiry_date')->nullable();
            
            // Professional & EV Info
            $table->string('occupation')->nullable();
            $table->string('ev_sector')->nullable(); // e.g. Passenger transport, Cargo delivery, Fleet operation, Maintenance
            $table->string('employer')->nullable();
            $table->string('ev_experience_years')->nullable();
            $table->string('tin_number')->nullable(); // For institutional / business members
            $table->string('passport_photo_path')->nullable();

            // Status Workflow
            $table->enum('status', [
                'draft',
                'submitted',
                'payment_pending',
                'payment_confirmed',
                'under_review',
                'documents_incomplete',
                'additional_info_required',
                'approved',
                'rejected',
                'cancelled'
            ])->default('draft');
            
            $table->text('reviewer_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->date('expiry_date')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            $table->index('membership_number');
            $table->index('status');
            $table->index('phone');
            $table->index('email');
        });

        Schema::create('membership_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->string('document_type'); // nida, driving_licence, passport_photo, tin, recommendation, vehicle_reg, other
            $table->string('file_path');
            $table->string('file_name');
            $table->unsignedBigInteger('file_size')->default(0);
            $table->string('mime_type')->nullable();
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('notes')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });

        Schema::create('membership_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->string('card_number')->unique();
            $table->date('issue_date');
            $table->date('expiry_date')->nullable();
            $table->string('qr_code_path')->nullable();
            $table->json('card_data')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->enum('vehicle_type', [
                'two_wheeler',
                'three_wheeler',
                'passenger_car',
                'van',
                'minibus',
                'bus',
                'commercial_truck',
                'other'
            ])->default('three_wheeler');
            $table->string('make')->nullable();
            $table->string('model')->nullable();
            $table->string('registration_number')->nullable();
            $table->year('year_of_manufacture')->nullable();
            $table->enum('ownership_type', ['owned', 'leased', 'company_owned', 'driver_operated', 'other'])->default('driver_operated');
            $table->decimal('battery_capacity_kwh', 8, 2)->nullable();
            $table->enum('charging_type', ['ac_slow', 'dc_fast', 'battery_swap', 'dual', 'other'])->default('battery_swap');
            $table->integer('daily_average_km')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
        Schema::dropIfExists('membership_cards');
        Schema::dropIfExists('membership_documents');
        Schema::dropIfExists('members');
        Schema::dropIfExists('membership_categories');
    }
};
