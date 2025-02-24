<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\RawSql;
use CodeIgniter\Database\Migration;

class CriaTabelaContatosAssuntos extends Migration
{
    private string $tabela = 'contatos_assuntos';

    public function up()
    {
        $this->forge->addField([
            'id',
            'assunto' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'descricao' => [
                'type' => 'TEXT',
            ],
            'criado' => [
                'type' => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'atualizado' => [
                'type' => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
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
        // $this->forge->addForeignKey(
        // );
    }
}
