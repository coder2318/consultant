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
        \Illuminate\Support\Facades\DB::statement('alter table categories alter column name type jsonb USING name::jsonb');
        Schema::table('applications', function (Blueprint $table) {
            $table->integer('views')->nullable();
            $table->enum('type', ['public', 'private'])->default('public');
            $table->boolean('is_visible')->default(true);
            $table->date('expired_date')->nullable();
        });

        Schema::table('profiles', function (Blueprint $table) {
            $table->boolean('is_consultant')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
