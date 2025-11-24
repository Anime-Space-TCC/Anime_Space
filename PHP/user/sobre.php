<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Sobre o Site - Animes Space</title>
    <link rel="stylesheet" href="../../CSS/style.css">
    <link rel="icon" href="../../img/slogan3.png" type="image/png">
</head>

<body class="sobre">
    <?php
    $current_page = 'sobre';
    include __DIR__ . '/navbar.php';
    ?>

    <main class="page-content">
        <h1 class="page-title">
            <img src="../../img/slogan3.png" alt="slogan site">
            QUEM SOMOS NÓS
        </h1>
        <section class="texto">
            <p>
                O <strong>Animes Space</strong> é um portal dedicado aos amantes de animes que buscam uma experiência
                completa,
                moderna e totalmente integrada. Aqui você encontra episódios, informações detalhadas, recomendações,
                gamificação e um ambiente feito por fãs — para fãs.
            </p>

            <p>
                Nosso foco é oferecer uma plataforma rápida, organizada e intuitiva, com ferramentas que facilitam
                descobrir, acompanhar e desfrutar do seu anime favorito.
            </p>

            <p>Entre nossos principais recursos, você encontra:</p>

            <ul>
                <li>Interface leve, responsiva e otimizada para qualquer dispositivo;</li>
                <li>Catálogo completo com filtros por gênero, ano, idioma e recomendações personalizadas;</li>
                <li>Streaming integrado para assistir episódios diretamente no site;</li>
                <li>Sistema de contas com favoritos, avaliações, histórico e progresso salvo;</li>
                <li>Gamificação com XP, níveis, conquistas e notificações interativas;</li>
                <li>Plataforma segura, organizada e em constante evolução.</li>
            </ul><br>
            <p>
                No <strong>Animes Space</strong>, nossa missão é conectar a comunidade otaku, oferecendo uma experiência
                rica e envolvente para todos os fãs de animes. Seja você um novato ou um veterano, aqui é o seu lugar
                para explorar o universo dos animes com facilidade e diversão.
            </p>
        </section>

        <h2>Nossa Equipe</h2><br>

        <section class="equipe">
            <figure>
                <img src="../../img/estevão.png" alt="Foto de Estevão">
            </figure>
            <figure>
                <img src="../../img/larissa.png" alt="Foto de Larissa">
            </figure>
            <figure>
                <img src="../../img/julio.png" alt="Foto de Júlio">
            </figure>
        </section>

        <section class="texto equipe-desc">
            <p><strong>Estevão:</strong> Desenvolvedor Full Stack, responsável pela programação, banco de dados e
                funcionalidades principais.</p>
            <p><strong>Larissa:</strong> Gerente de Projeto, cuidando da organização, escopo e coordenação do time.</p>
            <p><strong>Júlio:</strong>Tester, garantindo que tudo funcione com qualidade e estabilidade.</p>
        </section>
    </main>

    <footer>
        <?php include __DIR__ . '/rodape.php'; ?>
    </footer>
</body>

</html>