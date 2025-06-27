<?php

$db = \Core\Database::connect();
$stmt = $db->query("SELECT username, password FROM [User]");
$users = $stmt->fetchAll();

foreach ($users as $user) {
    $password = $user['password'];

    // Als het geen geldige bcrypt-hash is (begin niet met $2y$), dan is het plain text
    if (! str_starts_with($password, '$2y$')) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $update = $db->prepare("UPDATE [User] SET password = :password WHERE username = :username");
        $update->execute([
            ':password' => $hashed,
            ':username' => $user['username'],
        ]);
        echo "✅ Gehashed: {$user['username']}\n";
    } else {
        echo "⏩ Al gehashed: {$user['username']}\n";
    }
}
