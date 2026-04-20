<?= $this->extend('layouts/administradores'); ?>

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

			.listagem-site-filtros .form-select,
			.listagem-site-filtros .form-control {
				font-family: inherit;
			}

			.min-height-listagem-contatos {
				min-height: 6rem;
			}

			.vl-contatos-col-check .form-check-input {
				margin-top: 0;
				cursor: pointer;
			}

			#modal-resposta-contato .modal-contato-meta-label {
				flex: 0 0 5.5rem;
				max-width: 40%;
				text-align: end;
				padding-right: 0.5rem;
			}

			/* E-mails longos: limitar coluna e quebrar tokens sem espaços (text-break sozinho não basta em alguns casos). */
			.listagem-site-table-wrap .vl-admin-contato-email {
				max-width: 22rem;
				overflow-wrap: anywhere;
				word-break: break-word;
			}
		</style>

		<div class="d-sm-flex justify-content-between align-items-center mb-4">
			<div>
				<h1 class="h3 mb-1"><?= esc($titulo); ?></h1>
				<p class="text-muted small mb-0">Pesquise e responda às mensagens enviadas pelo formulário público de contato</p>
			</div>
		</div>

		<section class="card border rounded-3 shadow-sm mb-4" aria-labelledby="heading-contatos-admin">
			<div class="card-header bg-body-secondary bg-opacity-25 border-bottom p-3">
				<h2 id="heading-contatos-admin" class="h5 mb-1">Listagem de contatos</h2>
				<p class="text-muted small mb-0">Filtre por e-mail, assunto e estado de resposta</p>
			</div>
			<div class="card-body p-3">
				<div class="listagem-site-filtros rounded-3 border bg-body-secondary bg-opacity-50 p-3 mb-0">
					<form class="row g-2 g-md-3 align-items-end" method="get" novalidate="yes" name="pesquisa_contatos"
						id="pesquisa_contatos" data-np-autofill-form-type="other">
						<div class="col-12 col-md-4 col-lg-3">
							<label class="form-label small text-muted mb-1" for="pesquisa-contatos-email">E-mail</label>
							<input type="text" class="form-control form-control-sm" name="email" id="pesquisa-contatos-email"
								placeholder="Filtrar por e-mail" autocomplete="off" />
						</div>
						<div class="col-12 col-md-5 col-lg-5">
							<label class="form-label small text-muted mb-1" for="pesquisa-contatos-assunto">Assunto</label>
							<select class="form-select form-select-sm" name="assuntos" id="pesquisa-contatos-assunto">
								<option value="" selected>Escolha o assunto</option>
								<?php foreach ($assuntos as $assunto): ?>
									<option value="<?= esc($assunto['id']); ?>"><?= esc($assunto['assunto']); ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="col-12 col-md-3 col-lg-2">
							<label class="form-label small text-muted mb-1" for="pesquisa-contatos-status">Estado</label>
							<select class="form-select form-select-sm" name="status" id="pesquisa-contatos-status">
								<option selected value="NR">Não respondido</option>
								<option value="R">Respondido</option>
							</select>
						</div>
						<div class="col-12 col-md-12 col-lg-2 d-grid d-md-flex align-items-end">
							<button class="btn btn-primary btn-sm w-100" type="button" id="btn-pesquisar-contatos">
								<i class="fas fa-magnifying-glass me-1" aria-hidden="true"></i> Pesquisar
							</button>
						</div>
					</form>
				</div>

				<div class="border-top pt-3 mt-3">
					<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
						<div>
							<h3 class="h6 mb-0 text-muted" id="contatos-resultados-heading">Resultados</h3>
							<p class="small text-muted mb-0 mt-1" id="contatos-total-registros" aria-live="polite"></p>
						</div>
						<button type="button" class="btn btn-outline-danger btn-sm" id="btn-excluir-contatos-lote" disabled>
							<i class="fas fa-trash-can me-1" aria-hidden="true"></i> Excluir selecionados
						</button>
					</div>
					<div class="table-responsive listagem-site-table-wrap rounded border">
						<div class="contatos-list min-height-listagem-contatos"></div>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>

<div class="modal fade" id="modal-resposta-contato" tabindex="-1" aria-labelledby="modal-resposta-contato-label"
	aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h2 class="modal-title h5 mb-0" id="modal-resposta-contato-label">Responder contato</h2>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
			</div>
			<div class="modal-body">
				<div class="vstack gap-2 small mb-3 mb-md-4">
					<div class="d-flex align-items-baseline gap-1 flex-wrap">
						<span class="text-muted modal-contato-meta-label">Assunto</span>
						<span id="modal-contato-assunto" class="text-break flex-grow-1 min-w-0"></span>
					</div>
					<div class="d-flex align-items-baseline gap-1 flex-wrap">
						<span class="text-muted modal-contato-meta-label">E-mail</span>
						<span id="modal-contato-email" class="text-break flex-grow-1 min-w-0"></span>
					</div>
					<div class="d-flex align-items-baseline gap-1 flex-wrap">
						<span class="text-muted modal-contato-meta-label">Usuário</span>
						<span id="modal-contato-usuario" class="text-break flex-grow-1 min-w-0"></span>
					</div>
				</div>
				<div class="mb-3">
					<label class="form-label fw-semibold small mb-1">Mensagem recebida</label>
					<div id="modal-contato-descricao"
						class="small border rounded p-3 bg-body-secondary text-break"
						style="white-space: pre-wrap; max-height: 14rem; overflow-y: auto;"></div>
				</div>
				<div class="mb-0">
					<label class="form-label small" for="modal-contato-resposta">Sua resposta</label>
					<textarea id="modal-contato-resposta" class="form-control form-control-sm" rows="5"
						placeholder="Digite a resposta" autocomplete="off"></textarea>
					<small class="text-muted">Ao responder, um e-mail será enviado ao remetente com o conteúdo.</small>
				</div>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-primary btn-sm" id="btn-modal-contato-enviar">Enviar resposta</button>
			</div>
		</div>
	</div>
</div>

<script>
	(function () {
		var contatosListUrl = "<?= base_url('colaboradores/admin/contatosList'); ?>";
		var contatosExcluirBase = "<?= base_url('colaboradores/admin/contatosExcluir/'); ?>";
		var contatosExcluirLoteUrl = "<?= base_url('colaboradores/admin/contatosExcluirLote'); ?>";
		var contatoDetalheBase = "<?= base_url('colaboradores/admin/contatoDetalhe/'); ?>";
		var contatoRespostaBase = "<?= base_url('colaboradores/admin/contatoResposta/'); ?>";
		var modalContatoIdAtual = null;

		window.contatosId = null;
		window.contatosExclusaoLoteIds = null;

		function vlContatosAtualizaBtnLote() {
			var n = $('.contatos-list .contato-linha-check:checked').length;
			$('#btn-excluir-contatos-lote').prop('disabled', n === 0);
		}

		function vlContatosFormatarTotalRegistros(n) {
			if (n === 0) {
				return 'Nenhum registro encontrado com os filtros atuais.';
			}
			if (n === 1) {
				return 'Total: 1 registro.';
			}
			return 'Total: ' + n + ' registros.';
		}

		function vlContatosInjetarLista(html) {
			var $wrap = $('<div>').html(html);
			var totalRaw = $wrap.find('#vl-contatos-total-inline').attr('data-total');
			$wrap.find('#vl-contatos-total-inline').remove();
			if (totalRaw !== undefined && totalRaw !== '') {
				var n = parseInt(totalRaw, 10);
				if (!isNaN(n)) {
					$('#contatos-total-registros').text(vlContatosFormatarTotalRegistros(n));
				} else {
					$('#contatos-total-registros').text('');
				}
			} else {
				$('#contatos-total-registros').text('');
			}
			$('.contatos-list').html($wrap.html());
			vlContatosInitTooltips();
			vlContatosAtualizaBtnLote();
		}

		function vlContatosInitTooltips() {
			if (typeof bootstrap === 'undefined' || !bootstrap.Tooltip) {
				return;
			}
			document.querySelectorAll('.contatos-list [data-bs-toggle="tooltip"]').forEach(function (el) {
				bootstrap.Tooltip.getOrCreateInstance(el);
			});
		}

		function carregarContatosLista() {
			var $btn = $('#btn-pesquisar-contatos');
			var formData = $('#pesquisa_contatos').serialize();
			$btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1" aria-hidden="true"></i> Carregando…');
			$.ajax({
				url: contatosListUrl,
				type: 'get',
				dataType: 'html',
				data: formData,
				beforeSend: function () { $('#modal-loading').show(); },
				complete: function () {
					$('#modal-loading').hide();
					$btn.prop('disabled', false).html('<i class="fas fa-magnifying-glass me-1" aria-hidden="true"></i> Pesquisar');
				},
				success: function (data) {
					vlContatosInjetarLista(data);
				},
				error: function () {
					if (typeof popMessage === 'function') {
						popMessage('ATENÇÃO', 'Não foi possível carregar a listagem. Tente novamente.', TOAST_STATUS.DANGER);
					}
				}
			});
		}

		$(function () {
			$('#btn-pesquisar-contatos').on('click', function () {
				carregarContatosLista();
			});

			$(document).on('click', '.contatos-list .pagination a', function (e) {
				e.preventDefault();
				var href = $(e.currentTarget).attr('href');
				if (!href || href === '#') {
					return;
				}
				$.ajax({
					url: href,
					type: 'get',
					dataType: 'html',
					beforeSend: function () { $('#modal-loading').show(); },
					complete: function () { $('#modal-loading').hide(); },
					success: function (data) {
						vlContatosInjetarLista(data);
					},
					error: function () {
						if (typeof popMessage === 'function') {
							popMessage('ATENÇÃO', 'Não foi possível carregar esta página. Tente novamente.', TOAST_STATUS.DANGER);
						}
					}
				});
			});

			$(document).on('change', '.contatos-list .contatos-check-all', function () {
				var checked = $(this).prop('checked');
				$('.contatos-list .contato-linha-check').prop('checked', checked);
				$(this).prop('indeterminate', false);
				vlContatosAtualizaBtnLote();
			});

			$(document).on('change', '.contatos-list .contato-linha-check', function () {
				var total = $('.contatos-list .contato-linha-check').length;
				var sel = $('.contatos-list .contato-linha-check:checked').length;
				var $all = $('.contatos-list .contatos-check-all');
				$all.prop('checked', total > 0 && sel === total);
				$all.prop('indeterminate', sel > 0 && sel < total);
				vlContatosAtualizaBtnLote();
			});

			$('#btn-excluir-contatos-lote').on('click', function () {
				var ids = $('.contatos-list .contato-linha-check:checked').map(function () {
					return $(this).val();
				}).get();
				if (!ids.length) {
					return;
				}
				window.contatosId = null;
				window.contatosExclusaoLoteIds = ids;
				var msg = ids.length === 1
					? 'Excluir essa mensagem de contato.'
					: ('Excluir ' + ids.length + ' mensagens de contato selecionadas?');
				$('.conteudo-modal').text(msg);
				$('#mi-modal').modal('show');
			});

			$(document).on('click', '.contatos-list .btn-excluir', function (e) {
				e.preventDefault();
				window.contatosExclusaoLoteIds = null;
				$('.conteudo-modal').text('Excluir essa mensagem de contato.');
				window.contatosId = $(e.currentTarget).attr('data-contatos-id');
				$('#mi-modal').modal('show');
			});

			$('#mi-modal').on('hidden.bs.modal', function () {
				window.contatosExclusaoLoteIds = null;
				window.contatosId = null;
			});

			$('#modal-btn-si').on('click', function () {
				var loteIds = window.contatosExclusaoLoteIds ? window.contatosExclusaoLoteIds.slice() : null;
				var idUm = window.contatosId;
				window.contatosExclusaoLoteIds = null;
				window.contatosId = null;
				$('#mi-modal').modal('toggle');

				if (loteIds && loteIds.length > 0) {
					$.ajax({
						url: contatosExcluirLoteUrl,
						type: 'POST',
						contentType: 'application/json; charset=UTF-8',
						dataType: 'json',
						data: JSON.stringify({ ids: loteIds }),
						beforeSend: function () { $('#modal-loading').show(); },
						complete: function () { $('#modal-loading').hide(); },
						success: function (retorno) {
							if (retorno.status) {
								if (typeof popMessage === 'function') {
									popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
								}
								carregarContatosLista();
							} else {
								if (typeof popMessage === 'function') {
									popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
								}
							}
						},
						error: function () {
							if (typeof popMessage === 'function') {
								popMessage('ATENÇÃO', 'Não foi possível excluir os contactos selecionados. Tente novamente.', TOAST_STATUS.DANGER);
							}
						}
					});
					return false;
				}

				if (!idUm) {
					return false;
				}
				$.ajax({
					url: contatosExcluirBase + idUm,
					type: 'get',
					dataType: 'json',
					beforeSend: function () { $('#modal-loading').show(); },
					complete: function () { $('#modal-loading').hide(); },
					success: function (retorno) {
						if (retorno.status) {
							if (typeof popMessage === 'function') {
								popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
							}
							carregarContatosLista();
						} else {
							if (typeof popMessage === 'function') {
								popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
							}
						}
					},
					error: function () {
						if (typeof popMessage === 'function') {
							popMessage('ATENÇÃO', 'Não foi possível excluir o contacto. Tente novamente.', TOAST_STATUS.DANGER);
						}
					}
				});
				return false;
			});

			function vlModalContatoLimpar() {
				modalContatoIdAtual = null;
				$('#modal-contato-assunto, #modal-contato-email, #modal-contato-usuario, #modal-contato-descricao').empty();
				$('#modal-contato-resposta').val('');
			}

			$('#modal-resposta-contato').on('hidden.bs.modal', function () {
				vlModalContatoLimpar();
			});

			$(document).on('click', '.contatos-list .btn-contato-responder', function (e) {
				e.preventDefault();
				var id = $(e.currentTarget).attr('data-contato-id');
				if (!id) {
					return;
				}
				modalContatoIdAtual = id;
				$('#modal-loading').show();
				$.ajax({
					url: contatoDetalheBase + encodeURIComponent(id),
					type: 'GET',
					dataType: 'json',
					complete: function () { $('#modal-loading').hide(); },
					success: function (r) {
						if (!r || !r.status || !r.contato) {
							if (typeof popMessage === 'function') {
								popMessage('ATENÇÃO', (r && r.mensagem) ? r.mensagem : 'Não foi possível carregar o contacto.', TOAST_STATUS.DANGER);
							}
							modalContatoIdAtual = null;
							return;
						}
						var c = r.contato;
						$('#modal-contato-assunto').text(c.assunto || '');
						$('#modal-contato-email').text(c.email || '');
						$('#modal-contato-usuario').text(c.apelido ? String(c.apelido) : 'Usuário não cadastrado');
						$('#modal-contato-descricao').text(c.descricao || '');
						$('#modal-contato-resposta').val(c.resposta ? String(c.resposta) : '');
						var elModal = document.getElementById('modal-resposta-contato');
						if (elModal && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
							bootstrap.Modal.getOrCreateInstance(elModal).show();
						}
					},
					error: function () {
						if (typeof popMessage === 'function') {
							popMessage('ATENÇÃO', 'Não foi possível carregar o contacto. Tente novamente.', TOAST_STATUS.DANGER);
						}
						modalContatoIdAtual = null;
					}
				});
			});

			$('#btn-modal-contato-enviar').on('click', function () {
				if (!modalContatoIdAtual) {
					return;
				}
				var txt = ($('#modal-contato-resposta').val() || '').trim();
				if (!txt) {
					if (typeof popMessage === 'function') {
						popMessage('ATENÇÃO', 'Resposta não informada.', TOAST_STATUS.DANGER);
					}
					return;
				}
				var idEnvio = modalContatoIdAtual;
				$('#btn-modal-contato-enviar').prop('disabled', true);
				$('#modal-loading').show();
				$.ajax({
					url: contatoRespostaBase + encodeURIComponent(idEnvio),
					type: 'POST',
					data: { resposta: $('#modal-contato-resposta').val() },
					dataType: 'json',
					complete: function () {
						$('#modal-loading').hide();
						$('#btn-modal-contato-enviar').prop('disabled', false);
					},
					success: function (retorno) {
						if (retorno && retorno.status) {
							if (typeof popMessage === 'function') {
								popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
							}
							var elModal = document.getElementById('modal-resposta-contato');
							if (elModal && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
								var inst = bootstrap.Modal.getInstance(elModal);
								if (inst) {
									inst.hide();
								}
							}
							carregarContatosLista();
						} else {
							if (typeof popMessage === 'function') {
								popMessage('ATENÇÃO', (retorno && retorno.mensagem) ? retorno.mensagem : 'Não foi possível enviar a resposta.', TOAST_STATUS.DANGER);
							}
						}
					},
					error: function () {
						if (typeof popMessage === 'function') {
							popMessage('ATENÇÃO', 'Não foi possível enviar a resposta. Tente novamente.', TOAST_STATUS.DANGER);
						}
					}
				});
			});

			carregarContatosLista();
		});
	})();
</script>

<?= $this->endSection(); ?>
