<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use Config\Database;

final class RemoveConfiguracaoHomeOpcoes extends Migration
{
    /** @var list<string> */
    private array $chaves = [
        'home_banner',
        'home_banner_mostrar',
        'home_newsletter_mostrar',
        'home_talvez_goste',
        'home_talvez_goste_mostrar',
        'home_ultimos_videos',
        'home_ultimos_videos_mostrar',
    ];

    public function up(): void
    {
        Database::connect()->table('configuracao')->whereIn('config', $this->chaves)->delete();
    }

    public function down(): void
    {
        $db = Database::connect();
        $linhas = [
            ['config' => 'home_banner', 'config_valor' => '5'],
            ['config' => 'home_banner_mostrar', 'config_valor' => '1'],
            ['config' => 'home_newsletter_mostrar', 'config_valor' => '0'],
            ['config' => 'home_talvez_goste', 'config_valor' => '3'],
            ['config' => 'home_talvez_goste_mostrar', 'config_valor' => '1'],
            ['config' => 'home_ultimos_videos', 'config_valor' => '10'],
            ['config' => 'home_ultimos_videos_mostrar', 'config_valor' => '1'],
        ];
        foreach ($linhas as $linha) {
            if ($db->table('configuracao')->where('config', $linha['config'])->countAllResults() === 0) {
                $db->table('configuracao')->insert($linha);
            }
        }
    }
}
