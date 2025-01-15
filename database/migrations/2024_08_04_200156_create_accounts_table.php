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
        Schema::create('accounts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            // $table->foreignUuid('dealer_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('number');
            $table->string('name');
            $table->string('shortname');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('tax')->nullable();
            $table->string('taxoffice')->nullable();
            $table->text('detail')->nullable();
            $table->boolean('status')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('account_type_category_account_type_account', function (Blueprint $table) {
            $table->uuid('account_type_category_id')->nullable()->constrained()->cascadeOnDelete();
            $table->uuid('account_type_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignUuid('account_id')->nullable()->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_type_category_account_type_account');
        Schema::dropIfExists('accounts');
    }
};
