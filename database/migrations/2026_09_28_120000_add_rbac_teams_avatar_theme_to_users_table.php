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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('current_team_id')->nullable()->after('email');
            $table->string('avatar')->nullable()->after('current_team_id');
            $table->string('avatar_type')->default('initials')->after('avatar'); // initials, custom, gravatar
            $table->string('theme_preference')->default('system')->after('avatar_type'); // light, dark, system
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['current_team_id', 'avatar', 'avatar_type', 'theme_preference']);
        });
    }
};
