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
        Schema::table('categories', function (Blueprint $table) {
            $table->string('icon')->nullable();
        });

        Schema::table('resumes', function (Blueprint $table) {
            $table->integer('skill_ids')->nullable();
        });

        \Illuminate\Support\Facades\DB::statement("alter table resumes alter column skill_ids type integer[] using array[skill_ids]::INTEGER[];");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('icon');
        });
        Schema::table('resumes', function (Blueprint $table) {
            $table->dropColumn('skill_ids');
        });

    }
};
