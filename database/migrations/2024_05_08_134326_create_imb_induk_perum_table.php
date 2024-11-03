<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('imb_induk_perum', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location');
            $table->string('building_type');
            $table->decimal('area', 10, 2);
            $table->string('permit_number');
            $table->date('permit_date');
            $table->decimal('establishment_fee', 10, 2);
            $table->decimal('inspection_fee', 10, 2);
            $table->decimal('legal_fees', 10, 2);
            $table->decimal('total_payment', 10, 2);
            $table->date('payment_date');
            $table->string('payer_name');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imb_induk_perum');
    }
};
