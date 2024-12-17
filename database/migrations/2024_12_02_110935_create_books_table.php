<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('books', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('author');
        $table->string('bookId')->unique();
        $table->date('publicationDate');
        $table->text('description')->nullable();
        $table->string('image_path')->nullable(); // Add this line
        $table->enum('availability', ['Available', 'Borrowed', 'Reserved'])->default('Available');
        $table->foreignId('added_by')->nullable()->constrained('admins')->onDelete('set null');
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('books');
}

};
