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
        Schema::create('branch_selections', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid(column: 'branch_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignUuid(column: 'branch_address_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignUuid(column: 'branch_officer_id')->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_selections');
    }
};
