<?= $this->extend('layouts/colaboradores'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid py-3">
	<div class="container">

		<style>
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

		<section class="card border rounded-3 shadow-sm mt-4" aria-labelledby="heading-listagem-site-admin">
			<div class="card-header bg-body-secondary bg-opacity-25 border-bottom p-3">
				<h2 id="heading-listagem-site-admin" class="h5 mb-1">Listagem de artigos do site</h2>
			</div>
			<div class="card-body p-3">
				<div class="listagem-site-filtros rounded-3 border bg-body-secondary bg-opacity-50 p-3 mb-0">
					<form id="form-filtro-artigos-site" method="get" action="#">
						<div class="row g-2 g-md-3 align-items-end">
							<div class="col-12 col-md-6 col-lg-2">
								<label class="form-label small text-muted mb-1" for="select-campo-pesquisa">Buscar em</label>
								<select class="form-select form-select-sm" id="select-campo-pesquisa" name="select-campo-pesquisa"
									aria-label="Buscar em">
									<option value="titulo" selected>Título</option>
									<option value="texto">Conteúdo (texto)</option>
								</select>
							</div>
							<div class="col-12 col-md-6 col-lg-4">
								<label class="form-label small text-muted mb-1" for="text-pesquisa-publicado">Texto</label>
								<input class="form-control form-control-sm" type="search" id="text-pesquisa-publicado"
									name="text-pesquisa-publicado" placeholder="Pesquisar…" autocomplete="off"
									aria-label="Termo de pesquisa">
							</div>
							<div class="col-12 col-lg-6 d-flex flex-wrap gap-2 align-items-end">
								<button class="btn btn-primary btn-sm btn-pesquisar-publicado flex-grow-1 flex-lg-grow-0" type="submit">
									<i class="fas fa-magnifying-glass me-1" aria-hidden="true"></i> Pesquisar
								</button>
								<button class="btn btn-primary btn-sm flex-grow-1 flex-lg-grow-0" type="button" id="btn-limpar-filtros-listagem-admin">
									<i class="fas fa-rotate-left me-1" aria-hidden="true"></i> Limpar filtros
								</button>
							</div>
						</div>
						<div class="mt-2">
							<button class="btn btn-link btn-sm text-decoration-none p-0 collapsed" type="button"
								data-bs-toggle="collapse" data-bs-target="#filtrosAvancadosListagemAdmin" aria-expanded="false"
								aria-controls="filtrosAvancadosListagemAdmin" id="btn-toggle-filtros-avancados-admin">
								<span class="filtros-avancados-admin-when-collapsed">Mostrar filtros avançados</span>
								<span class="filtros-avancados-admin-when-expanded d-none">Ocultar filtros avançados</span>
								<i class="fas fa-chevron-down small ms-1" aria-hidden="true"></i>
							</button>
						</div>
						<div class="collapse mt-3" id="filtrosAvancadosListagemAdmin">
							<div class="row g-2 g-md-3 align-items-end">
								<div class="col-12 col-md-4">
									<label class="form-label small text-muted mb-1" for="select-pesquisa">Fase de produção</label>
									<select class="form-select form-select-sm" id="select-pesquisa" name="select-pesquisa"
										aria-label="Fase de produção">
										<option value="">Todas as fases</option>
										<option value="1">Escrevendo</option>
										<option value="2">Revisando</option>
										<option value="3">Narrando</option>
										<option value="4">Produzindo</option>
										<option value="5">Publicando</option>
										<option value="6">Pagando</option>
									</select>
								</div>
								<div class="col-12 col-md-5">
									<label class="form-label small text-muted mb-1" for="text-colaborador">Colaborador (apelido)</label>
									<input class="form-control form-control-sm" type="search" id="text-colaborador"
										name="text-colaborador" placeholder="Filtrar por colaborador…" autocomplete="off"
										aria-label="Pesquisar colaborador">
								</div>
								<div class="col-12 col-md-3">
									<label class="form-label small text-muted mb-1" for="select-colaborador">Papel</label>
									<select class="form-select form-select-sm" id="select-colaborador" name="select-colaborador"
										aria-label="Papel do colaborador">
										<option value="A">Sugestor</option>
										<option value="B" selected>Escritor</option>
										<option value="C">Revisor</option>
										<option value="D">Narrador</option>
										<option value="E">Produtor</option>
									</select>
								</div>
							</div>
							<div class="row g-2 g-md-3 align-items-end mt-2">
								<div class="col-12 col-md-6">
									<label class="form-label small text-muted mb-1" for="select-tipo">Tipo</label>
									<select class="form-select form-select-sm select-tipo" id="select-tipo" name="select-tipo"
										aria-label="Tipo de artigo">
										<option value="">Todos</option>
										<option value="T">Teórico</option>
										<option value="N">Notícia</option>
									</select>
								</div>
								<div class="col-12 col-md-6">
									<label class="form-label small text-muted mb-1" for="select-descartado">Situação</label>
									<select class="form-select form-select-sm select-descartado" id="select-descartado"
										name="select-descartado" aria-label="Ativos ou descartados">
										<option value="N">Ativos</option>
										<option value="S">Descartados</option>
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
					<div class="table-responsive listagem-site-table-wrap rounded border">
						<div class="tabela-publicado min-height-listagem"></div>
					</div>
				</div>
			</div>
		</section>
	</div>

</div>

<div class="modal fade" id="modalListagem" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
	aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Artigos em produção</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body corpo-listar">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary text-left" data-bs-dismiss="modal">Fechar</button>
			</div>
		</div>
	</div>
</div>

<script>
	function refreshListPublicado(url) {
		if (url == false) {
			url = '<?php echo base_url('colaboradores/artigos/artigosList'); ?>';
		}
		$.ajax({
			url: url,
			type: 'get',
			dataType: 'html',
			data: {
				texto: $('#text-pesquisa-publicado').val(),
				campo_pesquisa: $('#select-campo-pesquisa').val(),
				fase_producao: $('#select-pesquisa').val(),
				colaborador: $('#text-colaborador').val(),
				fase_producao_colaborador: $('#select-colaborador').val(),
				tipo: $('#select-tipo').val(),
				descartado: $('#select-descartado').val(),
				admin: true
			},
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide() },
			success: function (data) {
				$('.tabela-publicado').html(data);
			}
		});
	}

	function limparFiltrosListagemAdmin() {
		$('#select-campo-pesquisa').val('titulo');
		$('#text-pesquisa-publicado').val('');
		$('#select-pesquisa').val('');
		$('#select-tipo').val('');
		$('#select-descartado').val('N');
		$('#text-colaborador').val('');
		$('#select-colaborador').val('B');
		var elCol = document.getElementById('filtrosAvancadosListagemAdmin');
		if (elCol && typeof bootstrap !== 'undefined' && bootstrap.Collapse) {
			bootstrap.Collapse.getOrCreateInstance(elCol).hide();
		}
		refreshListPublicado(false);
	}

	$('#form-filtro-artigos-site').on('submit', function (e) {
		e.preventDefault();
		refreshListPublicado(false);
	});

	$('#btn-limpar-filtros-listagem-admin').on('click', function () {
		limparFiltrosListagemAdmin();
	});

	$('#filtrosAvancadosListagemAdmin').on('shown.bs.collapse hidden.bs.collapse', function (e) {
		var open = e.type === 'shown';
		$('.filtros-avancados-admin-when-collapsed').toggleClass('d-none', open);
		$('.filtros-avancados-admin-when-expanded').toggleClass('d-none', !open);
		$('#btn-toggle-filtros-avancados-admin').find('i.fas').toggleClass('fa-chevron-down', !open).toggleClass('fa-chevron-up', open);
	});

	$(document).ready(function () {
		refreshListPublicado(false);
	});

	$('.listagem-artigos-produzindo').on('click', function (e) {
		faseproducaoid = e.currentTarget.getAttribute('data-fase-producao');
		$.ajax({
			url: "<?= site_url('colaboradores/artigos/artigosProduzindo'); ?>/" + faseproducaoid,
			method: "GET",
			data: {},
			processData: false,
			contentType: false,
			cache: false,
			dataType: "html",
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide() },
			success: function (retorno) {
				$('.corpo-listar').html(retorno);
			}
		});
	});
</script>

<?= $this->endSection(); ?>