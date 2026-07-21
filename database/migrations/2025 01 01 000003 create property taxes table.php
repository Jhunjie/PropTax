<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_taxes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('tax_year');
            $table->decimal('amount_due', 10, 2);
            $table->decimal('penalties', 10, 2)->default(0);
            $table->decimal('total_payable', 10, 2);
            $table->date('due_date');
            $table->string('status')->default('unpaid'); // 'unpaid' | 'overdue' | 'paid'
            $table->timestamps();

            $table->unique(['property_id', 'tax_year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_taxes');
    }
};