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
        Schema::create('event_lists', function (Blueprint $table) {
            $table->increments('id'); // Инкрементальный порядковый номер заказа
            $table->unsignedInteger('event_id')->unique(); // Уникальный ид события
            $table->string('event_date', 20); // Дата и время на которое были куплены билеты
            $table->unsignedInteger('ticket_adult_price'); // Цена взрослого билета на момент покупки
            $table->unsignedInteger('ticket_adult_quantity'); // Количество купленных взрослых билетов в этом заказе
            $table->unsignedInteger('ticket_kid_price'); // Цена детского билета на момент покупки
            $table->unsignedInteger('ticket_kid_quantity'); // Количество купленных детских билетов в этом заказе
            $table->string('barcode', 120)->unique(); // Уникальный штрих код заказа
            $table->unsignedInteger('equal_price'); // Общая сумма заказа
            $table->timestamps(); // Дата создания заказа: created_at и updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_lists');
    }
};
