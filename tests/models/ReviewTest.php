<?php namespace HON\HonCuratorReview\Tests;

use Illuminate\Database\Eloquent\Model;
use RainLab\User\Models\User;
use HON\HonCuratorReview\Models\Review;
use HON\HonCuratorReview\Models\App;
use HON\HonCuratorReview\Models\Service;
use HON\HonCuratorReview\Models\Question;
use PluginTestCase;

class ReviewTest extends PluginTestCase
{

    public function testUserRelation()
    {
        User::extend(function($model) {
            $model->hasMany['reviews'] = 'HON\HonCuratorReview\Models\Review'; // user_id
            $model->hasMany['services'] = ['HON\HonCuratorReview\Models\Service', 'key' => 'owner_id']; // user_id
        });

        User::create([
            'email'                 => 'test@test.com',
            'password'              => 'test',
            'password_confirmation' => 'test',
            'first_name'            => 'test',
            'last_name'             => 'test',
            'permissions'           => [],
            'is_superuser'          => true,
            'is_activated'          => true
        ]);

        // Fetch created model
        $user = User::findByEmail('test@test.com');

        $this->assertInstanceOf('RainLab\User\Models\User', $user);

        $review = Review::create([
            'user_id' => 1,
            'app_id' => 1,
            'global_rate' => 4,
            'global_comment' => 'test',
            'title' => 'test'
        ]);

        $this->assertInstanceOf('HON\HonCuratorReview\Models\Review', $review);

        $this->assertEquals($user, $review->user);

    }

    public function testServiceAndAppRelation()
    {
        Service::create([
            'name' => 'Super Service'
        ]);

        App::create([
            'url' => 'http://super.service',
            'serv_id' => 1,
            'plat_id' => 1
        ]);

        // Fetch created model
        $service = Service::find(1);
        $app = App::find(1);

        $this->assertInstanceOf('HON\HonCuratorReview\Models\Service', $service);
        $this->assertInstanceOf('HON\HonCuratorReview\Models\App', $app);

        $review = Review::create([
            'user_id' => 1,
            'app_id' => 1,
            'global_rate' => 4,
            'global_comment' => 'test',
            'title' => 'test'
        ]);

        $this->assertInstanceOf('HON\HonCuratorReview\Models\Review', $review);

        $this->assertEquals($app, $review->app);
        $this->assertEquals($service, $review->app->service);

    }

    public function testQuestionRelation()
    {
        $question = Question::create([
            'responsetype_id' => 1,
            'question' => 'this.is.my.question.key.message'
        ]);
        $question2 = Question::create([
            'responsetype_id' => 1,
            'question' => 'this.is.my.second.question.key.message'
        ]);
        $this->assertInstanceOf('HON\HonCuratorReview\Models\Question', $question);
        $this->assertInstanceOf('HON\HonCuratorReview\Models\Question', $question2);

        $review = Review::create([
            'user_id' => 1,
            'app_id' => 1,
            'global_rate' => 4,
            'global_comment' => 'test',
            'title' => 'test'
        ]);

        $this->assertInstanceOf('HON\HonCuratorReview\Models\Review', $review);

        $review->questions()->attach([$question->id => ['value' => 'test'], $question2->id => ['value' => 42]]);
        $review = $review->fresh();

        $this->assertEquals(2, count($review->questions));
        $this->assertEquals(2, $review->questions_count[0]->count);
        $this->assertEquals('test', $review->questions[0]->pivot->value);
        $this->assertInstanceOf('HON\HonCuratorReview\Models\QuestionReviewPivot', $review->questions[0]->pivot);
        $this->assertEquals(42, $review->questions[1]->pivot->value);
    }



}