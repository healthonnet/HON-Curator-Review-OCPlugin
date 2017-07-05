<?php namespace HON\HonCuratorReview\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateHonHoncuratorreviewQuestionsPlatforms extends Migration
{
    public function up()
    {
        Schema::create('hon_honcuratorreview_questions_platforms', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('q_id');
            $table->integer('p_id');
            $table->primary(['q_id','p_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('hon_honcuratorreview_questions_platforms');
    }
}
