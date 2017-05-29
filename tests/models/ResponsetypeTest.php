<?php namespace HON\HonCuratorReview\Tests;

use HON\HonCuratorReview\Models\Responsetype;
use HON\HonCuratorReview\Models\Question;
use PluginTestCase;

class ResponsetypeTest extends PluginTestCase
{
    public function testModelAll()
    {
        $responsetypes = Responsetype::all();
        $this->assertEquals(3, count($responsetypes), "We should have 3 platforms after seeding");
    }

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

        $questionsFromRelation = $responsetype->questions;
        $this->assertEquals(2, count($questionsFromRelation));
    }
}