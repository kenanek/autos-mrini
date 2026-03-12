<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained()->cascadeOnDelete();
            $table->foreignId('car_model_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->year('year');
            $table->decimal('price', 12, 2);
            $table->decimal('old_price', 12, 2)->nullable();
            $table->integer('mileage')->default(0);
            $table->enum('fuel_type', ['gasoline', 'diesel', 'electric', 'hybrid', 'lpg'])->default('gasoline');
            $table->enum('transmission', ['manual', 'automatic', 'semi-automatic'])->default('manual');
            $table->string('body_type')->nullable(); // sedan, suv, hatchback, coupe, etc.
            $table->string('color')->nullable();
            $table->string('interior_color')->nullable();
            $table->integer('engine_size')->nullable(); // in cc
            $table->integer('horsepower')->nullable();
            $table->integer('doors')->nullable();
            $table->integer('seats')->nullable();
            $table->string('vin', 17)->nullable()->unique();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->enum('condition', ['new', 'used', 'certified'])->default('used');
            $table->enum('status', ['available', 'sold', 'reserved', 'draft'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->string('featured_image')->nullable();
            $table->integer('views_count')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'is_featured']);
            $table->index(['brand_id', 'car_model_id']);
            $table->index('year');
            $table->index('price');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
