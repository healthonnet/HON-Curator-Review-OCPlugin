<?php namespace HON\HonCuratorReview\Tests;

use HON\HonCuratorReview\Models\Platform;
use HON\HonCuratorReview\Models\Review;
use HON\HonCuratorUser\Models\Activity;
use HON\HonCuratorReview\Models\Question;
use HON\HonCuratorReview\Models\Responsetype;
use PluginTestCase;

class QuestionTest extends PluginTestCase
{

    public function testResponseTypeRelation()
    {
        $responsetype = Responsetype::find(1);

        $this->assertInstanceOf('HON\HonCuratorReview\Models\Responsetype', $responsetype);
        $question = Question::create([
            'responsetype_id' => $responsetype->id,
            'question' => 'this.is.my.question.key.message'
        ]);

        $this->assertInstanceOf('HON\HonCuratorReview\Models\Question', $question);
        $responsetypeFromRelation = $question->responsetype;

        $this->assertEquals($responsetype, $responsetypeFromRelation);
    }

    public function testActivityRelation()
    {
        $question = Question::create([
            'responsetype_id' => 1,
            'question' => 'this.is.my.question.key.message'
        ]);
        $this->assertInstanceOf('HON\HonCuratorReview\Models\Question', $question);

        $activity = Activity::find(1);
        $this->assertNotNull($activity);

        $question->activities()->attach($activity->id);

        $this->assertEquals($activity->id, $question->activities[0]->id);

        $question->save();

        // Refresh and add another one.
        $question = Question::find(1);

        $activity2 = Activity::find(2);
        $this->assertNotNull($activity2);
        $question->activities()->attach($activity2->id);

        $this->assertEquals($activity2->id, $question->activities[1]->id);
    }

    public function testPlatformRelation()
    {
        $question = Question::create([
            'responsetype_id' => 1,
            'question' => 'this.is.my.question.key.message'
        ]);
        $this->assertInstanceOf('HON\HonCuratorReview\Models\Question', $question);

        $platform = Platform::find(1);
        $this->assertNotNull($platform);

        $question->platforms()->attach($platform->id);

        $this->assertEquals($platform->id, $question->platforms[0]->id);

        $question->save();

        // Refresh and add another one.
        $question = Question::find(1);

        $platform2 = Platform::find(2);
        $this->assertNotNull($platform2);
        $question->platforms()->attach($platform2->id);

        $this->assertEquals($platform2->id, $question->platforms[1]->id);
    }

    public function testReviewRelation()
    {
        $question = Question::create([
            'responsetype_id' => 1,
            'question' => 'this.is.my.question.key.message'
        ]);
        $this->assertInstanceOf('HON\HonCuratorReview\Models\Question', $question);

        $review = Review::create([
            'user_id' => 1,
            'app_id' => 1,
            'global_rate' => 4,
            'global_comment' => 'test',
            'title' => 'test'
        ]);
        $review2 = Review::create([
            'user_id' => 1,
            'app_id' => 2,
            'global_rate' => 4,
            'global_comment' => 'test',
            'title' => 'test'
        ]);

        $this->assertInstanceOf('HON\HonCuratorReview\Models\Review', $review);
        $this->assertInstanceOf('HON\HonCuratorReview\Models\Review', $review2);

        $question->reviews()->attach([$review->id => ['value' => 'test'], $review2->id => ['value' => 42]]);

        $this->assertEquals(2, count($question->reviews));
        $this->assertEquals('test', $question->reviews[0]->pivot->value);
        $this->assertInstanceOf('HON\HonCuratorReview\Models\QuestionReviewPivot', $question->reviews[0]->pivot);
        $this->assertEquals(42, $question->reviews[1]->pivot->value);
    }
}