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
        Schema::create('hgs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('number');
            $table->string('filename')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamp('buyed_at', precision: 0);
            $table->timestamp('canceled_at', precision: 0)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('hgs_type_category_hgs_type_hgs', function (Blueprint $table) {
            $table->foreignUuid('hgs_type_category_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignUuid('hgs_type_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignUuid('hgs_id')->nullable()->constrained()->cascadeOnDelete();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hgs_type_category_hgs_type_hgs');
        Schema::dropIfExists('hgs');
    }
};
