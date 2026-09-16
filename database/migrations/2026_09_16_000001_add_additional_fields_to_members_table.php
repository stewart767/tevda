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
        Schema::table('members', function (Blueprint $table) {
            if (!Schema::hasColumn('members', 'is_founding_member')) {
                $table->boolean('is_founding_member')->default(false)->after('ev_sector');
            }
            if (!Schema::hasColumn('members', 'next_of_kin_name')) {
                $table->string('next_of_kin_name')->nullable()->after('passport_photo_path');
            }
            if (!Schema::hasColumn('members', 'next_of_kin_relationship')) {
                $table->string('next_of_kin_relationship')->nullable()->after('next_of_kin_name');
            }
            if (!Schema::hasColumn('members', 'next_of_kin_phone')) {
                $table->string('next_of_kin_phone')->nullable()->after('next_of_kin_relationship');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $columnsToDrop = [];
            if (Schema::hasColumn('members', 'is_founding_member')) {
                $columnsToDrop[] = 'is_founding_member';
            }
            if (Schema::hasColumn('members', 'next_of_kin_name')) {
                $columnsToDrop[] = 'next_of_kin_name';
            }
            if (Schema::hasColumn('members', 'next_of_kin_relationship')) {
                $columnsToDrop[] = 'next_of_kin_relationship';
            }
            if (Schema::hasColumn('members', 'next_of_kin_phone')) {
                $columnsToDrop[] = 'next_of_kin_phone';
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
