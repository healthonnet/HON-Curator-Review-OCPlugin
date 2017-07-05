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

    /**
     * @array belongsToMany Models relations
     */
    public $belongsToMany = [

        'questions' => [
            'HON\HonCuratorReview\Models\Question',
            'table'      => 'hon_honcuratorreview_questions_platforms',
            'key'        => 'p_id',
            'otherKey'   => 'q_id'
        ],

        'services' => [
            'HON\HonCuratorReview\Models\Service',
            'table'      => 'hon_honcuratorreview_services_platforms',
            'key'        => 'plat_id',
            'otherKey'   => 'serv_id',
            'pivot'      => ['url', 'deleted_at'],
            'pivotModel' => 'HON\HonCuratorReview\Models\PlatformServicePivot',
            'timestamps' => true
        ] // uses Service's model relation params
    ];

    /**
     * @array hasMany Models relations
     */
    public $hasMany = [
        'apps' => ['HON\HonCuratorReview\Models\App', 'key' => 'plat_id']
    ];
}