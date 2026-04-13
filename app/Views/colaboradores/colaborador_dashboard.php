<?= $this->extend('layouts/colaboradores'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid py-3">
	<div class="container">
		<div class="d-sm-flex justify-content-between align-items-center mb-4">
			<div>
				<h1 class="h3 mb-1">Dashboard de artigos</h1>
				<p class="text-muted small mb-0">Visão geral do pipeline e listagem de todos os artigos do site</p>
			</div>
			<a href="<?= site_url('colaboradores/artigos/cadastrar'); ?>" class="btn btn-sm btn-primary mt-2 mt-sm-0">Novo artigo</a>
		</div>

		<section class="mb-4" aria-labelledby="heading-pipeline">
			<h2 id="heading-pipeline" class="h5 text-body mb-1">Produção no site</h2>
			<p class="text-muted small mb-0">Clique numa fase para listar os artigos nesse estado</p>
			<div class="card border rounded-3 shadow-sm mt-2">
				<div class="card-body p-2 p-sm-3">
					<div class="row row-cols-2 row-cols-sm-3 row-cols-lg-6 g-2">
						<div class="col">
							<button type="button"
								class="listagem-artigos-produzindo w-100 d-flex align-items-center gap-2 rounded-2 p-2 border border-transparent text-body pipeline-fase-link"
								data-fase-producao="1" data-modal-fase-titulo="Escrevendo" data-bs-toggle="modal"
								data-bs-target="#modalListagem" aria-haspopup="dialog" aria-controls="modalListagem">
								<span class="pipeline-fase-icon rounded-2 p-2 bg-success bg-opacity-10 text-success flex-shrink-0"><i class="far fa-file-lines" aria-hidden="true"></i></span>
								<span class="text-start pipeline-fase-text">
									<span class="d-block fw-bold lh-sm"><?= ($resumo['escrevendo'] < 10) ? ('0' . $resumo['escrevendo']) : ($resumo['escrevendo']); ?></span>
									<span class="d-block small text-muted text-truncate">Escrevendo</span>
								</span>
							</button>
						</div>
						<div class="col">
							<button type="button"
								class="listagem-artigos-produzindo w-100 d-flex align-items-center gap-2 rounded-2 p-2 border border-transparent text-body pipeline-fase-link"
								data-fase-producao="2" data-modal-fase-titulo="Revisando" data-bs-toggle="modal"
								data-bs-target="#modalListagem" aria-haspopup="dialog" aria-controls="modalListagem">
								<span class="pipeline-fase-icon rounded-2 p-2 bg-primary bg-opacity-10 text-primary flex-shrink-0"><i class="fas fa-pen-to-square" aria-hidden="true"></i></span>
								<span class="text-start pipeline-fase-text">
									<span class="d-block fw-bold lh-sm"><?= ($resumo['revisando'] < 10) ? ('0' . $resumo['revisando']) : ($resumo['revisando']); ?></span>
									<span class="d-block small text-muted text-truncate">Revisando</span>
								</span>
							</button>
						</div>
						<div class="col">
							<button type="button"
								class="listagem-artigos-produzindo w-100 d-flex align-items-center gap-2 rounded-2 p-2 border border-transparent text-body pipeline-fase-link"
								data-fase-producao="3" data-modal-fase-titulo="Narrando" data-bs-toggle="modal"
								data-bs-target="#modalListagem" aria-haspopup="dialog" aria-controls="modalListagem">
								<span class="pipeline-fase-icon rounded-2 p-2 bg-info bg-opacity-10 text-info flex-shrink-0"><i class="fas fa-microphone" aria-hidden="true"></i></span>
								<span class="text-start pipeline-fase-text">
									<span class="d-block fw-bold lh-sm"><?= ($resumo['narrando'] < 10) ? ('0' . $resumo['narrando']) : ($resumo['narrando']); ?></span>
									<span class="d-block small text-muted text-truncate">Narrando</span>
								</span>
							</button>
						</div>
						<div class="col">
							<button type="button"
								class="listagem-artigos-produzindo w-100 d-flex align-items-center gap-2 rounded-2 p-2 border border-transparent text-body pipeline-fase-link"
								data-fase-producao="4" data-modal-fase-titulo="Produzindo" data-bs-toggle="modal"
								data-bs-target="#modalListagem" aria-haspopup="dialog" aria-controls="modalListagem">
								<span class="pipeline-fase-icon rounded-2 p-2 bg-secondary bg-opacity-10 text-secondary flex-shrink-0"><i class="fas fa-video" aria-hidden="true"></i></span>
								<span class="text-start pipeline-fase-text">
									<span class="d-block fw-bold lh-sm"><?= ($resumo['produzindo'] < 10) ? ('0' . $resumo['produzindo']) : ($resumo['produzindo']); ?></span>
									<span class="d-block small text-muted text-truncate">Produzindo</span>
								</span>
							</button>
						</div>
						<div class="col">
							<button type="button"
								class="listagem-artigos-produzindo w-100 d-flex align-items-center gap-2 rounded-2 p-2 border border-transparent text-body pipeline-fase-link"
								data-fase-producao="5" data-modal-fase-titulo="Publicando" data-bs-toggle="modal"
								data-bs-target="#modalListagem" aria-haspopup="dialog" aria-controls="modalListagem">
								<span class="pipeline-fase-icon rounded-2 p-2 bg-danger bg-opacity-10 text-danger flex-shrink-0"><i class="fab fa-youtube" aria-hidden="true"></i></span>
								<span class="text-start pipeline-fase-text">
									<span class="d-block fw-bold lh-sm"><?= ($resumo['publicando'] < 10) ? ('0' . $resumo['publicando']) : ($resumo['publicando']); ?></span>
									<span class="d-block small text-muted text-truncate">Publicando</span>
								</span>
							</button>
						</div>
						<div class="col">
							<button type="button"
								class="listagem-artigos-produzindo w-100 d-flex align-items-center gap-2 rounded-2 p-2 border border-transparent text-body pipeline-fase-link"
								data-fase-producao="6" data-modal-fase-titulo="Pagando" data-bs-toggle="modal"
								data-bs-target="#modalListagem" aria-haspopup="dialog" aria-controls="modalListagem">
								<span class="pipeline-fase-icon rounded-2 p-2 bg-warning bg-opacity-10 text-warning flex-shrink-0"><i class="fab fa-bitcoin" aria-hidden="true"></i></span>
								<span class="text-start pipeline-fase-text">
									<span class="d-block fw-bold lh-sm"><?= ($resumo['pagando'] < 10) ? ('0' . $resumo['pagando']) : ($resumo['pagando']); ?></span>
									<span class="d-block small text-muted text-truncate">Pagando</span>
								</span>
							</button>
						</div>
					</div>
				</div>
			</div>
		</section>

		<style>
			button.pipeline-fase-link {
				font: inherit;
				color: inherit;
				background-color: transparent;
				cursor: pointer;
			}
			.pipeline-fase-link:focus-visible {
				outline: 2px solid var(--bs-primary);
				outline-offset: 2px;
			}
			.pipeline-fase-link:hover {
				background-color: var(--bs-secondary-bg);
				border-color: var(--bs-border-color) !important;
			}
			.pipeline-fase-icon {
				font-size: 1rem;
				line-height: 1;
			}
			.pipeline-fase-text {
				min-width: 0;
			}
			.listagem-site-table-wrap {
				max-height: min(70vh, 42rem);
				overflow: auto;
			}
			.listagem-site-table-wrap .table thead.listagem-site-thead th,
			.dashboard-limites-table .table thead.listagem-site-thead th {
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
			[data-mdb-theme="dark"] .listagem-site-table-wrap .table thead.listagem-site-thead th,
			[data-bs-theme="dark"] .dashboard-limites-table .table thead.listagem-site-thead th,
			[data-mdb-theme="dark"] .dashboard-limites-table .table thead.listagem-site-thead th {
				box-shadow: 0 1px 0 rgba(255, 255, 255, 0.08);
			}
			#listagem-site-resultados-heading:focus,
			#listagem-site-resultados-heading:focus-visible {
				outline: none !important;
				box-shadow: none !important;
			}
			.listagem-site-filtros .form-select,
			.listagem-site-filtros .form-control {
				font-family: inherit;
			}
			.min-height-listagem {
				min-height: 6rem;
			}
		</style>

		<div class="accordion mb-4" id="accordionLimitesDashboard">
			<div class="accordion-item border rounded-3 overflow-hidden shadow-sm">
				<h2 class="accordion-header" id="headingLimitesDashboard">
					<button class="accordion-button collapsed py-3" type="button" data-bs-toggle="collapse"
						data-bs-target="#collapseLimitesDashboard" aria-expanded="false" aria-controls="collapseLimitesDashboard">
						Tempos e limites para colaborar
					</button>
				</h2>
				<div id="collapseLimitesDashboard" class="accordion-collapse collapse" data-bs-parent="#accordionLimitesDashboard"
					aria-labelledby="headingLimitesDashboard">
					<div class="accordion-body py-3 small">
						<p class="text-muted mb-2 mb-md-3">
							<strong class="text-body">Atenção:</strong> fora do prazo, o artigo é desmarcado; para marcar de novo o mesmo artigo, é preciso esperar o <strong>bloqueio</strong> de <strong><?= esc($limite['bloqueio']); ?></strong>.
							Desmarcação automática por prazo: <strong><?= ($limite['ativo'] == '1') ? ('ativa') : ('inativa'); ?></strong>.
						</p>
						<div class="table-responsive rounded border dashboard-limites-table mb-2 mb-md-3" style="max-width: 28rem">
							<table class="table table-sm align-middle mb-0 table-bordered">
								<thead class="listagem-site-thead">
									<tr>
										<th scope="col" class="text-nowrap">Tipo</th>
										<th scope="col" class="text-nowrap">Revisão</th>
										<th scope="col" class="text-nowrap">Narração</th>
										<th scope="col" class="text-nowrap">Produção</th>
									</tr>
								</thead>
								<tbody class="border-top-0">
									<tr>
										<th scope="row" class="text-uppercase fw-normal">Teórico</th>
										<td><?= esc($limite['teoria']['revisao']); ?></td>
										<td><?= esc($limite['teoria']['narracao']); ?></td>
										<td><?= esc($limite['teoria']['producao']); ?></td>
									</tr>
									<tr>
										<th scope="row" class="text-uppercase fw-normal">Notícia</th>
										<td><?= esc($limite['noticia']['revisao']); ?></td>
										<td><?= esc($limite['noticia']['narracao']); ?></td>
										<td><?= esc($limite['noticia']['producao']); ?></td>
									</tr>
								</tbody>
							</table>
						</div>
						<p class="text-muted mb-0">
							<strong class="text-body">Descarte por atraso:</strong> regra <strong><?= ($limite['descartar']['ativo'] == '1') ? ('ativa') : ('inativa'); ?></strong>
							— sem produzir em <strong><?= esc($limite['descartar']['tempo']); ?></strong>, o artigo é descartado.
						</p>
					</div>
				</div>
			</div>
		</div>

		<section class="card border rounded-3 shadow-sm" aria-labelledby="heading-listagem-site">
			<div class="card-header bg-body-secondary bg-opacity-25 border-bottom p-3">
				<div>
					<h2 id="heading-listagem-site" class="h5 mb-1">Listagem de artigos do site</h2>
					<p class="small text-muted mb-0">Os filtros aplicam-se à tabela abaixo; use <strong>Filtros avançados</strong> para fase e colaborador</p>
				</div>
			</div>
			<div class="card-body p-3">
				<div class="listagem-site-filtros rounded-3 border bg-body-secondary bg-opacity-50 p-3 mb-0">
					<form id="form-listagem-site-artigos" data-np-autofill-form-type="other" data-np-checked="1" data-np-watching="1">
						<div class="row g-2 g-md-3 align-items-end">
							<div class="col-12 col-md-6 col-lg-2">
								<label class="form-label small text-muted mb-1" for="select-campo-pesquisa">Buscar em</label>
								<select class="form-select form-select-sm" id="select-campo-pesquisa" name="campo_pesquisa" aria-label="Buscar em">
									<option value="titulo" selected>Título</option>
									<option value="texto">Conteúdo (texto)</option>
								</select>
							</div>
							<div class="col-12 col-md-6 col-lg-4">
								<label class="form-label small text-muted mb-1" for="text-pesquisa-publicado">Texto</label>
								<input class="form-control form-control-sm" type="search" id="text-pesquisa-publicado" name="texto"
									placeholder="Pesquisar…" aria-label="Texto a pesquisar">
							</div>
							<div class="col-12 col-lg-6 d-flex flex-wrap gap-2 align-items-end">
								<button class="btn btn-primary btn-sm btn-pesquisar-publicado flex-grow-1 flex-lg-grow-0" type="submit">
									<i class="fas fa-magnifying-glass me-1" aria-hidden="true"></i> Pesquisar
								</button>
								<button class="btn btn-primary btn-sm flex-grow-1 flex-lg-grow-0" type="button" id="btn-limpar-filtros-listagem">
									<i class="fas fa-rotate-left me-1" aria-hidden="true"></i> Limpar filtros
								</button>
							</div>
						</div>
						<div class="mt-2">
							<button class="btn btn-link btn-sm text-decoration-none p-0 collapsed" type="button"
								data-bs-toggle="collapse" data-bs-target="#filtrosAvancadosListagem" aria-expanded="false"
								aria-controls="filtrosAvancadosListagem" id="btn-toggle-filtros-avancados">
								<span class="filtros-avancados-label-when-collapsed">Mostrar filtros avançados</span>
								<span class="filtros-avancados-label-when-expanded d-none">Ocultar filtros avançados</span>
								<i class="fas fa-chevron-down small ms-1" aria-hidden="true"></i>
							</button>
						</div>
						<div class="collapse mt-3" id="filtrosAvancadosListagem">
							<div class="row g-2 g-md-3 align-items-end pt-1 border-top border-opacity-25">
								<div class="col-12 col-md-4">
									<label class="form-label small text-muted mb-1" for="select-fase-listagem-site">Fase de produção</label>
									<select class="form-select form-select-sm" id="select-fase-listagem-site" name="fase_producao" aria-label="Fase de produção">
										<option value="">Todas as fases</option>
										<option value="2">Revisando</option>
										<option value="3">Narrando</option>
										<option value="4">Produzindo</option>
										<option value="5">Publicando</option>
									</select>
								</div>
								<div class="col-12 col-md-5">
									<label class="form-label small text-muted mb-1" for="text-colaborador">Colaborador (apelido)</label>
									<input class="form-control form-control-sm" type="search" id="text-colaborador" name="colaborador"
										placeholder="Filtrar por colaborador…" aria-label="Pesquisar colaborador">
								</div>
								<div class="col-12 col-md-3">
									<label class="form-label small text-muted mb-1" for="select-colaborador-listagem-site">Papel</label>
									<select class="form-select form-select-sm" id="select-colaborador-listagem-site" name="fase_producao_colaborador" aria-label="Papel do colaborador">
										<option value="A">Sugestor</option>
										<option value="B" selected>Escritor</option>
										<option value="C">Revisor</option>
										<option value="D">Narrador</option>
										<option value="E">Produtor</option>
									</select>
								</div>
							</div>
						</div>
					</form>
				</div>

				<div class="border-top pt-3 mt-3">
					<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
						<h3 class="h6 mb-0 text-muted" id="listagem-site-resultados-heading" tabindex="-1">Resultados</h3>
						<span class="listagem-site-contador small text-muted" aria-live="polite"></span>
					</div>
					<div id="listagem-site-live" class="visually-hidden" aria-live="polite" aria-atomic="true"></div>
					<div class="table-responsive listagem-site-table-wrap rounded border">
						<div class="tabela-publicado min-height-listagem"></div>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>

<div class="modal fade" id="modalListagem" tabindex="-1" role="dialog" aria-labelledby="modalListagemTitulo"
	aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalListagemTitulo">Artigos em produção</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
			</div>
			<div class="modal-body corpo-listar"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
			</div>
		</div>
	</div>
</div>

<script>
	var modalListagemTituloDefault = 'Artigos em produção';

	$('#modalListagem').on('hidden.bs.modal', function () {
		$('#modalListagemTitulo').text(modalListagemTituloDefault);
	});

	var listagemSiteLoadingHtml = '<div class="listagem-site-loading d-flex align-items-center justify-content-center gap-2 p-4 text-muted small">' +
		'<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>' +
		'<span>Carregando resultados…</span></div>';

	function refreshListPublicado(url) {
		if (url == false) {
			url = '<?php echo base_url('colaboradores/artigos/artigosList'); ?>';
		}
		$('.tabela-publicado').html(listagemSiteLoadingHtml);
		$('#listagem-site-live').text('');
		$.ajax({
			url: url,
			type: 'get',
			dataType: 'html',
			data: {
				texto: $('#text-pesquisa-publicado').val(),
				campo_pesquisa: $('#select-campo-pesquisa').val(),
				fase_producao: $('#select-fase-listagem-site').val(),
				colaborador: $('#text-colaborador').val(),
				fase_producao_colaborador: $('#select-colaborador-listagem-site').val()
			},
			success: function (data) {
				$('.tabela-publicado').html(data);
				$('#listagem-site-live').text('Resultados atualizados.');
				setTimeout(function () {
					$('#listagem-site-live').text('');
				}, 2500);
				var hResultados = document.getElementById('listagem-site-resultados-heading');
				if (hResultados) {
					hResultados.focus();
				}
			},
			error: function () {
				$('.tabela-publicado').html(
					'<div class="alert alert-danger m-3 mb-0 small" role="alert">Não foi possível carregar a listagem. Tente novamente.</div>'
				);
				$('#listagem-site-live').text('Erro ao carregar a listagem.');
				var hResultados = document.getElementById('listagem-site-resultados-heading');
				if (hResultados) {
					hResultados.focus();
				}
			}
		});
	}

	function limparFiltrosListagemSite() {
		$('#select-campo-pesquisa').val('titulo');
		$('#text-pesquisa-publicado').val('');
		$('#select-fase-listagem-site').val('');
		$('#text-colaborador').val('');
		$('#select-colaborador-listagem-site').val('B');
		var elCol = document.getElementById('filtrosAvancadosListagem');
		if (elCol && typeof bootstrap !== 'undefined' && bootstrap.Collapse) {
			bootstrap.Collapse.getOrCreateInstance(elCol).hide();
		}
		refreshListPublicado(false);
	}

	$('#form-listagem-site-artigos').on('submit', function (e) {
		e.preventDefault();
		refreshListPublicado(false);
	});

	$('#btn-limpar-filtros-listagem').on('click', function () {
		limparFiltrosListagemSite();
	});

	$('#filtrosAvancadosListagem').on('shown.bs.collapse hidden.bs.collapse', function (e) {
		var open = e.type === 'shown';
		$('.filtros-avancados-label-when-collapsed').toggleClass('d-none', open);
		$('.filtros-avancados-label-when-expanded').toggleClass('d-none', !open);
		$('#btn-toggle-filtros-avancados').find('i.fas').toggleClass('fa-chevron-down', !open).toggleClass('fa-chevron-up', open);
	});

	$(document).ready(function () {
		refreshListPublicado(false);
	});

	$('.listagem-artigos-produzindo').on('click', function (e) {
		var faseproducaoid = e.currentTarget.getAttribute('data-fase-producao');
		var tituloFase = e.currentTarget.getAttribute('data-modal-fase-titulo');
		if (tituloFase) {
			$('#modalListagemTitulo').text('Artigos — ' + tituloFase);
		}
		$.ajax({
			url: "<?= site_url('colaboradores/artigos/artigosProduzindo'); ?>/" + faseproducaoid,
			method: "GET",
			data: {},
			processData: false,
			contentType: false,
			cache: false,
			dataType: "html",
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide(); },
			success: function (retorno) {
				$('.corpo-listar').html(retorno);
			}
		});
	});
</script>

<?= $this->endSection(); ?>
