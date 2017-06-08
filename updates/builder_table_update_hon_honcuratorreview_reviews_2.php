<?php namespace HON\HonCuratorReview\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateHonHoncuratorreviewReviews2 extends Migration
{
    public function up()
    {
        Schema::table('hon_honcuratorreview_reviews', function($table)
        {
            $table->smallInteger('global_rate')->default(0);
            $table->string('title');
        });
    }
    
    public function down()
    {
        Schema::table('hon_honcuratorreview_reviews', function($table)
        {
            $table->dropColumn('global_rate');
            $table->dropColumn('title');
        });
    }
}
