<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'control_number')) {
                $table->string('control_number', 30)->nullable()->unique()->after('invoice_number');
            }
        });

        // Populate existing invoices with standard 12-digit Control Numbers if empty
        $invoices = DB::table('invoices')->whereNull('control_number')->get();
        $startNumber = 1000001;
        foreach ($invoices as $inv) {
            DB::table('invoices')->where('id', $inv->id)->update([
                'control_number' => '99401' . sprintf('%07d', $startNumber++)
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'control_number')) {
                $table->dropColumn('control_number');
            }
        });
    }
};
