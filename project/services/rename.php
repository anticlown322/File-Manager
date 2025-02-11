<?php

$homePagePath = '..' . DIRECTORY_SEPARATOR . 'index.php';

if (isset($_GET['file'])) {
    $filename = '..' . DIRECTORY_SEPARATOR . $_GET['file'];

    if (isset($_POST['filename'])) {
        if (preg_match('/^[\w\-. ]+$/', $_POST['filename'])) {
            rename($filename, rtrim(pathinfo($filename, PATHINFO_DIRNAME), '/') .  DIRECTORY_SEPARATOR . $_POST['filename']);
            header("Location: $homePagePath");
        } else {
            exit('Please enter a valid name!');
        }
    }
} else {
    exit('Invalid file!');
}
