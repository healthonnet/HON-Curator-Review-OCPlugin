<?php namespace HON\HonCuratorReview\Tests;

use HON\HonCuratorReview\Models\Responsetype;
use HON\HonCuratorReview\Models\Question;
use PluginTestCase;

class ResponsetypeTest extends PluginTestCase
{
    public function  testRelations()
    {
        $responsetype = Responsetype::find(1);

        $this->assertInstanceOf('HON\HonCuratorReview\Models\Responsetype', $responsetype);

        $question = Question::create([
            'responsetype_id' => $responsetype->id,
            'question' => 'this.is.my.question.key.message'
        ]);
        $question2 = Question::create([
            'responsetype_id' => $responsetype->id,
            'question' => 'this.is.my.second.question.key.message'
        ]);

        $this->assertInstanceOf('HON\HonCuratorReview\Models\Question', $question);
        $this->assertInstanceOf('HON\HonCuratorReview\Models\Question', $question2);

        $this->assertEquals($question->id, $responsetype->questions()->find($question->id)->id);
        $this->assertEquals($question2->id, $responsetype->questions()->find($question2->id)->id);
    }
}