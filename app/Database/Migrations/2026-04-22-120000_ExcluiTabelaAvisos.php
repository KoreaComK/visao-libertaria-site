<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class ExcluiTabelaAvisos extends Migration
{
    private string $tabela = 'avisos';

    public function up(): void
    {
        $this->forge->dropTable($this->tabela, true);
    }

    public function down(): void
    {
        $this->forge->addField([
            'id' => [
                'type' => 'VARCHAR',
                'constraint' => 36,
                'default' => new RawSql('"uuid()"'),
            ],
            'aviso' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'link' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'default' => null,
            ],
            'inicio' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ],
            'fim' => [
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

        $this->forge->createTable($this->tabela, true);
    }
}
