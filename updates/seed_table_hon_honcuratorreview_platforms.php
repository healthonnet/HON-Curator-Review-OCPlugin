<?php namespace HON\HonCuratorReview\Updates;

use HON\HonCuratorReview\Models\Platform;
use October\Rain\Database\Updates\Seeder;

class SeedPlatformsTable extends Seeder
{
    public function run()
    {
        Platform::create([
            'name' => 'android',
        ]);

        Platform::create([
            'name' => 'ios',
        ]);

        Platform::create([
            'name' => 'website',
        ]);
    }
}
