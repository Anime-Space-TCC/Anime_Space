-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 31-Ago-2025 às 02:46
-- Versão do servidor: 10.4.24-MariaDB
-- versão do PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `anime_space`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `animes`
--

CREATE TABLE `animes` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nota` decimal(3,1) DEFAULT 0.0,
  `descricao` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sinopse` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `capa` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ano` year(4) DEFAULT NULL,
  `linguagem` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `animes`
--

INSERT INTO `animes` (`id`, `nome`, `nota`, `descricao`, `sinopse`, `capa`, `ano`, `linguagem`, `created_at`, `updated_at`) VALUES
(1, 'Solo Leveling', '8.5', 'Sung Jinwoo, o caçador mais fraco do mundo, ganha a habilidade de ficar mais forte sem limites após sobreviver a uma dungeon mortal, iniciando uma ascensão imparável.', 'Em um mundo onde portais místicos — as dungeons — surgem, caçadores enfrentam monstros para proteger a humanidade. Sung Jinwoo, classificado como um Hunter de Rank E, é considerado o mais fraco entre eles. Tudo muda quando ele sobrevive por pouco a uma dungeon dupla que quase aniquila seu grupo. Após esse evento traumático, Jinwoo é escolhido por um misterioso programa chamado Sistema, que lhe concede uma habilidade única: subir de nível infinitamente, algo impossível para os demais caçadores. Ele parte então em uma jornada incrível, enfrentando monstros e humanos em busca da origem de seu poder, até descobrir que foi destinado a se tornar o Shadow Monarch, um necromante imortal que luta para salvar a humanidade contra os demais Monarcas, que buscam sua destruição.', 'solo.png', 2021, NULL, '2025-08-31 00:31:54', '2025-08-31 00:31:54'),
(2, 'Sousou no Frieren', '9.2', 'Após derrotar o Rei Demônio, a maga élfica Frieren parte em uma jornada para entender os humanos e lidar com a passagem do tempo e a perda de seus companheiros.', 'A história começa após um grande triunfo: a mage élfica Frieren fazia parte de um grupo heroico que derrotou o Rei Demônio em uma jornada de dez anos. Após a vitória, seus companheiros — humanos e anões — envelhecem e morrem, enquanto Frieren, com seu tempo de vida milenar, os vê como eventos breves em sua própria existência. Ela retorna cinquenta anos depois — e percebe que perdeu a chance de se aprofundar nas relações com seus companheiros humanos, especialmente com o herói Himmel. Movida pelo arrependimento, ela embarca em uma nova jornada para reencontrar o passado, cumprir os desejos finais dos amigos e ensinar sua aprendiz humana, Fern, enquanto reflete sobre a transitoriedade da vida, a profundidade das emoções humanas e o valor das conexões verdadeiras.', 'frieren.png', 2022, 'legendado', '2025-08-31 00:31:54', '2025-08-31 00:31:54'),
(3, 'Dandadan', '8.8', 'Uma mistura de ação, comédia e sobrenatural, onde uma garota que acredita em fantasmas e um garoto que acredita em alienígenas acabam envolvidos com ambos.', 'Momo Ayase”, que acredita em fantasmas mas não em alienígenas, e “Ken Takakura” (apelidado Okarun), que acredita em alienígenas mas nega a existência de fantasmas, acabam em uma aposta para provar quem está certo — ela visita um hotspot alienígena, ele um lugar assombrado. Isso desencadeia eventos sobrenaturais extremos: Momo é abduzida por aliens, liberando seus poderes psíquicos latentes; Okarun, por sua vez, é possuído por um espírito. Juntos, enfrentam ameaças sobrenaturais com uma mistura caótica de ação, comédia e romance adolescente — desde espíritos malignos até confrontos com alienígenas. A história combina horror escancarado, risos constrangedores e rostos rosa de vergonha típica da puberdade', 'dandadan.png', 2023, 'legendado', '2025-08-31 00:31:54', '2025-08-31 00:31:54'),
(4, 'Dungeon Meshi', '8.7', 'Um grupo de aventureiros explora uma masmorra e sobrevive cozinhando monstros que encontram pelo caminho, combinando fantasia e culinária criativa.', 'Nesse mundo de fantasia, um grupo de aventureiros falha em derrotar um dragão vermelho e precisa salvar sua companheira feiticeira Falin, teletransportada para fora antes de ser devorada. Sem dinheiro ou suprimentos, eles decidem sobreviver cozinhando e comendo os monstros que encontram na dungeon. Liderados por Laios, um espadachim obcecado por monstros, e contando com um anão cozinheiro chamado Senshi, eles devolvem vida — literalmente — à aventura com criatividade e bom humor', 'dungeon.png', 2024, 'legendado', '2025-08-31 00:31:54', '2025-08-31 00:31:54'),
(5, 'Kaiju No. 8', '8.6', 'Em um Japão atacado por monstros gigantes, um homem ganha o poder de se transformar em um kaiju, e decide usar essa força para proteger o país.', 'Kafka Hibino trabalha limpando monstros kaiju, enquanto seu sonho de infância era se tornar membro da força que combate essas criaturas. Tudo muda quando ele acaba ingerindo um kaiju — e passa a ter a habilidade de se transformar em um deles. Agora, Kafka usa esse poder inusitado para ingressar na força de defesa e cumprir uma promessa feita a uma amiga de infância', 'kaiju8.png', 2024, 'legendado', '2025-08-31 00:31:54', '2025-08-31 00:31:54');

-- --------------------------------------------------------

--
-- Estrutura da tabela `anime_generos`
--

CREATE TABLE `anime_generos` (
  `anime_id` int(11) NOT NULL,
  `genero_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `anime_generos`
--

INSERT INTO `anime_generos` (`anime_id`, `genero_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(2, 2),
(2, 4),
(2, 5),
(3, 1),
(3, 3),
(3, 6),
(4, 2),
(4, 5),
(4, 6),
(5, 1),
(5, 7),
(5, 8);

-- --------------------------------------------------------

--
-- Estrutura da tabela `ano`
--

CREATE TABLE `ano` (
  `id` int(11) NOT NULL,
  `valor` year(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `ano`
--

INSERT INTO `ano` (`id`, `valor`) VALUES
(1, 2021),
(2, 2022),
(3, 2023),
(4, 2024);

-- --------------------------------------------------------

--
-- Estrutura da tabela `avaliacoes`
--

CREATE TABLE `avaliacoes` (
  `id` int(11) NOT NULL,
  `nota` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `anime_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `avaliacoes`
--

INSERT INTO `avaliacoes` (`id`, `nota`, `user_id`, `anime_id`) VALUES
(1, 10, 1, 4),
(2, 4, 1, 4),
(3, 2, 1, 4),
(4, 10, 1, 4),
(5, 10, 1, 4);

-- --------------------------------------------------------

--
-- Estrutura da tabela `comentarios`
--

CREATE TABLE `comentarios` (
  `id` int(11) NOT NULL,
  `episodio_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comentario` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_comentario` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `episodios`
--

CREATE TABLE `episodios` (
  `id` int(11) NOT NULL,
  `anime_id` int(11) NOT NULL,
  `temporada` int(11) NOT NULL DEFAULT 1,
  `numero` int(11) NOT NULL,
  `titulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duracao` int(11) DEFAULT NULL,
  `data_lancamento` date DEFAULT NULL,
  `miniatura` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `linguagem` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `episodios`
--

INSERT INTO `episodios` (`id`, `anime_id`, `temporada`, `numero`, `titulo`, `descricao`, `duracao`, `data_lancamento`, `miniatura`, `video_url`, `linguagem`) VALUES
(1, 1, 1, 1, 'O Mais Fraco', 'Jinwoo começa sua jornada como o caçador mais fraco.', 24, '2021-07-01', 'logo.png', 'https://drive.google.com/file/d/1RnLQJH6KwSR-rtXlhRimfnZJatIg2Y2V/view?usp=drivesdk', 'dublado'),
(2, 1, 1, 2, 'Despertar', 'Despertar dos poderes ocultos de Jinwoo.', 24, '2021-07-08', 'logo.png', 'https://drive.google.com/file/d/17EPHfXN-GqFyjZby2kIePP3WyQgCsj-h/view?usp=drive_link', 'legendado'),
(3, 1, 1, 3, 'Portão Mortal', 'Jinwoo enfrenta seu maior desafio em uma dungeon mortal.', 25, '2021-07-15', 'logo.png', 'https://drive.google.com/file/d/1lEj07Vr851FLUkZX95_s5sao52gleoHh/view?usp=drive_link', 'legendado'),
(4, 2, 1, 1, 'Adeus Heróis', 'Frieren enfrenta o fim de uma era e o início de sua jornada.', 24, '2022-10-01', 'logo.png', 'videos/frieren_ep1.mp4', 'legendado'),
(5, 2, 1, 2, 'Lembranças Eternas', 'Frieren relembra momentos com seus antigos companheiros.', 24, '2022-10-08', 'logo.png', 'videos/frieren_ep2.mp4', 'legendado'),
(6, 2, 1, 3, 'Viagem ao Norte', 'A jornada solitária de Frieren a leva ao desconhecido.', 25, '2022-10-15', 'logo.png', 'videos/frieren_ep3.mp4', 'legendado');

-- --------------------------------------------------------

--
-- Estrutura da tabela `episodio_reacoes`
--

CREATE TABLE `episodio_reacoes` (
  `id` int(11) NOT NULL,
  `episodio_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reacao` enum('like','dislike') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `episodio_reacoes`
--

INSERT INTO `episodio_reacoes` (`id`, `episodio_id`, `user_id`, `reacao`, `created_at`) VALUES
(1, 4, 1, 'like', '2025-08-31 00:44:47');

-- --------------------------------------------------------

--
-- Estrutura da tabela `favoritos`
--

CREATE TABLE `favoritos` (
  `id_favorito` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `anime_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `generos`
--

CREATE TABLE `generos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_destaque` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `generos`
--

INSERT INTO `generos` (`id`, `nome`, `id_destaque`) VALUES
(1, 'Ação', 1),
(2, 'Fantasia', 0),
(3, 'Sobrenatural', 1),
(4, 'Drama', 0),
(5, 'Aventura', 1),
(6, 'Comédia', 0),
(7, 'Sci-Fi', 0),
(8, 'Militar', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `historico`
--

CREATE TABLE `historico` (
  `id_historico` int(11) NOT NULL,
  `data_assistido` date NOT NULL,
  `episodio_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `noticias`
--

CREATE TABLE `noticias` (
  `id` int(11) NOT NULL,
  `data_publicacao` date NOT NULL,
  `imagem` varchar(150) NOT NULL,
  `titulo` varchar(60) NOT NULL,
  `resumo` text NOT NULL,
  `url_externa` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `quizzes`
--

CREATE TABLE `quizzes` (
  `id` int(11) NOT NULL,
  `episodio_id` int(11) NOT NULL,
  `pergunta` varchar(255) NOT NULL,
  `alternativa_a` varchar(255) NOT NULL,
  `alternativa_b` varchar(255) NOT NULL,
  `alternativa_c` varchar(255) NOT NULL,
  `alternativa_d` varchar(255) NOT NULL,
  `resposta_correta` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `quiz_respostas`
--

CREATE TABLE `quiz_respostas` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `episodio_id` int(11) NOT NULL,
  `pergunta_id` int(11) NOT NULL,
  `resposta_usuario` enum('A','B','C','D') COLLATE utf8mb4_unicode_ci NOT NULL,
  `correta` tinyint(1) NOT NULL,
  `data_resposta` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `recomendacoes`
--

CREATE TABLE `recomendacoes` (
  `id_recomendacoes` int(11) NOT NULL,
  `motivo` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `anime_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `suporte`
--

CREATE TABLE `suporte` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mensagem` text NOT NULL,
  `data_envio` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `temporadas`
--

CREATE TABLE `temporadas` (
  `id` int(11) NOT NULL,
  `anime_id` int(11) NOT NULL,
  `numero` int(11) NOT NULL,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ano_inicio` year(4) DEFAULT NULL,
  `ano_fim` year(4) DEFAULT NULL,
  `qtd_episodios` int(11) DEFAULT NULL,
  `capa` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` enum('admin','user') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `foto_perfil` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'default.jpg',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `tipo`, `foto_perfil`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@admin.com', '$2y$10$OYipWM3B9dSloJrEZeRezOfVPxioEf89jRfwOt5wJ37UqBMfXz5g2', 'admin', 'default.jpg', '2025-08-31 00:31:54', '2025-08-31 00:31:54'),
(2, 'julio', 'jc1368222@gmail.com', '$2y$10$WK.KasDH2UFqupPgRP46s.WfFTfhjIKybho7uFHyyhkocG7/c3G6y', 'user', 'default.jpg', '2025-08-31 00:32:34', '2025-08-31 00:35:50');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `animes`
--
ALTER TABLE `animes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices para tabela `anime_generos`
--
ALTER TABLE `anime_generos`
  ADD PRIMARY KEY (`anime_id`,`genero_id`),
  ADD KEY `genero_id` (`genero_id`);

--
-- Índices para tabela `ano`
--
ALTER TABLE `ano`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `valor` (`valor`);

--
-- Índices para tabela `avaliacoes`
--
ALTER TABLE `avaliacoes`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_comentarios_episodio_id` (`episodio_id`);

--
-- Índices para tabela `episodios`
--
ALTER TABLE `episodios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `anime_id` (`anime_id`,`temporada`,`numero`);

--
-- Índices para tabela `episodio_reacoes`
--
ALTER TABLE `episodio_reacoes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `episodio_id` (`episodio_id`,`user_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_reacoes_episodio_id` (`episodio_id`);

--
-- Índices para tabela `favoritos`
--
ALTER TABLE `favoritos`
  ADD PRIMARY KEY (`id_favorito`);

--
-- Índices para tabela `generos`
--
ALTER TABLE `generos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices para tabela `historico`
--
ALTER TABLE `historico`
  ADD PRIMARY KEY (`id_historico`);

--
-- Índices para tabela `noticias`
--
ALTER TABLE `noticias`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `episodio_id` (`episodio_id`);

--
-- Índices para tabela `quiz_respostas`
--
ALTER TABLE `quiz_respostas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_user_pergunta` (`user_id`,`pergunta_id`),
  ADD KEY `episodio_id` (`episodio_id`),
  ADD KEY `pergunta_id` (`pergunta_id`);

--
-- Índices para tabela `recomendacoes`
--
ALTER TABLE `recomendacoes`
  ADD PRIMARY KEY (`id_recomendacoes`);

--
-- Índices para tabela `suporte`
--
ALTER TABLE `suporte`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `temporadas`
--
ALTER TABLE `temporadas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `anime_id` (`anime_id`,`numero`);

--
-- Índices para tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `animes`
--
ALTER TABLE `animes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `ano`
--
ALTER TABLE `ano`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `avaliacoes`
--
ALTER TABLE `avaliacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `episodios`
--
ALTER TABLE `episodios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `episodio_reacoes`
--
ALTER TABLE `episodio_reacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `favoritos`
--
ALTER TABLE `favoritos`
  MODIFY `id_favorito` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `generos`
--
ALTER TABLE `generos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `historico`
--
ALTER TABLE `historico`
  MODIFY `id_historico` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `noticias`
--
ALTER TABLE `noticias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `quiz_respostas`
--
ALTER TABLE `quiz_respostas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `recomendacoes`
--
ALTER TABLE `recomendacoes`
  MODIFY `id_recomendacoes` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `suporte`
--
ALTER TABLE `suporte`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `temporadas`
--
ALTER TABLE `temporadas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `anime_generos`
--
ALTER TABLE `anime_generos`
  ADD CONSTRAINT `anime_generos_ibfk_1` FOREIGN KEY (`anime_id`) REFERENCES `animes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `anime_generos_ibfk_2` FOREIGN KEY (`genero_id`) REFERENCES `generos` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`episodio_id`) REFERENCES `episodios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `episodios`
--
ALTER TABLE `episodios`
  ADD CONSTRAINT `episodios_ibfk_1` FOREIGN KEY (`anime_id`) REFERENCES `animes` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `episodio_reacoes`
--
ALTER TABLE `episodio_reacoes`
  ADD CONSTRAINT `episodio_reacoes_ibfk_1` FOREIGN KEY (`episodio_id`) REFERENCES `episodios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `episodio_reacoes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `quizzes_ibfk_1` FOREIGN KEY (`episodio_id`) REFERENCES `episodios` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `quiz_respostas`
--
ALTER TABLE `quiz_respostas`
  ADD CONSTRAINT `quiz_respostas_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_respostas_ibfk_2` FOREIGN KEY (`episodio_id`) REFERENCES `episodios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_respostas_ibfk_3` FOREIGN KEY (`pergunta_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `temporadas`
--
ALTER TABLE `temporadas`
  ADD CONSTRAINT `temporadas_ibfk_1` FOREIGN KEY (`anime_id`) REFERENCES `animes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
