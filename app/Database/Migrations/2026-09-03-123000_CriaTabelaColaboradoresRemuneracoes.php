<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\RawSql;
use CodeIgniter\Database\Migration;

final class CriaTabelaColaboradoresRemuneracoes extends Migration
{
	private string $tabela = 'colaboradores_remuneracoes';

	public function up(): void
	{
		$this->forge->addField([
			'id',
			'colaboradores_id' => [
				'type'       => 'INT',
				'constraint' => 11,
			],
			'competencia' => [
				'type'       => 'CHAR',
				'constraint' => 7,
			],
			'tipo' => [
				'type'       => 'CHAR',
				'constraint' => 1,
			],
			'valor_reais' => [
				'type'       => 'DECIMAL',
				'constraint' => '10,2',
			],
			'horas_trabalhadas' => [
				'type'       => 'DECIMAL',
				'constraint' => '6,2',
				'null'       => true,
				'default'    => null,
			],
			'arquivo' => [
				'type'       => 'VARCHAR',
				'constraint' => 255,
				'null'       => true,
				'default'    => null,
			],
			'arquivo_nome' => [
				'type'       => 'VARCHAR',
				'constraint' => 255,
				'null'       => true,
				'default'    => null,
			],
			'pagamentos_id' => [
				'type'       => 'INT',
				'constraint' => 11,
				'null'       => true,
				'default'    => null,
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
			'colaboradores_id',
			'colaboradores',
			'id',
			'CASCADE',
			'CASCADE',
		);
		$this->forge->addForeignKey(
			'pagamentos_id',
			'pagamentos',
			'id',
			'CASCADE',
			'SET NULL',
		);
		$this->forge->addUniqueKey(['colaboradores_id', 'competencia']);
		$this->forge->createTable($this->tabela, true);
	}

	public function down(): void
	{
		$this->forge->dropTable($this->tabela);
	}
}
