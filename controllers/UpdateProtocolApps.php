<?php namespace Hon\Honcuratorreview\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use HON\HonCuratorReview\Models\App;
use Illuminate\Support\Facades\File;

/**
 * Update Tags Back-end Controller
 */
class UpdateProtocolApps extends Controller
{

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Hon.Honcuratorreview', 'honcuratorreview', 'updateprotocolapps');
    }
    public function index()
    {
        // Load the Json file in updates/json/
        $httpsAvailableList = json_decode(File::get(dirname(__FILE__).'/../updates/json/https-available.json'));

        foreach ($httpsAvailableList as $httpsAvailable) {
            $httpsAvailable = trim($httpsAvailable, "/");
            $search = $httpsAvailable;
            $disallowed = array('http://', 'https://');
            foreach($disallowed as $d) {
                if(strpos($search, $d) === 0) {
                    $search = str_replace($d, '', $search);
                }
            }

            $app = App::where('url', 'like', '%'. $search .'%')->first();
            if ($app instanceof App) {
                $app->url = $httpsAvailable;
                $app->save();
            }
        }

        dd("done");
    }
}
