<?php namespace HON\HonCuratorReview\Tests;

use HON\HonCuratorReview\Models\Tag;
use HON\HonCuratorReview\Models\Service;
use PluginTestCase;

class TagTest extends PluginTestCase
{

    public function testRelations()
    {
        $service = Service::create([
            'name' => 'Super Service',
            'description' => 'description',

        ]);
        $service2 = Service::create([
            'name' => 'Super Service 2',
            'description' => 'description',

        ]);
        $this->assertInstanceOf('HON\HonCuratorReview\Models\Service', $service);
        $this->assertInstanceOf('HON\HonCuratorReview\Models\Service', $service2);

        $tag = Tag::create([
            'name' => 'Awesome'
        ]);
        $tag = $tag->fresh();
        $this->assertInstanceOf('HON\HonCuratorReview\Models\Tag', $tag);
        $this->assertEquals('label', $tag->type);

        $tag->services()->attach([$service->id, $service2->id]);

        $this->assertEquals(2, count($tag->services));
        $this->assertEquals('Super Service', $tag->services[0]->name);
        $this->assertEquals('Super Service 2', $tag->services[1]->name);

    }
}