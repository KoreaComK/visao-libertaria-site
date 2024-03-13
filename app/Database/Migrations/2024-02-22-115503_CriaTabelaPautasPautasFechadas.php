<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

final class CriaTabelaPautasPautasFechadas extends Migration
{
    private string $tabela = 'pautas_pautas_fechadas';
    private string $foreignKeyRestriction = 'NO ACTION';

    public function up(): void
    {
        $this->forge->addField([
            'pautas_id' => [
                'type' => 'VARCHAR',
                'constraint' => 36,
            ],
            'pautas_fechadas_id' => [
                'type' => 'INT',
            ],
        ]);

        $this->forge->addForeignKey(
            'pautas_id',
            'pautas',
            'id',
            $this->foreignKeyRestriction,
            $this->foreignKeyRestriction
        );

        $this->forge->addForeignKey(
            'pautas_fechadas_id',
            'pautas_fechadas',
            'id',
            $this->foreignKeyRestriction,
            $this->foreignKeyRestriction
        );

        $this->forge->addPrimaryKey(['pautas_id', 'pautas_fechadas_id']);

        $this->forge->createTable($this->tabela, true);
    }

    public function down(): void
    {
        $this->forge->dropTable($this->tabela);
    }
}
