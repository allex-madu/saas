<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedAtToBakeriesTable extends Migration
{
    public function up()
    {
        Schema::table('bakeries', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('bakeries', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
