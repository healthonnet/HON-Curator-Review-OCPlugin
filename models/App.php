<?php namespace HON\HonCuratorReview\Models;

use Model;

/**
 * Model
 */
class App extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];
    protected $fillable = ['url', 'plat_id', 'serv_id'];

    /**
     * @var array Rules
     */
    public $rules = [
        'url' => 'required|url',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'hon_honcuratorreview_services_platforms';


    /**
     * @array belongsTo Models relations
     */
    public $belongsTo = [
        'service' => ['HON\HonCuratorReview\Models\Service', 'key' => 'serv_id' ],
        'platform' => ['HON\HonCuratorReview\Models\Platform', 'key' => 'plat_id' ],
    ];

    /**
     * @array hasMany Models relations
     */
    public $hasMany = [
        'reviews' => 'HON\HonCuratorReview\Models\Review'
    ];

    public function getNameAttribute() {
        $service = $this->service()->find($this->serv_id);
        $platform = $this->platform()->find($this->plat_id);
        return $service->name . ' ( ' . $platform->name . ' )';
    }

    /**
     * Custom accessor for preview_url
     * @return string
     */
    public function getPreviewUrlAttribute() {
        $googleFavIcon = 'https://www.google.com/s2/favicons?domain=' . $this->url;
        $previewUrl = 'https://icons.better-idea.org/icon?size=16..120..310&url=' . $this->url . '&fallback_icon_url=' . $googleFavIcon;
        // TODO Get Store App icon if possible.
        return $previewUrl;
    }

    /**
     * Custom accessor for average_review
     * @return float
     */
    public function getAverageRatingAttribute() {
        // TODO get all computable values.
        return 2;
    }
}