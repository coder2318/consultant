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
        \Illuminate\Support\Facades\DB::statement("alter table applications rename column text to description");
        \Illuminate\Support\Facades\DB::statement("alter table applications rename column name to title");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Illuminate\Support\Facades\DB::statement("alter table applications rename column description to text");
        \Illuminate\Support\Facades\DB::statement("alter table applications rename column title to name");

    }
};
