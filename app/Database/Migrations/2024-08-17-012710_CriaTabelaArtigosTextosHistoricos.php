<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\RawSql;
use CodeIgniter\Database\Migration;

class CriaTabelaArtigosTextosHistoricos extends Migration
{
    private string $tabela = 'artigos_textos_historicos';
    private string $foreignKeyRestriction = 'NO ACTION';
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'VARCHAR',
                'constraint' => 36,
                'default' => new RawSql('"uuid()"'),
            ],
            'artigos_id' => [
                'type' => 'VARCHAR',
                'constraint' => 36
            ],
            'titulo' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'gancho' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'texto' => [
                'type' => 'TEXT',
            ],
            'referencias' => [
                'type' => 'TEXT',
            ],
            'colaboradores_id' => [
                'type' => 'INT',
            ],
           'criado' => [
                'type' => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
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
    }
}
