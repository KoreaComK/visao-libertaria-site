<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\RawSql;
use CodeIgniter\Database\Migration;

class CriaTabelaPaginasEstaticas extends Migration
{
    private string $tabela = 'paginas_estaticas';

    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type' => 'VARCHAR',
                'constraint' => 36,
                'default' => new RawSql('"uuid()"'),
            ],
            'ativo' => [
                'type' => 'CHAR',
                'contraint' => 1,
                'null' => false,
                'default' => 'A',
            ],
            'url_friendly' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'localizacao' => [
                'type' => 'VARCHAR',
                'constraint' => 127,
                'null' => false,
            ],
            'titulo' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'conteudo' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'criado' => [
                'type' => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'atualizado' => [
                'type' => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->createTable($this->tabela, true);
    }

    public function down(): void
    {
        $this->forge->dropTable($this->tabela);
    }
}
