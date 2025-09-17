<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('fullname');
            $table->string('email')->unique();
            $table->string('studentid')->unique();
            $table->string('courseSection');
            $table->string('phone_number')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->string('password');
            $table->enum('role', ['admin', 'user'])->default('user');
            $table->timestamp('last_login')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
