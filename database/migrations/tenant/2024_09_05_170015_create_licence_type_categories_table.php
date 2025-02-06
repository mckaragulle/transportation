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
        Schema::create('licence_type_categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->boolean('status')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('licence_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('licence_type_category_id')->nullable()->constrained()->cascadeOnDelete();
        });

        Schema::table('licence_types', function (Blueprint $table) {
            $table->foreignUuid('licence_type_id')->after('licence_type_category_id')->nullable()->constrained()->cascadeOnDelete();
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
        Schema::dropIfExists('licence_types');
        Schema::dropIfExists('licence_type_categories');
    }
};
