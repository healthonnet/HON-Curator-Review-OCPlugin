<?php namespace HON\HonCuratorReview\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateHonHoncuratorreviewServicesPlatforms extends Migration
{
    public function up()
    {
        Schema::create('hon_honcuratorreview_services_platforms', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('serv_id');
            $table->integer('plat_id');
            $table->unique(['serv_id', 'plat_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('hon_honcuratorreview_services_platforms');
    }
}
