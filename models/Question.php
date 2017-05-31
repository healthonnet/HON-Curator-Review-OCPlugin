<?php namespace HON\HonCuratorReview\Models;

use Model;

/**
 * Model
 */
class Question extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];
    protected $fillable = ['question', 'responsetype_id'];

    /**
     * Softly implement the TranslatableModel behavior.
     */
    public $implement = ['@RainLab.Translate.Behaviors.TranslatableModel'];

    /**
     * @var array Attributes that support translation, if available.
     */
    public $translatable = ['question'];


    /*
     * Validation
     */
    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'hon_honcuratorreview_questions';

    /**
     * @array belongsTo Models relations
     */
    public $belongsTo = [
        'responsetype' => 'HON\HonCuratorReview\Models\Responsetype' // responsetype_id
    ];


    /**
     * @array belongsToMany Models relations
     */
    public $belongsToMany = [
        'activities' => [
            'HON\HonCuratorUser\Models\Activity',
            'table'      => 'hon_honcuratorreview_activities_questions',
            'key'        => 'q_id',
            'otherKey'   => 'a_id'
        ],
        'reviews' => [
            'HON\HonCuratorReview\Models\Review',
            'table'      => 'hon_honcuratorreview_reviews_questions',
            'key'        => 'q_id',
            'otherKey'   => 'r_id',
            'pivot'      => ['value', 'deleted_at'],
            'pivotModel' => 'HON\HonCuratorReview\Models\QuestionReviewPivot',
            'timestamps' => true
        ]
    ];
}