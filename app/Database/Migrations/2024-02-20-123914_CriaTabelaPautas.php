<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\RawSql;
use CodeIgniter\Database\Migration;

final class CriaTabelaPautas extends Migration
{
    private string $tabela = 'pautas';

    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type' => 'VARCHAR',
                'constraint' => 36,
                'default' => new RawSql('uuid()'),
            ],
            'colaboradores_id' => [
                'type' => 'INT',
            ],
            'redator_colaboradores_id' => [
                'type' => 'INT',
                'null' => true,
                'default' => null,
            ],
            'link' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'titulo' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'texto' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'imagem' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'pauta_antiga' => [
                'type' => 'CHAR',
                'constraint' => 1,
                'default' => 'N',
            ],
            'reservado' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ],
            'tag_fechamento' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'default' => null,
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

        $this->forge->addForeignKey('colaboradores_id', 'colaboradores', 'id', 'NO ACTION', 'NO ACTION');

        $this->forge->createTable($this->tabela, true);
    }

    public function down(): void
    {
        $this->forge->dropTable($this->tabela);
    }
}
