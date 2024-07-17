<?php

require_once '../classes/Model.php';

use classes\Model;

$model = new Model();
$homePagePath = '..' . DIRECTORY_SEPARATOR . 'home.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["fileToUpload"])) {
    $uploaded = true;
    $files = $_FILES["fileToUpload"];

    foreach ($files["name"] as $key => $name) {
        $targetFile = '..' . DIRECTORY_SEPARATOR . Model::UPLOAD_DIR . basename($name);
        if (!move_uploaded_file($files["tmp_name"][$key], $targetFile)) {
            $uploaded = false;
        }
    }

    if ($uploaded) {
        header("Location: $homePagePath");
        exit;
    } else {
        echo "Error during file upload.";
    }
}


