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
        Schema::create('commercants', function (Blueprint $table) {
            $table->id();
            $table->string('nom_entreprise');
            $table->string('adresse');
            $table->string('numero_siret')->unique();
            $table->string('email_responsable')->unique();
            $table->string('telephone');
            $table->string('iban')->nullable();
            $table->string('site_web')->nullable();
            $table->string('image')->nullable();
            $table->foreignId('id_utilisateur')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commercants');
    }
};
