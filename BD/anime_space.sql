-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 10-Out-2025 às 01:45
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

INSERT INTO `animes` (`id`, `nome`, `nota`, `sinopse`, `capa`, `ano`, `linguagem`, `created_at`, `updated_at`) VALUES
(1, 'Solo Leveling', '8.5', 'Em um mundo onde portais místicos — as dungeons — surgem, caçadores enfrentam monstros para proteger a humanidade. Sung Jinwoo, classificado como um Hunter de Rank E, é considerado o mais fraco entre eles. Tudo muda quando ele sobrevive por pouco a uma dungeon dupla que quase aniquila seu grupo. Após esse evento traumático, Jinwoo é escolhido por um misterioso programa chamado Sistema, que lhe concede uma habilidade única: subir de nível infinitamente, algo impossível para os demais caçadores. Ele parte então em uma jornada incrível, enfrentando monstros e humanos em busca da origem de seu poder, até descobrir que foi destinado a se tornar o Shadow Monarch, um necromante imortal que luta para salvar a humanidade contra os demais Monarcas, que buscam sua destruição.', 'solo.jpg', 2021, NULL, '2025-10-09 23:10:36', '2025-10-09 23:10:36'),
(2, 'Sousou no Frieren', '9.2', 'A história começa após um grande triunfo: a mage élfica Frieren fazia parte de um grupo heroico que derrotou o Rei Demônio em uma jornada de dez anos. Após a vitória, seus companheiros — humanos e anões — envelhecem e morrem, enquanto Frieren, com seu tempo de vida milenar, os vê como eventos breves em sua própria existência. Ela retorna cinquenta anos depois — e percebe que perdeu a chance de se aprofundar nas relações com seus companheiros humanos, especialmente com o herói Himmel. Movida pelo arrependimento, ela embarca em uma nova jornada para reencontrar o passado, cumprir os desejos finais dos amigos e ensinar sua aprendiz humana, Fern, enquanto reflete sobre a transitoriedade da vida, a profundidade das emoções humanas e o valor das conexões verdadeiras.', 'frieren.jpg', 2022, 'legendado', '2025-10-09 23:10:36', '2025-10-09 23:10:36'),
(3, 'Dandadan', '8.8', 'Momo Ayase”, que acredita em fantasmas mas não em alienígenas, e “Ken Takakura” (apelidado Okarun), que acredita em alienígenas mas nega a existência de fantasmas, acabam em uma aposta para provar quem está certo — ela visita um hotspot alienígena, ele um lugar assombrado. Isso desencadeia eventos sobrenaturais extremos: Momo é abduzida por aliens, liberando seus poderes psíquicos latentes; Okarun, por sua vez, é possuído por um espírito. Juntos, enfrentam ameaças sobrenaturais com uma mistura caótica de ação, comédia e romance adolescente — desde espíritos malignos até confrontos com alienígenas. A história combina horror escancarado, risos constrangedores e rostos rosa de vergonha típica da puberdade', 'dandadan.jpg', 2023, 'legendado', '2025-10-09 23:10:36', '2025-10-09 23:10:36'),
(4, 'Dungeon Meshi', '8.7', 'Nesse mundo de fantasia, um grupo de aventureiros falha em derrotar um dragão vermelho e precisa salvar sua companheira feiticeira Falin, teletransportada para fora antes de ser devorada. Sem dinheiro ou suprimentos, eles decidem sobreviver cozinhando e comendo os monstros que encontram na dungeon. Liderados por Laios, um espadachim obcecado por monstros, e contando com um anão cozinheiro chamado Senshi, eles devolvem vida — literalmente — à aventura com criatividade e bom humor', 'dungeon.jpg', 2024, 'legendado', '2025-10-09 23:10:36', '2025-10-09 23:10:36'),
(5, 'Kaiju No. 8', '8.6', 'Kafka Hibino trabalha limpando monstros kaiju, enquanto seu sonho de infância era se tornar membro da força que combate essas criaturas. Tudo muda quando ele acaba ingerindo um kaiju — e passa a ter a habilidade de se transformar em um deles. Agora, Kafka usa esse poder inusitado para ingressar na força de defesa e cumprir uma promessa feita a uma amiga de infância', 'kaiju8.jpg', 2024, 'legendado', '2025-10-09 23:10:36', '2025-10-09 23:10:36'),
(6, 'Tensei Shitara Slime Datta Ken', '8.4', 'Satoru Mikami, um funcionário comum de 37 anos, morre em um assalto e renasce em um mundo de fantasia como um slime. Apesar da forma incomum, ele adquire habilidades únicas, como predador e grande sábio. Adota o nome Rimuru Tempest e decide criar um mundo onde todos possam viver em paz, reunindo aliados poderosos e enfrentando inimigos temíveis.', 'slime.jpg', 2021, 'legendado', '2025-10-09 23:10:36', '2025-10-09 23:10:36'),
(7, 'Tokyo Revengers', '8.2', 'Takemichi Hanagaki descobre que sua ex-namorada do ensino médio, Hinata, morreu em um confronto de gangues. Ao sofrer um acidente, ele é misteriosamente transportado 12 anos para o passado, quando ainda estava no ensino médio. Com essa segunda chance, Takemichi tenta mudar o curso dos acontecimentos, infiltrando-se na Tokyo Manji Gang e lutando para salvar Hinata e seus amigos.', 'tokyo.jpg', 2021, 'legendado', '2025-10-09 23:10:36', '2025-10-09 23:10:36'),
(8, 'Spy x Family', '9.0', 'O espião de codinome \"Twilight\" recebe a missão de se infiltrar em uma escola de elite. Para isso, ele cria uma família falsa: adota a menina Anya, que tem poderes telepáticos, e se casa com Yor, uma assassina profissional que mantém sua identidade em segredo. Enquanto tentam manter as aparências, eles acabam desenvolvendo laços reais que mudam suas vidas.', 'spy.jpg', 2022, 'legendado', '2025-10-09 23:10:36', '2025-10-09 23:10:36'),
(9, 'Sakamoto Days', '9.5', 'ffdfdfdddgdbvgf', 'days.jpg', 2025, NULL, '2025-10-09 23:17:27', '2025-10-09 23:17:27'),
(10, 'Jujutsu Kisen', '8.9', 'ddcvdvkçsdlvçdldl', 'jujutso.jpg', 2023, NULL, '2025-10-09 23:22:41', '2025-10-09 23:22:41'),
(11, 'Hunter x Hunter', '9.0', 'vdvgdvsxfsxcvsxfsx', 'hunter.jpg', 2010, NULL, '2025-10-09 23:25:10', '2025-10-09 23:25:10'),
(12, 'Fairy Tail', '9.7', 'dvdvdvdvdvdv', 'fairy.jpg', 2011, NULL, '2025-10-09 23:28:43', '2025-10-09 23:28:43'),
(13, 'Jojo Bizzare', '8.5', 'axcccasSsSs', 'jojo.jpg', 2018, NULL, '2025-10-09 23:30:44', '2025-10-09 23:30:44'),
(14, 'Attack on Titan', '9.0', 'aasassasdadadadad', 'titan.jpg', 2010, NULL, '2025-10-09 23:33:19', '2025-10-09 23:33:19'),
(15, 'One Punch Man', '9.3', 'sdfasdfadadada', 'punch.jpg', 2017, NULL, '2025-10-09 23:35:21', '2025-10-09 23:35:21'),
(16, 'Naruto Shippuden', '9.8', 'zccsdaaaadadadadadad', 'naruto.jpg', 2010, NULL, '2025-10-09 23:38:00', '2025-10-09 23:38:00'),
(17, 'Overlord', '9.9', 'adadavdvdfgfsf', 'overlord.jpg', 2014, NULL, '2025-10-09 23:43:12', '2025-10-09 23:43:12');

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
(5, 8),
(6, 2),
(6, 5),
(6, 6),
(7, 1),
(7, 3),
(7, 4),
(8, 1),
(8, 4),
(8, 6),
(9, 1),
(9, 5),
(9, 6),
(9, 9),
(10, 1),
(10, 3),
(10, 6),
(10, 11),
(11, 1),
(11, 2),
(11, 5),
(11, 6),
(12, 1),
(12, 2),
(12, 5),
(12, 6),
(13, 1),
(13, 5),
(13, 6),
(13, 10),
(14, 1),
(14, 2),
(14, 4),
(14, 8),
(14, 11),
(15, 1),
(15, 2),
(15, 5),
(15, 6),
(16, 1),
(16, 2),
(16, 5),
(16, 6),
(17, 1),
(17, 2),
(17, 3),
(17, 5),
(17, 11);

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
  `user_id` int(11) NOT NULL,
  `anime_id` int(11) NOT NULL,
  `nota` decimal(3,1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `avaliacoes`
--

INSERT INTO `avaliacoes` (`user_id`, `anime_id`, `nota`) VALUES
(1, 1, '10.0');

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
(1, 1, 1, 1, 'O Mais Fraco', 'Jinwoo Sung, um caçador de baixo nível, enfrenta um calabouço perigoso e quase morre. Após esse evento, ele ganha uma habilidade única que permite aumentar seu poder constantemente.', 24, '2021-07-01', 'logo.png', 'https://youtu.be/XqD0oCHLIF8?si=pauVvPi57dqLWJy3', 'legendado'),
(2, 1, 1, 2, 'Despertar', 'Jinwoo começa a explorar seu novo poder e realiza missões mais difíceis, descobrindo que pode evoluir muito além do esperado para um caçador comum.', 24, '2021-07-08', 'logo.png', 'https://youtu.be/XqD0oCHLIF8?si=pauVvPi57dqLWJy3', 'legendado'),
(3, 1, 1, 3, 'Portão Mortal', 'Com seu crescimento, Jinwoo atrai atenção e começa a enfrentar inimigos maiores e mais perigosos, preparando-se para desafios que podem mudar seu destino.', 25, '2021-07-15', 'logo.png', 'https://youtu.be/XqD0oCHLIF8?si=pauVvPi57dqLWJy3', 'legendado'),
(4, 2, 1, 1, 'Adeus Heróis', 'Após a derrota do Rei Demônio, a elfa maga Frieren reflete sobre sua longa vida e a efemeridade dos humanos, começando uma jornada para entender melhor seus antigos companheiros.', 24, '2022-10-01', 'logo.png', 'https://youtu.be/-jGBp5HBLFs?si=T5DOoKvUdENIS368', 'legendado'),
(5, 2, 1, 2, 'Lembranças Eternas', 'Frieren visita os locais que marcou com sua antiga equipe, encontrando memórias e pessoas que a fazem questionar o valor do tempo e da amizade.', 24, '2022-10-08', 'logo.png', 'https://youtu.be/-jGBp5HBLFs?si=T5DOoKvUdENIS368', 'legendado'),
(6, 2, 1, 3, 'Viagem ao Norte', 'Ela encontra um jovem aprendiz e começa a ensinar magia, buscando transmitir seus conhecimentos antes que o tempo a alcance.', 25, '2022-10-15', 'logo.png', 'https://youtu.be/-jGBp5HBLFs?si=T5DOoKvUdENIS368', 'legendado');

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

-- --------------------------------------------------------

--
-- Estrutura da tabela `favoritos`
--

CREATE TABLE `favoritos` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `anime_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `favoritos`
--

INSERT INTO `favoritos` (`id`, `user_id`, `anime_id`, `created_at`) VALUES
(3, 1, 1, '2025-10-09 23:15:13');

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
(8, 'Militar', 0),
(9, 'Romance', 1),
(10, 'Ficção-Científica', 1),
(11, 'Terror', 0),
(12, 'Mistério', 0),
(13, 'Jogos', 1),
(14, 'Musical', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `historico`
--

CREATE TABLE `historico` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `episodio_id` int(11) NOT NULL,
  `data_assistido` timestamp NOT NULL DEFAULT current_timestamp(),
  `progresso_segundos` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `noticias`
--

CREATE TABLE `noticias` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `resumo` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `conteudo` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `imagem` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url_externa` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tags` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visualizacoes` int(11) DEFAULT 0,
  `data_publicacao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `noticias`
--

INSERT INTO `noticias` (`id`, `titulo`, `resumo`, `conteudo`, `imagem`, `url_externa`, `tags`, `visualizacoes`, `data_publicacao`) VALUES
(1, 'Novo Anime de Ação Anunciado para 2025', 'A produtora confirmou o lançamento de um anime que promete revolucionar o gênero de ação.', 'Durante a Anime Expo 2025, o estúdio revelou detalhes sobre sua nova produção. A série contará com batalhas épicas, personagens carismáticos e animação de ponta. O lançamento está previsto para outubro de 2025.', 'anime_acao2025.jpg', 'https://www.animenewsbrasil.com.br/noticia/acao-2025', 'ação, lançamento, anime', 215, '2025-10-09 20:10:37'),
(2, 'Mangá Popular Chega ao Fim', 'Após mais de 10 anos de publicação, um dos mangás mais queridos pelos fãs está chegando ao seu último capítulo.', 'O autor anunciou em entrevista que o arco final será emocionante e cheio de reviravoltas. Fãs aguardam ansiosos pela conclusão que promete ser inesquecível.', 'manga_final.jpg', 'https://www.animenewsbrasil.com.br/noticia/manga-final', 'mangá, final, drama', 542, '2025-10-09 20:10:37'),
(3, 'Evento Geek Reúne Milhares de Fãs', 'A convenção anual de cultura pop bateu recorde de público neste final de semana.', 'Além de palestras e lançamentos, o evento contou com concursos de cosplay, estandes de produtos exclusivos e pré-estreias de novos animes. A organização já confirmou a edição de 2026.', 'evento_geek2025.png', 'https://www.animenewsbrasil.com.br/noticia/evento-geek-2025', 'evento, geek, cultura pop', 367, '2025-10-09 20:10:37'),
(4, 'Nova Temporada de \"Sword Legend\" Ganha Trailer Épico', 'Os fãs aguardavam ansiosamente e agora o primeiro trailer da nova temporada já está disponível.', 'O estúdio revelou cenas inéditas da continuação da série, com foco em novos personagens e batalhas ainda mais intensas. A estreia está marcada para janeiro de 2026.', 'sword_legend_trailer.jpg', 'https://www.animenewsbrasil.com.br/noticia/sword-legend-2026', 'fantasia, ação, trailer', 489, '2025-10-09 20:10:37'),
(5, 'Top 10 Animes Mais Assistidos de 2024', 'A lista dos animes mais assistidos do ano surpreendeu com algumas produções independentes.', 'Entre os mais assistidos estão produções que conquistaram o público por suas histórias envolventes e trilhas sonoras marcantes. Confira a lista completa e onde assistir.', 'top10_animes2024.jpg', 'https://www.animenewsbrasil.com.br/noticia/top10-2024', 'ranking, popularidade, animes', 628, '2025-10-09 20:10:37');

-- --------------------------------------------------------

--
-- Estrutura da tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL,
  `estoque` int(11) DEFAULT 0,
  `imagem` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `categoria` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `quizzes`
--

CREATE TABLE `quizzes` (
  `id` int(11) NOT NULL,
  `anime_id` int(11) NOT NULL,
  `temporada` int(11) DEFAULT NULL,
  `pergunta` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alternativa_a` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alternativa_b` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alternativa_c` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alternativa_d` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `resposta_correta` char(1) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `quiz_respostas`
--

CREATE TABLE `quiz_respostas` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `anime_id` int(11) NOT NULL,
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
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `anime_id` int(11) NOT NULL,
  `motivo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_recomendacao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `suporte`
--

CREATE TABLE `suporte` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mensagem` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_envio` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

--
-- Extraindo dados da tabela `temporadas`
--

INSERT INTO `temporadas` (`id`, `anime_id`, `numero`, `nome`, `ano_inicio`, `ano_fim`, `qtd_episodios`, `capa`) VALUES
(1, 1, 1, 'Temporada 1', 2021, 2021, 3, 'solo.jpg'),
(2, 2, 1, 'Temporada 1', 2022, 2022, 3, 'frieren.jpg'),
(3, 3, 1, 'Temporada 1', 2023, 2023, 0, 'dandadan.jpg'),
(4, 4, 1, 'Temporada 1', 2024, 2024, 0, 'dungeon.jpg'),
(5, 5, 1, 'Temporada 1', 2024, 2024, 0, 'kaiju8.jpg'),
(6, 6, 1, 'Temporada 1', 2021, 2021, 0, 'slime.jpg'),
(7, 7, 1, 'Temporada 1', 2021, 2021, 0, 'tokyo.jpg'),
(8, 8, 1, 'Temporada 1', 2022, 2022, 0, 'spy.jpg');

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uses_2fa` tinyint(1) DEFAULT 0,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` enum('admin','user') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `foto_perfil` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'default.jpg',
  `xp` int(11) NOT NULL DEFAULT 0,
  `nivel` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `uses_2fa`, `password`, `tipo`, `foto_perfil`, `xp`, `nivel`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@admin.com', 0, '$2y$10$XNYmtVgbVQC7sg/HzrNoje0f1uMW4fs1fw0g/UePabKkBu5DdqXB2', 'admin', 'uploads/1.jpeg', 45, 1, '2025-10-09 23:10:37', '2025-10-09 23:15:17');

-- --------------------------------------------------------

--
-- Estrutura da tabela `xp_logs`
--

CREATE TABLE `xp_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tipo_acao` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `referencia_id` int(11) DEFAULT NULL,
  `xp_ganho` int(11) NOT NULL,
  `data_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `xp_logs`
--

INSERT INTO `xp_logs` (`id`, `user_id`, `tipo_acao`, `referencia_id`, `xp_ganho`, `data_registro`) VALUES
(1, 1, 'favorito', 1, 20, '2025-10-09 23:15:10'),
(2, 1, 'avaliacao', 1, 25, '2025-10-09 23:15:17');

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
  ADD PRIMARY KEY (`user_id`,`anime_id`),
  ADD KEY `anime_id` (`anime_id`);

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
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_user_anime_fav` (`user_id`,`anime_id`),
  ADD KEY `anime_id` (`anime_id`);

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
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_user_episodio_hist` (`user_id`,`episodio_id`),
  ADD KEY `episodio_id` (`episodio_id`);

--
-- Índices para tabela `noticias`
--
ALTER TABLE `noticias`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `anime_id` (`anime_id`);

--
-- Índices para tabela `quiz_respostas`
--
ALTER TABLE `quiz_respostas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_user_pergunta` (`user_id`,`pergunta_id`),
  ADD KEY `anime_id` (`anime_id`),
  ADD KEY `pergunta_id` (`pergunta_id`);

--
-- Índices para tabela `recomendacoes`
--
ALTER TABLE `recomendacoes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_user_anime_rec` (`user_id`,`anime_id`),
  ADD KEY `anime_id` (`anime_id`);

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
-- Índices para tabela `xp_logs`
--
ALTER TABLE `xp_logs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_user_acao_ref` (`user_id`,`tipo_acao`,`referencia_id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `animes`
--
ALTER TABLE `animes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de tabela `ano`
--
ALTER TABLE `ano`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `favoritos`
--
ALTER TABLE `favoritos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `generos`
--
ALTER TABLE `generos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `historico`
--
ALTER TABLE `historico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `noticias`
--
ALTER TABLE `noticias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `suporte`
--
ALTER TABLE `suporte`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `temporadas`
--
ALTER TABLE `temporadas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `xp_logs`
--
ALTER TABLE `xp_logs`
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
-- Limitadores para a tabela `avaliacoes`
--
ALTER TABLE `avaliacoes`
  ADD CONSTRAINT `avaliacoes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `avaliacoes_ibfk_2` FOREIGN KEY (`anime_id`) REFERENCES `animes` (`id`) ON DELETE CASCADE;

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
-- Limitadores para a tabela `favoritos`
--
ALTER TABLE `favoritos`
  ADD CONSTRAINT `favoritos_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favoritos_ibfk_2` FOREIGN KEY (`anime_id`) REFERENCES `animes` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `historico`
--
ALTER TABLE `historico`
  ADD CONSTRAINT `historico_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `historico_ibfk_2` FOREIGN KEY (`episodio_id`) REFERENCES `episodios` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `quizzes_ibfk_1` FOREIGN KEY (`anime_id`) REFERENCES `animes` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `quiz_respostas`
--
ALTER TABLE `quiz_respostas`
  ADD CONSTRAINT `quiz_respostas_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_respostas_ibfk_2` FOREIGN KEY (`anime_id`) REFERENCES `animes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_respostas_ibfk_3` FOREIGN KEY (`pergunta_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `recomendacoes`
--
ALTER TABLE `recomendacoes`
  ADD CONSTRAINT `recomendacoes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recomendacoes_ibfk_2` FOREIGN KEY (`anime_id`) REFERENCES `animes` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `temporadas`
--
ALTER TABLE `temporadas`
  ADD CONSTRAINT `temporadas_ibfk_1` FOREIGN KEY (`anime_id`) REFERENCES `animes` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `xp_logs`
--
ALTER TABLE `xp_logs`
  ADD CONSTRAINT `xp_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
