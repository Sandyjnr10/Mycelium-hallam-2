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
        Schema::create('team_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->index();
            $table->string('handle');
            $table->string('handle_id')->nullable(); // Twitter uses an id for each user
            $table->string('website');
            $table->date('contacted')->nullable();
            $table->boolean('engagement')->nullable();
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
        Schema::dropIfExists('team_contacts');
    }
};
