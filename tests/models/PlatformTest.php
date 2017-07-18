<?php namespace HON\HonCuratorReview\Tests;

use HON\HonCuratorReview\Models\App;
use HON\HonCuratorReview\Models\Platform;
use HON\HonCuratorReview\Models\Question;
use HON\HonCuratorReview\Models\Service;
use PluginTestCase;

class PlatformTest extends PluginTestCase
{
    public function testModelAll()
    {
        $platforms = Platform::all();
        $this->assertEquals(3, count($platforms), "We should have 3 platforms after seeding");
    }

    public function testServiceAndAppRelation()
    {
        $service = Service::create([
            'name' => 'Super Service',
            'description' => 'description',
        ]);
        $this->assertInstanceOf('HON\HonCuratorReview\Models\Service', $service);
        $this->assertNotNull($service, "it should return the service object");

        $superUrl = 'https://superservice.website';

        $platform = Platform::find(1);
        $platform->services()->attach($service->id, ['url' => $superUrl]);
        $platform->save();

        $app = App::create([
            'url' => 'http://super.service',
            'serv_id' => 2,
            'plat_id' => 1
        ]);

        $platform = $platform->fresh();

        $this->assertInstanceOf('HON\HonCuratorReview\Models\Platform', $platform);
        $this->assertInstanceOf('HON\HonCuratorReview\Models\App', $app);
        $this->assertInstanceOf('HON\HonCuratorReview\Models\PlatformServicePivot', $platform->services[0]->pivot);
        $this->assertInstanceOf('HON\HonCuratorReview\Models\PlatformServicePivot', $platform->services[0]->pivot);
        $this->assertEquals($superUrl, $platform->services[0]->pivot->url, 'it Should have url as a pivot value');

        $this->assertCount(2,$platform->apps);
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

        $platform->questions()->attach($question->id);

        $this->assertEquals($platform->id, $platform->questions[0]->id);

        $platform->save();

        // Refresh and add another one.
        $question2 = Question::create([
            'responsetype_id' => 2,
            'question' => 'this.is.my.question.key.message2'
        ]);

        $platform = Platform::find(1);
        $platform->questions()->attach($question2->id);

        $this->assertEquals($question2->id, $platform->questions[1]->id);
    }
}