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
        Schema::create('specification_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('specification_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('specification_groups')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('unit')->nullable(); // e.g. 'GB', 'GHz', 'Watt', 'mm', 'Hz'
            $table->string('input_type')->default('text'); // text, number, select
            $table->boolean('is_filterable')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index('group_id');
            $table->index('is_filterable');
        });

        Schema::create('product_specifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('attribute_id')->constrained('specification_attributes')->cascadeOnDelete();
            $table->text('value');
            $table->timestamps();

            $table->unique(['product_id', 'attribute_id']);
            $table->index('product_id');
            $table->index('attribute_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_specifications');
        Schema::dropIfExists('specification_attributes');
        Schema::dropIfExists('specification_groups');
    }
};
