<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

final class CriaTabelaPautasCategorias extends Migration
{
	private string $tabela = 'pautas_categorias';
	private string $foreignKeyRestriction = 'NO ACTION';

	public function up(): void
	{
		$this->forge->addField([
			'pautas_id' => [
				'type' => 'VARCHAR',
				'constraint' => 36,
			],
			'categorias_id' => [
				'type' => 'INT',
			],
		]);

		$this->forge->addForeignKey(
			'pautas_id',
			'pautas',
			'id',
			$this->foreignKeyRestriction,
			$this->foreignKeyRestriction
		);

		$this->forge->addForeignKey(
			'categorias_id',
			'categorias',
			'id',
			$this->foreignKeyRestriction,
			$this->foreignKeyRestriction
		);

		$this->forge->addPrimaryKey(['pautas_id', 'categorias_id']);

		$this->forge->createTable($this->tabela, true);
	}

	public function down(): void
	{
		$this->forge->dropTable($this->tabela, true);
	}
}
