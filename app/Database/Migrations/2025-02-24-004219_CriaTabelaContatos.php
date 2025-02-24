<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\RawSql;
use CodeIgniter\Database\Migration;

class CriaTabelaContatos extends Migration
{
    private string $tabela = 'contatos';

    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'VARCHAR',
                'constraint' => 36,
                'default' => new RawSql('"uuid()"'),
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'contatos_assuntos_id' => [
                'type' => 'INT'
            ],
            'descricao' => [
                'type' => 'TEXT',
            ],
            'resposta' => [
                'type' => 'TEXT',
                'default' => null
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
        $this->forge->addForeignKey(
            'contatos_assuntos_id',
            'contatos_assuntos',
            'id'
        );
    }
}
