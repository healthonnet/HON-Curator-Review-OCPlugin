<?php namespace HON\HonCuratorReview\Tests;

use Illuminate\Database\Eloquent\Model;
use RainLab\User\Models\User;
use HON\HonCuratorReview\Models\Review;
use HON\HonCuratorReview\Models\Service;
use PluginTestCase;

class UserTest extends PluginTestCase
{

    public function testReviewRelation()
    {
        User::extend(function($model) {
            $model->hasMany['reviews'] = 'HON\HonCuratorReview\Models\Review'; // user_id
            $model->hasMany['services'] = ['HON\HonCuratorReview\Models\Service', 'key' => 'owner_id']; // user_id
        });

        $user = User::create([
            'email'                 => 'test@test.com',
            'password'              => 'test',
            'password_confirmation' => 'test',
            'first_name'            => 'test',
            'last_name'             => 'test',
            'permissions'           => [],
            'is_superuser'          => true,
            'is_activated'          => true
        ]);

        $this->assertInstanceOf('RainLab\User\Models\User', $user);

        $review = Review::create([
            'user_id' => 1,
            'service_id' => 1
        ]);

        $review2 = Review::create([
            'user_id' => 1,
            'service_id' => 2
        ]);

        $this->assertInstanceOf('HON\HonCuratorReview\Models\Review', $review);
        $this->assertInstanceOf('HON\HonCuratorReview\Models\Review', $review2);

        $user->reviews()->saveMany([$review, $review2]);
        $this->assertEquals(2, count($user->reviews));

        $this->assertEquals(1, $user->reviews[0]->id);
        $this->assertEquals(2, $user->reviews[1]->id);
        $this->assertEquals(1, $user->reviews[0]->user_id);
        $this->assertEquals(1, $user->reviews[1]->user_id);
        $this->assertEquals(1, $user->reviews[0]->service_id);
        $this->assertEquals(2, $user->reviews[1]->service_id);


    }

    public function testServiceRelation()
    {
        User::extend(function($model) {
            $model->hasMany['reviews'] = 'HON\HonCuratorReview\Models\Review'; // user_id
            $model->hasMany['services'] = ['HON\HonCuratorReview\Models\Service', 'key' => 'owner_id']; // user_id
        });

        $user = User::create([
            'email'                 => 'test@test.com',
            'password'              => 'test',
            'password_confirmation' => 'test',
            'first_name'            => 'test',
            'last_name'             => 'test',
            'permissions'           => [],
            'is_superuser'          => true,
            'is_activated'          => true
        ]);

        $this->assertInstanceOf('RainLab\User\Models\User', $user);


        $service = Service::create([
            'name' => 'Super Service'
        ]);

        $service2 = Service::create([
            'name' => 'Super Service 2'
        ]);

        $this->assertInstanceOf('HON\HonCuratorReview\Models\Service', $service);
        $this->assertInstanceOf('HON\HonCuratorReview\Models\Service', $service2);

        $user->services()->saveMany([$service, $service2]);
        $this->assertEquals(2, count($user->services));

        $this->assertEquals(1, $user->services[0]->id);
        $this->assertEquals(2, $user->services[1]->id);
        $this->assertEquals('Super Service', $user->services[0]->name);
        $this->assertEquals('Super Service 2', $user->services[1]->name);


    }
}