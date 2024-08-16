<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\RawSql;
use CodeIgniter\Database\Migration;

final class CriaTabelaConquistas extends Migration
{
    private string $tabela = 'conquistas';

    public function up(): void
    {
        $this->forge->addField([
            'id',
            'nome' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'imagem' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'tipo' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'pontuacao' => [
                'type' => 'INT',
                'null' => true,
                'default' => 0,
            ],
            'criado' => [
                'type' => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'excluido' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ],
        ]);

        $this->forge->addPrimaryKey('id');

        $this->forge->createTable($this->tabela, true);
    }

    public function down(): void
    {
        $this->forge->dropTable($this->tabela);
    }
}
