<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('method')->nullable(); // cash|transfer
            $table->string('status')->default('unpaid'); // unpaid|paid
            $table->unsignedInteger('amount')->default(0);
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'method']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
