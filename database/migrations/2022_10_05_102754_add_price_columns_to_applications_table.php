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
        Schema::table('applications', function (Blueprint $table) {
            $table->decimal('price_from')->nullable();
            $table->decimal('price_to')->nullable();
            $table->string('when')->nullable();
            $table->date('when_date')->nullable();
            $table->dropColumn('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('price_from');
            $table->dropColumn('price_to');
            $table->dropColumn('when');
            $table->dropColumn('when_date');
            $table->date('date');
        });
    }
};
