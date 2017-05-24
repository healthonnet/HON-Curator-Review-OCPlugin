<?php namespace HON\HonCuratorUser\Tests;

use HON\HonCuratorReview\Models\Responsetype;
use PluginTestCase;

class ResponsetypeTest extends PluginTestCase
{
    public function testModelAll()
    {
        $responsetypes = Responsetype::all();
        $this->assertEquals(3, count($responsetypes), "We should have 3 platforms after seeding");
    }
}