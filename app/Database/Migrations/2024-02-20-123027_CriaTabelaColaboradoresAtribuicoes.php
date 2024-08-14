<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

final class CriaTabelaColaboradoresAtribuicoes extends Migration
{
    private string $tabela = 'colaboradores_atribuicoes';
    private string $foreignKeyRestriction = 'NO ACTION';

    public function up(): void
    {
        $this->forge->addField([
            'colaboradores_id' => [
                'type' => 'INT',
            ],
            'atribuicoes_id' => [
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
            'atribuicoes_id',
            'atribuicoes',
            'id',
            $this->foreignKeyRestriction,
            $this->foreignKeyRestriction
        );

        $this->forge->addKey(['colaboradores_id', 'atribuicoes_id'], true);

        $this->forge->createTable($this->tabela, true);
    }

    public function down(): void
    {
        $this->forge->dropTable($this->tabela);
    }
}
