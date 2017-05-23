<?php namespace HON\HonCuratorReview\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateHonHoncuratorreviewActivitiesQuestions extends Migration
{
    public function up()
    {
        Schema::create('hon_honcuratorreview_activities_questions', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('q_id');
            $table->integer('a_id');
            $table->primary(['q_id','a_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('hon_honcuratorreview_activities_questions');
    }
}
