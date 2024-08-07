<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\RawSql;
use CodeIgniter\Database\Migration;

final class CriaTabelaColaboradoresHistoricos extends Migration
{
    private string $tabela = 'colaboradores_historicos';

    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type' => 'VARCHAR',
                'constraint' => 36,
            ],
            'colaboradores_id' => [
                'type' => 'INT',
            ],
            'acao' => [
                'type' => 'VARCHAR',
                'constraint' => 256,
                'null' => true,
                'default' => null,
            ],
            'objeto' => [
                'type' => 'VARCHAR',
                'constraint' => 256,
                'null' => true,
                'default' => null,
            ],
            'objeto_id' => [
                'type' => 'VARCHAR',
                'constraint' => 256,
                'null' => true,
                'default' => null,
            ],
            'criado' => [
                'type' => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addForeignKey(
            'colaboradores_id',
            'colaboradores',
            'id',
            'NO ACTION',
            'NO ACTION',
        );

        $this->forge->addPrimaryKey('id');

        $this->forge->createTable($this->tabela, true);
    }

    public function down(): void
    {
        $this->forge->dropTable($this->tabela);
    }
}
