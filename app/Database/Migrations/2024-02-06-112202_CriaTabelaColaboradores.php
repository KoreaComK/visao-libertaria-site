<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\RawSql;
use CodeIgniter\Database\Migration;

final class CriaTabelaColaboradores extends Migration
{
    private string $tabela = 'colaboradores';

    public function up(): void
    {
        $this->forge->addField([
            'id',
            'apelido' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'avatar' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
                'default' => null,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'twitter' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
                'default' => null,
            ],
            'carteira' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
                'default' => null,
            ],
            'senha' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
            ],
            'pontuacao_total' => [
                'type' => 'DOUBLE',
                'default' => 0,
            ],
            'pontuacao_mensal' => [
                'type' => 'DOUBLE',
                'default' => 0,
            ],
            'pontos_escritor' => [
                'type' => 'INT',
                'default' => 0,
            ],
            'pontos_pautador' => [
                'type' => 'INT',
                'default' => 0,
            ],
            'strike_data' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ],
            'shadowban' => [
                'type' => 'CHAR(1)',
                'null' => false,
                'default' => 'N',
            ],
            'bloqueado' => [
                'type' => 'CHAR(1)',
                'null' => false,
                'default' => 'N',
            ],
            'confirmacao_hash' => [
                'type' => 'VARCHAR',
                'constraint' => '64',
            ],
            'confirmado_data' => [
                'type' => 'DATETIME',
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

        $this->forge->createTable($this->tabela, true);
    }

    public function down(): void
    {
        $this->forge->dropTable($this->tabela);
    }
}
