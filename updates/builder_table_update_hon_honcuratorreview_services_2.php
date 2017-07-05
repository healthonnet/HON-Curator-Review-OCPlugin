<?php namespace HON\HonCuratorReview\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateHonHoncuratorreviewServices2 extends Migration
{
    public function up()
    {
        Schema::table('hon_honcuratorreview_services', function($table)
        {
            $table->text('description')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('hon_honcuratorreview_services', function($table)
        {
            $table->dropColumn('description');
        });
    }
}
