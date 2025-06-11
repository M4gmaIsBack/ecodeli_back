<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->float('montant');
            $table->unsignedBigInteger('id_utilisateur');
            $table->unsignedBigInteger('id_prestation')->nullable();
            $table->unsignedBigInteger('id_livraison')->nullable();
            $table->integer('status')->default(0);

            $table->foreign('id_utilisateur')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_prestation')->references('id')->on('annonce_prestataires')->onDelete('set null');
            $table->foreign('id_livraison')->references('id')->on('livraisons')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};

