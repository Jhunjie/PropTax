<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Registration no longer collects the resident's full name — it is
     * populated later from the linked property record's `name_of_account`
     * once an admin links the account (see UserEmailUpdate). Until then,
     * a newly registered/pending user has no name on file.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->nullable(false)->default('')->change();
        });
    }
};
