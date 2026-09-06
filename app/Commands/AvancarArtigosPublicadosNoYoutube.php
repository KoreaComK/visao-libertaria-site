<?php

declare(strict_types=1);

namespace App\Commands;

use App\Models\ArtigosModel;
use App\Models\ProjetosVideosModel;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class AvancarArtigosPublicadosNoYoutube extends BaseCommand
{
	protected $group = 'Cron';

	protected $name = 'cron:avancar-artigos-publicados-youtube';

	protected $description = 'Avança para fase 6 artigos com link de vídeo já cadastrado no YouTube.';

	protected $usage = 'cron:avancar-artigos-publicados-youtube';

	public function run(array $params)
	{
		helper('_formata_video');

		$videoIdsCadastrados = (new ProjetosVideosModel())->findColumn('video_id');
		$videoIdsCadastrados = $videoIdsCadastrados !== null
			? array_flip($videoIdsCadastrados)
			: [];

		if ($videoIdsCadastrados === []) {
			return $this->encerrar('Nenhum vídeo cadastrado. Nada a fazer.');
		}

		$artigosPublicar = (new ArtigosModel())
			->where('fase_producao_id', 5)
			->where('link_video_youtube IS NOT NULL', null, false)
			->where('link_video_youtube !=', '')
			->where('descartado', null)
			->findAll();

		if ($artigosPublicar === []) {
			return $this->encerrar('Nenhum artigo na fase publicar com link de vídeo. Nada a fazer.');
		}

		// Cron sem usuário logado: historico do Model exige sessão (colaborador sistema = 1).
		$session = \Config\Services::session();
		$session->start();
		$session->set('colaboradores', ['id' => 1]);

		$artigosModelAtualizacao = new ArtigosModel();
		$publicadoEm = $artigosModelAtualizacao->getNow();
		$avancados = 0;

		foreach ($artigosPublicar as $artigo) {
			$linkVideo = $artigo['link_video_youtube'];
			$videoId = extrair_id_video_youtube($linkVideo);

			if ($videoId === null) {
				foreach (array_keys($videoIdsCadastrados) as $videoIdCadastrado) {
					if (str_contains($linkVideo, $videoIdCadastrado)) {
						$videoId = $videoIdCadastrado;
						break;
					}
				}
			}

			if ($videoId !== null && isset($videoIdsCadastrados[$videoId])) {
				$artigosModelAtualizacao->update($artigo['id'], [
					'fase_producao_id' => 6,
					'publicado_colaboradores_id' => 1,
					'publicado' => $publicadoEm,
				]);
				$avancados++;
			}
		}

		if ($avancados > 0) {
			$this->invalidarCacheHome();
		}

		return $this->encerrar('Concluído: ' . $avancados . ' artigo(s) avançado(s) para fase publicada.');
	}

	private function invalidarCacheHome(): void
	{
		if (getenv('CI_ENVIRONMENT') !== 'production') {
			return;
		}

		$arquivo = WRITEPATH . 'cache/home_version.txt';
		$diretorio = dirname($arquivo);

		if (! is_dir($diretorio)) {
			mkdir($diretorio, 0775, true);
		}

		$versaoSite = '0';
		$arquivoSite = WRITEPATH . 'cache/site_config_version.txt';
		if (is_file($arquivoSite)) {
			$lida = trim((string) file_get_contents($arquivoSite));
			if ($lida !== '') {
				$versaoSite = $lida;
			}
		}

		$versaoHome = '0';
		if (is_file($arquivo)) {
			$lida = trim((string) file_get_contents($arquivo));
			if ($lida !== '') {
				$versaoHome = $lida;
			}
		}

		$chaveAntiga = 'home_anon_' . $versaoSite . '_' . $versaoHome;
		file_put_contents($arquivo, (string) time());
		\Config\Services::cache()->delete($chaveAntiga);
	}

	private function encerrar(string $mensagem, string $nivel = 'info'): int
	{
		$this->registrar($mensagem, $nivel);

		return EXIT_SUCCESS;
	}

	private function registrar(string $mensagem, string $nivel = 'info'): void
	{
		if ($nivel === 'error') {
			CLI::error($mensagem);
			$this->logger->error('cron:avancar-artigos-publicados-youtube: ' . $mensagem);
			return;
		}

		CLI::write($mensagem);
		$this->logger->info('cron:avancar-artigos-publicados-youtube: ' . $mensagem);
	}
}
