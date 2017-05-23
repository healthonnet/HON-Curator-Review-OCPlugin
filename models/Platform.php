<?php namespace HON\HonCuratorReview\Models;

use Model;

/**
 * Model
 */
class Platform extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /*
     * Validation
     */
    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'hon_honcuratorreview_platforms';
}