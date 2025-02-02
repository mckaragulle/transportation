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
        Schema::create('dealer_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('dealer_type_category_id')->nullable()->constrained()->cascadeOnDelete();
        });

        Schema::table('dealer_types', function (Blueprint $table) {
            $table->foreignUuid('dealer_type_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->boolean('status')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dealer_types');
    }
};
