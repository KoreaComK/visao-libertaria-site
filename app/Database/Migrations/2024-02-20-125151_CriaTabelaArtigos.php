<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\RawSql;
use CodeIgniter\Database\Migration;

final class CriaTabelaArtigos extends Migration
{
    private string $tabela = 'artigos';
    private string $foreignKeyRestriction = 'NO ACTION';

    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type' => 'VARCHAR',
                'constraint' => 36,
                'default' => new RawSql('"uuid()"'),
            ],
            'url_friendly' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'fase_producao_id' => [
                'type' => 'INT',
            ],
            'tipo_artigo' => [
                'type' => 'CHAR',
                'constraint' => 1,
                'null' => false,
                'default' => 'T',
            ],
            'link' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'default' => null,
            ],
            'sugerido_colaboradores_id' => [
                'type' => 'INT',
                'null' => true,
                'default' => null,
            ],
            'titulo' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'gancho' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'texto_original' => [
                'type' => 'TEXT',
            ],
            'referencias' => [
                'type' => 'TEXT',
                'null' => true,
                'default' => null,
            ],
            'imagem' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'escrito_colaboradores_id' => [
                'type' => 'INT',
                'null' => true,
                'default' => null,
            ],
            'texto_revisado' => [
                'type' => 'TEXT',
                'null' => true,
                'default' => null,
            ],
            'revisado_colaboradores_id' => [
                'type' => 'INT',
                'null' => true,
                'default' => null,
            ],
            'arquivo_audio' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'default' => null,
            ],
            'narrado_colaboradores_id' => [
                'type' => 'INT',
                'null' => true,
                'default' => null,
            ],
            'link_produzido' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'default' => null,
            ],
            'link_shorts' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'produzido_colaboradores_id' => [
                'type' => 'INT',
                'null' => true,
                'default' => null,
            ],
            'publicado' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ],
            'publicado_colaboradores_id' => [
                'type' => 'INT',
                'null' => true,
                'default' => null,
            ],
            'link_video_youtube' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'default' => null,
            ],
            'descartado' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ],
            'descartado_colaboradores_id' => [
                'type' => 'INT',
                'null' => true,
                'default' => null,
            ],
            'palavras_escritor' => [
                'type' => 'INT',
            ],
            'palavras_revisor' => [
                'type' => 'INT',
            ],
            'palavras_narrador' => [
                'type' => 'INT',
            ],
            'palavras_produtor' => [
                'type' => 'INT',
            ],
            'marcado_colaboradores_id' => [
                'type' => 'INT',
                'null' => true,
                'default' => null,
            ],
            'marcado' => [
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
        ]);

        $this->forge->addPrimaryKey('id');

        $this->adicionaForeignKeys();

        $this->forge->createTable($this->tabela, true);
    }

    public function down(): void
    {
        $this->forge->dropTable($this->tabela);
    }

    private function adicionaForeignKeys(): void
    {
        $this->forge->addForeignKey(
            'fase_producao_id',
            'fase_producao',
            'id',
            $this->foreignKeyRestriction,
            $this->foreignKeyRestriction,
        );

        $this->forge->addForeignKey(
            'sugerido_colaboradores_id',
            'colaboradores',
            'id',
            $this->foreignKeyRestriction,
            $this->foreignKeyRestriction,
        );

        $this->forge->addForeignKey(
            'escrito_colaboradores_id',
            'colaboradores',
            'id',
            $this->foreignKeyRestriction,
            $this->foreignKeyRestriction,
        );

        $this->forge->addForeignKey(
            'revisado_colaboradores_id',
            'colaboradores',
            'id',
            $this->foreignKeyRestriction,
            $this->foreignKeyRestriction,
        );

        $this->forge->addForeignKey(
            'narrado_colaboradores_id',
            'colaboradores',
            'id',
            $this->foreignKeyRestriction,
            $this->foreignKeyRestriction,
        );

        $this->forge->addForeignKey(
            'produzido_colaboradores_id',
            'colaboradores',
            'id',
            $this->foreignKeyRestriction,
            $this->foreignKeyRestriction,
        );

        $this->forge->addForeignKey(
            'publicado_colaboradores_id',
            'colaboradores',
            'id',
            $this->foreignKeyRestriction,
            $this->foreignKeyRestriction,
        );

        $this->forge->addForeignKey(
            'descartado_colaboradores_id',
            'colaboradores',
            'id',
            $this->foreignKeyRestriction,
            $this->foreignKeyRestriction,
        );

        $this->forge->addForeignKey(
            'marcado_colaboradores_id',
            'colaboradores',
            'id',
            $this->foreignKeyRestriction,
            $this->foreignKeyRestriction,
        );
    }
}
