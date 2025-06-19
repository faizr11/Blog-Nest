<?php

require __DIR__ . '/../koneksi.php';

$query = "SELECT * FROM users";
$result = $koneksi->query($query);

$users = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

return $users;