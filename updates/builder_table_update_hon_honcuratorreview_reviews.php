<?php namespace HON\HonCuratorReview\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateHonHoncuratorreviewReviews extends Migration
{
    public function up()
    {
        Schema::table('hon_honcuratorreview_reviews', function($table)
        {
            $table->renameColumn('service_id', 'app_id');
        });
    }
    
    public function down()
    {
        Schema::table('hon_honcuratorreview_reviews', function($table)
        {
            $table->renameColumn('app_id', 'service_id');
        });
    }
}
