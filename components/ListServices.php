<?php namespace HON\HonCuratorReview\Components;

use HON\HonCuratorReview\Models\Platform;
use HON\HonCuratorReview\Models\Service;
use HON\HonCuratorReview\Models\Tag;
use Illuminate\Support\Facades\Input;

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
        $this->addCss('/plugins/hon/honcuratorreview/assets/css/listServices.css');
        $this->page['platforms'] = Platform::all();

        $this->page['search'] = Input::get('search');
        $this->page['platform'] = Input::get('platform');

        // Clean unknown tags
        $filters = Tag::cleanInput(Input::get('filters'));
        $this->page['filters'] = $filters;
        $this->page['services'] = Service::searchWithPagination($filters, $this->page['search'], $this->page['platform']);

        // TODO SORT by translation (not key)
        $tags = Tag::all()->sortBy('name');
        $this->page['tags'] = array();
        $typeTag = array();
        foreach ($tags as $tag) {
            $typeTag[$tag->type][] = $tag;
        }
        $this->page['tags'] = $typeTag;

    }

}