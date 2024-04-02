<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\RawSql;
use CodeIgniter\Database\Migration;

final class CriaTabelaArtigosHistoricos extends Migration
{
    private string $tabela = 'artigos_historicos';
    private string $foreignKeyRestriction = 'NO ACTION';

    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type' => 'VARCHAR',
                'constraint' => 36,
                'default' => 'uuid()',
            ],
            'artigos_id' => [
                'type' => 'VARCHAR',
                'constraint' => 36,
            ],
            'acao' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'colaboradores_id' => [
                'type' => 'INT',
            ],
            'criado' => [
                'type' => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addForeignKey(
            'artigos_id',
            'artigos',
            'id',
            $this->foreignKeyRestriction,
            $this->foreignKeyRestriction,
        );

        $this->forge->addForeignKey(
            'colaboradores_id',
            'colaboradores',
            'id',
            $this->foreignKeyRestriction,
            $this->foreignKeyRestriction,
        );

        $this->forge->addPrimaryKey('id');

        $this->forge->createTable($this->tabela, true);
    }

    public function down(): void
    {
        $this->forge->dropTable($this->tabela);
    }
}
