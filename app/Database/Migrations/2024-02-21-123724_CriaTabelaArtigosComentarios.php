<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\RawSql;
use CodeIgniter\Database\Migration;

final class CriaTabelaArtigosComentarios extends Migration
{
    private string $tabela = 'artigos_comentarios';

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
            'colaboradores_id' => [
                'type' => 'INT',
            ],
            'comentario' => [
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
                'default' => null,
            ],
        ]);

        $this->forge->addPrimaryKey('id');

        $this->forge->addForeignKey('artigos_id', 'artigos', 'id', 'NO ACTION', 'NO ACTION');

        $this->forge->createTable($this->tabela, true);
    }

    public function down(): void
    {
        $this->forge->dropTable($this->tabela);
    }
}
