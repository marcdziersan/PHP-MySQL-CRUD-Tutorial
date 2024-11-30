<?php
require_once 'Entry.php';

$entryObj = new Entry();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $entry = $entryObj->getById($id);
    if (!$entry) {
        die("Eintrag nicht gefunden!");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];

    $entryObj->update($id, $title, $description);

    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Eintrag bearbeiten</title>
</head>
<body>
    <h1>Eintrag bearbeiten</h1>
    <form action="update.php" method="POST">
        <input type="hidden" name="id" value="<?= $entry['id'] ?>">
        <label for="title">Titel:</label>
        <input type="text" name="title" id="title" value="<?= htmlspecialchars($entry['title']) ?>" required>
        <br>
        <label for="description">Beschreibung:</label>
        <textarea name="description" id="description" required><?= htmlspecialchars($entry['description']) ?></textarea>
        <br>
        <button type="submit">Speichern</button>
    </form>
</body>
</html>
