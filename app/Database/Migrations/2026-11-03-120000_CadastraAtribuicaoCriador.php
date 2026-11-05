<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use Config\Database;

final class CadastraAtribuicaoCriador extends Migration
{
	private int $id = 12;

	public function up(): void
	{
		$db = Database::connect();
		$existe = $db->table('atribuicoes')
			->where('id', $this->id)
			->countAllResults() > 0;

		if ($existe) {
			return;
		}

		$db->table('atribuicoes')->insert([
			'id' => $this->id,
			'nome' => 'Criador',
			'cor' => 'primary',
		]);
	}

	public function down(): void
	{
		$db = Database::connect();
		$db->table('colaboradores_atribuicoes')
			->where('atribuicoes_id', $this->id)
			->delete();
		$db->table('atribuicoes')
			->where('id', $this->id)
			->where('nome', 'Criador')
			->delete();
	}
}
