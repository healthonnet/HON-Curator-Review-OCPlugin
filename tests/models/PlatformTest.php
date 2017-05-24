<?php namespace HON\HonCuratorUser\Tests;

use HON\HonCuratorReview\Models\Platform;
use PluginTestCase;

class PlatformTest extends PluginTestCase
{
    public function testModelAll()
    {
        $platforms = Platform::all();
        $this->assertEquals(3, count($platforms), "We should have 3 platforms after seeding");
    }
}