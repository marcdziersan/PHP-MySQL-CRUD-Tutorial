<?php
require_once 'Entry.php';

$entryObj = new Entry();
$entries = $entryObj->getAll();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>CRUD System (OOP)</title>
</head>
<body>
    <h1>Einträge</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Titel</th>
            <th>Beschreibung</th>
            <th>Aktionen</th>
        </tr>
        <?php foreach ($entries as $entry): ?>
        <tr>
            <td><?= $entry['id'] ?></td>
            <td><?= htmlspecialchars($entry['title']) ?></td>
            <td><?= htmlspecialchars($entry['description']) ?></td>
            <td>
                <a href="update.php?id=<?= $entry['id'] ?>">Bearbeiten</a>
                <a href="delete.php?id=<?= $entry['id'] ?>">Löschen</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h2>Neuen Eintrag hinzufügen</h2>
    <form action="create.php" method="POST">
        <label for="title">Titel:</label>
        <input type="text" name="title" id="title" required>
        <br>
        <label for="description">Beschreibung:</label>
        <textarea name="description" id="description" required></textarea>
        <br>
        <button type="submit">Hinzufügen</button>
    </form>
</body>
</html>
