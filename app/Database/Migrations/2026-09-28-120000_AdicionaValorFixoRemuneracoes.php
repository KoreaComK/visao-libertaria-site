<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

final class AdicionaValorFixoRemuneracoes extends Migration
{
	public function up(): void
	{
		if ($this->db->fieldExists('valor_fixo_reais', 'colaboradores_remuneracoes')) {
			return;
		}

		$this->forge->addColumn('colaboradores_remuneracoes', [
			'valor_fixo_reais' => [
				'type'       => 'DECIMAL',
				'constraint' => '10,2',
				'null'       => true,
				'default'    => null,
				'after'      => 'valor_reais',
			],
		]);
	}

	public function down(): void
	{
		$this->forge->dropColumn('colaboradores_remuneracoes', 'valor_fixo_reais');
	}
}
