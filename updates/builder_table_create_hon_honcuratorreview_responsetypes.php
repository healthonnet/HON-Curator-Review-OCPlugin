<?php namespace HON\HonCuratorReview\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateHonHoncuratorreviewResponsetypes extends Migration
{
    public function up()
    {
        Schema::create('hon_honcuratorreview_responsetypes', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('label');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('hon_honcuratorreview_responsetypes');
    }
}
