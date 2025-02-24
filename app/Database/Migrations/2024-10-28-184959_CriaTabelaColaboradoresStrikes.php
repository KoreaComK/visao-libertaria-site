<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\RawSql;
use CodeIgniter\Database\Migration;

class CriaTabelaColaboradoresStrikes extends Migration
{
    private string $tabela = 'colaboradores_strikes';

    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'VARCHAR',
                'constraint' => 36,
                'default' => new RawSql('"uuid()"'),
            ],
            'colaboradores_id' => [
                'type' => 'INT'
            ],
            'data' => [
                'type' => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'motivo' => [
                'type' => 'TEXT',
            ],
            'excluido' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null
            ],
        ]);

        $this->forge->addPrimaryKey('id');

        $this->adicionaForeignKeys();

        $this->forge->createTable($this->tabela, true);
    }

    public function down()
    {
        //
    }

    private function adicionaForeignKeys(): void
    {
        $this->forge->addForeignKey(
            'colaboradores_id',
            'colaboradores',
            'id'
        );
    }
}
