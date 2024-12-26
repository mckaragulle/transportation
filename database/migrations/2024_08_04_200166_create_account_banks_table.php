<?php

use App\Models\Account;
use App\Models\Bank;
use App\Models\City;
use App\Models\Dealer;
use App\Models\District;
use App\Models\Locality;
use App\Models\Neighborhood;
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
        Schema::create('account_banks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('dealer_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignUuid('account_id')->nullable()->constrained()->cascadeOnDelete();
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
        Schema::dropIfExists('account_banks');
    }
};
