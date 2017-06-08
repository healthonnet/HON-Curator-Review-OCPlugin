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
            'pivotModel' => 'HON\HonCuratorReview\Models\PlatformServicePivot',
            'timestamps' => true
        ]
    ];

    /**
     * @array hasMany Models relations
     */
    public $hasMany = [
        'apps' => ['HON\HonCuratorReview\Models\App', 'key' => 'serv_id'] // serv_id
    ];

    /**
     * @array hasManyThrough Models relations
     */
    public $hasManyThrough = [
        'reviews' => [
            'HON\HonCuratorReview\Models\Review',
            'key'        => 'serv_id',
            'through'    => 'HON\HonCuratorReview\Models\App',
        ],
    ];

    /**
     * @array belongsTo Models relations
     */
    public $belongsTo = [
        'user' => ['RainLab\User\Models\User', 'key' => 'owner_id' ]
    ];

    /**
     * Custom accessor for preview_url
     * @return string
     */
    public function getPreviewUrlAttribute() {
        // TODO prefer App Icon over website image
        return $this->apps[0]->preview_url;
    }

    /**
     * Custom accessor for average_review
     * @return float
     */
    public function getAverageRatingAttribute() {
        if (!count($this->apps)) {
            return 0;
        }

        $value = 0;
        foreach ($this->apps as $app){
            $value += $app->average_rating;
        }
        return $value / count($this->apps);
    }

    /**
     * Custom accessor for average_review
     * @return Review[]
     */
    public function getLastReviewsAttribute() {
        return $this->reviews()->latest()->take(3)->get();
    }
}