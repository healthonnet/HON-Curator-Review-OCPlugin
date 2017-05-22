<?php namespace HON\HonCuratorReview\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateHonHoncuratorreviewServices extends Migration
{
    public function up()
    {
        Schema::rename('hon_honcuratorreview_service', 'hon_honcuratorreview_services');
        Schema::table('hon_honcuratorreview_services', function($table)
        {
            $table->increments('id')->unsigned(false)->change();
        });
    }
    
    public function down()
    {
        Schema::rename('hon_honcuratorreview_services', 'hon_honcuratorreview_service');
        Schema::table('hon_honcuratorreview_service', function($table)
        {
            $table->increments('id')->unsigned()->change();
        });
    }
}
