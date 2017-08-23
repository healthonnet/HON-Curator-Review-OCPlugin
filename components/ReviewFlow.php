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

    public function onRun()
    {
        $this->addCss('/plugins/hon/honcuratorreview/assets/css/common.css');
        $this->page['review_flow'] = Review::query()->latest()->take(6)->get();
    }

}