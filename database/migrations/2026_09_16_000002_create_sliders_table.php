<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('subtitle')->nullable();
            $table->string('image_path');
            $table->string('link_url')->nullable();
            $table->integer('order_number')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('is_active');
            $table->index('order_number');
        });

        // Seed default 3 slides so admin has working slides out of the box
        DB::table('sliders')->insert([
            [
                'title' => 'Commercial Electric Vehicles in Tanzania',
                'subtitle' => 'Smart battery swap and clean transport fleet in Dar es Salaam',
                'image_path' => 'images/slider/slide-1.jpg',
                'link_url' => null,
                'order_number' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'EV Diagnostics & Technician Training',
                'subtitle' => 'Professional skills and accredited high-voltage automotive certifications',
                'image_path' => 'images/slider/slide-2.jpg',
                'link_url' => null,
                'order_number' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Clean Electric Commercial Transport Fleet',
                'subtitle' => 'Sustainable e-mobility, public transit, and green transit loans',
                'image_path' => 'images/slider/slide-3.jpg',
                'link_url' => null,
                'order_number' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};
