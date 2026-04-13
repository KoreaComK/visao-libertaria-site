<?= $this->extend('layouts/colaboradores'); ?>

<?= $this->section('content'); ?>

<?php
$nomeEscritorUrl = $_SESSION['colaboradores']['nome'] ?? '';
$metricas = $metricas ?? array(
	'em_producao' => 0,
	'media_dias_publicar' => null,
	'media_dias_publicar_n' => 0,
	'media_palavras_30d' => null,
	'media_palavras_30d_n' => 0,
	'media_palavras_historico' => null,
);
?>

<style>
	.listagem-site-filtros .form-control,
	.listagem-site-filtros .form-select {
		font-family: inherit;
	}

	.listagem-site-table-wrap {
		max-height: min(70vh, 42rem);
		overflow: auto;
	}

	.listagem-site-table-wrap .table thead.listagem-site-thead th {
		position: sticky;
		top: 0;
		z-index: 2;
		background-color: var(--bs-secondary-bg) !important;
		color: var(--bs-body-color);
		font-weight: 600;
		font-size: 0.7rem;
		letter-spacing: 0.04em;
		text-transform: uppercase;
		border-bottom: 1px solid var(--bs-border-color) !important;
		box-shadow: 0 1px 0 rgba(0, 0, 0, 0.06);
		vertical-align: middle;
	}

	[data-bs-theme="dark"] .listagem-site-table-wrap .table thead.listagem-site-thead th,
	[data-mdb-theme="dark"] .listagem-site-table-wrap .table thead.listagem-site-thead th {
		box-shadow: 0 1px 0 rgba(255, 255, 255, 0.08);
	}

	.min-height-listagem {
		min-height: 6rem;
	}

	.meus-artigos-kpi-card .tooltip-inner {
		text-align: left;
		max-width: 16rem;
	}

	.kanban-producao-scroll {
		overflow-x: auto;
		flex-wrap: nowrap;
		-webkit-overflow-scrolling: touch;
		scroll-snap-type: x proximity;
	}

	.kanban-producao-col {
		scroll-snap-align: start;
	}

	.kanban-card-titulo {
		display: -webkit-box;
		-webkit-line-clamp: 2;
		-webkit-box-orient: vertical;
		overflow: hidden;
	}

	.meus-publicados-campo-titulo {
		max-width: 16rem;
	}

	.meus-publicados-filtro-linha {
		row-gap: 0.5rem;
		column-gap: 1rem;
	}

	.meus-artigos-kpi-card .kpi-metrica-bloco {
		flex-grow: 1;
		display: flex;
		flex-direction: column;
		justify-content: flex-end;
		min-height: 4.25rem;
	}
</style>

<div class="container-fluid py-3">
	<div class="container">
		<div class="d-sm-flex justify-content-between align-items-center mb-4">
			<div>
				<h1 class="h3 mb-1"><?= esc($titulo ?? 'Meus artigos'); ?></h1>
				<p class="text-muted small mb-0">Resumo da sua atividade e listas dos seus artigos</p>
			</div>
			<a href="<?= site_url('colaboradores/artigos/cadastrar'); ?>" class="btn btn-sm btn-primary mt-2 mt-sm-0">Novo artigo</a>
		</div>

		<section class="mb-4" aria-labelledby="heading-meus-artigos-30d">
			<h2 id="heading-meus-artigos-30d" class="h5 text-body mb-1">Últimos 30 dias</h2>
			<p class="text-muted small mb-3 mb-md-4">Métricas dos últimos 30 dias, comparadas aos 30 dias imediatamente anteriores</p>
			<div class="row g-3">
				<div class="col-md-6">
					<div class="card border rounded-3 shadow-sm h-100 meus-artigos-kpi-card">
						<div class="card-body p-3">
							<div class="d-flex align-items-start justify-content-between gap-2 mb-2">
								<h3 class="h6 text-body mb-0">Artigos escritos</h3>
								<button type="button" class="btn btn-link text-muted p-0 lh-1 border-0"
									data-bs-toggle="tooltip" data-bs-placement="top"
									aria-label="Ajuda: artigos escritos"
									data-bs-title="Artigos que você criou como escritor nos últimos 30 dias (inclui rascunhos e excluídos recentemente).">
									<i class="fas fa-circle-question" aria-hidden="true"></i>
								</button>
							</div>
							<p class="display-6 text-primary mb-1">
								<?= (($artigos['atual'] < 10) ? ('0') : ('')) . (number_format($artigos['atual'], 0, ',', '.')); ?>
							</p>
							<p class="small text-muted mb-0">
								<span class="text-<?= ($artigos['diferenca'] > 0) ? ('success') : (($artigos['diferenca'] < 0) ? ('danger') : ('body-secondary')); ?>">
									<?= ($artigos['diferenca'] > 0) ? ('+') : (''); ?><?= number_format($artigos['diferenca'], 0, ',', '.'); ?>
									<i class="fas <?= ($artigos['diferenca'] > 0) ? ('fa-up-long') : (($artigos['diferenca'] < 0) ? ('fa-down-long') : ('fa-minus')); ?> fa-xs" aria-hidden="true"></i>
								</span>
								vs período anterior
								<span class="text-body-secondary">(<?= number_format($artigos['antigo'], 0, ',', '.'); ?>)</span>
							</p>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="card border rounded-3 shadow-sm h-100 meus-artigos-kpi-card">
						<div class="card-body p-3">
							<div class="d-flex align-items-start justify-content-between gap-2 mb-2">
								<h3 class="h6 text-body mb-0">Artigos publicados</h3>
								<button type="button" class="btn btn-link text-muted p-0 lh-1 border-0"
									data-bs-toggle="tooltip" data-bs-placement="top"
									aria-label="Ajuda: artigos publicados"
									data-bs-title="Artigos com data de publicação nos últimos 30 dias e você como escritor.">
									<i class="fas fa-circle-question" aria-hidden="true"></i>
								</button>
							</div>
							<p class="display-6 text-success mb-1">
								<?= (($artigos['publicados_atual'] < 10) ? ('0') : ('')) . (number_format($artigos['publicados_atual'], 0, ',', '.')); ?>
							</p>
							<p class="small text-muted mb-0">
								<span class="text-<?= ($artigos['publicados_diferenca'] > 0) ? ('success') : (($artigos['publicados_diferenca'] < 0) ? ('danger') : ('body-secondary')); ?>">
									<?= ($artigos['publicados_diferenca'] > 0) ? ('+') : (''); ?><?= number_format($artigos['publicados_diferenca'], 0, ',', '.'); ?>
									<i class="fas <?= ($artigos['publicados_diferenca'] > 0) ? ('fa-up-long') : (($artigos['publicados_diferenca'] < 0) ? ('fa-down-long') : ('fa-minus')); ?> fa-xs" aria-hidden="true"></i>
								</span>
								vs período anterior
								<span class="text-body-secondary">(<?= number_format($artigos['publicados_antigo'], 0, ',', '.'); ?>)</span>
							</p>
						</div>
					</div>
				</div>
			</div>
		</section>

		<section class="mb-4" aria-labelledby="heading-meus-artigos-totais">
			<h2 id="heading-meus-artigos-totais" class="h5 text-body mb-1">Totais</h2>
			<p class="text-muted small mb-3 mb-md-4">Números acumulados como escritor. Escritos, publicados e palavras referem-se a artigos ativos; descartados mostra o total histórico de artigos que eliminou</p>
			<div class="row g-3 mb-4">
				<div class="col-6 col-md-3">
					<div class="card border rounded-3 shadow-sm h-100 meus-artigos-kpi-card">
						<div class="card-body p-3">
							<div class="d-flex align-items-start justify-content-between gap-2 mb-2">
								<h3 class="h6 text-body mb-0">Escritos</h3>
								<button type="button" class="btn btn-link text-muted p-0 lh-1 border-0"
									data-bs-toggle="tooltip" data-bs-placement="top"
									aria-label="Ajuda: escritos"
									data-bs-title="Total de artigos em que você é o escritor e que não foram descartados (todas as fases).">
									<i class="fas fa-circle-question" aria-hidden="true"></i>
								</button>
							</div>
							<p class="display-6 text-primary mb-1">
								<?= (($resumo['escritos'] < 10) ? ('0') : ('')) . number_format((int) $resumo['escritos'], 0, ',', '.'); ?>
							</p>
							<p class="small text-muted mb-0">Artigos ativos no seu nome</p>
						</div>
					</div>
				</div>
				<div class="col-6 col-md-3">
					<div class="card border rounded-3 shadow-sm h-100 meus-artigos-kpi-card">
						<div class="card-body p-3">
							<div class="d-flex align-items-start justify-content-between gap-2 mb-2">
								<h3 class="h6 text-body mb-0">Publicados</h3>
								<button type="button" class="btn btn-link text-muted p-0 lh-1 border-0"
									data-bs-toggle="tooltip" data-bs-placement="top"
									aria-label="Ajuda: publicados"
									data-bs-title="Artigos concluídos no site (fases finais), você como escritor, não descartados.">
									<i class="fas fa-circle-question" aria-hidden="true"></i>
								</button>
							</div>
							<p class="display-6 text-success mb-1">
								<?= (($resumo['publicados'] < 10) ? ('0') : ('')) . number_format((int) $resumo['publicados'], 0, ',', '.'); ?>
							</p>
							<p class="small text-muted mb-0">Como escritor, já no ar ou arquivados</p>
						</div>
					</div>
				</div>
				<div class="col-6 col-md-3">
					<div class="card border rounded-3 shadow-sm h-100 meus-artigos-kpi-card">
						<div class="card-body p-3">
							<div class="d-flex align-items-start justify-content-between gap-2 mb-2">
								<h3 class="h6 text-body mb-0">Palavras totais</h3>
								<button type="button" class="btn btn-link text-muted p-0 lh-1 border-0"
									data-bs-toggle="tooltip" data-bs-placement="top"
									aria-label="Ajuda: palavras totais"
									data-bs-title="Soma das palavras contabilizadas para o escritor em todos os seus artigos ativos (não descartados).">
									<i class="fas fa-circle-question" aria-hidden="true"></i>
								</button>
							</div>
							<p class="display-6 text-primary mb-1">
								<?= (($resumo['palavras_totais'] < 10) ? ('0') : ('')) . number_format((int) $resumo['palavras_totais'], 0, ',', '.'); ?>
							</p>
							<p class="small text-muted mb-0">Acumulado no papel de escritor</p>
						</div>
					</div>
				</div>
				<div class="col-6 col-md-3">
					<div class="card border rounded-3 shadow-sm h-100 meus-artigos-kpi-card">
						<div class="card-body p-3">
							<div class="d-flex align-items-start justify-content-between gap-2 mb-2">
								<h3 class="h6 text-body mb-0">Descartados</h3>
								<button type="button" class="btn btn-link text-muted p-0 lh-1 border-0"
									data-bs-toggle="tooltip" data-bs-placement="top"
									aria-label="Ajuda: descartados"
									data-bs-title="Total histórico de artigos seus em que o descarte foi registado; não entram nas contagens de artigos ativos acima.">
									<i class="fas fa-circle-question" aria-hidden="true"></i>
								</button>
							</div>
							<p class="display-6 text-warning mb-1">
								<?= (($resumo['descartados'] < 10) ? ('0') : ('')) . number_format((int) ($resumo['descartados'] ?? 0), 0, ',', '.'); ?>
							</p>
							<p class="small text-muted mb-0">Histórico de artigos eliminados</p>
						</div>
					</div>
				</div>
			</div>
		</section>

		<section class="mb-4" aria-labelledby="heading-meus-artigos-metricas">
			<h2 id="heading-meus-artigos-metricas" class="h5 text-body mb-1">Produção e ritmo de publicação</h2>
			<p class="text-muted small mb-3 mb-md-4">Indicadores atuais da trilha de produção, tempo até publicar nos últimos 30 dias e tamanho médio do texto em comparação com o seu histórico como escritor</p>
			<div class="row g-3">
				<div class="col-md-6 col-xl-4">
					<div class="card border rounded-3 shadow-sm h-100 meus-artigos-kpi-card">
						<div class="card-body p-3 d-flex flex-column h-100">
							<div class="d-flex align-items-start justify-content-between gap-2 mb-2">
								<h3 class="h6 text-body mb-0">Em produção</h3>
								<button type="button" class="btn btn-link text-muted p-0 lh-1 border-0"
									data-bs-toggle="tooltip" data-bs-placement="top"
									aria-label="Ajuda: em produção"
									data-bs-title="Artigos seus como escritor que ainda não estão publicados (fases antes de concluído), excluindo descartados.">
									<i class="fas fa-circle-question" aria-hidden="true"></i>
								</button>
							</div>
							<div class="kpi-metrica-bloco">
								<p class="display-6 text-body mb-0"><?= number_format((int) $metricas['em_producao'], 0, ',', '.'); ?></p>
							</div>
							<p class="small text-muted mb-0 pt-2 mt-auto border-top border-opacity-25">Na trilha de produção agora (reflete o quadro Kanban abaixo)</p>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-xl-4">
					<div class="card border rounded-3 shadow-sm h-100 meus-artigos-kpi-card">
						<div class="card-body p-3 d-flex flex-column h-100">
							<div class="d-flex align-items-start justify-content-between gap-2 mb-2">
								<h3 class="h6 text-body mb-0">Tempo até publicar</h3>
								<button type="button" class="btn btn-link text-muted p-0 lh-1 border-0"
									data-bs-toggle="tooltip" data-bs-placement="top"
									aria-label="Ajuda: tempo até publicar"
									data-bs-title="Média de dias entre a criação do artigo e a data de publicação, só para artigos publicados nos últimos 30 dias, não descartados, com datas válidas.">
									<i class="fas fa-circle-question" aria-hidden="true"></i>
								</button>
							</div>
							<div class="kpi-metrica-bloco">
								<?php if ($metricas['media_dias_publicar'] !== null): ?>
									<p class="display-6 text-body mb-0"><?= number_format($metricas['media_dias_publicar'], 1, ',', '.'); ?> <span class="fs-6 fw-normal text-muted">dias</span></p>
								<?php else: ?>
									<p class="display-6 text-body-secondary mb-0">—</p>
								<?php endif; ?>
							</div>
							<p class="small text-muted mb-0 pt-2 mt-auto border-top border-opacity-25">
								<?php if ($metricas['media_dias_publicar'] !== null): ?>
									Média com base em <?= (int) $metricas['media_dias_publicar_n']; ?> artigo<?= ((int) $metricas['media_dias_publicar_n'] === 1) ? ('') : ('s'); ?> publicado<?= ((int) $metricas['media_dias_publicar_n'] === 1) ? ('') : ('s'); ?> nos últimos 30 dias
								<?php else: ?>
									Sem artigos publicados nesta janela para calcular a média
								<?php endif; ?>
							</p>
						</div>
					</div>
				</div>
				<div class="col-md-12 col-xl-4">
					<div class="card border rounded-3 shadow-sm h-100 meus-artigos-kpi-card">
						<div class="card-body p-3 d-flex flex-column h-100">
							<div class="d-flex align-items-start justify-content-between gap-2 mb-2">
								<h3 class="h6 text-body mb-0">Palavras por artigo</h3>
								<button type="button" class="btn btn-link text-muted p-0 lh-1 border-0"
									data-bs-toggle="tooltip" data-bs-placement="top"
									aria-label="Ajuda: palavras por artigo"
									data-bs-title="Média de palavras (campo do escritor) nos artigos publicados nos últimos 30 dias, comparada à média em todos os artigos seus como escritor.">
									<i class="fas fa-circle-question" aria-hidden="true"></i>
								</button>
							</div>
							<div class="kpi-metrica-bloco">
								<?php if ($metricas['media_palavras_30d'] !== null): ?>
									<p class="display-6 text-body mb-0">
										<?= number_format((int) round($metricas['media_palavras_30d']), 0, ',', '.'); ?>
										<span class="fs-6 fw-normal text-muted">últ. 30 dias</span>
									</p>
								<?php else: ?>
									<p class="display-6 text-body-secondary mb-0">— <span class="fs-6 fw-normal text-muted">últ. 30 dias</span></p>
								<?php endif; ?>
							</div>
							<div class="pt-2 mt-auto border-top border-opacity-25">
								<?php if ($metricas['media_palavras_historico'] !== null): ?>
									<p class="small text-muted mb-0">
										<span class="text-body">Média geral (todos os seus artigos):</span>
										<strong><?= number_format((int) round($metricas['media_palavras_historico']), 0, ',', '.'); ?></strong> palavras
									</p>
									<?php if ($metricas['media_palavras_30d'] !== null): ?>
										<?php
										$diffPal = $metricas['media_palavras_30d'] - $metricas['media_palavras_historico'];
										$cls = ($diffPal > 0) ? 'success' : (($diffPal < 0) ? 'danger' : 'body-secondary');
										?>
										<p class="small mb-0 mt-1">
											<span class="text-<?= $cls; ?>"><?= ($diffPal > 0) ? ('+') : (''); ?><?= number_format((int) round($diffPal), 0, ',', '.'); ?></span>
											<span class="text-muted"> vs média geral</span>
										</p>
									<?php endif; ?>
								<?php else: ?>
									<p class="small text-muted mb-0">Ainda sem artigos como escritor para média geral.</p>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<section aria-labelledby="heading-meus-artigos-listas">
			<div class="card border rounded-3 shadow-sm mb-4">
				<div class="card-header bg-body-secondary bg-opacity-25 border-bottom p-3">
					<div class="d-sm-flex justify-content-between align-items-center gap-2">
						<h3 class="h6 mb-0">Meus artigos em produção</h3>
						<a href="<?= site_url('colaboradores/artigos/cadastrar'); ?>"
							class="btn btn-sm btn-outline-primary flex-shrink-0">Novo artigo</a>
					</div>
				</div>
				<div class="card-body p-3">
					<div class="listagem-site-table-wrap rounded border min-height-listagem">
						<div class="tabela-meus-producao"></div>
					</div>
				</div>
			</div>

			<div class="card border rounded-3 shadow-sm">
				<div class="card-header bg-body-secondary bg-opacity-25 border-bottom p-3">
					<div class="d-sm-flex justify-content-between align-items-center gap-2">
						<h3 class="h6 mb-0">Meus artigos publicados</h3>
						<?php if ($nomeEscritorUrl !== ''): ?>
							<a href="<?= site_url('site/escritor/' . urlencode($nomeEscritorUrl)); ?>"
								class="btn btn-sm btn-outline-primary flex-shrink-0" target="_blank" rel="noopener noreferrer">Ver página pública</a>
						<?php endif; ?>
					</div>
				</div>
				<div class="card-body p-3">
					<div class="listagem-site-filtros rounded-3 border bg-body-secondary bg-opacity-50 p-3 mb-3">
						<form class="mb-0" id="form-meus-publicados">
							<div class="d-flex flex-wrap align-items-end meus-publicados-filtro-linha">
								<div class="meus-publicados-campo-titulo flex-shrink-0">
									<label class="form-label small text-muted mb-1" for="titulo-meus-publicados">Pesquisar por título</label>
									<input class="form-control form-control-sm w-100" type="search" id="titulo-meus-publicados"
										name="titulo" placeholder="Pesquisar…" aria-label="Pesquisar por título" autocomplete="off">
								</div>
								<div class="form-check flex-shrink-0 mb-1">
									<input class="form-check-input" type="checkbox" value="1" id="incluir-descartados-meus-publicados"
										name="incluir_descartados" aria-describedby="help-incluir-descartados-publicados">
									<label class="form-check-label small" for="incluir-descartados-meus-publicados">Incluir artigos descartados</label>
								</div>
								<button class="btn btn-primary btn-sm btn-pesquisar-meus-publicados ms-auto flex-shrink-0" type="submit" aria-label="Pesquisar">
									<i class="fas fa-magnifying-glass me-1" aria-hidden="true"></i> Pesquisar
								</button>
							</div>
						</form>
					</div>
					<div class="table-responsive listagem-site-table-wrap rounded border min-height-listagem">
						<div class="tabela-meus-publicados"></div>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>

<script>
	var artigoId = null;
	var urlMeusArtigosList = '<?= esc(base_url('colaboradores/artigos/meusArtigosList'), 'js'); ?>';

	var meusArtigosLoadingHtml = '<div class="d-flex align-items-center justify-content-center gap-2 p-4 text-muted small">' +
		'<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>' +
		'<span>Carregando…</span></div>';

	function debounceMeusArtigos(fn, ms) {
		var t;
		return function () {
			var ctx = this, args = arguments;
			clearTimeout(t);
			t = setTimeout(function () { fn.apply(ctx, args); }, ms);
		};
	}

	function refreshMeusProducao() {
		$('.tabela-meus-producao').html(meusArtigosLoadingHtml);
		$.ajax({
			url: urlMeusArtigosList,
			type: 'get',
			dataType: 'html',
			data: {
				tipo: 'emProducao'
			},
			success: function (data) {
				$('.tabela-meus-producao').html(data);
			},
			error: function () {
				$('.tabela-meus-producao').html(
					'<div class="alert alert-danger m-3 mb-0 small" role="alert">Não foi possível carregar a lista. Tente novamente.</div>'
				);
			}
		});
	}

	function meusPublicadosQueryParams() {
		return {
			titulo: $('#titulo-meus-publicados').val(),
			tipo: 'finalizado',
			incluir_descartados: $('#incluir-descartados-meus-publicados').is(':checked') ? '1' : '0'
		};
	}

	function refreshMeusPublicados(url) {
		$('.tabela-meus-publicados').html(meusArtigosLoadingHtml);
		var opts = {
			type: 'get',
			dataType: 'html',
			success: function (data) {
				$('.tabela-meus-publicados').html(data);
			},
			error: function () {
				$('.tabela-meus-publicados').html(
					'<div class="alert alert-danger m-3 mb-0 small" role="alert">Não foi possível carregar a lista. Tente novamente.</div>'
				);
			}
		};
		if (!url) {
			opts.url = urlMeusArtigosList;
			opts.data = meusPublicadosQueryParams();
		} else {
			var u = new URL(url, window.location.href);
			var p = meusPublicadosQueryParams();
			Object.keys(p).forEach(function (k) {
				u.searchParams.set(k, p[k]);
			});
			opts.url = u.pathname + (u.search || '');
			opts.data = {};
		}
		$.ajax(opts);
	}

	$(document).ready(function () {
		document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
			if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
				new bootstrap.Tooltip(el);
			}
		});
		refreshMeusProducao();
		refreshMeusPublicados(false);
	});

	var debouncedPublicados = debounceMeusArtigos(function () {
		refreshMeusPublicados(false);
	}, 400);

	$('#titulo-meus-publicados').on('input', debouncedPublicados);

	$('#incluir-descartados-meus-publicados').on('change', function () {
		refreshMeusPublicados(false);
	});

	$('#form-meus-publicados').on('submit', function (e) {
		e.preventDefault();
		refreshMeusPublicados(false);
	});

	$("#modal-btn-si").on("click", function () {
		$("#mi-modal").modal('hide');
		if (typeof artigoId === 'undefined' || artigoId === null) {
			return false;
		}
		$.ajax({
			url: "<?php echo base_url('colaboradores/artigos/descartar/'); ?>" + artigoId,
			type: 'get',
			dataType: 'json',
			data: {},
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide(); },
			success: function (retorno) {
				if (retorno.status) {
					popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
					refreshMeusProducao();
					refreshMeusPublicados(false);
				} else {
					popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
				}
				artigoId = null;
			}
		});
		return false;
	});
</script>

<?= $this->endSection(); ?>
