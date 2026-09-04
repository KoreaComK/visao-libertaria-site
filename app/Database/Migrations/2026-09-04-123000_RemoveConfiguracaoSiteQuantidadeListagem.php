<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use Config\Database;

final class RemoveConfiguracaoSiteQuantidadeListagem extends Migration
{
	private string $chave = 'site_quantidade_listagem';

	public function up(): void
	{
		Database::connect()->table('configuracao')->where('config', $this->chave)->delete();
	}

	public function down(): void
	{
		$db = Database::connect();
		if ($db->table('configuracao')->where('config', $this->chave)->countAllResults() === 0) {
			$db->table('configuracao')->insert([
				'config' => $this->chave,
				'config_valor' => '12',
			]);
		}
	}
}
