<?php namespace HON\HonCuratorReview\Updates;

use HON\HonCuratorReview\Models\Responsetype;
use October\Rain\Database\Updates\Seeder;

class SeedResponsetypesTable extends Seeder
{
    public function run()
    {
        Responsetype::create([
            'label' => 'checkbox',
        ]);

        Responsetype::create([
            'label' => 'radio',
        ]);

        Responsetype::create([
            'label' => 'textarea',
        ]);
    }
}
