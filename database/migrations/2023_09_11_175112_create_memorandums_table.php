<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memorandums', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('emisor_id')->nullable();
            $table->foreign('emisor_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('receptor_id')->nullable();
            $table->foreign('receptor_id')->references('id')->on('users')->onDelete('cascade');
            $table->text('content')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('memorandums');
    }
};
