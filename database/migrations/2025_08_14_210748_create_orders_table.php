<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('total_price', 8, 2);
            $table->string('status')->default('pending');
            $table->timestamps();
            $table->decimal('total_price', 8, 2)->default(0)->change();
            $table->decimal('total', 8, 2)->default(0);
            $table->unsignedBigInteger('discount_id')->nullable();
            $table->decimal('discount_total', 8, 2)->default(0);
            $table->foreign('discount_id')->references('id')->on('discounts')->onDelete('set null');

            });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
