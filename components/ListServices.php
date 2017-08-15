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
        $this->page['tags'] = Tag::with('services')->get()->sortBy(function($tag)
        {
            return $tag->services->count();
        }, SORT_REGULAR, true);
        $this->page['search'] = Input::get('search');


        // Clean unknown tags
        $filters = Tag::cleanInput(Input::get('filters'));
        $this->page['filters'] = $filters;
        $this->page['services'] = $this->services = Service::searchWithPagination($filters, $this->page['search']);
    }

}