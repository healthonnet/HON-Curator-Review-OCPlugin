<?php namespace HON\HonCuratorReview\Tests;

use HON\HonCuratorReview\Models\Platform;
use HON\HonCuratorReview\Models\App;
use HON\HonCuratorReview\Models\Review;
use HON\HonCuratorReview\Models\Service;
use PluginTestCase;

class AppTest extends PluginTestCase
{

    public function testReviewRelation()
    {

        $app = App::create([
            'url' => 'http://super.service',
            'serv_id' => 1,
            'plat_id' => 1
        ]);

        $review = Review::create([
            'user_id' => 1,
            'app_id' => $app->id,
            'global_rate' => 4,
            'global_comment' => 'test',
            'title' => 'test'
        ]);

        $review2 = Review::create([
            'user_id' => 2,
            'app_id' => $app->id,
            'global_rate' => 4,
            'global_comment' => 'test',
            'title' => 'test'
        ]);

        $this->assertInstanceOf('HON\HonCuratorReview\Models\Review', $review);
        $this->assertInstanceOf('HON\HonCuratorReview\Models\Review', $review2);
        $this->assertInstanceOf('HON\HonCuratorReview\Models\App', $app);

        $this->assertEquals(2, count($app->reviews));
        $this->assertEquals($review->user_id, $app->reviews[0]->user_id);
        $this->assertEquals($review2->user_id, $app->reviews[1]->user_id);
    }

    public function testServiceAndPlatformRelations()
    {
        Service::create([
            'name' => 'Super Service'
        ]);

        $service = Service::find(1);
        $this->assertInstanceOf('HON\HonCuratorReview\Models\Service', $service);

        $platform = Platform::find(1);
        $this->assertInstanceOf('HON\HonCuratorReview\Models\Platform', $platform);

        $app = App::create([
            'url' => 'http://super.service',
            'serv_id' => 1,
            'plat_id' => 1
        ]);

        $this->assertInstanceOf('HON\HonCuratorReview\Models\App', $app);

        $this->assertEquals($app->service, $service);
        $this->assertEquals($app->platform, $platform);
    }

    public function testNameAccessor()
    {
        Service::create([
            'name' => 'Super Service'
        ]);

        $app = App::create([
            'url' => 'http://super.service',
            'serv_id' => 1,
            'plat_id' => 1
        ]);

        $this->assertInstanceOf('HON\HonCuratorReview\Models\App', $app);
        $this->assertEquals($app->name, 'Super Service ( android )');
    }

}