<?php namespace HON\HonCuratorReview\Models;

use Model;

/**
 * Model
 */
class Tag extends Model
{
    use \October\Rain\Database\Traits\Validation;

    protected $fillable = ['name', 'type'];

    /**
     * Softly implement the TranslatableModel behavior.
     */
    public $implement = ['@RainLab.Translate.Behaviors.TranslatableModel'];

    /**
     * @var array Attributes that support translation, if available.
     */
    public $translatable = ['name'];

    /*
     * Validation
     */
    public $rules = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'hon_honcuratorreview_tags';

    /**
     * @array belongsToMany Models relations
     */
    public $belongsToMany = [
        'services' => [
            'HON\HonCuratorReview\Models\Service',
            'table'    => 'hon_honcuratorreview_services_tags', // service_id tag_id
        ]
    ];

    /**
     * Custom accessor for average_review
     * @return Review[]
     */
    public function getUntranslatednameAttribute() {
        return $this->getTranslateAttribute('name', 'en');
    }

    /**
     * Keep only existing inputs
     * @param String $input
     * @return String $cleanedInput
     */
    public static function cleanInput($input)
    {
        $entries = array_unique(explode('|',$input));
        $tags = Tag::all()->lists('untranslatedname');

        return join('|', array_intersect($entries,$tags));


    }

}