<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use Config\Database;

final class CadastraConfiguracaoAntiIa extends Migration
{
    public function up(): void
    {
        $db = Database::connect();

        $configuracoes = [
            [
                'config' => 'anti_ia_termos',
                'config_valor' => '',
            ],
            [
                'config' => 'anti_ia_limite_minimo',
                'config_valor' => '10',
            ],
            [
                'config' => 'anti_ia_limite_maximo',
                'config_valor' => '25',
            ],
        ];

        foreach ($configuracoes as $linha) {
            if ($db->table('configuracao')->where('config', $linha['config'])->countAllResults() === 0) {
                $db->table('configuracao')->insert($linha);
            }
        }
    }

    public function down(): void
    {
        $db = Database::connect();
        $db->table('configuracao')->whereIn('config', [
            'anti_ia_termos',
            'anti_ia_limite_minimo',
            'anti_ia_limite_maximo',
        ])->delete();
    }
}
