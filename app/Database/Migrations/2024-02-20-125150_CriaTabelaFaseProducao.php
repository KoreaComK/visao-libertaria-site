<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

final class CriaTabelaFaseProducao extends Migration
{
    private string $tabela = 'fase_producao';
    private string $foreignKeyRestriction = 'NO ACTION';

    public function up(): void
    {
        $this->forge->addField([
            'id',
            'nome' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'etapa_anterior' => [
                'type' => 'INT',
                'null' => true,
                'default' => null,
            ],
            'etapa_posterior' => [
                'type' => 'INT',
                'null' => true,
                'default' => null,
            ],
            'mostrar_site' => [
                'type' => 'CHAR',
                'constraint' => 1,
                'default' => 'N',
            ],
        ]);

        $this->forge->addForeignKey(
            'etapa_anterior',
            'fase_producao',
            'id',
            $this->foreignKeyRestriction,
            $this->foreignKeyRestriction,
        );
        $this->forge->addForeignKey(
            'etapa_posterior',
            'fase_producao',
            'id',
            $this->foreignKeyRestriction,
            $this->foreignKeyRestriction,
        );

        $this->forge->createTable($this->tabela, true);
    }

    public function down(): void
    {
        $this->forge->dropTable($this->tabela);
    }
}
