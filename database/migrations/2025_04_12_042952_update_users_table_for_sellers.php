<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateUsersTableForSellers extends Migration
{
    public function up()
    {
        // Rename 'status' to 'user_role' using raw SQL for MariaDB compatibility
        DB::statement("ALTER TABLE users CHANGE status user_role VARCHAR(255)");

        // Then modify it to ENUM
        DB::statement("ALTER TABLE users MODIFY user_role ENUM('Student', 'Lecturer') DEFAULT 'Student'");

        // Drop the 'verification_status' column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('verification_status');
        });

        // Add new 'faculty' column
        Schema::table('users', function (Blueprint $table) {
            $table->string('faculty')->nullable()->after('gender');
        });
    }

    public function down()
    {
        // Rename 'user_role' back to 'status'
        DB::statement("ALTER TABLE users CHANGE user_role status VARCHAR(255)");
        DB::statement("ALTER TABLE users MODIFY status ENUM('Student', 'Lecturer') DEFAULT 'Student'");

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('faculty');
            $table->string('verification_status')->nullable();
        });
    }
}

