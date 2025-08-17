<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
    $table->id();
    $table->string('code')->unique();
    $table->decimal('amount', 8, 2);
    $table->enum('type', ['fixed', 'percentage']);
    $table->dateTime('valid_from');
    $table->dateTime('valid_to');
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};