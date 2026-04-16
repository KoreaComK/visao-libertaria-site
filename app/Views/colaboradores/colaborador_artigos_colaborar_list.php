<?= $this->extend('layouts/colaboradores'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid py-3">
	<div class="container">
		<div class="d-sm-flex justify-content-between align-items-center mb-4">
			<div>
				<h1 class="h3 mb-1">Artigos para colaborar</h1>
				<p class="text-muted small mb-0">Escolha a fase, pesquise e marque artigos para trabalhar nesta etapa</p>
			</div>
		</div>

		<div class="accordion mb-4" id="accordionLimitesColaborar">
			<div class="accordion-item border rounded-3 overflow-hidden shadow-sm">
				<h2 class="accordion-header" id="headingLimitesColaborar">
					<button class="accordion-button collapsed py-3" type="button" data-bs-toggle="collapse"
						data-bs-target="#collapseLimitesColaborar" aria-expanded="false" aria-controls="collapseLimitesColaborar">
						Tempos e limites para colaborar
					</button>
				</h2>
				<div id="collapseLimitesColaborar" class="accordion-collapse collapse" data-bs-parent="#accordionLimitesColaborar"
					aria-labelledby="headingLimitesColaborar">
					<div class="accordion-body py-3 small">
						<p class="text-muted mb-2 mb-md-3">
							<strong class="text-body">Atenção:</strong> fora do prazo, o artigo é desmarcado; para marcar de novo o mesmo artigo, é preciso esperar o <strong>bloqueio</strong> de <strong><?= esc($limite['bloqueio']); ?></strong>.
							Desmarcação automática por prazo: <strong><?= ($limite['ativo'] == '1') ? ('ativa') : ('inativa'); ?></strong>.
						</p>
						<div class="table-responsive rounded border colab-limites-table mb-2 mb-md-3" style="max-width: 28rem">
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

		<section class="mb-4" aria-labelledby="heading-colab-fase">
			<h2 id="heading-colab-fase" class="h5 text-body mb-1">Fase de colaboração</h2>
			<p class="text-muted small mb-0">Selecione a etapa em que pretende colaborar</p>
			<div class="card border rounded-3 shadow-sm mt-2">
				<div class="card-body p-2 p-sm-3">
					<div class="row row-cols-2 row-cols-sm-2 row-cols-lg-4 g-2">
						<?php if (in_array('3', $permissoes)): ?>
							<div class="col">
								<input type="radio" class="btn-check radio" name="fase_producao" id="revisar" value="2"
									<?= ($primeira['id'] == '2') ? ('checked') : (''); ?>>
								<label class="colab-fase-label" for="revisar">
									<span class="colab-fase-icon rounded-2 p-2 bg-primary bg-opacity-10 text-primary flex-shrink-0"><i
											class="fas fa-pen-to-square" aria-hidden="true"></i></span>
									<span class="text-start colab-fase-text">
										<span class="d-block fw-bold lh-sm">Revisar</span>
										<span class="d-block small text-muted text-truncate">Revisando</span>
									</span>
								</label>
							</div>
						<?php endif; ?>
						<?php if (in_array('4', $permissoes)): ?>
							<div class="col">
								<input type="radio" class="btn-check radio" name="fase_producao" id="narrar" value="3"
									<?= ($primeira['id'] == '3') ? ('checked') : (''); ?>>
								<label class="colab-fase-label" for="narrar">
									<span class="colab-fase-icon rounded-2 p-2 bg-info bg-opacity-10 text-info flex-shrink-0"><i
											class="fas fa-microphone" aria-hidden="true"></i></span>
									<span class="text-start colab-fase-text">
										<span class="d-block fw-bold lh-sm">Narrar</span>
										<span class="d-block small text-muted text-truncate">Narrando</span>
									</span>
								</label>
							</div>
						<?php endif; ?>
						<?php if (in_array('5', $permissoes)): ?>
							<div class="col">
								<input type="radio" class="btn-check radio" name="fase_producao" id="produzir" value="4"
									<?= ($primeira['id'] == '4') ? ('checked') : (''); ?>>
								<label class="colab-fase-label" for="produzir">
									<span class="colab-fase-icon rounded-2 p-2 bg-secondary bg-opacity-10 text-secondary flex-shrink-0"><i
											class="fas fa-video" aria-hidden="true"></i></span>
									<span class="text-start colab-fase-text">
										<span class="d-block fw-bold lh-sm">Produzir</span>
										<span class="d-block small text-muted text-truncate">Produzindo</span>
									</span>
								</label>
							</div>
						<?php endif; ?>
						<?php if (in_array('6', $permissoes)): ?>
							<div class="col">
								<input type="radio" class="btn-check radio" name="fase_producao" id="publicar" value="5"
									<?= ($primeira['id'] == '5') ? ('checked') : (''); ?>>
								<label class="colab-fase-label" for="publicar">
									<span class="colab-fase-icon rounded-2 p-2 bg-danger bg-opacity-10 text-danger flex-shrink-0"><i
											class="fab fa-youtube" aria-hidden="true"></i></span>
									<span class="text-start colab-fase-text">
										<span class="d-block fw-bold lh-sm">Publicar</span>
										<span class="d-block small text-muted text-truncate">Publicando</span>
									</span>
								</label>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</section>

		<section class="card border rounded-3 shadow-sm" aria-labelledby="heading-colab-listagem">
			<div class="card-header bg-body-secondary bg-opacity-25 border-bottom p-3">
				<div class="d-sm-flex justify-content-between align-items-start gap-2">
					<div>
						<h2 id="heading-colab-listagem" class="h5 mb-1">Listagem nesta fase</h2>
						<p class="small text-muted mb-0">A mostrar artigos para <span class="fase-producao-nome"
								style="text-transform: lowercase;"></span></p>
					</div>
				</div>
			</div>
			<div class="card-body p-3">
				<div class="listagem-site-filtros rounded-3 border bg-body-secondary bg-opacity-50 p-3 mb-0"
					data-np-autofill-form-type="other" data-np-checked="1" data-np-watching="1">
					<form class="mb-0" id="form-colaborar-filtros">
						<div class="row g-2 g-md-3 align-items-end">
							<div class="col-12 col-md-7 col-lg-8">
								<label class="form-label small text-muted mb-1" for="text-pesquisa">Pesquisar</label>
								<input class="form-control form-control-sm" type="search" id="text-pesquisa"
									name="text-pesquisa" placeholder="Pesquisar…" aria-label="Pesquisar">
							</div>
							<div class="col-12 col-md-5 col-lg-4">
								<label class="form-label small text-muted mb-1" for="select-tipo">Tipo de artigo</label>
								<select class="form-select form-select-sm select-pesquisa" id="select-tipo" name="select-tipo"
									aria-label="Tipo de artigo">
									<option value="">Todos os tipos</option>
									<option value="T">Teórico</option>
									<option value="N">Notícia</option>
								</select>
							</div>
						</div>
						<div class="d-flex justify-content-end mt-2 mt-md-3">
							<button class="btn btn-primary btn-sm btn-pesquisar" type="submit" aria-label="Pesquisar">
								<i class="fas fa-magnifying-glass me-1" aria-hidden="true"></i> Pesquisar
							</button>
						</div>
					</form>
				</div>

				<div class="border-top pt-3 mt-3">
					<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
						<h3 class="h6 mb-0 text-muted">Resultados</h3>
					</div>
					<div class="table-responsive listagem-site-table-wrap rounded border">
						<div class="tabela-publicado min-height-listagem"></div>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>

<style>
	.colab-fase-label {
		display: flex;
		align-items: center;
		gap: 0.5rem;
		width: 100%;
		margin: 0;
		padding: 0.5rem;
		border-radius: var(--bs-border-radius);
		border: 1px solid transparent;
		cursor: pointer;
		color: inherit;
		background-color: transparent;
		font: inherit;
		text-align: left;
	}

	.colab-fase-label:hover {
		background-color: var(--bs-secondary-bg);
		border-color: var(--bs-border-color);
	}

	.btn-check:checked+.colab-fase-label {
		background-color: var(--bs-secondary-bg);
		border-color: var(--bs-border-color);
		box-shadow: inset 0 0 0 2px var(--bs-primary);
	}

	.btn-check:focus-visible+.colab-fase-label {
		outline: 2px solid var(--bs-primary);
		outline-offset: 2px;
	}

	.colab-fase-icon {
		font-size: 1rem;
		line-height: 1;
	}

	.colab-fase-text {
		min-width: 0;
	}

	.listagem-site-filtros .form-select,
	.listagem-site-filtros .form-control {
		font-family: inherit;
	}

	.listagem-site-table-wrap {
		max-height: min(70vh, 42rem);
		overflow: auto;
	}

	.listagem-site-table-wrap .table thead.listagem-site-thead th,
	.colab-limites-table .table thead.listagem-site-thead th {
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
	[data-bs-theme="dark"] .colab-limites-table .table thead.listagem-site-thead th,
	[data-mdb-theme="dark"] .colab-limites-table .table thead.listagem-site-thead th {
		box-shadow: 0 1px 0 rgba(255, 255, 255, 0.08);
	}

	.min-height-listagem {
		min-height: 6rem;
	}
</style>

<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalPrevia" aria-hidden="true" id="modalPrevia">
	<div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Prévia do artigo</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body"></div>
			<div class="modal-footer d-flex justify-content-between">
				<button type="button" class="btn btn-default" data-bs-dismiss="modal"
					id="modal-btn-close">Fechar</button>
			</div>
		</div>
	</div>
</div>

<script>

	function refreshListPublicado(url, fase_producao) {
		if (url == false) {
			url = '<?php echo base_url('colaboradores/artigos/artigosColaborarList'); ?>';
		}
		if (fase_producao != false) {
			$.ajax({
				url: url,
				type: 'get',
				dataType: 'html',
				data: {
					fase_producao_id: fase_producao,
					texto: $('#text-pesquisa').val(),
					tipo: $('#select-tipo').val()
				},
				beforeSend: function () { $('#modal-loading').show(); },
				complete: function () { $('#modal-loading').hide() },
				success: function (data) {
					$('.tabela-publicado').html(data);
				}
			});
		}
	}
	function showPrevia(artigoId) {
		$.ajax({
			url: "<?php echo base_url('colaboradores/artigos/buscaArtigoJSON/'); ?>" + artigoId,
			type: 'post',
			dataType: 'json',
			data: {
				artigo: artigoId
			},
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide() },
			success: function (dados) {
				if (dados.status) {
					$('#modal-btn-marcar').attr('data-vl-artigo', artigoId);
				} else {
					popMessage('ATENÇÃO', dados.mensagem, TOAST_STATUS.DANGER);
				}
			}
		});

	}

	$("#modal-btn-marcar").on("click", function (e) {
		$('.conteudo-modal').html('Deseja marcar este artigo?');
		artigoId = $(e.currentTarget).attr('data-vl-artigo');
		$("#mi-modal").modal('toggle');
		$("#modal-btn-si").addClass('modal-btn-confirma-marcar');
		document.getElementById('mi-modal').addEventListener('hide.bs.modal', function (event) {
			$("#modal-btn-si").removeClass('modal-btn-confirma-marcar');
		});

	});

	$(document).ready(function () {
		$("#modal-btn-si").on("click", function () {
			if ($('#modal-btn-si').hasClass('modal-btn-confirma-marcar')) {
				$("#mi-modal").modal('toggle');
				$.ajax({
					url: "<?php echo base_url('colaboradores/artigos/marcar/'); ?>" + artigoId,
					type: 'get',
					dataType: 'json',
					data: {
					},
					beforeSend: function () { $('#modal-loading').show(); },
					complete: function () { $('#modal-loading').hide() },
					success: function (retorno) {
						if (retorno.status) {
							popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
							$(".btn-pesquisar").trigger("click");
						} else {
							popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
						}
						artigoId = null;
					}
				});
				return false;
			}
		});

		$("#modal-btn-si").on("click", function () {
			if ($('#modal-btn-si').hasClass('modal-btn-confirma-desmarcar')) {
				$("#mi-modal").modal('toggle');
				$.ajax({
					url: "<?php echo base_url('colaboradores/artigos/desmarcar/'); ?>" + artigoId,
					type: 'get',
					dataType: 'json',
					data: {
					},
					beforeSend: function () { $('#modal-loading').show(); },
					complete: function () { $('#modal-loading').hide() },
					success: function (retorno) {
						if (retorno.status) {
							popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
							$(".btn-pesquisar").trigger("click");
						} else {
							popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
						}
						artigoId = null;
					}
				});
				return false;
			}
		});
	});

	$('.btn-pesquisar').on('click', function (e) {
		refreshListPublicado(false, $("input[name='fase_producao']:checked").val());
	});

	$('.select-pesquisa').on('change', function (e) {
		refreshListPublicado(false, $("input[name='fase_producao']:checked").val());
	});

	$('.radio').on('click', function (e) {
		refreshListPublicado(false, e.currentTarget.value);
	})

	$("form").on("submit", function (e) {
		e.preventDefault();
	});

	$(document).ready(function () {
		refreshListPublicado(false, <?= $primeira['id']; ?>);
	});

</script>

<?= $this->endSection(); ?>
