<?php

require_once 'helpers.php';

$name = $_POST['name'] ?? null;
$password = $_POST['password'] ?? null;
$passwordConfirmation = $_POST['password_confirmation'] ?? null;

if (empty($name)) {
    setValidationError('name', 'Incorrect name');
}

if (empty($password)) {
    setValidationError('password', 'Password cannot be empty');
}

if ($password !== $passwordConfirmation) {
    setValidationError('password', 'Passwords do not match');
}

if (!empty($_SESSION['validation'])) {
    setOldValue('name', $name);
    redirect('../register.php');
}

$pdo = getPDO();

$query = "INSERT INTO user_data (name, password) VALUES (:name, :password)";

$params = [
    'name' => $name,
    'password' => password_hash($password, PASSWORD_DEFAULT)
];

$stmt = $pdo->prepare($query);

try {
    $stmt->execute($params);
} catch (\Exception $e) {
    die($e->getMessage());
}

redirect('../index.php');
