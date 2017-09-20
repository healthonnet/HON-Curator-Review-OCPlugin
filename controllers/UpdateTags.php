<?php namespace Hon\Honcuratorreview\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use HON\HonCuratorReview\Models\Service;
use HON\HonCuratorReview\Models\Tag;
use Illuminate\Support\Facades\File;

/**
 * Update Tags Back-end Controller
 */
class UpdateTags extends Controller
{

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Hon.Honcuratorreview', 'honcuratorreview', 'updatetags');
    }
    public function index()
    {
        // Load the Json file in updates/json/
        $honConducts = json_decode(File::get(dirname(__FILE__).'/../updates/json/honconducts.json'));
        // loop them and check and update existing services
        foreach ($honConducts as $honConduct) {
            // don't create new tags only bind new relations
            $service = Service::where('name', $honConduct->name)->first();
            if (!$service) continue;

            if (property_exists($honConduct, 'tags')) {
                foreach ($honConduct->tags as $key => $tagType) {
                    $categorieName = $key;
                    foreach ($tagType as $tagName ) {
                        $tag = Tag::where(['name' => $tagName, 'type' => $categorieName])->first();
                        if (!$tag) continue;
                        $tag->services()->sync([$service->id], false);
                    }
                }
            }
        }
        dd("done");
    }
}
