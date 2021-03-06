<?php namespace HON\HonCuratorReview\Models;

use Model;

/**
 * Model
 */
class Responsetype extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    /*
     * Validation
     */
    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'hon_honcuratorreview_responsetypes';

    /**
     * @array hasMany Models relations
     */
    public $hasMany = [
        'questions' => ['HON\HonCuratorReview\Models\Question', 'delete'=>'true'] // responsetype_id
    ];
}