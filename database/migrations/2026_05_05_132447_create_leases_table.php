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
        Schema::create('leases', function (Blueprint $table) {
          $table->id();
          $table->foreignId('property_id')->constrained()->restrictOnDelete();
          $table->foreignId('tenant_id')->constrained('users');
          $table->enum('status', ['pending', 'active', 'ended', 'terminated'])->default('pending');
          $table->decimal('rent_amount', 10, 2);
          $table->date('start_date');
          $table->date('end_date')->nullable();
          $table->timestamp('terminated_at')->nullable();
          $table->text('termination_notes')->nullable();
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leases');
    }
};
