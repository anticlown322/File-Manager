<?php

require_once 'classes/Controller.php';
require_once 'classes/Model.php';
require_once 'classes/View.php';
require_once 'services/TemplateEngine.php';
require_once 'services/helpers.php';

use classes\Controller;
use classes\Model;
use classes\View;

checkAuth();

$model = new Model();
$controller = new Controller($model);
$view = new View($model, $controller);

echo $view->getNewPage();
