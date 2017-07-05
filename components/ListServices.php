<?php namespace HON\HonCuratorReview\Components;

use HON\HonCuratorReview\Models\Platform;
use HON\HonCuratorReview\Models\Service;
use HON\HonCuratorReview\Models\Tag;

class ListServices extends \Cms\Classes\ComponentBase
{
    public $services;
    public $platforms;

    public function componentDetails()
    {
        return [
            'name' => 'List Services',
            'description' => 'Displays a collection of services.'
        ];
    }

    public function onRun()
    {
        $this->page['platforms'] = Platform::all();
        $this->page['tags'] = Tag::all();
        $this->page['services'] = $this->services = Service::searchWithPagination();
    }

}