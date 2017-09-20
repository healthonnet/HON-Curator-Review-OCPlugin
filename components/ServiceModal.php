<?php namespace HON\HonCuratorReview\Components;

use HON\HonCuratorReview\Models\App;
use HON\HonCuratorReview\Models\Service as ServiceModel;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use October\Rain\Exception\ValidationException;
use RainLab\User\Facades\Auth;

class ServiceModal extends ServiceDetails
{
    public $service;

    public function componentDetails()
    {
        return [
            'name' => 'ServiceButton',
            'description' => 'Displays a button with modals workflow.'
        ];
    }


    public function onSaveService()
    {
        if (!Auth::check()) { return; }

        $user = Auth::getUser();
        $inputs = Input::all();
        $acceptedPlatforms = array();
        $erroredPlatforms = array();
        // At least one valid platform
        foreach ($inputs['platforms'] as $platform => $url) {
            $validator = Validator::make(
                [
                    'plat_id' => $platform,
                    'url' => $url
                ],
                [
                    'plat_id' => 'required|exists:hon_honcuratorreview_platforms,id',
                    'url' => 'required|unique:hon_honcuratorreview_services_platforms|url',
                ]
            );

            if ($validator->fails()) {
                $erroredPlatforms['platform['. $platform .']'] = $validator->messages()->first();
            } else {
                $acceptedPlatforms[$platform] = $url;
            }
        }
        if (empty($acceptedPlatforms)) {
            throw new ValidationException($erroredPlatforms);
        }

        if (Input::get('owner')) {
            $inputs['owner_id'] = $user->id;
        }
        $inputs['creator_id'] = $user->id;

        $service = new ServiceModel();
        $service->fill($inputs);
        if ($service->validate()) {
            $service->save();
            foreach ($acceptedPlatforms as $plaform => $url) {
                $app = new App(['url' => $url, 'plat_id' => $plaform, 'serv_id' => $service->id]);
                if($app->validate()) {
                    $app->save();
                }
            }
        }
        $this->page['service'] = $service;
        $this->page['target'] = Input::get('target');
        $this->page['apps'] = $service->filterReviewedAppsBy($user);
        $this->page['user'] = $user;
    }

}