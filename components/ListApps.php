<?php namespace HON\HonCuratorReview\Components;

use HON\HonCuratorReview\Models\App;

class ListApps extends \Cms\Classes\ComponentBase
{
    public $apps;

    public function componentDetails()
    {
        return [
            'name' => 'List Apps',
            'description' => 'Displays a collection of apps.'
        ];
    }

    public function onRun()
    {
        $this->page['apps'] = $this->apps = App::paginate(1);
    }

}