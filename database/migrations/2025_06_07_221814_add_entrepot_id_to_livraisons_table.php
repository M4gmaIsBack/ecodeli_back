<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEntrepotIdToLivraisonsTable extends Migration
{
    public function up()
    {
        Schema::table('livraisons', function (Blueprint $table) {
            $table->unsignedBigInteger('entrepot_id')->nullable();

            $table->foreign('entrepot_id')
                ->references('id')
                ->on('entrepots')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('livraisons', function (Blueprint $table) {
            $table->dropForeign(['entrepot_id']);
            $table->dropColumn('entrepot_id');
        });
    }
}

