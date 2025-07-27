<?php
$tasksFile = 'tasks.json';
if (!file_exists($tasksFile)) {
    file_put_contents($tasksFile, json_encode([]));
}
$tasks = json_decode(file_get_contents($tasksFile), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['task'])) {
        $tasks[] = ['text' => $_POST['task'], 'done' => false];
    }
    if (isset($_POST['toggle'])) {
        $tasks[$_POST['toggle']]['done'] = !$tasks[$_POST['toggle']]['done'];
    }
    if (isset($_POST['delete'])) {
        array_splice($tasks, $_POST['delete'], 1);
    }
    file_put_contents($tasksFile, json_encode($tasks));
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>To-Do List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>📝 My To-Do List</h1>
    <form method="POST">
        <input type="text" name="task" placeholder="New task..." required>
        <button type="submit">Add</button>
    </form>
    <ul>
        <?php foreach ($tasks as $i => $task): ?>
            <li class="<?= $task['done'] ? 'done' : '' ?>">
                <form method="POST" class="inline">
                    <input type="hidden" name="toggle" value="<?= $i ?>">
                    <button type="submit"><?= $task['done'] ? '✅' : '⬜' ?></button>
                </form>
                <?= htmlspecialchars($task['text']) ?>
                <form method="POST" class="inline">
                    <input type="hidden" name="delete" value="<?= $i ?>">
                    <button type="submit">🗑️</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
