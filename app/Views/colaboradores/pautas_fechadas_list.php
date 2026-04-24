<?= $this->extend('layouts/administradores'); ?>

<?= $this->section('content'); ?>
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

	.listagem-site-table-wrap .table tbody tr th,
	.listagem-site-table-wrap .table tbody tr td {
		padding-top: 0.4rem;
		padding-bottom: 0.4rem;
		font-size: 0.875rem;
		vertical-align: middle;
	}
</style>

<div class="container-fluid py-3">
	<div class="container">
		<div class="d-sm-flex justify-content-between align-items-center mb-4 gap-3">
			<div>
				<h1 id="heading-pautas-fechadas" class="h3 mb-1"><?= esc($titulo); ?></h1>
				<p class="text-muted small mb-0">Visualize os fechamentos anteriores e acesse os detalhes de cada grupo de pautas.</p>
			</div>
			<div>
				<a href="<?= site_url('colaboradores/pautas/fechar'); ?>" class="btn btn-primary btn-sm w-100 w-sm-auto">
					Fechar nova pauta
				</a>
			</div>
		</div>

		<section class="card border rounded-3 shadow-sm" aria-labelledby="heading-pautas-fechadas">
			<div class="card-body p-3">
				<div class="listagem-site-filtros rounded-3 border bg-body-secondary bg-opacity-50 p-3 mb-0">
					<form id="form-filtros-pautas-fechadas" method="get" autocomplete="off">
						<div class="row g-2 g-md-3 align-items-end">
							<div class="col-12 col-md-5 col-lg-4">
								<label class="form-label small text-muted mb-1" for="filtro-fechamento-pauta">Nome do fechamento</label>
								<input type="text" class="form-control form-control-sm" name="fechamento" id="filtro-fechamento-pauta"
									placeholder="Pesquisar pelo título do fechamento" autocomplete="off">
							</div>
							<div class="col-12 col-md-5 col-lg-4">
								<label class="form-label small text-muted mb-1" for="filtro-tema-pauta">Tema</label>
								<input type="text" class="form-control form-control-sm" name="tema" id="filtro-tema-pauta"
									placeholder="Pesquisar por tema (tag)">
							</div>
							<div class="col-12 col-md-2 col-lg-4 d-flex gap-2">
								<button class="btn btn-primary btn-sm flex-grow-1" type="submit" id="btn-pesquisar-pautas-fechadas">
									<i class="fas fa-magnifying-glass me-1" aria-hidden="true"></i>Pesquisar
								</button>
								<button class="btn btn-primary btn-sm" type="button" id="btn-limpar-filtros-pautas-fechadas" title="Limpar filtros">
									<i class="fas fa-rotate-left" aria-hidden="true"></i>
								</button>
							</div>
						</div>
					</form>
				</div>
				<div class="border-top pt-3 mt-3">
					<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
						<div>
							<h2 class="h6 mb-0 text-muted">Resultados</h2>
							<p class="small text-muted mb-0 mt-1" id="pautas-fechadas-quantidade-registros" aria-live="polite"></p>
						</div>
					</div>
					<div class="table-responsive listagem-site-table-wrap rounded border">
						<div class="pautas-fechadas-list min-vh-25">
							<div class="text-center text-muted small py-4">Carregando histórico de pautas fechadas...</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>

<script>
	function formatarTotalPautasFechadas(n) {
		if (n === 0) {
			return 'Nenhum fechamento encontrado com os filtros atuais.';
		}
		if (n === 1) {
			return 'Total: 1 fechamento.';
		}
		return 'Total: ' + n + ' fechamentos.';
	}

	window.atualizarQuantidadePautasFechadas = function () {
		var quantidade = parseInt($('.pautas-fechadas-list #pautas-fechadas-total-registros').attr('data-total-registros'), 10);
		if (isNaN(quantidade)) {
			quantidade = 0;
		}
		$('#pautas-fechadas-quantidade-registros').text(formatarTotalPautasFechadas(quantidade));
	};

	function initTooltipsPautasFechadas() {
		document.querySelectorAll('.pautas-fechadas-list [data-bs-toggle="tooltip"]').forEach(function (el) {
			bootstrap.Tooltip.getOrCreateInstance(el);
		});
	}

	function refreshPautasFechadasList() {
		$.ajax({
			url: "<?= base_url('colaboradores/pautas/pautasFechadasList'); ?>",
			type: 'get',
			dataType: 'html',
			data: {
				fechamento: $('#filtro-fechamento-pauta').val() || '',
				tema: $('#filtro-tema-pauta').val() || ''
			},
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide(); },
			success: function (data) {
				$('.pautas-fechadas-list').html(data);
				initTooltipsPautasFechadas();
				window.atualizarQuantidadePautasFechadas();
			},
			error: function () {
				$('.pautas-fechadas-list').html('<div class="alert alert-warning m-0">Não foi possível carregar os resultados agora.</div>');
				window.atualizarQuantidadePautasFechadas();
			}
		});
	}

	$('#form-filtros-pautas-fechadas').on('submit', function (event) {
		event.preventDefault();
		refreshPautasFechadasList();
	});

	$('#btn-limpar-filtros-pautas-fechadas').on('click', function () {
		$('#filtro-fechamento-pauta').val('');
		$('#filtro-tema-pauta').val('');
		refreshPautasFechadasList();
	});

	$(document).on('click', '.pautas-fechadas-list .page-link', function (e) {
		e.preventDefault();
		var href = $(this).attr('href');
		if (!href) {
			return;
		}
		var pageMatch = href.match(/[?&]page_pautas=(\d+)/);
		var data = {
			fechamento: $('#filtro-fechamento-pauta').val() || '',
			tema: $('#filtro-tema-pauta').val() || ''
		};
		if (pageMatch) {
			data.page_pautas = pageMatch[1];
		}
		$.ajax({
			url: "<?= base_url('colaboradores/pautas/pautasFechadasList'); ?>",
			type: 'get',
			data: data,
			dataType: 'html',
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide(); },
			success: function (html) {
				$('.pautas-fechadas-list').html(html);
				initTooltipsPautasFechadas();
				window.atualizarQuantidadePautasFechadas();
			}
		});
	});

	$(document).ready(function () {
		refreshPautasFechadasList();
	});
</script>
<?= $this->endSection(); ?>
