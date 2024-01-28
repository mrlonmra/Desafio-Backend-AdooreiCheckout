<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Cria a tabela 'sales'
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 10, 2);
            $table->timestamps();
            $table->softDeletes();  //Adiciona suporte para soft deletes
        });

        // Tabela pivot para a relação muitos-para-muitos entre products e sales
        Schema::create('product_sale', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('sale_id');
            $table->integer('amount')->default(1);
            $table->timestamps();
            // Adiciona chaves estrangeiras e regras de exclusão em cascata
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
        });
    }
};
