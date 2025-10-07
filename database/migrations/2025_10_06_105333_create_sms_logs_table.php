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
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();
            $table->string('provider');
            $table->string('to');
            $table->text('body');
            $table->enum('status',['queued','sent','failed'])->default('queued');
            $table->string('external_id')->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();

            $table->index(['provider','status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_logs');
    }
};
