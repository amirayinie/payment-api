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
        Schema::create('credit_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained()->cascadeOnDelete();
            $table->string('card_number',16)->unique();
            $table->char('cvv2', 4)->nullable();
            $table->unsignedTinyInteger('expire_month')->nullable();
            $table->unsignedSmallInteger('expire_year')->nullable();
            $table->unsignedBigInteger('balance_toman')->default(0);
            $table->enum('status',['active','blocked'])->default('active');
            $table->timestamps();

            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_cards');
    }
};
