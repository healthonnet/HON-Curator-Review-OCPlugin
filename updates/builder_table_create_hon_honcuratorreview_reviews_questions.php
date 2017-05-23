<?php namespace HON\HonCuratorReview\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateHonHoncuratorreviewReviewsQuestions extends Migration
{
    public function up()
    {
        Schema::create('hon_honcuratorreview_reviews_questions', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('r_id');
            $table->integer('q_id');
            $table->text('value')->nullable();
            $table->primary(['r_id','q_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('hon_honcuratorreview_reviews_questions');
    }
}
