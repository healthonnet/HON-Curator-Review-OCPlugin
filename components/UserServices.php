<?php namespace HON\HonCuratorReview\Components;

use HON\HonCuratorReview\Models\Service;
use RainLab\User\Models\User;

class UserServices extends ServiceModal
{
    public $service;

    public function componentDetails()
    {
        return [
            'name' => 'UserServices',
            'description' => 'Displays a review slider'
        ];
    }

    public function defineProperties()
    {
        return [
            'userId' => [
                'title'             => 'userId',
                'description'       => '(optional) user id review flow',
                'default'           => 0,
                'type'              => 'integer',
            ]
        ];
    }

    public function onRun()
    {
        $this->addCss('/plugins/hon/honcuratorreview/assets/css/common.css');
        $userId = $this->property('userId');
        $query = Service::query()->where('owner_id', $userId);
        $this->page['user_services'] = $query->get();
        $this->page['service_owner'] = User::find($userId);
    }

}