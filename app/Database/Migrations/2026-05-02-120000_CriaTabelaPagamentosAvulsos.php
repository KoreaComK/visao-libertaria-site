<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\RawSql;
use CodeIgniter\Database\Migration;

final class CriaTabelaPagamentosAvulsos extends Migration
{
	private string $tabela = 'pagamentos_avulsos';

	public function up(): void
	{
		$this->forge->addField([
			'id',
			'pagamentos_id' => [
				'type'       => 'INT',
				'constraint' => 11,
			],
			'colaboradores_id' => [
				'type'       => 'INT',
				'constraint' => 11,
			],
			'valor_bitcoin' => [
				'type' => 'DOUBLE',
			],
			'criado' => [
				'type'    => 'DATETIME',
				'default' => new RawSql('CURRENT_TIMESTAMP'),
			],
			'atualizado' => [
				'type'    => 'DATETIME',
				'default' => new RawSql('CURRENT_TIMESTAMP'),
			],
		]);

		$this->forge->addForeignKey(
			'pagamentos_id',
			'pagamentos',
			'id',
			'CASCADE',
			'CASCADE',
		);
		$this->forge->addForeignKey(
			'colaboradores_id',
			'colaboradores',
			'id',
			'CASCADE',
			'CASCADE',
		);
		$this->forge->createTable($this->tabela, true);
	}

	public function down(): void
	{
		$this->forge->dropTable($this->tabela);
	}
}
