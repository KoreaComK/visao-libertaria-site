<?php

declare(strict_types=1);

namespace App\Commands;

use App\Models\ProjetosModel;
use App\Models\ProjetosVideosModel;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CaptarVideosYoutube extends BaseCommand
{
	protected $group = 'Cron';

	protected $name = 'cron:captar-videos-youtube';

	protected $description = 'Capta vídeos novos dos canais YouTube dos projetos e invalida o cache da home se houver novidades.';

	protected $usage = 'cron:captar-videos-youtube';

	public function run(array $params)
	{
		$youtubeApiKey = getenv('YOUTUBE_API_KEY');
		if ($youtubeApiKey === false || $youtubeApiKey === '') {
			return $this->encerrar('YOUTUBE_API_KEY vazia. Nada a fazer.');
		}

		$projetos = (new ProjetosModel())->findAll();
		if ($projetos === []) {
			return $this->encerrar('Nenhum projeto cadastrado. Nada a fazer.');
		}

		$projetosVideosModel = new ProjetosVideosModel();
		$videoIdsCadastrados = $projetosVideosModel->findColumn('video_id');
		$videoIdsCadastrados = $videoIdsCadastrados !== null
			? array_flip($videoIdsCadastrados)
			: [];

		$client = \Config\Services::curlrequest();
		$inseridos = 0;

		foreach ($projetos as $projeto) {
			$responseBody = $this->requisicaoYoutubeApi($client, 'https://www.googleapis.com/youtube/v3/search', [
				'key' => $youtubeApiKey,
				'channelId' => $projeto['canal_youtube_id'],
				'part' => 'snippet',
				'order' => 'date',
				'maxResults' => 50,
				'type' => 'video',
			]);

			if ($responseBody === null) {
				continue;
			}

			if (($responseBody['pageInfo']['totalResults'] ?? 0) <= 0) {
				continue;
			}

			foreach ($responseBody['items'] ?? [] as $video) {
				$videoId = $video['id']['videoId'] ?? null;
				if ($videoId === null || isset($videoIdsCadastrados[$videoId])) {
					continue;
				}

				$dataPublicacao = new \DateTime($video['snippet']['publishedAt']);
				$projetosVideosModel->insert([
					'video_id' => $videoId,
					'titulo' => $video['snippet']['title'],
					'projetos_id' => $projeto['id'],
					'publicado' => $dataPublicacao->format('Y-m-d H:i:s'),
					'thumbnail' => $video['snippet']['thumbnails']['high']['url'],
					'short' => $this->videoEhShort($client, $youtubeApiKey, $projeto['canal_youtube_id'], $videoId),
				]);
				$videoIdsCadastrados[$videoId] = true;
				$inseridos++;
			}
		}

		if ($inseridos > 0) {
			$this->invalidarCacheHome();
		}

		return $this->encerrar('Concluído: ' . $inseridos . ' vídeo(s) novo(s) captado(s).');
	}

	private function videoEhShort($client, string $youtubeApiKey, string $canalYoutubeId, string $videoId): bool
	{
		$canalYoutubeId = trim($canalYoutubeId);
		if ($canalYoutubeId === '' || ! str_starts_with($canalYoutubeId, 'UC')) {
			return false;
		}

		$playlistId = 'UUSH' . substr($canalYoutubeId, 2);

		$body = $this->requisicaoYoutubeApi($client, 'https://www.googleapis.com/youtube/v3/playlistItems', [
			'key' => $youtubeApiKey,
			'part' => 'contentDetails',
			'playlistId' => $playlistId,
			'videoId' => $videoId,
			'maxResults' => 1,
		]);

		return $body !== null && ! empty($body['items']);
	}

	/**
	 * GET na API do YouTube sem interromper o cron em erro HTTP ou de rede.
	 */
	private function requisicaoYoutubeApi($client, string $url, array $query): ?array
	{
		try {
			$response = $client->request('GET', $url, [
				'query' => $query,
				'http_errors' => false,
				'timeout' => 30,
			]);

			$statusCode = $response->getStatusCode();
			if ($statusCode !== 200) {
				$erroApi = json_decode($response->getBody(), true);
				$mensagemErro = $erroApi['error']['message'] ?? $response->getReasonPhrase();
				log_message(
					'warning',
					'[Cron YouTube] HTTP ' . $statusCode . ' em ' . $url . ': ' . $mensagemErro
				);

				return null;
			}

			$body = json_decode($response->getBody(), true);

			return is_array($body) ? $body : null;
		} catch (\Throwable $e) {
			log_message('error', '[Cron YouTube] Falha na requisição para ' . $url . ': ' . $e->getMessage());

			return null;
		}
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
			$this->logger->error('cron:captar-videos-youtube: ' . $mensagem);
			return;
		}

		CLI::write($mensagem);
		$this->logger->info('cron:captar-videos-youtube: ' . $mensagem);
	}
}
