<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\RawSql;
use CodeIgniter\Database\Migration;

final class CriaTabelaPagamentos extends Migration
{
    private string $tabela = 'pagamentos';

    public function up(): void
    {
        $this->forge->addField([
            'id',
            'titulo' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'quantidade_bitcoin' => [
                'type' => 'DOUBLE',
                'default' => 0,
            ],
            'multiplicador_escrito' => [
                'type' => 'FLOAT',
                'default' => 0,
            ],
            'multiplicador_revisado' => [
                'type' => 'FLOAT',
                'default' => 0,
            ],
            'multiplicador_narrado' => [
                'type' => 'FLOAT',
                'default' => 0,
            ],
            'multiplicador_produzido' => [
                'type' => 'FLOAT',
                'default' => 0,
            ],
            'multiplicador_escrito_noticia' => [
                'type' => 'FLOAT',
                'default' => 0,
            ],
            'multiplicador_revisado_noticia' => [
                'type' => 'FLOAT',
                'default' => 0,
            ],
            'multiplicador_narrado_noticia' => [
                'type' => 'FLOAT',
                'default' => 0,
            ],
            'multiplicador_produzido_noticia' => [
                'type' => 'FLOAT',
                'default' => 0,
            ],
            'hash_transacao' => [
                'type' => 'VARCHAR',
                'constraint' => 256,
                'null' => true,
                'default' => null,
            ],
            'pontuacao_total' => [
                'type' => 'FLOAT',
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
        ]);

        $this->forge->createTable($this->tabela, true);
    }

    public function down(): void
    {
        $this->forge->dropTable($this->tabela);
    }
}
