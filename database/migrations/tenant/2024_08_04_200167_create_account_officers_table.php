<?php

use App\Models\Account;
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
        Schema::create('account_officers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('account_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('number')->index();
            $table->string('name')->index();
            $table->string('slug')->nullable()->index();
            $table->string('surname')->index()->nullable();
            $table->string('title')->index()->nullable();
            $table->string('phone1')->index();
            $table->string('phone2')->index()->nullable();
            $table->string('email')->index()->nullable();
            $table->text('detail')->nullable();
            $table->text('files')->nullable();
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
        Schema::dropIfExists('account_officers');
    }
};
