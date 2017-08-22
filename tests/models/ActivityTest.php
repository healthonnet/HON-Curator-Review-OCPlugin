<?php namespace HON\HonCuratorReview\Tests;

use HON\HonCuratorUser\Models\Activity;
use HON\HonCuratorReview\Models\Question;
use PluginTestCase;

class ActivityTest extends PluginTestCase
{

    public function testRelations()
    {
        Activity::extend(function ($model) {
            $model->belongsToMany['questions'] = [
                'HON\HonCuratorReview\Models\Question',
                'table'      => 'hon_honcuratorreview_activities_questions',
                'key'        => 'a_id',
                'otherKey'   => 'q_id'
            ];
        });

        $question = Question::create([
            'responsetype_id' => 1,
            'question' => 'this.is.my.question.key.message'
        ]);

        $question2 = Question::create([
            'responsetype_id' => 1,
            'question' => 'this.is.my.second.question.key.message'
        ]);

        $activity = Activity::create([
            'label' => 'test',
            'description' => 'test',
            'level' => 1
        ]);

        $this->assertInstanceOf('HON\HonCuratorReview\Models\Question', $question);
        $this->assertInstanceOf('HON\HonCuratorReview\Models\Question', $question2);
        $this->assertInstanceOf('HON\HonCuratorUser\Models\Activity', $activity);


        $activity->questions()->attach([$question->id, $question2->id]);

        $this->assertEquals(2, count($activity->questions));
        $this->assertEquals('this.is.my.question.key.message', $activity->questions[0]->question);
        $this->assertEquals('this.is.my.second.question.key.message', $activity->questions[1]->question);

    }
}