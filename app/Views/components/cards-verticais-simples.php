<?php

declare(strict_types=1);

use CodeIgniter\I18n\Time;

/*
Variáveis:
dados = {
id, imagem, url, titulo, autor, revisor, narrador, produtor (artigo),
publicacao, texticulo, link_video_youtube, tipo_conteudo
}
*/

helper('_formata_video');
helper('pauta_imagem');

$tipo = $dados['tipo_conteudo'] ?? 'artigo';
$ytIdLista = null;
$imagemBruta = trim((string) ($dados['imagem'] ?? ''));
$imagemSrc = $imagemBruta !== '' ? $imagemBruta : base_url('public/assets/imagem-default.png');
$abrirYoutube = false;
$href = '';
$linkClass = '';

if ($tipo === 'artigo') {
	$ytIdLista = extrair_id_video_youtube($dados['link_video_youtube'] ?? null);
	if ($ytIdLista !== null) {
		$imagemSrc = cria_url_thumb($ytIdLista);
		$href = cria_link_watch($ytIdLista);
	} else {
		$href = (string) ($dados['link_video_youtube'] ?? '#');
	}
	$linkClass = 'gen-video-popup';
	$abrirYoutube = true;
} else {
	$imagemSrc = url_imagem_pauta($dados['id'] ?? '');
}

$dataPublicacao = '';
try {
	if (! empty($dados['publicacao'])) {
		$dataPublicacao = app_time($dados['publicacao'])->toLocalizedString('dd MMM yyyy');
	}
} catch (\Throwable) {
	$dataPublicacao = '';
}

$titulo = $dados['titulo'] ?? '';
$autor = $dados['autor'] ?? '';
$resumo = $dados['texticulo'] ?? '';
$hrefAutor = site_url('site/escritor/' . urlencode($autor));

$papeisArtigo = [];
if ($tipo === 'artigo') {
	foreach (
		[
			'Escritor' => $autor,
			'Revisor' => $dados['revisor'] ?? '',
			'Narrador' => $dados['narrador'] ?? '',
			'Produtor' => $dados['produtor'] ?? '',
		] as $rotuloPapel => $nomePapel
	) {
		$n = trim((string) $nomePapel);
		if ($n !== '') {
			$papeisArtigo[] = [
				'rotulo' => $rotuloPapel,
				'nome' => $n,
				'href' => site_url('site/escritor/' . urlencode($n)),
			];
		}
	}
}
?>

<div class="vl-card-vertical-col col-sm-6 col-lg-3">
	<div class="vl-card-vertical card h-100 border-0 shadow-sm rounded-3 overflow-hidden w-100">
		<div class="vl-card-vertical-thumb rounded-top-3 position-relative">
			<?php if ($abrirYoutube): ?>
				<a href="<?= esc($href, 'attr'); ?>"
					class="vl-card-vertical-thumb-link text-decoration-none <?= esc($linkClass, 'attr'); ?>"
					aria-label="Assistir vídeo no YouTube">
					<img class="vl-card-vertical-thumb-img" src="<?= esc($imagemSrc, 'attr'); ?>"
						alt="<?= esc($titulo, 'attr'); ?>"
						loading="lazy">
					<span class="vl-card-vertical-play position-absolute top-50 start-50 translate-middle text-white" aria-hidden="true">
						<i class="bi bi-play-circle-fill" style="font-size: 2.75rem; opacity: 0.92; filter: drop-shadow(0 2px 6px rgba(0,0,0,.45));"></i>
					</span>
				</a>
			<?php else: ?>
				<div class="vl-card-vertical-thumb-link">
					<img class="vl-card-vertical-thumb-img" src="<?= esc($imagemSrc, 'attr'); ?>"
						alt="<?= esc($titulo, 'attr'); ?>"
						loading="lazy">
				</div>
			<?php endif; ?>
		</div>
		<div class="card-body d-flex flex-column p-3">
			<h2 class="h6 card-title lh-sm mb-2">
				<?php if ($abrirYoutube): ?>
					<a href="<?= esc($href, 'attr'); ?>"
						class="link-dark text-decoration-none fw-bold <?= esc($linkClass, 'attr'); ?>"><?= esc($titulo); ?></a>
				<?php else: ?>
					<span class="text-dark fw-bold"><?= esc($titulo); ?></span>
				<?php endif; ?>
			</h2>
			<p class="card-text small text-body-secondary mb-3 flex-grow-1"
				style="display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 3; line-clamp: 3; overflow: hidden;">
				<?= esc($resumo); ?></p>
			<div class="mt-auto pt-2 border-top small text-secondary">
				<?php if ($dataPublicacao !== ''): ?>
					<div class="mb-2 small text-dark">
						<i class="bi bi-calendar3 me-1" aria-hidden="true"></i><?= esc($dataPublicacao); ?>
					</div>
				<?php endif; ?>
				<?php if ($tipo === 'artigo' && $papeisArtigo !== []): ?>
					<?php foreach (array_chunk($papeisArtigo, 2) as $parPapeis): ?>
						<div class="row g-2 mb-1">
							<?php foreach ($parPapeis as $papel): ?>
								<div class="col-6">
									<div class="min-w-0">
										<div class="text-body-secondary text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.03em;"><?= esc($papel['rotulo']); ?></div>
										<a href="<?= esc($papel['href'], 'attr'); ?>" class="link-secondary text-truncate d-block small fw-semibold"><?= esc($papel['nome']); ?></a>
									</div>
								</div>
							<?php endforeach; ?>
							<?php if (count($parPapeis) === 1): ?>
								<div class="col-6"></div>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				<?php else: ?>
					<div class="d-flex flex-wrap align-items-center column-gap-2 row-gap-1">
						<span class="text-nowrap">
							<i class="bi bi-person me-1" aria-hidden="true"></i>
							<a href="<?= esc($hrefAutor, 'attr'); ?>" class="link-secondary"><?= esc($autor); ?></a>
						</span>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
