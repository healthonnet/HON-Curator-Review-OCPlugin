<?php namespace HON\HonCuratorReview\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateHonHoncuratorreviewTags extends Migration
{
    public function up()
    {
        Schema::create('hon_honcuratorreview_tags', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('hon_honcuratorreview_tags');
    }
}
