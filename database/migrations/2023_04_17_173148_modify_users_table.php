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
            //
            $table->string('name', 60)->change();
            $table->string('password')->nullable(true)->default(null)->change();
            $table->string('phone')->unique();
            $table->unsignedBigInteger('position_id');
            $table->string('photo');  // "/images/users/5b977ba1245cc29.jpeg"
            $table->foreign('position_id')->references('id')->on('positions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->string('name')->change();
            $table->dropColumn('phone');
            $table->dropForeign(['position_id']);
            $table->dropColumn('photo');
        });
    }
};
