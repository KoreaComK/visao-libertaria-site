<?php

namespace App\Controllers;

use CodeIgniter\Database\Config;

class Inicializandobancodados extends BaseController
{
	public function index()
	{
		$db = db_connect();

		$db->query('CREATE TABLE IF NOT EXISTS `colaboradores` (
		`id` int PRIMARY KEY AUTO_INCREMENT,
		`apelido` varchar(255) NOT NULL,
		`avatar` varchar(255) DEFAULT null,
		`email` varchar(255) NOT NULL,
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
		`link` varchar(255),
		`titulo` varchar(255),
		`texto` TEXT,
		`imagem` varchar(255),
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
			`hash_transacao` varchar(256) DEFAULT null,
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


		echo 'Banco de dados e tabelas criadas com sucesso';
	}
}