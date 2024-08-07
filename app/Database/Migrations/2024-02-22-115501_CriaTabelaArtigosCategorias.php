<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

final class CriaTabelaArtigosCategorias extends Migration
{
    private string $tabela = 'artigos_categorias';
    private string $foreignKeyRestriction = 'NO ACTION';

    public function up(): void
    {
        $this->forge->addField([
            'artigos_id' => [
                'type' => 'VARCHAR',
                'constraint' => '36',
            ],
            'categorias_id' => [
                'type' => 'INT',
            ],
        ]);

        $this->forge->addForeignKey(
            'artigos_id',
            'artigos',
            'id',
            $this->foreignKeyRestriction,
            $this->foreignKeyRestriction
        );

        $this->forge->addForeignKey(
            'categorias_id',
            'categorias',
            'id',
            $this->foreignKeyRestriction,
            $this->foreignKeyRestriction
        );

        $this->forge->addPrimaryKey(['artigos_id', 'categorias_id']);

        $this->forge->createTable($this->tabela, true);
    }

    public function down(): void
    {
        $this->forge->dropTable($this->tabela);
    }
}
