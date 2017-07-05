<?php namespace HON\HonCuratorReview\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateHonHoncuratorreviewTags extends Migration
{
    public function up()
    {
        Schema::table('hon_honcuratorreview_tags', function($table)
        {
            $table->string('type');
        });
    }
    
    public function down()
    {
        Schema::table('hon_honcuratorreview_tags', function($table)
        {
            $table->dropColumn('type');
        });
    }
}
