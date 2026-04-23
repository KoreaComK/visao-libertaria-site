<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use Config\Database;

final class RemoveConfiguracaoLinksRedesETextoPerfil extends Migration
{
    /** @var list<string> */
    private array $chaves = [
        'link_youtube',
        'link_twitter',
        'link_instagram',
        'texto_informacao_perfil',
    ];

    public function up(): void
    {
        Database::connect()->table('configuracao')->whereIn('config', $this->chaves)->delete();
    }

    public function down(): void
    {
        $db = Database::connect();
        $linhas = [
            [
                'config' => 'link_instagram',
                'config_valor' => 'https://www.instagram.com',
            ],
            [
                'config' => 'link_twitter',
                'config_valor' => 'https://twitter.com',
            ],
            [
                'config' => 'link_youtube',
                'config_valor' => 'https://www.youtube.com',
            ],
            [
                'config' => 'texto_informacao_perfil',
                'config_valor' => 'Leia nossas diretrizes para aceitar artigos no Visão Libertária, clicando aqui.\r\n\r\nVeja as diretrizes e cuidados para ser um revisor, clicando aqui.\r\n\r\nSaiba as configurações e definições para enviar seu arquivo de áudio, clicando aqui.\r\n\r\nEncontre todos os parâmetros e insumos para produzir os vídeos do canal, clicando aqui.',
            ],
        ];
        foreach ($linhas as $linha) {
            if ($db->table('configuracao')->where('config', $linha['config'])->countAllResults() === 0) {
                $db->table('configuracao')->insert($linha);
            }
        }
    }
}
