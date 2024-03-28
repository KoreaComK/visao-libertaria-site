<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

final class CriaTabelaConfiguracao extends Migration
{
    private string $tabela = 'configuracao';

    public function up(): void
    {
        $this->forge->addField([
            'config' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'config_valor' => [
                'type' => 'TEXT',
            ],
        ]);

        $this->forge->addPrimaryKey('config');

        $this->forge->createTable($this->tabela, true);
    }

    public function down(): void
    {
        $this->forge->dropTable($this->tabela);
    }
}
