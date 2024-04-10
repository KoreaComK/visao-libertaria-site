<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CriaTabelaColaboradoresNotificacoes extends Migration
{
    private string $tabela = 'colaboradores_notificacoes';
    private string $foreignKeyRestriction = 'NO ACTION';

    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'VARCHAR',
                'constraint' => 36,
                'default' => 'uuid()',
            ],
            'sujeito_colaboradores_id' => [
                'type' => 'INT',
            ],
            'acao' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'notificacao' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'url' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'colaboradores_id' => [
                'type' => 'INT',
            ],
            'data_visualizado' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ],
        ]);

        $this->forge->addPrimaryKey('id');

        $this->forge->addForeignKey(
            'sujeito_colaboradores_id',
            'colaboradores',
            'id',
            $this->foreignKeyRestriction,
            $this->foreignKeyRestriction
        );
        $this->forge->addForeignKey(
            'colaboradores_id',
            'colaboradores',
            'id',
            $this->foreignKeyRestriction,
            $this->foreignKeyRestriction
        );

        $this->forge->createTable($this->tabela, true);
    }

    public function down()
    {
        $this->forge->dropTable($this->tabela);
    }
}
