<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

final class AdicionaIndicesPerformance extends Migration
{
	public function up(): void
	{
		$indexes = [
			'CREATE INDEX idx_pc_pauta_excluido ON pautas_comentarios (pautas_id, excluido)',
			'CREATE INDEX idx_pautas_listagem ON pautas (reservado, redator_colaboradores_id, criado)',
			'CREATE INDEX idx_pautas_colab_criado ON pautas (colaboradores_id, criado)',
			'CREATE INDEX idx_pautas_colab_usadas ON pautas (colaboradores_id, reservado, tag_fechamento)',
			'CREATE INDEX idx_pv_projeto_short_pub ON projetos_videos (projetos_id, short, publicado)',
			'CREATE INDEX idx_pv_publicado ON projetos_videos (publicado)',
			'CREATE INDEX idx_artigos_fase_desc_pub ON artigos (fase_producao_id, descartado, publicado)',
			'CREATE INDEX idx_notif_colab_visualizado ON colaboradores_notificacoes (colaboradores_id, data_visualizado)',
		];

		foreach ($indexes as $sql) {
			$this->db->query($sql);
		}
	}

	public function down(): void
	{
		$indexes = [
			'idx_pc_pauta_excluido' => 'pautas_comentarios',
			'idx_pautas_listagem' => 'pautas',
			'idx_pautas_colab_criado' => 'pautas',
			'idx_pautas_colab_usadas' => 'pautas',
			'idx_pv_projeto_short_pub' => 'projetos_videos',
			'idx_pv_publicado' => 'projetos_videos',
			'idx_artigos_fase_desc_pub' => 'artigos',
			'idx_notif_colab_visualizado' => 'colaboradores_notificacoes',
		];

		foreach ($indexes as $name => $table) {
			$this->db->query("DROP INDEX {$name} ON {$table}");
		}
	}
}
