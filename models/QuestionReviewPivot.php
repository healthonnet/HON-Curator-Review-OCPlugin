<?php namespace HON\HonCuratorReview\Models;

use October\Rain\Database\Pivot;

/**
 * Question-Review Pivot Model
 */
class QuestionReviewPivot extends Pivot
{

    use \October\Rain\Database\Traits\Validation;

    /**
     * @var array Rules
     */
    public $rules = [
        'value' => 'required',
    ];

}