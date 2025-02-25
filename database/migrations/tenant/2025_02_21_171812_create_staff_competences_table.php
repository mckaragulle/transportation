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
        Schema::create('staff_competences', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('staff_type_category_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignUuid('staff_type_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignUuid('staff_id')->nullable()->constrained()->cascadeOnDelete()->on('staffs');
            $table->timestamp('expiry_date_at')->nullable();
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
        //
    }
};
