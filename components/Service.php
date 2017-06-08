<?php namespace HON\HonCuratorReview\Components;

use HON\HonCuratorReview\Models\Service as ServiceModel;

class Service extends \Cms\Classes\ComponentBase
{
    public $service;

    public function componentDetails()
    {
        return [
            'name' => 'Service',
            'description' => 'Displays a detail view of a service.'
        ];
    }

    public function onRun()
    {
        // TODO Better fail catch
        $this->page['service'] = $this->service = ServiceModel::findOrFail($this->property('id'));
    }

}