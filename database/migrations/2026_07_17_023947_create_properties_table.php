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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('account_code');
            $table->foreignId('acct_no')->constrained('users','id')->restrictOnDelete();
            $table->foreignId('type_id')->constrained('property_types','id')->restrictOnDelete();
            $table->string('lot_no')->nullable();
            $table->foreignId('brgy_id')->constrained('barangays','id')->restrictOnDelete();
            $table->date('date_registered');
            $table->string('status');

            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
