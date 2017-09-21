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
    protected $fillable = ['user_id', 'app_id', 'global_rate', 'title', 'global_comment'];

    /*
     * Validation
     */
    public $rules = [
        'global_rate' => 'required|integer|min:0|max:5',
        'title' => 'required|min:3|max:50',
        'global_comment' => 'required|min:3|max:500',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'hon_honcuratorreview_reviews';

    /**
     * @array belongsTo Models relations
     */
    public $belongsTo = [
        'app' => 'HON\HonCuratorReview\Models\App', // app_id
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
            'pivotModel' => 'HON\HonCuratorReview\Models\QuestionReviewPivot',
            'timestamps' => true,
        ],
        'questions_count' => [
            'HON\HonCuratorReview\Models\Question',
            'table'      => 'hon_honcuratorreview_reviews_questions',
            'key'        => 'r_id',
            'otherKey'   => 'q_id',
            'count' => true
        ]
    ];

    public function getNewQuestion()
    {
        $id = $this->id;
        $platform_id = $this->app->platform->id;
        $question = $this->user->activity->questions()->whereDoesntHave('reviews', function ($query) use($id) {
            $query->whereId($id);
        })->whereHas('platforms', function ($query) use($platform_id){
            $query->whereId($platform_id);
        })->orderByRaw("RAND()")->first();
        return $question;
    }
}