<?php

$users = [
    ["username" => "super",      "password" => "12345", "role" => 1],
    ["username" => "admin",      "password" => "12345", "role" => 2],
    ["username" => "recenzent0", "password" => "12345", "role" => 3],
    ["username" => "recenzent1", "password" => "12345", "role" => 3],
    ["username" => "recenzent2", "password" => "12345", "role" => 3],
    ["username" => "autor0",     "password" => "12345", "role" => 4],
    ["username" => "autor1",     "password" => "12345", "role" => 4],
    ["username" => "autor2",     "password" => "12345", "role" => 4],
];

$outputFile = __DIR__ . "/SQLdoDatabaze/hashed_users.sql";
$fh = fopen($outputFile, "w");

foreach ($users as $u) {

    $username = $u["username"];
    $plainPassword = $u["password"];
    $roleId = $u["role"];

    // BCrypt hash
    $hash = password_hash($plainPassword, PASSWORD_BCRYPT, ['cost' => 12]);

    // Insert SQL
    $sql = sprintf(
        "INSERT INTO user (username, name, email, password, roles_id, blocked)
         VALUES ('%s', '%s', '%s', '%s', %d, 0);\n",
        $username,
        ucfirst($username),
        $username . "@mail.com",
        $hash,
        $roleId
    );

    fwrite($fh, $sql);
}

fclose($fh);

echo "Vygenerováno do: " . $outputFile;
