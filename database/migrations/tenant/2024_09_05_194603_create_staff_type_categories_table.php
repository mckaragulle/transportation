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
        Schema::create('staff_type_categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('target')->default('all');
            $table->boolean('status')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('staff_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('staff_type_category_id')->nullable()->constrained()->cascadeOnDelete();
        });

        Schema::table('staff_types', function (Blueprint $table) {
            $table->foreignUuid('staff_type_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->boolean('status')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('staffs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('id_number');
            $table->string('name');
            $table->string('surname')->nullable();
            $table->string('slug');
            $table->string('phone1')->nullable();
            $table->string('phone2')->nullable();
            $table->string('email')->nullable();
            $table->string('archive_number')->nullable();
            $table->text('detail')->nullable();
            $table->string('filename')->nullable();
            $table->boolean('status')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('staff_type_category_staff_type_staff', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('staff_type_category_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignUuid('staff_type_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignUuid('staff_id')->nullable()
                ->references('id')->on('staffs')
                ->constrained()->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_type_category_staff_type_staff');
        Schema::dropIfExists('staffs');
        Schema::dropIfExists('staff_types');
        Schema::dropIfExists('staff_type_categories');
    }
};
