<?php namespace HON\HonCuratorReview\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateHonHoncuratorreviewReviews3 extends Migration
{
    public function up()
    {
        Schema::table('hon_honcuratorreview_reviews', function($table)
        {
            $table->string('global_comment', 255)->default('');
        });
    }
    
    public function down()
    {
        Schema::table('hon_honcuratorreview_reviews', function($table)
        {
            $table->dropColumn('global_comment');
        });
    }
}
