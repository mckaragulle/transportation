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
        Schema::create('branch_banks', function (Blueprint $table) {
            $table->uuid('id')->primary();
        });
        Schema::table('branch_banks', function (Blueprint $table) {
            $table->foreignUuid('branch_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignUuid('bank_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('iban')->unique()->index();
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
        Schema::dropIfExists('branch_banks');
    }
};
