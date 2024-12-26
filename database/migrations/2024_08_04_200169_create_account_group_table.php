<?php

use App\Models\Account;
use App\Models\Group;
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
        Schema::create('account_group', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('account_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignUuid('group_id')->nullable()->constrained()->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_group');
    }
};
