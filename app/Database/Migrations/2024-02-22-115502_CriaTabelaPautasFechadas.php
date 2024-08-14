<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

final class CriaTabelaPautasFechadas extends Migration
{
    private string $tabela = 'pautas_fechadas';

    public function up(): void
    {
        $this->forge->addField([
            'id',
            'titulo' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'criado' => [
                'type' => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'excluido' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->createTable($this->tabela, true);
    }

    public function down(): void
    {
        $this->forge->dropTable($this->tabela);
    }
}
