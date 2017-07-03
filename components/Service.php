<?php namespace HON\HonCuratorReview\Components;

use HON\HonCuratorReview\Models\Review;
use HON\HonCuratorReview\Models\Service as ServiceModel;
use Illuminate\Support\Facades\Input;

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

    public function onSaveReview()
    {
        $review = Review::where('app_id', Input::get('app_id'))->where('user_id', Input::get('user_id'))->first();
        if (!$review)
            $review = new Review();

        $review->fill(Input::all());
        if ($review->validate()) {
            $review->save();
            return $review;
        }
    }

    public function onRemoveReview()
    {
        $review = Review::findOrFail(Input::get('review_id'));
        $review->delete();
    }

}