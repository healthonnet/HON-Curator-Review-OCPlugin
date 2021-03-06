<?php namespace HON\HonCuratorReview\Components;

use HON\HonCuratorReview\Models\Platform;
use HON\HonCuratorReview\Models\Service as ServiceModel;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use RainLab\User\Facades\Auth;

class ReviewModal extends ServiceModal
{
    public $service;

    public function componentDetails()
    {
        return [
            'name' => 'ReviewModal',
            'description' => 'Displays a button with modals workflow.'
        ];
    }

    public function onRun()
    {
        $this->addCss('/plugins/hon/honcuratorreview/assets/css/common.css');
        $this->addCss('/plugins/hon/honcuratorreview/assets/css/customforms.css');
        $this->addJs('/plugins/hon/honcuratorreview/assets/js/textCounter.js');
        $request = Input::get('modal_search', Input::get('search'));
        if ($request) {
            $this->page['modal_request'] = $request;
            $this->page['modal_services'] = ServiceModel::search(array(), $request);
        }
    }

    public function onStartReview()
    {
        if (!Auth::check()) { return Redirect::to('/signin'); }

        $id = Input::get('service');
        $this->page['user'] = Auth::getUser();
        $this->page['service'] = ServiceModel::find($id);
        $this->page['target'] = Input::get('target');
        $this->page['apps'] = $this->page['service']->filterReviewedAppsBy(Auth::getUser());
    }

    public function onStartService()
    {
        if (!Auth::check()) { return Redirect::to('/signin');}
        $this->page['target'] = Input::get('target');
        $this->page['platforms'] = Platform::all();
        $this->page['user'] = Auth::getUser();
    }

    public function onSearchService()
    {
        $request = Input::get('modal_search', Input::get('search'));
        if ($request) {
            $this->page['modal_request'] = $request;
            $this->page['modal_services'] = ServiceModel::search(array(), $request);
        }
    }

}