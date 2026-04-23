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
		<div class="d-sm-flex justify-content-between align-items-center mb-4">
			<div>
				<h1 id="heading-permissoes" class="h3 mb-1"><?= $titulo; ?></h1>
				<p class="text-muted small mb-0">Filtre colaboradores e edite suas permissões na listagem abaixo</p>
			</div>
		</div>
		<section class="card border rounded-3 shadow-sm" aria-labelledby="heading-permissoes">
			<div class="card-body p-3">
				<div class="listagem-site-filtros rounded-3 border bg-body-secondary bg-opacity-50 p-3 mb-0">
					<form id="pesquisa-permissoes" method="get" autocomplete="off">
						<div class="row g-2 g-md-3 align-items-end">
							<div class="col-12 col-md-6 col-lg-3">
								<label class="form-label small text-muted mb-1" for="apelido">Apelido</label>
								<input type="text" class="form-control form-control-sm" name="apelido" id="apelido" placeholder="Filtrar por apelido">
							</div>
							<div class="col-12 col-md-6 col-lg-3">
								<label class="form-label small text-muted mb-1" for="email">E-mail</label>
								<input type="text" class="form-control form-control-sm" name="email" id="email" placeholder="Filtrar por e-mail">
							</div>
							<div class="col-12 col-md-6 col-lg-2">
								<label class="form-label small text-muted mb-1" for="atribuicao">Atribuição</label>
								<select class="form-select form-select-sm" name="atribuicao" id="atribuicao">
									<option value="" selected>Todas</option>
									<?php foreach ($atribuicoes as $atribuicao): ?>
										<option value="<?= $atribuicao['id']; ?>"><?= $atribuicao['nome']; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-12 col-md-6 col-lg-2">
								<label class="form-label small text-muted mb-1" for="status">Status</label>
								<select class="form-select form-select-sm" name="status" id="status">
									<option selected value="A">Ativo</option>
									<option value="I">Inativo</option>
								</select>
							</div>
							<div class="col-12 col-lg-2 d-flex gap-2">
								<button class="btn btn-primary btn-sm flex-grow-1 btn-submeter" type="submit">
									<i class="fas fa-magnifying-glass me-1" aria-hidden="true"></i>Pesquisar
								</button>
								<button class="btn btn-primary btn-sm" type="button" id="btn-limpar-filtros">
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
							<p class="small text-muted mb-0 mt-1" id="permissoes-quantidade-registros" aria-live="polite"></p>
						</div>
					</div>
					<div class="table-responsive listagem-site-table-wrap rounded border">
						<div class="permissoes-list min-vh-25"></div>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>

<script>
	function formatarTotalRegistrosPermissoes(n) {
		if (n === 0) {
			return 'Nenhum registro encontrado com os filtros atuais.';
		}
		if (n === 1) {
			return 'Total: 1 registro.';
		}
		return 'Total: ' + n + ' registros.';
	}

	function atualizarQuantidadeRegistros() {
		var quantidade = parseInt($('.permissoes-list #permissoes-total-registros').attr('data-total-registros'), 10);
		if (isNaN(quantidade)) {
			quantidade = 0;
		}
		var texto = formatarTotalRegistrosPermissoes(quantidade);
		$('#permissoes-quantidade-registros').text(texto);
	}

	function refreshPermissoesList() {
		$.ajax({
			url: "<?php echo base_url('colaboradores/admin/permissoesList'); ?>",
			type: 'get',
			dataType: 'html',
			data: {
				apelido: $('#apelido').val(),
				email: $('#email').val(),
				atribuicao: $('#atribuicao').val(),
				status: $('#status').val(),
			},
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide(); },
			success: function (data) {
				$('.permissoes-list').html(data);
				atualizarQuantidadeRegistros();
			},
			error: function () {
				$('.permissoes-list').html('<div class="alert alert-warning m-0">Nao foi possivel carregar os resultados agora.</div>');
				atualizarQuantidadeRegistros();
			}
		});
	}

	$('#pesquisa-permissoes').on('submit', function (event) {
		event.preventDefault();
		refreshPermissoesList();
	});

	$('#btn-limpar-filtros').on('click', function () {
		$('#apelido').val('');
		$('#email').val('');
		$('#atribuicao').val('');
		$('#status').val('A');
		refreshPermissoesList();
	});

	$(document).ready(function () {
		refreshPermissoesList();
	});
</script>

<?= $this->endSection(); ?>