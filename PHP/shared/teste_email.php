<?php
require_once __DIR__ . '/auth.php'; // caminho do auth.php

// E-mail de teste (pode ser o seu mesmo)
$testeEmail = 'estevaoetc@gmail.com';
$codigoTeste = random_int(100000, 999999);

if (enviarCodigo2FA($testeEmail, $codigoTeste)) {
    echo "E-mail enviado com sucesso! Código: $codigoTeste";
} else {
    echo "Falha ao enviar e-mail. Verifique o log de erros.";
}
