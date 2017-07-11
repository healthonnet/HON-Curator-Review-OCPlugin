<?php namespace HON\HonCuratorReview\Components;

use HON\HonCuratorReview\Models\Service as ServiceModel;
use Illuminate\Support\Facades\Input;
use RainLab\User\Facades\Auth;

class ReviewButton extends ServiceDetails
{
    public $service;

    public function componentDetails()
    {
        return [
            'name' => 'ReviewButton',
            'description' => 'Displays a button with modals workflow.'
        ];
    }

    public function onRun()
    {
        $request = Input::get('modal_search', Input::get('search'));
        if ($request) {
            $this->page['modal_services'] = ServiceModel::search(array(), $request);
        }
    }

    public function onStartReview()
    {
        if (!Auth::check()) { return; }

        $id = Input::get('service');
        $this->page['service'] = ServiceModel::find($id);
        $this->page['target'] = Input::get('target');
        $this->page['apps'] = $this->page['service']->filterReviewedAppsBy(Auth::getUser());
    }

    public function onSearchService()
    {
        $request = Input::get('modal_search', Input::get('search'));
        if ($request) {
            $this->page['modal_services'] = ServiceModel::search(array(), $request);
        }
    }

}