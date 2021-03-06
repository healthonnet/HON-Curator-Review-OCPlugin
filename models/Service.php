<?php namespace HON\HonCuratorReview\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Model;
use phpDocumentor\Reflection\Types\Boolean;
use RainLab\User\Models\User;

/**
 * Model
 */
class Service extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];

    protected $fillable = ['name', 'owner_id', 'description', 'creator_id'];

    /**
     * Softly implement the TranslatableModel behavior.
     */
    public $implement = ['@RainLab.Translate.Behaviors.TranslatableModel'];

    /**
     * @var array Attributes that support translation, if available.
     */
    public $translatable = ['description'];

    /*
     * Validation
     */
    public $rules = [
        'name' => 'required|min:3|unique:hon_honcuratorreview_services',
        'description' => 'required|min:5'
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
            'pivot'      => ['url', 'creator_id', 'deleted_at'],
            'pivotModel' => 'HON\HonCuratorReview\Models\PlatformServicePivot',
            'timestamps' => true
        ]
    ];

    /**
     * @array hasMany Models relations
     */
    public $hasMany = [
        'apps' => ['HON\HonCuratorReview\Models\App', 'key' => 'serv_id', 'delete'=>'true'] // serv_id
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
        'user' => ['RainLab\User\Models\User', 'key' => 'owner_id' ],
        'creator' => ['RainLab\User\Models\User', 'key' => 'creator_id' ],
    ];

    /**
     * Scope for reviews count
     */
    public function scopeReviewsCount($query){
        return $query->leftjoin('hon_honcuratorreview_services_platforms',
            'hon_honcuratorreview_services_platforms.serv_id','=','hon_honcuratorreview_services.id')
            ->leftjoin('hon_honcuratorreview_reviews',
                'hon_honcuratorreview_reviews.app_id','=','hon_honcuratorreview_services_platforms.id')
            ->selectRaw('hon_honcuratorreview_services.*, count(hon_honcuratorreview_reviews.id) as count');
    }

    public function scopeMatchRequest($query, $filters, $search, $platform ) {
        // Add platform
        if ($platform) {
            $query->whereHas('platforms', function ($query) use ($platform) {
                $query->where('hon_honcuratorreview_platforms.name', '=', $platform);
            });
        }

        // Add filters
        if ($filters) {
            $query->leftjoin('hon_honcuratorreview_services_tags as st',
                'hon_honcuratorreview_services.id','=','st.service_id')
            ->leftjoin('hon_honcuratorreview_tags',
                'hon_honcuratorreview_tags.id','=','st.tag_id')
            ->whereIn('hon_honcuratorreview_tags.name',explode('|', $filters))
            ->groupBy('st.service_id')
            ->havingRaw('COUNT(DISTINCT st.tag_id) = '.count(explode('|', $filters)));
        }

        // Add search field
        if ($search) {
            $query->where(function($query) use ($search){
                $query->where('hon_honcuratorreview_services.name', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%')
                    ->orWhereHas('platforms', function ($query) use ($search){
                        $query->where('url', 'like', '%'.$search.'%');
                    })
                    ->orWhereHas('tags', function ($query) use ($search){
                        $query->where('hon_honcuratorreview_tags.name', 'like', '%'.$search.'%');
                    });
            });
        }

        $query->reviewsCount();
        $query->groupBy('hon_honcuratorreview_services.id');
        return $query;
    }

    /**
     * Custom accessor for preview_url
     * @return string
     */
    public function getPreviewUrlAttribute() {
        // TODO Find a dynamic way to get the better icon
        return $this->apps[0]->preview_url;
    }

    /**
     * Custom accessor for average_review
     * @return float
     */
    public function getAverageRatingAttribute() {

        $reviews = DB::select('select count(1) as count, SUM(global_rate) as sum 
        from hon_honcuratorreview_reviews as rev 
        join hon_honcuratorreview_services_platforms as app on app.id = rev.app_id 
        join hon_honcuratorreview_services as serv on app.serv_id = serv.id 
        where serv.id = ? and rev.deleted_at IS NULL', [$this->id]);

        if (!$reviews[0]->count) {
            return 0;
        }

        return $reviews[0]->sum / $reviews[0]->count;
    }

    /**
     * Custom accessor for reviewCount
     * @return float
     */
    public function getReviewCountAttribute() {

        $reviews = DB::select('select count(1) as count, SUM(global_rate) as sum 
        from hon_honcuratorreview_reviews as rev 
        join hon_honcuratorreview_services_platforms as app on app.id = rev.app_id 
        join hon_honcuratorreview_services as serv on app.serv_id = serv.id 
        where serv.id = ? and rev.deleted_at IS NULL', [$this->id]);

        if (!$reviews[0]->count) {
            return 0;
        }

        return $reviews[0]->count;
    }

    /**
     * Custom accessor for average_review
     * @return Review[]
     */
    public function getLastReviewsAttribute() {
        return $this->reviews()->latest()->take(3)->get();
    }


    /**
     * Return all apps not reviewed by a given User for this service
     * @param User $user
     * @return Review[]
     */
    public function filterReviewedAppsBy(User $user)
    {
        if (!$user)
            return false;
        return $this->apps->filter(function($app) use ($user) {
            if (Review::where('app_id', $app->id)->where('user_id', $user->id)->first()) {
                return;
            }
            return $app;
        });
    }

    /**
     * Return all platforms that does not exist by a given Service
     * @return Collection[]
     */
    public function filterExistingPlatforms()
    {
        $allPlatforms = Platform::all();
        return $allPlatforms->diff($this->platforms);
    }

    /**
     * @param String $platform
     * @return Boolean
     */
    public function hasPlatform($platform)
    {
        foreach ( $this->platforms as $servicePlatorm) {
            if ($servicePlatorm->name === $platform) {
                return true;
            }
        }
        return false;
    }

    /**
     * Prepare search
     * @param Input[]
     * @return Builder $query
     */
    public static function prepareSearch($filters, $search, $platform, $sortBy = false)
    {
        // Prepare query
        $query = Service::has('platforms');

        $preparedQuery = $query->matchRequest($filters, $search, $platform);

        if ($sortBy == 'reviews') {

            $preparedQuery = $query->orderBy('count', 'desc');
        }
        return  $preparedQuery;
    }


    /**
     * Get Search results with smart pagination
     * @return Service[]
     */
    public static function searchWithPagination($filters, $search, $platform = false, $sortBy = 'reviews')
    {
        $query = Service::prepareSearch($filters, $search, $platform, $sortBy);

        return $query->paginate(6);
    }

    /**
     * Get Search results
     * @return Service[]
     */
    public static function search($filters, $search, $platform = false, $sortBy = 'reviews')
    {
        $query = Service::prepareSearch($filters, $search, $platform, $sortBy);

        return $query->get();
    }
}