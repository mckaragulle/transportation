<?php

use App\Models\Account;
use App\Models\AccountType;
use App\Models\AccountTypeCategory;
use App\Models\Dealer;
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
            $table->id();
            $table->foreignIdFor(Dealer::class)->nullable()->constrained()->cascadeOnDelete();
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
            $table->foreignIdFor(AccountTypeCategory::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(AccountType::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Account::class)->nullable()->constrained()->cascadeOnDelete();
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
