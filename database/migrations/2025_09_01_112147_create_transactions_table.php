<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['incoming', 'outgoing']);
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('dealer_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('incoming_id')->nullable()->constrained('incomings')->onDelete('cascade');
            $table->foreignId('outgoing_id')->nullable()->constrained('outgoings')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->date('date');
            $table->text('description')->nullable();
            $table->text('remark')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
