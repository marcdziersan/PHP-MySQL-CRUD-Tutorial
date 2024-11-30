<?php
require_once 'Entry.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $entryObj = new Entry();
    $entryObj->delete($id);

    header("Location: index.php");
    exit();
}
?>
