<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\RawSql;
use CodeIgniter\Database\Migration;

final class CriaTabelaPautasComentarios extends Migration
{
    private string $tabela = 'pautas_comentarios';
    private string $foreignKeyRestriction = 'NO ACTION';

    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type' => 'VARCHAR',
                'constraint' => 36,
            ],
            'pautas_id' => [
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

        $this->forge->addForeignKey(
            'pautas_id',
            'pautas',
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

        $this->forge->addPrimaryKey('id');

        $this->forge->createTable($this->tabela, true);
    }

    public function down(): void
    {
        $this->forge->dropTable($this->tabela);
    }
}
