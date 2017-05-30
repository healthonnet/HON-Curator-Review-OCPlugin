<?php namespace HON\HonCuratorReview\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Reviews extends Controller
{
    public $implement = ['Backend\Behaviors\ListController','Backend\Behaviors\FormController', 'Backend.Behaviors.RelationController'];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $relationConfig = '$/hon/honcuratorreview/controllers/reviews/config_relations.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('HON.HonCuratorReview', 'main-Review-item', 'reviews-menu-item');
    }
}