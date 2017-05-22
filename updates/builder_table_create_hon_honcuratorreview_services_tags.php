<?php namespace HON\HonCuratorReview\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateHonHoncuratorreviewServicesTags extends Migration
{
    public function up()
    {
        Schema::create('hon_honcuratorreview_services_tags', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('service_id');
            $table->integer('tag_id');
            $table->primary(['service_id','tag_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('hon_honcuratorreview_services_tags');
    }
}
