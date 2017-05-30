<?php namespace HON\HonCuratorReview\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Reviews extends Controller
{
    public $implement = ['Backend\Behaviors\ListController'];
    
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('HON.HonCuratorReview', 'main-Review-item');
    }
}