<?php
$password = 'user123'; 
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Hashed password: " . $hash;
?>
