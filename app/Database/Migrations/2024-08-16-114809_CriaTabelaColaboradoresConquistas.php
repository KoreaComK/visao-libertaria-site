<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\RawSql;
use CodeIgniter\Database\Migration;

final class CriaTabelaColaboradoresConquistas extends Migration
{
    private string $tabela = 'colaboradores_conquistas';

    private string $foreignKeyRestriction = 'NO ACTION';

    public function up(): void
    {
        $this->forge->addField([
            'colaboradores_id' => [
                'type' => 'INT',
            ],
            'conquistas_id' => [
                'type' => 'INT',
            ],
        ]);

        $this->forge->addForeignKey(
            'colaboradores_id',
            'colaboradores',
            'id',
            $this->foreignKeyRestriction,
            $this->foreignKeyRestriction
        );

        $this->forge->addForeignKey(
            'conquistas_id',
            'conquistas',
            'id',
            $this->foreignKeyRestriction,
            $this->foreignKeyRestriction
        );

        $this->forge->addPrimaryKey(['colaboradores_id', 'conquistas_id']);

        $this->forge->createTable($this->tabela, true);
    }

    public function down(): void
    {
        $this->forge->dropTable($this->tabela);
    }
}
