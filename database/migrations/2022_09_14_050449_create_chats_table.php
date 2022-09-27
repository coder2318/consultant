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
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->integer('profile_ids');
            $table->dateTime('last_time')->nullable();
            $table->timestamps();
        });

        \Illuminate\Support\Facades\DB::statement("alter table chats alter column profile_ids type integer[] using array[profile_ids]::INTEGER[];");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chats');
    }
};
