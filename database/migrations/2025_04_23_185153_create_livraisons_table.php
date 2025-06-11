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
        Schema::create('livraisons', function (Blueprint $table) {
            $table->id();
            $table->string('type_livraison');
            $table->text('description');
            $table->float('poids');
            $table->string('taille');
            $table->string('adresse_depart');
            $table->string('adresse_arrivee');
            $table->float('prix');
            $table->text('instructions')->nullable();
            $table->integer('status')->default(0);
            $table->foreignId('id_client')->constrained('clients')->onDelete('cascade');
            $table->foreignId('id_livreur')->constrained('livreurs')->onDelete('cascade')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livraisons');
    }
};
