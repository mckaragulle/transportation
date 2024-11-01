<?php

use App\Models\Account;
use App\Models\City;
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
        Schema::create('account_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Account::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(City::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(District::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Neighborhood::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Locality::class)->nullable()->constrained()->cascadeOnDelete();
            $table->string('name')->unique()->index();
            $table->string('slug')->nullable()->unique()->index();
            $table->string('phone1')->nullable()->index();
            $table->string('phone2')->nullable()->index();
            $table->string('email')->nullable()->index();
            $table->text('address1')->nullable();
            $table->text('address2')->nullable();
            $table->text('detail')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_addresses');
    }
};
