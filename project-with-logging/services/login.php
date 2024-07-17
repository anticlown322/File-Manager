<?php

require_once 'helpers.php';

$name = $_POST['name'] ?? null;
$password = $_POST['password'] ?? null;

$user = findUser($name);

if (!$user) {
    setMessage('error', "User $name not found");
    redirect('../index.php');
}

if (!password_verify($password, $user['password'])) {
    setMessage('error', 'Incorrect password');
    redirect('../index.php');
}

$_SESSION['user']['id'] = $user['id'];

redirect('../home.php');