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
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_category_id')->constrained()->cascadeOnDelete();
            // Number as printed on the menu; a string so that entries like "12a" stay possible.
            $table->string('number', 10)->nullable();
            $table->string('name_de');
            $table->string('name_en');
            $table->string('description_de')->nullable();
            $table->string('description_en')->nullable();
            $table->unsignedInteger('price_cents');
            $table->boolean('is_spicy')->default(false);
            // Allergen/additive codes as printed on the menu, e.g. ["7", "A"].
            $table->json('allergens')->nullable();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
