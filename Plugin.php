<?php namespace HON\HonCuratorReview;

use System\Classes\PluginBase;
use RainLab\User\Models\User as UserModel;
use HON\HonCuratorUser\Models\Activity;


class Plugin extends PluginBase
{

    public $require = ['RainLab.User', 'HON.HonCuratorUser', 'Rainlab.Translate'];

    public function registerComponents()
    {
        return [
            'HON\HonCuratorReview\Components\ListApps' => 'listApps',
            'HON\HonCuratorReview\Components\ListServices' => 'listServices'
        ];
    }

    public function registerSettings()
    {
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
        // Add extension's relations and attributes.
        UserModel::extend(function($model) {
            $model->hasMany['reviews'] = 'HON\HonCuratorReview\Models\Review'; // user_id
            $model->hasMany['services'] = ['HON\HonCuratorReview\Models\Service', 'key' => 'owner_id']; // user_id

            $fillables = $model->getFillable();
            $model->fillable($fillables);
        });

        Activity::extend(function ($model) {
            $model->belongsToMany['questions'] = [
                'HON\HonCuratorReview\Models\Question',
                'table'      => 'hon_honcuratorreview_activities_questions',
                'key'        => 'a_id',
                'otherKey'   => 'q_id'
            ];
        });
    }
}
