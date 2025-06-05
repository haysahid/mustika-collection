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
            $table->string('username')->unique()->after('name');
            $table->string('address')->nullable()->after('password');
            $table->string('phone')->nullable()->after('address');
            $table->string('avatar')->nullable()->after('profile_photo_path');
            $table->bigInteger('role_id')->unsigned()->after('avatar');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->timestamp('disabled_at')->nullable()->after('role_id');
            $table->softDeletes()->after('disabled_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
            $table->dropColumn('address');
            $table->dropColumn('phone');
            $table->dropColumn('avatar');
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
            $table->dropColumn('disabled_at');
            $table->dropSoftDeletes();
        });
    }
};
