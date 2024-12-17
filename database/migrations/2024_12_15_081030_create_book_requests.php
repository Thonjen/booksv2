<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('book_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Cascade on delete
            $table->foreignId('book_id')->constrained()->onDelete('cascade'); // Cascade on delete
            $table->enum('request_type', ['borrow', 'reserve']); // Restrict to 'borrow' or 'reserve'
            $table->date('request_date')->default(now()); // Defaults to current date
            $table->date('return_date')->nullable(); // Nullable for reservations
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Enum for status
            $table->timestamps();
        });
    }
    

    public function down()
    {
        Schema::dropIfExists('book_requests');
    }
};
