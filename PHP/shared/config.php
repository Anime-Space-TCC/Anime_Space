<?php

return [
    'smtp' => [
        'host'     => 'smtp.gmail.com',    // o servidor SMTP real (ex: smtp.gmail.com)
        'username' => 'exemplo@gmail.com', // e-mail de teste
        'password' => '################',  // senha ou app password
        'port'     => 587,                 // geralmente 587 para TLS
        'secure'   => 'tls',               // TLS ou SSL
        'from'     => 'exemplo@gmail.com', // mesmo e-mail usado como remetente
        'fromName' => 'Anime Space'
    ]
];
