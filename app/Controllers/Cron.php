<?php

namespace App\Controllers;

use Config\App;
use CodeIgniter\I18n\Time;
use App\Libraries\ArtigosHistoricos;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Cron extends BaseController
{
	/*HOME PAGE*/
	public function index($hash = NULL)
	{
		/*VERIFICANDO SE O CRON ESTÁ SENDO PROCESSADO PELO SISTEMA E NÃO POR UM USUÁRIO QUALQUER*/
		if ($hash === null) {
			return redirect()->to(base_url());
		}
		$configuracaoModel = new \App\Models\ConfiguracaoModel();
		$hash_base = $configuracaoModel->find('cron_hash')['config_valor'];
		if ($hash != $hash_base) {
			return redirect()->to(base_url());
		}

		$this->limparPautasAntigas($configuracaoModel);

		$this->desmarcarArtigosExpirados($configuracaoModel);

		$this->descartarArtigosAreaEscrita();

		$this->enviarEmailCarteiraVazia($configuracaoModel);

		$this->limparNotificacoesAntigas($configuracaoModel);

		$this->descartarArtigosAbandonados($configuracaoModel);

		$homeCacheInvalidar = $this->captarVideosYoutube();
		if ($this->avancarArtigosPublicadosNoYoutube()) {
			$homeCacheInvalidar = true;
		}

		if ($homeCacheInvalidar) {
			$this->invalidarCacheHome();
		}

		return 'Cron Finalizado';
	}

	/**
	 * Remove pautas antigas não reservadas (e comentários) e pautas de redator com artigo já criado.
	 */
	private function limparPautasAntigas(\App\Models\ConfiguracaoModel $configuracaoModel): void
	{
		$cronPautas = $configuracaoModel->find('cron_pautas_status_delete')['config_valor'];
		if ($cronPautas != '1') {
			return;
		}

		$cronDataPautas = $configuracaoModel->find('cron_pautas_data_delete')['config_valor'];
		$limiteCriacao = new Time('-' . $cronDataPautas);

		$pautasModel = new \App\Models\PautasModel();
		$pautasAntigas = $pautasModel
			->where('criado <=', $limiteCriacao->toDateTimeString())
			->where('reservado', null)
			->where('tag_fechamento', null)
			->where('redator_colaboradores_id', null)
			->withDeleted()
			->findAll();

		if ($pautasAntigas !== []) {
			$idsPautasAntigas = array_column($pautasAntigas, 'id');
			$pautasModel->db->table('pautas_comentarios')
				->whereIn('pautas_id', $idsPautasAntigas)
				->delete();

			$pautasExclusao = new \App\Models\PautasModel();
			foreach ($idsPautasAntigas as $idPauta) {
				$pautasExclusao->delete($idPauta, true);
			}
		}

		$pautasRedatorModel = new \App\Models\PautasModel();
		$pautasRedator = $pautasRedatorModel
			->where('redator_colaboradores_id IS NOT NULL', null, false)
			->findAll();

		if ($pautasRedator === []) {
			return;
		}

		$artigosModel = new \App\Models\ArtigosModel();
		$artigosRedator = $artigosModel
			->select('link, escrito_colaboradores_id')
			->where('escrito_colaboradores_id IS NOT NULL', null, false)
			->where('link IS NOT NULL', null, false)
			->where('link !=', '')
			->findAll();

		$artigosPorLinkRedator = [];
		foreach ($artigosRedator as $artigo) {
			$chave = $artigo['link'] . "\0" . $artigo['escrito_colaboradores_id'];
			$artigosPorLinkRedator[$chave] = true;
		}

		$pautasExclusaoRedator = new \App\Models\PautasModel();
		foreach ($pautasRedator as $pauta) {
			$chave = $pauta['link'] . "\0" . $pauta['redator_colaboradores_id'];
			if (! isset($artigosPorLinkRedator[$chave])) {
				continue;
			}
			$pautasExclusaoRedator->delete($pauta['id']);
		}
	}

	/**
	 * Desmarca artigos com marcação expirada (teoria e notícia × revisão, narração, produção).
	 */
	private function desmarcarArtigosExpirados(\App\Models\ConfiguracaoModel $configuracaoModel): void
	{
		if ($configuracaoModel->find('cron_artigos_desmarcar_status')['config_valor'] != '1') {
			return;
		}

		$artigosHistoricos = new ArtigosHistoricos();
		$regras = [
			['cron_artigos_teoria_desmarcar_data_revisao', 2, 'T'],
			['cron_artigos_teoria_desmarcar_data_narracao', 3, 'T'],
			['cron_artigos_teoria_desmarcar_data_producao', 4, 'T'],
			['cron_artigos_noticia_desmarcar_data_revisao', 2, 'N'],
			['cron_artigos_noticia_desmarcar_data_narracao', 3, 'N'],
			['cron_artigos_noticia_desmarcar_data_producao', 4, 'N'],
		];

		foreach ($regras as [$configChave, $faseProducaoId, $tipoArtigo]) {
			$this->desmarcarArtigosPorPrazo(
				$configuracaoModel,
				$artigosHistoricos,
				$configChave,
				$faseProducaoId,
				$tipoArtigo
			);
		}
	}

	/**
	 * Descarta artigos parados na área de escrita (fase 1) há mais de 7 dias.
	 */
	private function descartarArtigosAreaEscrita(): void
	{
		$limiteAtualizado = new Time('-7 days');

		$artigosModel = new \App\Models\ArtigosModel();
		$artigos = $artigosModel
			->where('fase_producao_id', 1)
			->where('atualizado <=', $limiteAtualizado->toDateTimeString())
			->where('descartado', null)
			->findAll();

		if ($artigos === []) {
			return;
		}

		$artigosHistoricos = new ArtigosHistoricos();
		$artigosAtualizacao = new \App\Models\ArtigosModel();

		foreach ($artigos as $artigo) {
			$artigosHistoricos->cadastraHistorico($artigo['id'], 'descartou', $artigo['escrito_colaboradores_id']);
			$artigosAtualizacao->update($artigo['id'], [
				'descartado' => $artigosAtualizacao->getNow(),
				'descartado_colaboradores_id' => $artigo['escrito_colaboradores_id'],
			]);
		}
	}

	/**
	 * Envia e-mail mensal para colaboradores que contribuíram em artigos publicados sem carteira cadastrada.
	 */
	private function enviarEmailCarteiraVazia(\App\Models\ConfiguracaoModel $configuracaoModel): void
	{
		$cronDiasEmailCarteira = $configuracaoModel->find('cron_email_carteira_data')['config_valor'];
		$time = new Time('+' . $cronDiasEmailCarteira);
		$ultimoEnvio = app_time($configuracaoModel->find('cron_email_carteira')['config_valor']);

		if ($time->getMonth() === $time->today()->getMonth() || $ultimoEnvio->getMonth() === $time->getMonth()) {
			return;
		}

		$colaboradoresModel = new \App\Models\ColaboradoresModel();
		$colaboradores = $colaboradoresModel
			->select('DISTINCT colaboradores.email')
			->join(
				'artigos',
				'artigos.fase_producao_id = 6 AND artigos.descartado IS NULL AND (
					artigos.escrito_colaboradores_id = colaboradores.id
					OR artigos.revisado_colaboradores_id = colaboradores.id
					OR artigos.narrado_colaboradores_id = colaboradores.id
					OR artigos.produzido_colaboradores_id = colaboradores.id
				)',
				'inner',
				false
			)
			->where('(colaboradores.carteira IS NULL OR colaboradores.carteira = \'\')', null, false)
			->findAll();

		if ($colaboradores !== []) {
			$emails = array_column($colaboradores, 'email');
			$enviaEmail = new \App\Libraries\EnviaEmail();
			$enviaEmail->enviaEmail(
				null,
				'VISÃO LIBERTÁRIA - CARTEIRA NÃO CADASTRADA',
				$enviaEmail->getMensagemCarteiraVazia(),
				false,
				$emails
			);
		}

		$configuracaoModel->update('cron_email_carteira', ['config_valor' => $time->toDateString()]);
	}

	/**
	 * Remove notificações antigas (visualizadas e cadastradas) conforme prazos da config.
	 */
	private function limparNotificacoesAntigas(\App\Models\ConfiguracaoModel $configuracaoModel): void
	{
		if ($configuracaoModel->find('cron_notificacoes_status_delete')['config_valor'] != '1') {
			return;
		}

		$prazoVisualizadas = $configuracaoModel->find('cron_notificacoes_data_visualizado')['config_valor'];
		$limiteVisualizadas = new Time('-' . $prazoVisualizadas);

		$notificacoesModel = new \App\Models\ColaboradoresNotificacoesModel();
		$notificacoesModel
			->where('data_visualizado <=', $limiteVisualizadas->toDateTimeString())
			->delete();

		$prazoCadastradas = $configuracaoModel->find('cron_notificacoes_data_cadastrado')['config_valor'];
		$limiteCadastradas = new Time('-' . $prazoCadastradas);

		$notificacoesModel = new \App\Models\ColaboradoresNotificacoesModel();
		$notificacoesModel
			->where('criado <=', $limiteCadastradas->toDateTimeString())
			->delete();
	}

	/**
	 * Descarta artigos abandonados nas fases 1–4 conforme prazo configurado.
	 */
	private function descartarArtigosAbandonados(\App\Models\ConfiguracaoModel $configuracaoModel): void
	{
		if ($configuracaoModel->find('cron_artigos_descartar_status')['config_valor'] != '1') {
			return;
		}

		$prazo = $configuracaoModel->find('cron_artigos_descartar_data')['config_valor'];
		$limiteCriacao = new Time('-' . $prazo);

		$artigosModel = new \App\Models\ArtigosModel();
		$artigos = $artigosModel
			->where('criado <=', $limiteCriacao->toDateTimeString())
			->where('descartado', null)
			->whereIn('fase_producao_id', ['1', '2', '3', '4'])
			->findAll();

		if ($artigos === []) {
			return;
		}

		$artigosExclusao = new \App\Models\ArtigosModel();
		foreach ($artigos as $artigo) {
			$artigosExclusao->update($artigo['id'], ['descartado_colaboradores_id' => 1]);
			$artigosExclusao->delete($artigo['id']);
		}
	}

	/**
	 * Capta vídeos novos dos canais YouTube dos projetos.
	 */
	private function captarVideosYoutube(): bool
	{
		$youtubeApiKey = getenv('YOUTUBE_API_KEY');
		$projetos = (new \App\Models\ProjetosModel())->findAll();

		if ($projetos === []) {
			return false;
		}

		$projetosVideosModel = new \App\Models\ProjetosVideosModel();
		$videoIdsCadastrados = $projetosVideosModel->findColumn('video_id');
		$videoIdsCadastrados = $videoIdsCadastrados !== null
			? array_flip($videoIdsCadastrados)
			: [];

		$client = \Config\Services::curlrequest();
		$homeCacheInvalidar = false;

		foreach ($projetos as $projeto) {
			$videoResponse = $client->request('GET', 'https://www.googleapis.com/youtube/v3/search', [
				'query' => [
					'key' => $youtubeApiKey,
					'channelId' => $projeto['canal_youtube_id'],
					'part' => 'snippet',
					'order' => 'date',
					'maxResults' => 50,
					'type' => 'video',
				],
			]);

			$responseBody = json_decode($videoResponse->getBody(), true);
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
				$homeCacheInvalidar = true;
			}
		}

		return $homeCacheInvalidar;
	}

	/**
	 * Avança para fase 6 artigos com link de vídeo já cadastrado no YouTube.
	 */
	private function avancarArtigosPublicadosNoYoutube(): bool
	{
		helper('_formata_video');

		$projetosVideosModel = new \App\Models\ProjetosVideosModel();
		$videoIdsCadastrados = $projetosVideosModel->findColumn('video_id');
		$videoIdsCadastrados = $videoIdsCadastrados !== null
			? array_flip($videoIdsCadastrados)
			: [];

		if ($videoIdsCadastrados === []) {
			return false;
		}

		$artigosModel = new \App\Models\ArtigosModel();
		$artigosPublicar = $artigosModel
			->where('fase_producao_id', 5)
			->where('link_video_youtube IS NOT NULL', null, false)
			->where('link_video_youtube !=', '')
			->where('descartado', null)
			->findAll();

		if ($artigosPublicar === []) {
			return false;
		}

		$artigosModelAtualizacao = new \App\Models\ArtigosModel();
		$publicadoEm = $artigosModelAtualizacao->getNow();
		$homeCacheInvalidar = false;

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
				$homeCacheInvalidar = true;
			}
		}

		return $homeCacheInvalidar;
	}

	private function desmarcarArtigosPorPrazo(
		\App\Models\ConfiguracaoModel $configuracaoModel,
		ArtigosHistoricos $artigosHistoricos,
		string $configChave,
		int $faseProducaoId,
		string $tipoArtigo
	): void {
		$prazo = $configuracaoModel->find($configChave)['config_valor'];
		$limiteMarcado = new Time('-' . $prazo);

		$artigosModel = new \App\Models\ArtigosModel();
		$artigos = $artigosModel
			->where('marcado <=', $limiteMarcado->toDateTimeString())
			->where('fase_producao_id', $faseProducaoId)
			->where('tipo_artigo', $tipoArtigo)
			->findAll();

		if ($artigos === []) {
			return;
		}

		$artigosAtualizacao = new \App\Models\ArtigosModel();
		foreach ($artigos as $artigo) {
			$artigosHistoricos->cadastraHistorico($artigo['id'], 'desmarcou', $artigo['marcado_colaboradores_id']);
			$artigosAtualizacao->update($artigo['id'], [
				'marcado' => null,
				'marcado_colaboradores_id' => null,
			]);
		}
	}

	private function videoEhShort($client, string $youtubeApiKey, string $canalYoutubeId, string $videoId): bool
	{
		$canalYoutubeId = trim($canalYoutubeId);
		if ($canalYoutubeId === '' || ! str_starts_with($canalYoutubeId, 'UC')) {
			return false;
		}

		$playlistId = 'UUSH' . substr($canalYoutubeId, 2);

		$response = $client->request('GET', 'https://www.googleapis.com/youtube/v3/playlistItems', [
			'query' => [
				'key' => $youtubeApiKey,
				'part' => 'contentDetails',
				'playlistId' => $playlistId,
				'videoId' => $videoId,
				'maxResults' => 1,
			],
			'http_errors' => false,
		]);

		if ($response->getStatusCode() !== 200) {
			return false;
		}

		$body = json_decode($response->getBody(), true);

		return ! empty($body['items']);
	}
}
