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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_card_id')->constrained('credit_cards')->cascadeOnDelete();
            $table->foreignId('to_card_id')->constrained('credit_cards')->cascadeOnDelete();
            $table->unsignedMediumInteger('amount_toman');
            $table->enum('status',['initiated','succeeded','failed'])->default('initiated');
            $table->string('refrence')->unique();
            $table->timestamp('performed_at')->nullable();
            $table->timestamps();

            $table->index(['status','performed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
