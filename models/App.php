<?php namespace HON\HonCuratorReview\Models;

use Illuminate\Support\Facades\DB;
use Model;

/**
 * Model
 */
class App extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];
    protected $fillable = ['url', 'plat_id', 'serv_id', 'creator_id'];

    /**
     * @var array Rules
     */
    public $rules = [
        'url'     => 'required|url|unique:hon_honcuratorreview_services_platforms,url',
        'plat_id' => 'required',
        'serv_id' => 'required',
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
        'creator' => ['RainLab\User\Models\User', 'key' => 'creator_id' ],
    ];

    /**
     * @array hasMany Models relations
     */
    public $hasMany = [
        'reviews' => ['HON\HonCuratorReview\Models\Review', 'delete'=> true]
    ];

    public function getNameAttribute() {
        $service = $this->service()->find($this->serv_id);
        if (!$service) return;
        $platform = $this->platform()->find($this->plat_id);
        if (!$platform) return;
        return $service->name . ' ( ' . $platform->name . ' )';
    }

    /**
     * Custom accessor for preview_url
     * @return string
     */
    public function getPreviewUrlAttribute() {
        $googleFavIcon = 'https://www.google.com/s2/favicons?domain=' . $this->url;
        $previewUrl = 'https://www.healthcurator.org/better-icon/icon?size=16..120..310&url=' . $this->url . '&fallback_icon_url=' . $googleFavIcon;
        // TODO Get Store App icon if possible.
        return $previewUrl;
    }

    /**
     * Custom accessor for average_review
     * @return float
     */
    public function getAverageRatingAttribute() {
        // TODO get all computable values.

        $reviews = DB::select('select count(1) as count, SUM(global_rate) as sum from hon_honcuratorreview_reviews where app_id = ?', [$this->id]);

        if (!$reviews[0]->count) {
            return 0;
        }

        return $reviews[0]->sum / $reviews[0]->count;
    }
}