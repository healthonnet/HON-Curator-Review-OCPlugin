<?php namespace HON\HonCuratorReview\Tests;

use HON\HonCuratorReview\Models\Platform;
use HON\HonCuratorReview\Models\Service;
use PluginTestCase;

class PlatformTest extends PluginTestCase
{
    public function testModelAll()
    {
        $platforms = Platform::all();
        $this->assertEquals(3, count($platforms), "We should have 3 platforms after seeding");
    }

    public function testRelations()
    {
        $service = Service::create([
            'name' => 'Super Service'
        ]);
        $this->assertInstanceOf('HON\HonCuratorReview\Models\Service', $service);
        $this->assertNotNull($service, "it should return the service object");
        if ($service->id) {
            $superUrl = 'https://superservice.website';
            $platform = Platform::find(1);
            $platform->services()->attach($service->id, ['url' => $superUrl]);

            $this->assertInstanceOf('HON\HonCuratorReview\Models\Platform', $platform);

            if ($platform) {
                $this->assertEquals($superUrl, $platform->services[0]->pivot->url, 'it Should have url as a pivot value');
            }
        }
    }
}