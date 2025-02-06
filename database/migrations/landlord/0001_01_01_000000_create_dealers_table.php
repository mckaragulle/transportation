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
        Schema::create('dealers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('slug')->unique()->nullable();
            $table->string('number');            
            $table->string('shortname');
            $table->string('phone')->nullable();
            $table->string('tax')->nullable();
            $table->string('taxoffice')->nullable();
            $table->text('detail')->nullable();            
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->boolean('status')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('dealer_type_category_dealer_type_dealer', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('dealer_type_category_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignUuid('dealer_type_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignUuid('dealer_id')->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //        Schema::dropIfExists('departments');
        Schema::dropIfExists('dealers');
    }
};
