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

	#pagamentos-quantidade-min::-webkit-outer-spin-button,
	#pagamentos-quantidade-min::-webkit-inner-spin-button,
	#pagamentos-quantidade-max::-webkit-outer-spin-button,
	#pagamentos-quantidade-max::-webkit-inner-spin-button {
		-webkit-appearance: none;
		margin: 0;
	}

	#pagamentos-quantidade-min,
	#pagamentos-quantidade-max {
		appearance: textfield;
		-moz-appearance: textfield;
	}
</style>

<div class="container-fluid py-3">
	<div class="container">
		<div class="d-sm-flex justify-content-between align-items-center mb-4">
			<div>
				<h1 class="h3 mb-1"><?= esc($titulo); ?></h1>
				<p class="text-muted small mb-0">Visualize os pagamentos já realizados e abra os detalhes de cada lançamento</p>
			</div>
			<a class="btn btn-primary btn-sm mt-2 mt-sm-0" href="<?= site_url('colaboradores/admin/financeiro/pagar'); ?>">
				<i class="fas fa-plus me-1" aria-hidden="true"></i>Fazer pagamento
			</a>
		</div>

		<section class="card border rounded-3 shadow-sm" aria-labelledby="heading-pagamentos-admin">
			<div class="card-body p-3">
				<div class="listagem-site-filtros rounded-3 border bg-body-secondary bg-opacity-50 p-3 mb-0">
					<form id="pesquisa-pagamentos" method="get" autocomplete="off">
						<div class="row g-2 g-md-3 align-items-end">
							<div class="col-12 col-md-4">
								<label class="form-label small text-muted mb-1" for="pagamentos-titulo">Título</label>
								<input type="text" class="form-control form-control-sm" id="pagamentos-titulo" name="titulo" placeholder="Filtrar por título">
							</div>
							<div class="col-12 col-md-4">
								<label class="form-label small text-muted mb-1">Qtde. pago (faixa)</label>
								<div class="row g-2">
									<div class="col-6">
										<input type="number" min="0" step="0.00000001" class="form-control form-control-sm" id="pagamentos-quantidade-min" name="quantidade_bitcoin_min" placeholder="Mínimo">
									</div>
									<div class="col-6">
										<input type="number" min="0" step="0.00000001" class="form-control form-control-sm" id="pagamentos-quantidade-max" name="quantidade_bitcoin_max" placeholder="Máximo">
									</div>
								</div>
							</div>
							<div class="col-12 col-md-2">
								<label class="form-label small text-muted mb-1" for="pagamentos-hash">Hash</label>
								<input type="text" class="form-control form-control-sm" id="pagamentos-hash" name="hash_transacao" placeholder="Filtrar por hash">
							</div>
							<div class="col-12 col-md-2 d-flex gap-2">
								<button class="btn btn-primary btn-sm flex-grow-1" type="submit">
									<i class="fas fa-magnifying-glass me-1" aria-hidden="true"></i>Pesquisar
								</button>
								<button class="btn btn-primary btn-sm" type="button" id="btn-limpar-filtros-pagamentos">
									<i class="fas fa-rotate-left" aria-hidden="true"></i>
								</button>
							</div>
						</div>
					</form>
				</div>

				<div class="border-top pt-3 mt-3">
					<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
						<div>
							<h2 id="heading-pagamentos-admin" class="h6 mb-0 text-muted">Resultados</h2>
							<p class="small text-muted mb-0 mt-1" id="pagamentos-quantidade-registros" aria-live="polite"></p>
						</div>
					</div>
					<div class="table-responsive listagem-site-table-wrap rounded border">
						<div class="pagamentos-list min-vh-25"></div>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>

<script>
	function formatarTotalRegistrosPagamentos(n) {
		if (n === 0) {
			return 'Nenhum registro encontrado.';
		}
		if (n === 1) {
			return 'Total: 1 registro.';
		}
		return 'Total: ' + n + ' registros.';
	}

	function atualizarQuantidadeRegistrosPagamentos() {
		var quantidade = parseInt($('.pagamentos-list #pagamentos-total-registros').attr('data-total-registros'), 10);
		if (isNaN(quantidade)) {
			quantidade = 0;
		}
		$('#pagamentos-quantidade-registros').text(formatarTotalRegistrosPagamentos(quantidade));
	}

	function refreshPagamentosList() {
		$.ajax({
			url: "<?php echo base_url('colaboradores/admin/pagamentosList'); ?>",
			type: 'get',
			dataType: 'html',
			data: {
				titulo: $('#pagamentos-titulo').val(),
				quantidade_bitcoin_min: $('#pagamentos-quantidade-min').val(),
				quantidade_bitcoin_max: $('#pagamentos-quantidade-max').val(),
				hash_transacao: $('#pagamentos-hash').val()
			},
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide(); },
			success: function (data) {
				$('.pagamentos-list').html(data);
				atualizarQuantidadeRegistrosPagamentos();
			},
			error: function () {
				$('.pagamentos-list').html('<div class="alert alert-warning m-0">Nao foi possivel carregar os pagamentos agora.</div>');
				atualizarQuantidadeRegistrosPagamentos();
			}
		});
	}

	$('#pesquisa-pagamentos').on('submit', function (event) {
		event.preventDefault();
		refreshPagamentosList();
	});

	$('#btn-limpar-filtros-pagamentos').on('click', function () {
		$('#pagamentos-titulo').val('');
		$('#pagamentos-quantidade-min').val('');
		$('#pagamentos-quantidade-max').val('');
		$('#pagamentos-hash').val('');
		refreshPagamentosList();
	});

	$(document).ready(function () {
		refreshPagamentosList();
	});
</script>
<?= $this->endSection(); ?>