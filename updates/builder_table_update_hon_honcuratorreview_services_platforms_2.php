<?php namespace HON\HonCuratorReview\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateHonHoncuratorreviewServicesPlatforms2 extends Migration
{
    public function up()
    {
        Schema::table('hon_honcuratorreview_services_platforms', function($table)
        {
            $table->integer('creator_id')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('hon_honcuratorreview_services_platforms', function($table)
        {
            $table->dropColumn('creator_id');
        });
    }
}
