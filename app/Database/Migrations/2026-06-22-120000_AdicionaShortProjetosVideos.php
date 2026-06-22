<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

final class AdicionaShortProjetosVideos extends Migration
{
	public function up(): void
	{
		$this->forge->addColumn('projetos_videos', [
			'short' => [
				'type'    => 'BOOLEAN',
				'null'    => false,
				'default' => false,
				'after'   => 'thumbnail',
			],
		]);
	}

	public function down(): void
	{
		$this->forge->dropColumn('projetos_videos', 'short');
	}
}
