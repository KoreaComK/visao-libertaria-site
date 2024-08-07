<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\RawSql;
use CodeIgniter\Database\Migration;

class CriaTabelaColaboradoresNotificacoes extends Migration
{
    private string $tabela = 'colaboradores_notificacoes';
    private string $foreignKeyRestriction = 'NO ACTION';

    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type' => 'VARCHAR',
                'constraint' => 36,
            ],
            'sujeito_colaboradores_id' => [
                'type' => 'INT',
            ],
            'acao' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'objeto' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'notificacao' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'id_objeto' => [
                'type' => 'VARCHAR',
                'constraint' => 36,
            ],
            'colaboradores_id' => [
                'type' => 'INT',
            ],
            'data_visualizado' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ],
            'criado' => [
                'type' => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addPrimaryKey('id');

        $this->forge->addForeignKey(
            'sujeito_colaboradores_id',
            'colaboradores',
            'id',
            $this->foreignKeyRestriction,
            $this->foreignKeyRestriction
        );
        $this->forge->addForeignKey(
            'colaboradores_id',
            'colaboradores',
            'id',
            $this->foreignKeyRestriction,
            $this->foreignKeyRestriction
        );

        $this->forge->createTable($this->tabela, true);
    }

    public function down(): void
    {
        $this->forge->dropTable($this->tabela);
    }
}
