<?php

require_once '../classes/Model.php';

use classes\Model;

$model = new Model();
$homePagePath = '..' . DIRECTORY_SEPARATOR . 'home.php';

if (isset($_GET['file'])) {
    $filename = '..' . DIRECTORY_SEPARATOR . $_GET['file'];

    if (is_dir($filename)) {
        if (rmdir($filename)) {
            header("Location: $homePagePath");
            exit;
        } else {
            exit('Directory must be empty!');
        }
    } else {
        unlink($filename);
        header("Location: $homePagePath");
        exit;
    }
} else {
    exit('Invalid Request!');
}
