<?php namespace HON\HonCuratorReview\Components;

use HON\HonCuratorReview\Models\Question;
use HON\HonCuratorReview\Models\Review;
use HON\HonCuratorReview\Models\App;
use HON\HonCuratorReview\Models\Service as ServiceModel;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use October\Rain\Exception\ValidationException;
use RainLab\User\Facades\Auth;

class ServiceDetails extends \Cms\Classes\ComponentBase
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
        $this->addCss('/plugins/hon/honcuratorreview/assets/css/common.css');
        $this->addCss('/plugins/hon/honcuratorreview/assets/css/serviceDetails.css');

        // TODO Better fail catch
        $this->page['service'] = $this->service = ServiceModel::findOrFail($this->property('id'));
        $this->page['remainingPlatforms'] = $this->service->filterExistingPlatforms();
    }

    public function onAddApp()
    {
        self::onRun();

        if (!Auth::check()) return;
        $acceptedPlatforms = array();
        $erroredPlatforms = array();
        // At least one valid platform
        foreach (Input::get('platforms') as $platform => $url) {
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

        foreach ($acceptedPlatforms as $plaform => $url) {
            $app = new App(['url' => $url, 'plat_id' => $plaform, 'serv_id' => $this->service->id]);
            if($app->validate()) {
                $app->save();
            }
        }
    }

    public function onSaveReview()
    {
        if (!Auth::check()) return;
        $user = Auth::getUser();

        $review = Review::where('app_id', Input::get('app_id'))->where('user_id', $user->id)->first();
        if (!$review) {
            $review = new Review();
        }

        $review->fill(Input::all());
        $review->user_id = $user->id;

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
        $review = Review::findOrFail(Input::get('review_id'));
        $question = Question::findOrFail(Input::get('question_id'));
        $response = Input::get('response');
        // Prevent already exist
        if (!$review->questions()->where('q_id', $question->id)->first()) {
            $review->questions()->attach([$question->id => ['value' =>  $response]]);
        }

        return $this->onRequestQuestion();
    }

}