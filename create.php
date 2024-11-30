<?php
require_once 'Entry.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];

    $entryObj = new Entry();
    $entryObj->create($title, $description);

    header("Location: index.php");
    exit();
}
?>
