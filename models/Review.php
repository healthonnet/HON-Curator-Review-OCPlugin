<?php namespace HON\HonCuratorReview\Models;

use Model;

/**
 * Model
 */
class Review extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];
    protected $fillable = ['user_id', 'service_id'];

    /*
     * Validation
     */
    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'hon_honcuratorreview_reviews';

    /**
     * @array belongsTo Models relations
     */
    public $belongsTo = [
        'service' => 'HON\HonCuratorReview\Models\Service', // service_id
        'user' => 'RainLab\User\Models\User' // user_id
    ];

    /**
     * @array belongsToMany Models relations
     */
    public $belongsToMany = [
        'questions' => [
            'HON\HonCuratorReview\Models\Question',
            'table'      => 'hon_honcuratorreview_reviews_questions',
            'key'        => 'r_id',
            'otherKey'   => 'q_id',
            'pivot'      => ['value', 'deleted_at'],
            'timestamps' => true
        ]
    ];
}