<?php namespace HON\HonCuratorReview\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateHonHoncuratorreviewTags2 extends Migration
{
    public function up()
    {
        Schema::table('hon_honcuratorreview_tags', function($table)
        {
            $table->string('type', 255)->default('label')->change();
        });
    }
    
    public function down()
    {
        Schema::table('hon_honcuratorreview_tags', function($table)
        {
            $table->string('type', 255)->default(null)->change();
        });
    }
}
