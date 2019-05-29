<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreviouslyDeletedAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = config('previously-deleted.table');

        Schema::create($tableName, function (Blueprint $table) {

            $table->increments('id');

            $table->string('table');

            $table->string('attribute');
            $table->text('value');

            $table->string('method')->nullable();

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
        $tableName = config('previously-deleted.table');

        Schema::drop($tableName);
    }
}
