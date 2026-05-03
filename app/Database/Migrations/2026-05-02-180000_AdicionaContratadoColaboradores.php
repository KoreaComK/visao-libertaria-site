<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

final class AdicionaContratadoColaboradores extends Migration
{
	public function up(): void
	{
		$this->forge->addColumn('colaboradores', [
			'contratado' => [
				'type'       => 'CHAR',
				'constraint' => 1,
				'null'       => false,
				'default'    => 'N',
				'after'      => 'pontos_pautador',
			],
		]);
	}

	public function down(): void
	{
		$this->forge->dropColumn('colaboradores', 'contratado');
	}
}
