<?php namespace Hon\Honcuratorreview\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use HON\HonCuratorReview\Models\Service;
use HON\HonCuratorReview\Models\App;
use HON\HonCuratorReview\Models\Tag;
use Illuminate\Support\Facades\File;

/**
 * Update Tags Back-end Controller
 */
class Cleaner extends Controller
{

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Hon.Honcuratorreview', 'honcuratorreview', 'cleaner');
    }
    public function index()
    {
        // Load the Json file in updates/json/
        $cleanList = [
            "http://example.com"
        ];

        $aDeleted = array();
        foreach ($cleanList as $todelete) {
            $todelete = trim($todelete, "/");
            $app = App::where('url', 'like', '%' . $todelete . '%')->first();

            if (!$app) continue;

            $service = $app->service;
            $app->delete();
            if(count($service->apps) == 0) {
                $service->delete();
            }
            array_push($aDeleted, $todelete);
        }
        dd($aDeleted);
    }
}
