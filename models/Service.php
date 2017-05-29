<?php namespace HON\HonCuratorReview\Models;

use Model;

/**
 * Model
 */
class Service extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];

    protected $fillable = ['name', 'owner_id'];
    /*
     * Validation
     */
    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'hon_honcuratorreview_services';

    /**
     * @array belongsToMany Models relations
     */
    public $belongsToMany = [
        'tags' => [
            'HON\HonCuratorReview\Models\Tag',
            'table'    => 'hon_honcuratorreview_services_tags', // service_id tag_id
        ],
        'platforms' => [
            'HON\HonCuratorReview\Models\Platform',
            'table'      => 'hon_honcuratorreview_services_platforms',
            'key'        => 'serv_id',
            'otherKey'   => 'plat_id',
            'pivot'      => ['url', 'deleted_at'],
            'timestamps' => true
        ]
    ];

    /**
     * @array hasMany Models relations
     */
    public $hasMany = [
        'reviews' => 'HON\HonCuratorReview\Models\Review' // service_id
    ];

    /**
     * @array belongsTo Models relations
     */
    public $belongsTo = [
        'user' => ['RainLab\User\Models\User', 'key' => 'owner_id' ]
    ];
}