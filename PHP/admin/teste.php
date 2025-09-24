<?php

// password_hash
$senha = "#3etc-TcC";
$hash = password_hash($senha, PASSWORD_DEFAULT);

echo "Hash gerado: " . $hash . "<br>";

?>