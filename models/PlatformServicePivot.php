<?php namespace HON\HonCuratorReview\Models;

use October\Rain\Database\Pivot;

/**
 * Question-Review Pivot Model
 */
class PlatformServicePivot extends Pivot
{

    use \October\Rain\Database\Traits\Validation;

    /**
     * @var array Rules
     */
    public $rules = [
        'url' => 'required|url|unique:hon_honcuratorreview_services_platforms,url',
        'creator_id' => 'integer'
    ];

}