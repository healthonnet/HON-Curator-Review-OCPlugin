<?php namespace HON\HonCuratorReview\Components;

use HON\HonCuratorReview\Models\Question;
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
            $this->page['review'] = $review;
            return $this->onRequestQuestion();
        }
    }

    public function onRemoveReview()
    {
        $review = Review::findOrFail(Input::get('review_id'));
        $review->delete();
    }

    public function onRequestQuestion()
    {
        $review = $this->page['review'];
        if (!$review) {
            $review = Review::findOrFail(Input::get('review_id'));
        }
        $target = Input::get('target');
        if ($target) {
            $this->page['target'] = $target;
        }

        $this->page['review'] = $review;
        $question = $review->getNewQuestion();
        $this->page['question'] = $question;
        return "ok";
    }

    public function onSaveAndRequestQuestion()
    {
        // TODO Save response
        $review = Review::findOrFail(Input::get('review_id'));
        $question = Question::findOrFail(Input::get('question_id'));
        $response = Input::get('response');

        $review->questions()->attach([$question->id => ['value' =>  $response]]);

        return $this->onRequestQuestion();
    }

}