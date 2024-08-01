<?php

namespace App\Controllers;

use CodeIgniter\Database\Config;

class Inicializandobancodados extends BaseController
{
	public function __construct()
	{
	}

	public function index()
	{
		$db = db_connect();

		$db->query('CREATE TABLE IF NOT EXISTS `colaboradores` (
		`id` int PRIMARY KEY AUTO_INCREMENT,
		`apelido` varchar(255) NOT NULL,
		`avatar` varchar(255) DEFAULT null,
		`email` varchar(255) NOT NULL,
		`twitter` varchar(255) DEFAULT null,
		`carteira` varchar(255) DEFAULT null,
		`senha` varchar(64) NOT NULL,
		`pontuacao_total` double DEFAULT 0,
		`pontuacao_mensal` double DEFAULT 0,
		`strikes` int DEFAULT 0,
		`strike_data` datetime DEFAULT null,
		`confirmacao_hash` varchar(64),
		`confirmado_data` datetime DEFAULT null,
		`criado` datetime DEFAULT now(),
		`atualizado` datetime DEFAULT now(),
		`excluido` datetime DEFAULT null
			);
		');
		$db->query('CREATE TABLE IF NOT EXISTS  `atribuicoes` (
		`id` int PRIMARY KEY AUTO_INCREMENT,
		`nome` varchar(255) NOT NULL,
		`cor` varchar(255) DEFAULT null,
		`criado` datetime DEFAULT now(),
		`atualizado` datetime DEFAULT now(),
		`excluido` datetime DEFAULT null
			);
		');
		$db->query('CREATE TABLE IF NOT EXISTS  `colaboradores_atribuicoes` (
		`colaboradores_id` int NOT NULL,
		`atribuicoes_id` int NOT NULL,
		PRIMARY KEY (`colaboradores_id`, `atribuicoes_id`)
			);
		');
		$db->query('CREATE TABLE IF NOT EXISTS  `pautas` (
		`id` varchar(36) PRIMARY KEY DEFAULT "uuid()",
		`colaboradores_id` int,
		`redator_colaboradores_id` int DEFAULT null,
		`link` varchar(255),
		`titulo` varchar(255),
		`texto` TEXT,
		`imagem` varchar(255),
		`pauta_antiga` CHAR(1) NOT NULL DEFAULT "N",
		`reservado` datetime DEFAULT null,
		`tag_fechamento` varchar(255) DEFAULT null,
		`criado` datetime DEFAULT now(),
		`atualizado` datetime DEFAULT now(),
		`excluido` datetime DEFAULT null
			);
		');
		$db->query('CREATE TABLE IF NOT EXISTS  `artigos` (
		`id` varchar(36) PRIMARY KEY DEFAULT "uuid()",
		`url_friendly` varchar(255) NOT NULL,
		`fase_producao_id` int NOT NULL,
		`tipo_artigo` CHAR(1) NOT NULL DEFAULT "T",
		`link` varchar(255) DEFAULT null,
		`sugerido_colaboradores_id` int DEFAULT null,
		`titulo` varchar(255) NOT NULL,
		`gancho` varchar(255) NOT NULL,
		`texto_original` TEXT NOT NULL,
		`referencias` TEXT DEFAULT null,
		`imagem` varchar(255) NOT NULL,
		`escrito_colaboradores_id` int DEFAULT null,
		`texto_revisado` TEXT DEFAULT null,
		`revisado_colaboradores_id` int DEFAULT null,
		`arquivo_audio` varchar(255) DEFAULT null,
		`narrado_colaboradores_id` int DEFAULT null,
		`link_produzido` varchar(255) DEFAULT null,
		`link_shorts` VARCHAR(255) NULL, 
		`produzido_colaboradores_id` int DEFAULT null,
		`publicado` datetime DEFAULT null,
		`publicado_colaboradores_id` int DEFAULT null,
		`link_video_youtube` varchar(255) DEFAULT null,
		`descartado` datetime DEFAULT null,
		`descartado_colaboradores_id` int DEFAULT null,
		`palavras_escritor` int NOT NULL,
		`palavras_revisor` int NOT NULL,
		`palavras_narrador` int NOT NULL,
		`palavras_produtor` int NOT NULL,
		`marcado_colaboradores_id` int DEFAULT null,
		`marcado` datetime DEFAULT null,
		`criado` datetime DEFAULT now(),
		`atualizado` datetime DEFAULT now()
			);
		');
		$db->query('CREATE TABLE IF NOT EXISTS  `fase_producao` (
		`id` int PRIMARY KEY AUTO_INCREMENT,
		`nome` varchar(255) NOT NULL,
		`etapa_anterior` int DEFAULT null,
		`etapa_posterior` int DEFAULT null,
		`mostrar_site` CHAR(1) NOT NULL DEFAULT "N"
			);
			');
		$db->query('CREATE TABLE IF NOT EXISTS  `categorias` (
		`id` int PRIMARY KEY AUTO_INCREMENT,
		`nome` varchar(255) NOT NULL,
		`criado` datetime DEFAULT now(),
		`atualizado` datetime DEFAULT now(),
		`excluido` datetime DEFAULT null
			);
			');
		$db->query('CREATE TABLE IF NOT EXISTS  `artigos_comentarios` (
		`id` varchar(36) PRIMARY KEY DEFAULT "uuid()",
		`artigos_id` varchar(36) NOT NULL,
		`colaboradores_id` int NOT NULL,
		`comentario` TEXT NOT NULL,
		`criado` datetime DEFAULT now(),
		`atualizado` datetime DEFAULT now(),
		`excluido` datetime DEFAULT null
			);
			');
		$db->query('CREATE TABLE IF NOT EXISTS  `artigos_categorias` (
		`artigos_id` varchar(36) NOT NULL,
		`categorias_id` int NOT NULL,
		PRIMARY KEY (`artigos_id`, `categorias_id`)
			);
			');

		$db->query('CREATE TABLE IF NOT EXISTS  `pautas_fechadas` (
			`id` int PRIMARY KEY AUTO_INCREMENT,
			`titulo` varchar(255),
			`criado` datetime DEFAULT now(),
			`excluido` datetime DEFAULT null
		);
		');

		$db->query('CREATE TABLE IF NOT EXISTS  `pautas_pautas_fechadas` (
			`pautas_id` varchar(36) NOT NULL,
			`pautas_fechadas_id` int NOT NULL,
			PRIMARY KEY (`pautas_id`, `pautas_fechadas_id`)
		);
		');

		$db->query('CREATE TABLE IF NOT EXISTS  `pagamentos` (
			`id` int PRIMARY KEY AUTO_INCREMENT,
			`titulo` varchar(255),
			`quantidade_bitcoin` double DEFAULT 0,
			`multiplicador_escrito` float DEFAULT 0,
			`multiplicador_revisado` float DEFAULT 0,
			`multiplicador_narrado` float DEFAULT 0,
			`multiplicador_produzido` float DEFAULT 0,
			`multiplicador_escrito_noticia` float DEFAULT 0,
			`multiplicador_revisado_noticia` float DEFAULT 0,
			`multiplicador_narrado_noticia` float DEFAULT 0,
			`multiplicador_produzido_noticia` float DEFAULT 0,
			`hash_transacao` varchar(256) DEFAULT null,
			`pontuacao_total` float DEFAULT null,
			`criado` datetime DEFAULT now(),
			`atualizado` datetime DEFAULT now()
		);
		');

		$db->query('CREATE TABLE IF NOT EXISTS  `pagamentos_artigos` (
			`artigos_id` varchar(36) NOT NULL,
			`pagamentos_id` int NOT NULL,
			PRIMARY KEY (`artigos_id`, `pagamentos_id`)
		);
		');

		$db->query('CREATE TABLE IF NOT EXISTS  `artigos_historicos` (
			`id` varchar(36) PRIMARY KEY DEFAULT "uuid()",
			`artigos_id` varchar(36) NOT NULL,
			`acao` varchar(255) NOT NULL,
			`colaboradores_id` int NOT NULL,
			`criado` datetime DEFAULT now()
		);
		');

		$db->query('CREATE TABLE IF NOT EXISTS  `configuracao` (
			`config` varchar(255) PRIMARY KEY,
			`config_valor` TEXT NOT NULL
		);
		');

		$db->query('CREATE TABLE IF NOT EXISTS  `colaboradores_historicos` (
			`id` varchar(36) PRIMARY KEY DEFAULT "uuid()",
			`colaboradores_id` int NOT NULL,
			`acao` varchar(256) DEFAULT null,
			`objeto` varchar(256) DEFAULT null,
			`objeto_id` varchar(256) DEFAULT null,
			`criado` datetime DEFAULT now()
		);
		');

		$db->query('CREATE TABLE IF NOT EXISTS  `pautas_comentarios` (
		`id` varchar(36) PRIMARY KEY DEFAULT "uuid()",
		`pautas_id` varchar(36) NOT NULL,
		`colaboradores_id` int NOT NULL,
		`comentario` TEXT NOT NULL,
		`criado` datetime DEFAULT now(),
		`atualizado` datetime DEFAULT now(),
		`excluido` datetime DEFAULT null
			);
			');

		$db->query('CREATE TABLE IF NOT EXISTS `colaboradores_notificacoes` (
			`id` varchar(36) PRIMARY KEY DEFAULT "uuid()",
			`sujeito_colaboradores_id` INT NOT NULL,
			`acao` VARCHAR(255) NOT NULL,
			`objeto` VARCHAR(255) NOT NULL,
			`notificacao` VARCHAR(255) NOT NULL,
			`id_objeto` VARCHAR(36) NOT NULL,
			`colaboradores_id` INT NOT NULL,
			`data_visualizado` DATETIME NULL DEFAULT NULL,
			`criado` datetime DEFAULT now()
			);
			');

		$db->query('CREATE TABLE IF NOT EXISTS `avisos` (
				`id` varchar(36) PRIMARY KEY DEFAULT "uuid()",
				`aviso` TEXT NOT NULL,
				`link` varchar(255) DEFAULT null,
				`inicio` datetime DEFAULT null,
				`fim` datetime DEFAULT null,
				`criado` datetime DEFAULT now()
			);
			');

		$db->query('CREATE TABLE IF NOT EXISTS `paginas_estaticas` (
				`id` varchar(36) PRIMARY KEY DEFAULT "uuid()",
    			`ativo` CHAR(1) NOT NULL DEFAULT "A",
    			`url_friendly` varchar(255) NOT NULL,
    			`localizacao` varchar(127) NOT NULL,
				`titulo` varchar(255) NOT NULL,
				`conteudo` TEXT NOT NULL,
				`criado` datetime DEFAULT now(),
				`atualizado` datetime DEFAULT now()
			);
		');

		$db->query('
			ALTER TABLE `colaboradores_atribuicoes` ADD FOREIGN KEY (`colaboradores_id`) REFERENCES `colaboradores` (`id`);
			');
		$db->query('
			ALTER TABLE `colaboradores_atribuicoes` ADD FOREIGN KEY (`atribuicoes_id`) REFERENCES `atribuicoes` (`id`);
			');
		$db->query('
			ALTER TABLE `pautas` ADD FOREIGN KEY (`colaboradores_id`) REFERENCES `colaboradores` (`id`);
			');
		$db->query('
			ALTER TABLE `pautas` ADD FOREIGN KEY (`redator_colaboradores_id`) REFERENCES `colaboradores` (`id`);
			');
		$db->query('
			ALTER TABLE `artigos` ADD FOREIGN KEY (`fase_producao_id`) REFERENCES `fase_producao` (`id`);
			');
		$db->query('
			ALTER TABLE `artigos` ADD FOREIGN KEY (`sugerido_colaboradores_id`) REFERENCES `colaboradores` (`id`);
			');
		$db->query('
			ALTER TABLE `artigos` ADD FOREIGN KEY (`escrito_colaboradores_id`) REFERENCES `colaboradores` (`id`);
			');
		$db->query('
			ALTER TABLE `artigos` ADD FOREIGN KEY (`revisado_colaboradores_id`) REFERENCES `colaboradores` (`id`);
			');
		$db->query('
			ALTER TABLE `artigos` ADD FOREIGN KEY (`narrado_colaboradores_id`) REFERENCES `colaboradores` (`id`);
			');
		$db->query('
			ALTER TABLE `artigos` ADD FOREIGN KEY (`produzido_colaboradores_id`) REFERENCES `colaboradores` (`id`);
			');
		$db->query('
			ALTER TABLE `artigos` ADD FOREIGN KEY (`publicado_colaboradores_id`) REFERENCES `colaboradores` (`id`);
			');
		$db->query('
			ALTER TABLE `artigos` ADD FOREIGN KEY (`descartado_colaboradores_id`) REFERENCES `colaboradores` (`id`);
			');
		$db->query('
			ALTER TABLE `artigos` ADD FOREIGN KEY (`marcado_colaboradores_id`) REFERENCES `colaboradores` (`id`);
			');
		$db->query('
			ALTER TABLE `fase_producao` ADD FOREIGN KEY (`etapa_anterior`) REFERENCES `fase_producao` (`id`);
			');
		$db->query('
			ALTER TABLE `fase_producao` ADD FOREIGN KEY (`etapa_posterior`) REFERENCES `fase_producao` (`id`);
			');
		$db->query('
			ALTER TABLE `artigos_categorias` ADD FOREIGN KEY (`artigos_id`) REFERENCES `artigos` (`id`);
			');
		$db->query('
			ALTER TABLE `artigos_categorias` ADD FOREIGN KEY (`categorias_id`) REFERENCES `categorias` (`id`);
			');
		$db->query('
			ALTER TABLE `artigos_comentarios` ADD FOREIGN KEY (`artigos_id`) REFERENCES `artigos` (`id`);
			');
		$db->query('
			ALTER TABLE `artigos_comentarios` ADD FOREIGN KEY (`colaboradores_id`) REFERENCES `colaboradores` (`id`);
			');
		$db->query('
			ALTER TABLE `pautas_comentarios` ADD FOREIGN KEY (`pautas_id`) REFERENCES `pautas` (`id`);
			');
		$db->query('
			ALTER TABLE `pautas_comentarios` ADD FOREIGN KEY (`colaboradores_id`) REFERENCES `colaboradores` (`id`);
			');
		$db->query('
			ALTER TABLE `pagamentos_artigos` ADD FOREIGN KEY (`artigos_id`) REFERENCES `artigos` (`id`);
			');
		$db->query('
			ALTER TABLE `pagamentos_artigos` ADD FOREIGN KEY (`pagamentos_id`) REFERENCES `pagamentos` (`id`);
			');

		$db->query('ALTER TABLE `pautas_pautas_fechadas` ADD FOREIGN KEY (`pautas_id`) REFERENCES `pautas` (`id`);');
		$db->query('ALTER TABLE `pautas_pautas_fechadas` ADD FOREIGN KEY (`pautas_fechadas_id`) REFERENCES `pautas_fechadas` (`id`);');

		$db->query('ALTER TABLE `artigos_historicos` ADD FOREIGN KEY (`artigos_id`) REFERENCES `artigos` (`id`);');
		$db->query('ALTER TABLE `artigos_historicos` ADD FOREIGN KEY (`colaboradores_id`) REFERENCES `colaboradores` (`id`);');

		$db->query('ALTER TABLE `colaboradores_historicos` ADD FOREIGN KEY (`colaboradores_id`) REFERENCES `colaboradores` (`id`);');

		$db->query('ALTER TABLE `colaboradores_notificacoes` ADD FOREIGN KEY (`sujeito_colaboradores_id`) REFERENCES `colaboradores` (`id`);');
		$db->query('ALTER TABLE `colaboradores_notificacoes` ADD FOREIGN KEY (`colaboradores_id`) REFERENCES `colaboradores` (`id`);');

		$db->query("INSERT INTO `configuracao` (`config`, `config_valor`) VALUES
		('artigo_regras_escrever', '			<h3>Atenção ao escrever artigos</h3>\r\n			<p>O estilo geral de artigos no Visão Libertária mudou. Note que artigos precisam seguir o tipo de artigo e\r\n				estilo do canal. Precisam ter uma visão libertária em algum ponto. Apenas notícia, não serve.</p>\r\n			<ul>\r\n				<li>Buscar escrever artigos lógicos, com contribuição, citando notícia ou fato, desenvolvimento, com visão\r\n					libertária, e conclusão.</li>\r\n				<li>Use corretores ortográficos para evitar erros de grafia e concordância.</li>\r\n				<li>Evite frases longas. Elas podem funcionar em livros, mas, para ser narrada, precisa ter, no máximo 30\r\n					palavras.</li>\r\n			</ul>\r\n			<p>Além disso, apesar de ter sido feito no passado, nosso estilo atual modou:</p>\r\n			<ul>\r\n				<li>Não incluir mugidos e imitações de caracteres cômicos.</li>\r\n				<li>Não usar apelidos de países. A primeira vez que citar um país no texto usar expressões como \"aquele país\r\n					que estatistas chamam de xxxx\" ou frases similares.</li>\r\n				<li>Evitar termos que possam ter interpretações erradas, como rato, macaco, porco, etc.</li>\r\n				<li>Termos como bovino gadoso, rei do gado, parasita público, mafioso, fraudemia, etc, bem como palavrões\r\n					leves ou pesados, devem ser usados com moderação. Nunca usados nos primeiros 30 segundos do texto, ou\r\n					seja, título, gancho, e nas primeiras 200 palavras.</li>\r\n				<li>Não usar nomes satíricos para políticos ou pessoas do público. Evitar usar nome de pessoas que não são\r\n					políticos ou pessoas muito conhecidas.</li>\r\n			</ul>'),
		('artigo_regras_revisar', '			<h3>Informações importantes sobre a revisão de artigos.</h3>\r\n			<p>O texto precisa ser revisado, relendo-se todo o texto e corrigindo pontos necessários para</p>\r\n			<ul>\r\n				<li>Ortografia deve estar perfeita, pode-se revisar usando o MS Word.</li>\r\n				<li>Concordância verbal e outras regras de gramática estão boas.</li>\r\n				<li>Narração sempre usa ou todos os verbos em terceira pessoa ou todos em primeira pessoa, jamais\r\n					misturando.</li>\r\n				<li>Mugidos e vozes de personagens são terminantemente proibidos.</li>\r\n			</ul>\r\n			<p>Lembre-se: você não pode revisar seu próprio texto. É notório que quando a pessoa revisa seu texto, por mais\r\n				que se esforce, não gera uma revisão tão eficiente.</p>\r\n			<p>Qualquer dúvida converse com o pessoal no Discord no fórum #duvidas-revisao ou tire suas dúvidas no e-mail:\r\n				anpsu@gmail.com</p>\r\n			<p>Além disso, apesar de ter sido feito no passado, nosso estilo atual mudou.</p>\r\n			<ul>\r\n				<li>Não incluir mugidos e imitações de caracteres cômicos.</li>\r\n				<li>Não usar apelidos de países. A primeira vez que citar um país no texto usar expressões como \"aquele país\r\n					que estatistas chamam de xxxx\" ou frases similares.</li>\r\n				<li>Evitar termos que possam ter interpretações erradas, como rato, macaco, porco, etc.</li>\r\n				<li>Termos como bovino gadoso, rei do gado, parasita público, mafioso, fraudemia, etc, bem como palavrões\r\n					leves ou pesados, devem ser usados com moderação. Nunca usados nos primeiros 30 segundos do texto, ou\r\n					seja, título, gancho, e nas primeiras 200 palavras.</li>\r\n				<li>Não usar nomes satíricos para políticos ou pessoas do público. Evitar usar nome de pessoas que não são\r\n					políticos ou pessoas muito conhecidas.</li>\r\n			</ul>'),
		('artigo_regras_narrar', ''),
		('artigo_regras_produzir', ''),
		('artigo_tamanho_maximo', '2500'),
		('artigo_tamanho_minimo', '1000'),
		('artigo_visualizacao_narracao', '{gancho}\r\nEste é o Visão Libertária, sua fonte de informações descentralizadas e distribuídas.\r\n{texto}\r\nObrigado por sua audiência.\r\nEste artigo foi {colaboradores}\r\nSe você gostou do vídeo, compartilhe em suas redes sociais. Caso deseje ser avisado de outros vídeos, inscreva-se no canal, e depois clique no botão da campainha. Escreva artigos você também, acesse visão libertária ponto com. Até a próxima.'),
		('contato_email', 'email@email.com'),
		('contato_email_copia', 'email2@email2.com'),
		('cron_hash', 'ADICIONE A SUA HASH AQUI'),
		('cron_pautas_data_delete', '7 days'),
		('cron_pautas_status_delete', '1'),
		('descricao_padrao_youtube', 'Referências:\r\n{referencias}\r\n\r\nVeja nosso site: http://visaolibertaria.com\r\n\r\nCamisas e merchandising: http://pimentanocafe.com.br/visaolibe...\r\n\r\n{tags}\r\n\r\nAjude o canal:\r\n\r\nhttps://picpay.me/ancapsu\r\nhttps://padrim.com.br/ancapsu\r\n16vmNcrA4Mvf7CaRLirAmpnjz1ZH3bWNkQ (Bitcoin)\r\nLSCnrubCVcpuLrGTTLMqpwRTXvYz7vMbbA (Litecoin)\r\n0x28aec946919c70e5e25d7c6785ede7622278b463 (Ethereum)\r\nnano_1s6i6xwujzqnmie8nc3x8rfumdnebpsd6h4cp9wgkcpk4eb1xn5u7n48ok5b (Nano)\r\n0x1302536c28695e47aaedd020aaf39f500cf6c22f (BNB)'),
		('home_banner', '5'),
		('home_banner_mostrar', '1'),
		('home_newsletter_mostrar', '0'),
		('home_talvez_goste', '3'),
		('home_talvez_goste_mostrar', '1'),
		('home_ultimos_videos', '10'),
		('home_ultimos_videos_mostrar', '1'),
		('limite_pautas_diario', '5'),
		('limite_pautas_semanal', '20'),
		('link_instagram', 'https://www.instagram.com'),
		('link_twitter', 'https://twitter.com'),
		('link_youtube', 'https://www.youtube.com'),
		('pauta_dias_antigo', '5'),
		('pauta_tamanho_maximo', '100'),
		('pauta_tamanho_minimo', '10'),
		('site_quantidade_listagem', '12'),
		('site_nome', '{\"ancap.su\":\"ANCAP.SU\",\"ancapsu.com\":\"ANCAP.SU\",\"safesrc.com\":\"Safe Source\",\"visaolibertaria.com\":\"Visão Libertária\",\"wrevolving.com\":\"Mundo Em Revolução\",\"default\":\"Visão Libertária\"}'),
		('site_descricao', '{\"ancap.su\":\"ANCAP.SU\",\"ancapsu.com\":\"ANCAP.SU\",\"safesrc.com\":\"Safe Source\",\"visaolibertaria.com\":\"Visão Libertária\",\"wrevolving.com\":\"Mundo Em Revolução\",\"default\":\"Visão Libertária\"}'),
		('texto_informacao_perfil', 'Leia nossas diretrizes para aceitar artigos no Visão Libertária, clicando aqui.\r\n\r\nVeja as diretrizes e cuidados para ser um revisor, clicando aqui.\r\n\r\nSaiba as configurações e definições para enviar seu arquivo de áudio, clicando aqui.\r\n\r\nEncontre todos os parâmetros e insumos para produzir os vídeos do canal, clicando aqui.'),
		('pauta_bot_hash', 'ADICIONE A SUA HASH AQUI'),
		('cron_artigos_desmarcar_status', '1'),
		('cron_artigos_desmarcar_data_revisao', '24 hours'),
		('cron_artigos_desmarcar_data_narracao', '6 hours'),
		('cron_artigos_desmarcar_data_producao', '12 hours'),
		('cron_email_carteira', ''),
		('artigo_tempo_bloqueio', '48 hours'),
		('cron_notificacoes_data_visualizado', '1 weeks'),
		('cron_notificacoes_data_cadastrado', '1 months'),
		('cron_email_carteira_data', '3 days'),
		('cron_notificacoes_status_delete', '1'),
		('cron_artigos_descartar_status', '1'),
		('cron_artigos_descartar_data', '1 months')
		;
		");


		echo 'Banco de dados e tabelas criadas com sucesso';
	}
}
