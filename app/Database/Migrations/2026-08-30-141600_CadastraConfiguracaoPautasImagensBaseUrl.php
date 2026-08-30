<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use Config\Database;

final class CadastraConfiguracaoPautasImagensBaseUrl extends Migration
{
	public function up(): void
	{
		$db = Database::connect();
		$existe = $db->table('configuracao')
			->where('config', 'pautas_imagens_base_url')
			->countAllResults() > 0;

		if ($existe) {
			return;
		}

		$db->table('configuracao')->insert([
			'config' => 'pautas_imagens_base_url',
			'config_valor' => '',
		]);
	}

	public function down(): void
	{
		Database::connect()
			->table('configuracao')
			->where('config', 'pautas_imagens_base_url')
			->delete();
	}
}
