<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('fee_type'); // membership_registration, annual_subscription, training_fee, certificate_reissue, id_card_replacement, other
            $table->foreignId('category_id')->nullable()->constrained('membership_categories')->nullOnDelete();
            $table->decimal('amount', 12, 2)->default(0.00);
            $table->string('currency', 10)->default('TZS');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_configurable')->default(true);
            $table->timestamps();
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('member_id')->nullable()->constrained('members')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 10)->default('TZS');
            $table->string('purpose'); // Membership Registration, Annual Fee, Training: Course Name, etc.
            $table->enum('status', ['unpaid', 'paid', 'cancelled', 'waived'])->default('unpaid');
            $table->date('due_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('invoice_number');
            $table->index('status');
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_reference')->unique();
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->nullOnDelete();
            $table->foreignId('member_id')->nullable()->constrained('members')->nullOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 10)->default('TZS');
            $table->enum('payment_method', ['mobile_money', 'bank_transfer', 'cash', 'online_card', 'other'])->default('mobile_money');
            $table->string('transaction_reference')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->string('proof_of_payment_path')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('payment_reference');
            $table->index('transaction_reference');
            $table->index('status');
        });

        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_number')->unique();
            $table->foreignId('payment_id')->constrained('payments')->cascadeOnDelete();
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->nullOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 10)->default('TZS');
            $table->timestamp('issued_at');
            $table->string('receipt_pdf_path')->nullable();
            $table->timestamps();

            $table->index('receipt_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receipts');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('fees');
    }
};
