<?php
// index.php
// Simple secure command trigger example

// Whitelist of allowed commands
$allowedCommands = [
    'sync' => 'HOME=/tmp git pull',
];

$output = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if (array_key_exists($action, $allowedCommands)) {
        $command = $allowedCommands[$action];

        // Execute command and capture output
        $output = shell_exec($command . ' 2>&1');
    } else {
        $error = 'Invalid action requested.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Server Command Runner</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        button { padding: 10px 20px; font-size: 16px; }
        pre { background: #f4f4f4; padding: 15px; border-radius: 6px; }
    </style>
</head>
<body>

<h2>Run Server Command</h2>

<form method="post">
    <button type="submit" name="action" value="sync">Sync</button>
</form>

<?php if ($output): ?>
    <h3>Command Output</h3>
    <pre><?= htmlspecialchars($output) ?></pre>
<?php endif; ?>

<?php if ($error): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

</body>
</html>
