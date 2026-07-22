<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_properties', function (Blueprint $table) {
            $table->id();
            $table->string('acct_email_address');
            $table->unsignedBigInteger('acct_no');
            $table->string('name_of_account');
            $table->string('account_code');
            $table->string('type');
            $table->string('lot_no')->nullable();
            $table->string('brgy_name');
            $table->string('lgu');
            $table->date('date_of_registration');
            $table->string('status');
            $table->timestamps();
            $table->index(['acct_email_address']);
            $table->foreign(['acct_email_address'])->references(['email'])->on('users');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('user_properties');
    }
};