<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>
<?php
$avatarPadrao = site_url('public/assets/avatar-default.png');
$avatarBruto = isset($colaborador['avatar']) ? trim((string) $colaborador['avatar']) : '';
$avatarSrc = ($avatarBruto !== '') ? $avatarBruto : $avatarPadrao;
$urlListaColaborador = site_url('site/colaboradorList/' . rawurlencode($colaborador['apelido']));
?>

<div class="container py-4">
	<div class="card border-0 shadow-sm mb-4">
		<div class="card-body p-4 p-lg-5 vl-perfil-hero">
			<div class="row align-items-center g-4">
				<div class="col-md-auto text-center text-md-start">
					<div class="position-relative d-inline-block mb-3">
						<img class="rounded-circle border border-3 border-white shadow d-block"
							style="width: 8.5rem; height: 8.5rem; object-fit: cover;"
							src="<?= esc($avatarSrc, 'attr'); ?>"
							alt="Avatar de <?= esc($colaborador['apelido']); ?>">
						<span class="position-absolute top-100 start-50 translate-middle badge bg-danger rounded-pill px-3 py-2 shadow-sm text-nowrap"><?= (int) $contador_pautas; ?>
							pauta<?= ($contador_pautas > 1) ? 's' : ''; ?></span>
					</div>
				</div>
				<div class="col min-w-0 text-center text-md-start">
					<h1 class="h2 mb-2 mb-md-3"><?= esc($colaborador['apelido']); ?></h1>
					<div class="d-flex flex-wrap justify-content-center justify-content-md-start gap-2 mb-3">
						<?php foreach ($atribuicoes as $atribuicao):
							$corAtrib = esc($atribuicao['cor'], 'attr');
							$corBgSubtle = ($atribuicao['cor'] === 'white') ? 'light' : $corAtrib;
							?>
							<span class="badge rounded-pill fw-semibold text-dark bg-<?= $corBgSubtle; ?>-subtle border border-<?= $corAtrib; ?> border-opacity-50"><?= esc($atribuicao['nome']); ?></span>
						<?php endforeach; ?>
					</div>
					<p class="text-muted small mb-0">
						<i class="bi bi-calendar3 me-1" aria-hidden="true"></i>
						Cadastrou-se no site há <?= esc($tempo); ?>.
					</p>
				</div>
				<?php if (! empty($conquistaDestaque)):
					$nomeConquistaCol = trim((string) ($conquistaDestaque['nome'] ?? ''));
					$tooltipConquistaCol = $nomeConquistaCol !== ''
						? ('Conquista de colaborador no site: ' . $nomeConquistaCol . '.')
						: 'Conquista de colaborador no site.';
					?>
					<div class="col-12 col-md-auto text-center text-md-end ms-md-auto pt-3 pt-md-0 ps-md-4 border-top border-md-0 border-md-start border-secondary border-opacity-25">
						<p class="small fw-semibold text-dark text-uppercase mb-3 mb-md-2" style="font-size: 0.72rem; letter-spacing: 0.06em;">Conquista de colaborador</p>
						<img class="rounded-circle shadow-sm border border-white vl-conquista-destaque-img"
							src="<?= esc(site_url($conquistaDestaque['imagem']), 'attr'); ?>"
							alt="<?= esc($nomeConquistaCol !== '' ? $nomeConquistaCol : 'Conquista de colaborador', 'attr'); ?>"
							data-bs-toggle="tooltip"
							data-bs-placement="left"
							data-bs-title="<?= esc($tooltipConquistaCol, 'attr'); ?>">
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<div class="card border-0 shadow-sm mb-4">
		<div class="card-body p-4">
			<div class="row g-4 align-items-start">
				<div class="col-lg-7">
					<h3 class="h6 text-dark mb-3">Atividade em pautas</h3>
					<ul class="list-inline small mb-3 mb-lg-0">
						<li class="list-inline-item me-3"><span class="text-dark fw-semibold"><?= esc(sprintf('%02d', (int) $resumo_pautas_periodo['cadastradas_semana'])); ?></span> cadastrada<?= ((int) $resumo_pautas_periodo['cadastradas_semana'] !== 1) ? 's' : ''; ?> nesta semana</li>
						<li class="list-inline-item me-3"><span class="text-dark fw-semibold"><?= esc(sprintf('%02d', (int) $resumo_pautas_periodo['usadas_semana'])); ?></span> usada<?= ((int) $resumo_pautas_periodo['usadas_semana'] !== 1) ? 's' : ''; ?> nesta semana</li>
						<li class="list-inline-item me-3"><span class="text-dark fw-semibold"><?= esc(sprintf('%02d', (int) $resumo_pautas_periodo['usadas_mes'])); ?></span> usada<?= ((int) $resumo_pautas_periodo['usadas_mes'] !== 1) ? 's' : ''; ?> no mês</li>
						<li class="list-inline-item"><span class="text-dark fw-semibold"><?= esc(sprintf('%02d', (int) $resumo_pautas_periodo['usadas_ano'])); ?></span> usada<?= ((int) $resumo_pautas_periodo['usadas_ano'] !== 1) ? 's' : ''; ?> no ano</li>
					</ul>
					<p class="small text-body-secondary mb-0">
						<i class="bi bi-clock-history me-1" aria-hidden="true"></i>
						<?php if ($ultima_pauta_cadastrada_formatada !== null): ?>
							Última pauta cadastrada: <span class="text-dark fw-semibold"><?= esc($ultima_pauta_cadastrada_formatada); ?></span>
						<?php else: ?>
							Ainda não há pautas cadastradas por este colaborador.
						<?php endif; ?>
					</p>
				</div>
				<div class="col-lg-5">
					<h3 class="h6 text-dark mb-3">Veja também</h3>
					<div class="d-flex flex-wrap gap-2">
						<a href="<?= esc(site_url('site/noticias'), 'attr'); ?>" class="btn btn-sm btn-outline-secondary">Notícias</a>
						<a href="<?= esc(site_url('site/escritor/' . rawurlencode($colaborador['apelido'])), 'attr'); ?>" class="btn btn-sm btn-outline-secondary">Perfil de escritor</a>
					</div>
					<p class="small text-body-secondary mt-3 mb-0">Em <span class="text-dark fw-semibold">Pautas</span> cadastre e acompanhe sugestões de tema. O perfil de escritor mostra <span class="text-dark fw-semibold">artigos publicados</span> e a participação em cada papel.</p>
				</div>
			</div>
		</div>
	</div>

	<section class="mb-5">
		<header class="border-bottom pb-3 mb-4">
			<h2 class="h4 mb-0 text-dark" id="vlColaboradorPautasTitulo">Pautas reservadas de <?= esc($colaborador['apelido']); ?></h2>
		</header>
		<div class="row <?= esc($classeListaCSS, 'attr'); ?>"></div>
	</section>
</div>
<script>
	document.addEventListener('DOMContentLoaded', function () {
		document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
			new bootstrap.Tooltip(el);
		});
	});

	(function () {
		var urlLista = <?= json_encode($urlListaColaborador, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>;

		$(document).ready(function () {
			$.ajax({
				url: urlLista,
				type: 'get',
				dataType: 'html',
				beforeSend: function () { $('#modal-loading').show(); },
				complete: function () { $('#modal-loading').hide(); },
				success: function (data) {
					$('.<?= esc($classeListaCSS, 'js'); ?>').html(data);
				}
			});
		});
	})();
</script>

<?= $this->endSection(); ?>
