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
				<h1 id="heading-categorias" class="h3 mb-1"><?= esc($titulo); ?></h1>
			</div>
			<div>
				<button type="button" class="btn btn-primary btn-sm w-100 w-sm-auto" id="btn-cadastrar-categoria">
					Cadastrar categoria
				</button>
			</div>
		</div>

		<section class="card border rounded-3 shadow-sm" aria-labelledby="heading-categorias">
			<div class="card-body p-3">
				<div class="listagem-site-filtros rounded-3 border bg-body-secondary bg-opacity-50 p-3 mb-0">
					<form id="form-filtros-categorias" method="get" autocomplete="off">
						<div class="row g-2 g-md-3 align-items-end">
							<div class="col-12 col-md-6 col-lg-5">
								<label class="form-label small text-muted mb-1" for="filtro-nome-categoria">Nome</label>
								<input type="text" class="form-control form-control-sm" name="nome" id="filtro-nome-categoria"
									placeholder="Pesquisar pelo nome da categoria" autocomplete="off">
							</div>
							<div class="col-12 col-md-6 col-lg-3">
								<label class="form-label small text-muted mb-1" for="filtro-situacao-categoria">Situação</label>
								<select class="form-select form-select-sm" name="situacao" id="filtro-situacao-categoria">
									<option value="todas" selected>Todas</option>
									<option value="ativas">Ativas</option>
									<option value="inativadas">Inativadas</option>
								</select>
							</div>
							<div class="col-12 col-lg-4 d-flex gap-2">
								<button class="btn btn-primary btn-sm flex-grow-1" type="submit" id="btn-pesquisar-categorias">
									<i class="fas fa-magnifying-glass me-1" aria-hidden="true"></i>Pesquisar
								</button>
								<button class="btn btn-primary btn-sm" type="button" id="btn-limpar-filtros-categorias" title="Limpar filtros">
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
							<p class="small text-muted mb-0 mt-1" id="categorias-quantidade-registros" aria-live="polite"></p>
						</div>
					</div>
					<div class="table-responsive listagem-site-table-wrap rounded border">
						<div class="categorias-list min-vh-25">
							<div class="text-center text-muted small py-4">Carregando categorias...</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>

<div class="modal fade" id="modal-categoria-form" tabindex="-1" aria-labelledby="modal-categoria-form-label" aria-hidden="true">
	<div class="modal-dialog modal-md modal-dialog-centered">
		<div class="modal-content">
			<form id="categorias_form" method="post" novalidate autocomplete="off">
				<div class="modal-header">
					<h2 class="modal-title h5 mb-0" id="modal-categoria-form-label">Cadastro de categoria</h2>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
				</div>
				<div class="modal-body">
					<input type="hidden" id="categoria-form-id" name="id" value="">
					<div class="mb-0">
						<label class="form-label" for="categoria-form-nome">Nome</label>
						<input type="text" class="form-control" id="categoria-form-nome" name="nome"
							placeholder="Nome da categoria" maxlength="255" required>
					</div>
				</div>
				<div class="modal-footer justify-content-between">
					<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
					<button type="submit" class="btn btn-primary btn-sm" id="btn-salvar-categoria">Salvar categoria</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
	var categoriaIdAcao = null;
	var categoriaTipoAcao = null;

	function formatarTotalCategorias(n) {
		if (n === 0) {
			return 'Nenhuma categoria encontrada com os filtros atuais.';
		}
		if (n === 1) {
			return 'Total: 1 categoria.';
		}
		return 'Total: ' + n + ' categorias.';
	}

	function atualizarQuantidadeCategorias() {
		var quantidade = parseInt($('.categorias-list #categorias-total-registros').attr('data-total-registros'), 10);
		if (isNaN(quantidade)) {
			quantidade = 0;
		}
		$('#categorias-quantidade-registros').text(formatarTotalCategorias(quantidade));
	}

	function miModalCategorias() {
		var el = document.getElementById('mi-modal');
		if (!el || typeof bootstrap === 'undefined' || !bootstrap.Modal) {
			return null;
		}
		return bootstrap.Modal.getOrCreateInstance(el);
	}

	function abrirModalConfirmacaoCategoria() {
		var inst = miModalCategorias();
		if (inst) {
			inst.show();
		}
	}

	function fecharModalConfirmacaoCategoria() {
		var inst = miModalCategorias();
		if (inst) {
			inst.hide();
		}
	}

	function refreshCategoriasList(extraData) {
		var data = {
			nome: $('#filtro-nome-categoria').val() || '',
			situacao: $('#filtro-situacao-categoria').val() || 'todas'
		};
		if (extraData) {
			$.extend(data, extraData);
		}
		$.ajax({
			url: "<?= base_url('colaboradores/admin/categoriasList'); ?>",
			type: 'get',
			dataType: 'html',
			data: data,
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide(); },
			success: function (html) {
				$('.categorias-list').html(html);
				atualizarQuantidadeCategorias();
			},
			error: function () {
				$('.categorias-list').html('<div class="alert alert-warning m-0">Não foi possível carregar os resultados agora.</div>');
				atualizarQuantidadeCategorias();
			}
		});
	}

	function textoSeguro(valor) {
		return $('<div>').text(valor).html();
	}

	function resetarAcaoCategoria() {
		categoriaIdAcao = null;
		categoriaTipoAcao = null;
	}

	function dadosBotaoCategoria($botao) {
		return {
			id: $botao.attr('data-categoria-id') || $botao.closest('[data-categoria-id]').attr('data-categoria-id'),
			nome: $botao.attr('data-categoria-nome') || $botao.closest('[data-categoria-nome]').attr('data-categoria-nome') || 'esta categoria',
			qtd: parseInt($botao.attr('data-categoria-qtd') || $botao.closest('[data-categoria-qtd]').attr('data-categoria-qtd'), 10)
		};
	}

	$('#form-filtros-categorias').on('submit', function (event) {
		event.preventDefault();
		refreshCategoriasList();
	});

	$('#btn-limpar-filtros-categorias').on('click', function () {
		$('#filtro-nome-categoria').val('');
		$('#filtro-situacao-categoria').val('todas');
		refreshCategoriasList();
	});

	$(document).on('click', '.categorias-list .page-link', function (e) {
		e.preventDefault();
		var href = $(this).attr('href');
		if (!href) {
			return;
		}
		var pageMatch = href.match(/[?&]page_categorias=(\d+)/);
		var extra = {};
		if (pageMatch) {
			extra.page_categorias = pageMatch[1];
		}
		refreshCategoriasList(extra);
	});

	$(document).on('click', '.btn-inativar-categoria', function (e) {
		e.preventDefault();
		e.stopPropagation();
		var dados = dadosBotaoCategoria($(this));
		categoriaIdAcao = dados.id;
		categoriaTipoAcao = 'inativar';
		$('.conteudo-modal').html('Deseja realmente inativar a categoria <strong>' + textoSeguro(dados.nome) + '</strong>? Ela continuará visível nesta listagem como inativada.');
		abrirModalConfirmacaoCategoria();
	});

	$(document).on('click', '.btn-reativar-categoria', function (e) {
		e.preventDefault();
		e.stopPropagation();
		var dados = dadosBotaoCategoria($(this));
		categoriaIdAcao = dados.id;
		categoriaTipoAcao = 'reativar';
		$('.conteudo-modal').html('Deseja reativar a categoria <strong>' + textoSeguro(dados.nome) + '</strong>?');
		abrirModalConfirmacaoCategoria();
	});

	$(document).on('click', '.btn-desvincular-pautas', function (e) {
		e.preventDefault();
		e.stopPropagation();
		var dados = dadosBotaoCategoria($(this));
		categoriaIdAcao = dados.id;
		categoriaTipoAcao = 'desvincular';
		var qtd = dados.qtd;
		if (isNaN(qtd) || qtd < 1) {
			qtd = 1;
		}
		var quantidadeTexto = qtd === 1 ? 'a pauta vinculada' : 'as ' + qtd + ' pautas vinculadas';
		$('.conteudo-modal').html('Deseja remover ' + quantidadeTexto + ' da categoria <strong>' + textoSeguro(dados.nome) + '</strong>? As pautas não serão excluídas, apenas deixarão de pertencer a esta categoria.');
		abrirModalConfirmacaoCategoria();
	});

	$(document).on('click', '#modal-btn-no', function () {
		fecharModalConfirmacaoCategoria();
		resetarAcaoCategoria();
	});

	$(document).on('click', '#modal-btn-si', function () {
		if (!categoriaIdAcao || !categoriaTipoAcao) {
			return;
		}
		var urlAcao = "<?= base_url('colaboradores/admin/categoriasExcluir/'); ?>";
		if (categoriaTipoAcao === 'desvincular') {
			urlAcao = "<?= base_url('colaboradores/admin/categoriasDesvincularPautas/'); ?>";
		} else if (categoriaTipoAcao === 'reativar') {
			urlAcao = "<?= base_url('colaboradores/admin/categoriasReativar/'); ?>";
		}
		fecharModalConfirmacaoCategoria();
		$.ajax({
			url: urlAcao + categoriaIdAcao,
			type: 'get',
			headers: { 'X-Requested-With': 'XMLHttpRequest' },
			dataType: 'json',
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide(); },
			success: function (retorno) {
				if (retorno.status) {
					popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
					refreshCategoriasList();
				} else {
					popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
				}
				resetarAcaoCategoria();
			},
			error: function () {
				popMessage('ATENÇÃO', 'Não foi possível concluir a ação agora.', TOAST_STATUS.DANGER);
				resetarAcaoCategoria();
			}
		});
	});

	function modalCategoriaFormInstancia() {
		var el = document.getElementById('modal-categoria-form');
		if (!el || typeof bootstrap === 'undefined' || !bootstrap.Modal) {
			return null;
		}
		return bootstrap.Modal.getOrCreateInstance(el);
	}

	function abrirModalCategoriaForm(id, nome) {
		var ehEdicao = !!id;
		$('#modal-categoria-form-label').text(ehEdicao ? 'Atualização de categoria' : 'Cadastro de categoria');
		$('#categoria-form-id').val(id || '');
		$('#categoria-form-nome').val(nome || '');
		var inst = modalCategoriaFormInstancia();
		if (inst) {
			inst.show();
		}
	}

	$('#modal-categoria-form').on('shown.bs.modal', function () {
		$('#categoria-form-nome').trigger('focus');
	});

	$('#modal-categoria-form').on('hidden.bs.modal', function () {
		$('#categoria-form-id').val('');
		$('#categoria-form-nome').val('');
	});

	$('#btn-cadastrar-categoria').on('click', function () {
		abrirModalCategoriaForm('', '');
	});

	$(document).on('click', '.btn-editar-categoria', function (e) {
		e.preventDefault();
		e.stopPropagation();
		var dados = dadosBotaoCategoria($(this));
		abrirModalCategoriaForm(dados.id, dados.nome === 'esta categoria' ? '' : dados.nome);
	});

	$('#categorias_form').on('submit', function (e) {
		e.preventDefault();
		var id = String($('#categoria-form-id').val() || '').trim();
		var nome = String($('#categoria-form-nome').val() || '').trim();
		if (nome === '') {
			popMessage('ATENÇÃO', 'Informe o nome da categoria.', TOAST_STATUS.DANGER);
			$('#categoria-form-nome').trigger('focus');
			return;
		}
		var url = "<?= base_url('colaboradores/admin/categoriasGravar'); ?>";
		if (id !== '') {
			url += '/' + id;
		}
		$.ajax({
			url: url,
			method: 'POST',
			headers: { 'X-Requested-With': 'XMLHttpRequest' },
			data: { nome: nome },
			dataType: 'json',
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide(); },
			success: function (retorno) {
				if (retorno.status) {
					var inst = modalCategoriaFormInstancia();
					if (inst) {
						inst.hide();
					}
					popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
					refreshCategoriasList();
				} else {
					popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
				}
			},
			error: function () {
				popMessage('ATENÇÃO', 'Não foi possível salvar a categoria agora.', TOAST_STATUS.DANGER);
			}
		});
	});

	$(function () {
		refreshCategoriasList();
	});
</script>
<?= $this->endSection(); ?>
