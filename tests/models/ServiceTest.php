<?php namespace HON\HonCuratorReview\Tests;

use HON\HonCuratorReview\Models\Platform;
use HON\HonCuratorReview\Models\Tag;
use RainLab\User\Models\User;
use HON\HonCuratorReview\Models\App;
use HON\HonCuratorReview\Models\Review;
use HON\HonCuratorReview\Models\Service;
use PluginTestCase;

class ServiceTest extends PluginTestCase
{

    public function testUserRelation()
    {
        User::extend(function($model) {
            $model->hasMany['reviews'] = 'HON\HonCuratorReview\Models\Review'; // user_id
            $model->hasMany['services'] = ['HON\HonCuratorReview\Models\Service', 'key' => 'owner_id'];
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

        $service = Service::create([
            'name' => 'Super Service',
            'owner_id' => 1
        ]);
        $this->assertInstanceOf('HON\HonCuratorReview\Models\Service', $service);

        $this->assertEquals($user, $service->user);

    }

    public function testPlatformRelations()
    {
        $superUrl = 'https://superservice.website';

        $service = Service::create([
            'name' => 'Super Service'
        ]);
        $this->assertInstanceOf('HON\HonCuratorReview\Models\Service', $service);

        $platform = Platform::find(1);
        $platform2 = Platform::find(2);
        $this->assertInstanceOf('HON\HonCuratorReview\Models\Platform', $platform);
        $this->assertInstanceOf('HON\HonCuratorReview\Models\Platform', $platform2);

        $service->platforms()->attach([$platform->id => ['url' => $superUrl], $platform2->id => ['url' => $superUrl]]);

        $this->assertEquals(2, count($service->platforms), 'it Should have two platforms');
        $this->assertEquals($superUrl, $service->platforms[0]->pivot->url, 'it Should have url as a pivot value');
        $this->assertEquals($superUrl, $service->platforms[1]->pivot->url, 'it Should have url as a pivot value');
        $this->assertInstanceOf('HON\HonCuratorReview\Models\PlatformServicePivot', $service->platforms[1]->pivot);

        $apps = App::all();
        $this->assertCount(2,$apps);

    }

    public function testTagRelations()
    {
        $service = Service::create([
            'name' => 'Super Service'
        ]);
        $this->assertInstanceOf('HON\HonCuratorReview\Models\Service', $service);

        $tag = Tag::create([
            'name' => 'Awesome'
        ]);
        $tag2 = Tag::create([
            'name' => 'Awesome 2'
        ]);
        $this->assertInstanceOf('HON\HonCuratorReview\Models\Tag', $tag);

        $service->tags()->attach([$tag->id, $tag2->id]);

        $this->assertEquals(2, count($service->tags));
        $this->assertEquals('Awesome', $service->tags[0]->name);
        $this->assertEquals('Awesome 2', $service->tags[1]->name);

    }

    public function testReviewRelation()
    {
        $service = Service::create([
            'name' => 'Super Service'
        ]);

        $this->assertInstanceOf('HON\HonCuratorReview\Models\Service', $service);

        $app = App::create([
            'url' => 'http://super.service',
            'serv_id' => 1,
            'plat_id' => 1
        ]);

        $review = Review::create([
            'user_id' => 1,
            'app_id' => 1,
            'global_rate' => 4,
            'global_comment' => 'test',
            'title' => 'test'
        ]);

        $review2 = Review::create([
            'user_id' => 2,
            'app_id' => 1,
            'global_rate' => 4,
            'global_comment' => 'test',
            'title' => 'test'
        ]);

        $this->assertInstanceOf('HON\HonCuratorReview\Models\Review', $review);
        $this->assertInstanceOf('HON\HonCuratorReview\Models\Review', $review2);
        $this->assertInstanceOf('HON\HonCuratorReview\Models\App', $app);

        $this->assertEquals(2, count($service->reviews));
        $this->assertEquals($review->user_id, $service->reviews[0]->user_id);
        $this->assertEquals($review2->user_id, $service->reviews[1]->user_id);
    }

    public function testAppRelation()
    {
        $service = Service::create([
            'name' => 'Super Service'
        ]);

        $this->assertInstanceOf('HON\HonCuratorReview\Models\Service', $service);

        $app = App::create([
            'url' => 'http://super.service',
            'serv_id' => 1,
            'plat_id' => 1
        ]);
        $app2 = App::create([
            'url' => 'http://super.service2',
            'serv_id' => 1,
            'plat_id' => 2
        ]);

        $this->assertInstanceOf('HON\HonCuratorReview\Models\App', $app);
        $this->assertInstanceOf('HON\HonCuratorReview\Models\App', $app2);

        $this->assertEquals(2, count($service->apps));
        $this->assertEquals($app->url, $service->apps[0]->url);
        $this->assertEquals($app2->url, $service->apps[1]->url);
    }

}