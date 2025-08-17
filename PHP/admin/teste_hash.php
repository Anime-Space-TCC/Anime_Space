<?php
$hash = '$2y$10$13YOmTrGN0gJtmIf0ns3GuhQXiRH5lDcpY9tlKUuhu.ty7D/u3sg.';
$senha = '123456';

if (password_verify($senha, $hash)) {
    echo "Senha correta!";
} else {
    echo "Senha incorreta!";
}