<?php namespace Hon\Honcuratorreview\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

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
        // TODO Update tags
        // Load the Json file in updates/json/
        // loop them and check and update existing services
        // don't create new tags only bind new relations

        $test = 2 + 40;
        dd($test);
    }
}
