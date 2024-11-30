
# PHP MySQL CRUD System (OOP)

Dieses Tutorial zeigt, wie du ein einfaches CRUD-System (Create, Read, Update, Delete) mit PHP und MySQL in einer objektorientierten Struktur erstellst.

---

## Anforderungen
- PHP (z. B. über XAMPP oder LAMP)
- MySQL-Datenbank
- Ein Texteditor (z. B. Visual Studio Code)
- Grundlegende Kenntnisse in PHP und SQL

---

## Installation

### 1. Datenbank einrichten
1. Öffne phpMyAdmin oder ein anderes MySQL-Tool.
2. Erstelle eine neue Datenbank namens `crud_tutorial`.
3. Führe das folgende SQL-Skript aus:
    ```sql
    CREATE TABLE entries (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT NOT NULL
    );
    ```

---

### 2. Verzeichnisstruktur erstellen
Erstelle ein neues Projektverzeichnis mit folgender Struktur:

```
/crud_oop
  |-- index.php
  |-- db.php
  |-- Entry.php
  |-- create.php
  |-- update.php
  |-- delete.php
```

---

### 3. Dateien erstellen und Code hinzufügen

#### **`db.php`**
Erstellt die Datenbankverbindung als Singleton-Klasse:
```php
<?php
class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $host = 'localhost';
        $dbname = 'crud_tutorial';
        $username = 'root';
        $password = '';
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Datenbankverbindung fehlgeschlagen: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->pdo;
    }
}
?>
```

#### **`Entry.php`**
Die CRUD-Logik wird in einer Klasse kapsuliert:
```php
<?php
require_once 'db.php';

class Entry {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM entries");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM entries WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($title, $description) {
        $stmt = $this->pdo->prepare("INSERT INTO entries (title, description) VALUES (:title, :description)");
        $stmt->execute(['title' => $title, 'description' => $description]);
    }

    public function update($id, $title, $description) {
        $stmt = $this->pdo->prepare("UPDATE entries SET title = :title, description = :description WHERE id = :id");
        $stmt->execute(['id' => $id, 'title' => $title, 'description' => $description]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM entries WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
?>
```

#### **`index.php`**
Zeigt alle Einträge an und bietet ein Formular zum Hinzufügen neuer Einträge:
```php
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
```

#### **`create.php`**
Fügt einen neuen Eintrag hinzu:
```php
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
```

#### **`update.php`**
Bearbeitet einen bestehenden Eintrag:
```php
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
```

#### **`delete.php`**
Löscht einen Eintrag:
```php
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
```

---

### Teste das CRUD-System
1. **Starte den Server:** (z. B. über XAMPP oder `php -S localhost:8000`).
2. **Öffne `index.php` im Browser:** Du solltest die Einträge sehen können.
3. **Teste die Funktionen:**
   - **Hinzufügen:** Fülle das Formular aus und drücke auf *Hinzufügen*.
   - **Bearbeiten:** Klicke auf *Bearbeiten*, ändere die Werte und speichere.
   - **Löschen:** Klicke auf *Löschen*.

---

Das System ist jetzt OOP-basiert und kann einfach erweitert werden!
