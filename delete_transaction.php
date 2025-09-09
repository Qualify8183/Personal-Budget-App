<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$transaction_id = $_GET['id'];

$deleteStmt = $pdo->prepare("DELETE FROM transactions WHERE id = :id AND user_id = :user_id");
$deleteStmt->execute(['id' => $transaction_id, 'user_id' => $user_id]);

header("Location: dashboard.php");
exit();
