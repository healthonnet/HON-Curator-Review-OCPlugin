<?php namespace HON\HonCuratorReview\Components;

use HON\HonCuratorReview\Models\Review;

class ReviewFlow extends ServiceModal
{
    public $service;

    public function componentDetails()
    {
        return [
            'name' => 'ReviewFlow',
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
        $query = Review::query()->latest()->take(6);
        $userId = $this->property('userId');

        if ($userId) {
            $query = Review::query()->where('user_id', $userId)->latest()->take(6);
        }

        $this->page['review_flow'] = $query->get();
    }

}