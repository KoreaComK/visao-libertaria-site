<?php

use CodeIgniter\I18n\Time;

?>
<?= $this->extend('layouts/administradores'); ?>

<?= $this->section('content'); ?>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<style>
	.metricas-colaborador {
		--bs-gutter-x: 0.75rem;
		--bs-gutter-y: 0.75rem;
	}

	.metricas-colaborador .card.card-body {
		padding: 0.75rem !important;
	}

	.metricas-colaborador h6 {
		font-size: 0.82rem;
		margin-bottom: 0.25rem;
	}

	.metricas-colaborador h2 {
		font-size: 1.35rem !important;
		line-height: 1.1;
		margin-bottom: 0.15rem !important;
	}

	.metricas-colaborador p {
		font-size: 0.75rem;
		margin-bottom: 0 !important;
	}

	@media (min-width: 1200px) {
		.metricas-colaborador .metricas-total-item {
			flex: 0 0 20%;
			max-width: 20%;
			width: 20%;
		}
	}

	.status-acao-item {
		padding-top: 0.4rem !important;
		padding-bottom: 0.4rem !important;
	}

	.status-acao-item .btn.btn-link {
		font-size: 0.82rem;
		line-height: 1.1;
	}
</style>

<div class="container w-auto">
	<div class="row pb-4 mt-3">
		<div class="col-12 order-2">
			<h1 class="mb-0 h2"><?= $titulo; ?></h1>
		</div>
	</div>

	<div class="row g-4">
		<div class="col-12">
			<div class="row g-2 metricas-colaborador">
				<div class="col-md-6 col-xl-3">
					<div class="card card-body bg-primary bg-opacity-10 p-3 h-100">
						<h6>Artigos escritos
							<a tabindex="0" class="hb6 btn-light btn-floating mb-0 btn-tooltip" data-toggle="tooltip"
								data-placement="top" title="Artigos escritos nos últimos 30 dias">
								<i class="bi bi-info-circle-fill small"></i>
							</a>
						</h6>
						<h2 class="fs-2 text-primary mb-1">
							<?= (($artigos['atual'] < 10) ? ('0') : ('')) . (number_format($artigos['atual'], 0, ',', '.')); ?>
						</h2>
						<p class="mb-2"><span
								class="text-<?= ($artigos['diferenca'] > 0) ? ('success') : ('danger'); ?> ms-1 me-1 "><?= (number_format($artigos['diferenca'], 0, ',', '.')); ?>
								<i
									class="fas <?= ($artigos['diferenca'] > 0) ? ('fa-up-long') : (($artigos['diferenca'] < 0) ? ('fa-down-long') : ('fa-minus')); ?> fa-xs"></i></span>
							vs último mês</p>
					</div>
				</div>
				<div class="col-md-6 col-xl-3">
					<div class="card card-body bg-success bg-opacity-10 p-3 h-100">
						<h6>Artigos publicados
							<a tabindex="0" class="hb6 btn-light btn-floating mb-0 btn-tooltip" data-toggle="tooltip"
								data-placement="top" title="Artigos publicados nos últimos 30 dias">
								<i class="bi bi-info-circle-fill small"></i>
							</a>
						</h6>
						<h2 class="fs-2 text-success mb-1">
							<?= (($artigos['publicados_atual'] < 10) ? ('0') : ('')) . (number_format($artigos['publicados_atual'], 0, ',', '.')); ?>
						</h2>
						<p class="mb-2">
							<span
								class="text-<?= ($artigos['publicados_diferenca'] > 0) ? ('success') : ('danger'); ?> ms-1 me-1 "><?= (number_format($artigos['publicados_diferenca'], 0, ',', '.')); ?>
								<i
									class="fas <?= ($artigos['publicados_diferenca'] > 0) ? ('fa-up-long') : (($artigos['publicados_diferenca'] < 0) ? ('fa-down-long') : ('fa-minus')); ?> fa-xs"></i></span>
							vs último mês
						</p>
					</div>
				</div>
				<div class="col-md-6 col-xl-3">
					<div class="card card-body bg-info bg-opacity-10 p-3 h-100">
						<h6>Pautas cadastradas
							<a tabindex="0" class="hb6 btn-light btn-floating mb-0 btn-tooltip" data-toggle="tooltip"
								data-placement="top" title="Pautas cadastradas nos últimos 30 dias">
								<i class="bi bi-info-circle-fill small"></i>
							</a>
						</h6>
						<h2 class="fs-2 text-info mb-1">
							<?= (($pautas['atual'] < 10) ? ('0') : ('')) . (number_format($pautas['atual'], 0, ',', '.')); ?>
						</h2>
						<p class="mb-2"><span
								class="text-<?= ($pautas['diferenca'] > 0) ? ('success') : ('danger'); ?> ms-1 me-1 "><?= (number_format($pautas['diferenca'], 0, ',', '.')); ?>
								<i
									class="fas <?= ($pautas['diferenca'] > 0) ? ('fa-up-long') : (($pautas['diferenca'] < 0) ? ('fa-down-long') : ('fa-minus')); ?> fa-xs"></i></span>
							vs último mês</p>
					</div>
				</div>
				<div class="col-md-6 col-xl-3">
					<div class="card card-body bg-warning bg-opacity-10 p-3 h-100">
						<h6>Pautas utilizadas
							<a tabindex="0" class="hb6 btn-light btn-floating mb-0 btn-tooltip" data-toggle="tooltip"
								data-placement="top" title="Pautas utilizadas nos últimos 30 dias">
								<i class="bi bi-info-circle-fill small"></i>
							</a>
						</h6>
						<h2 class="fs-2 text-warning mb-1">
							<?= (($pautas['utilizados_atual'] < 10) ? ('0') : ('')) . (number_format($pautas['utilizados_atual'], 0, ',', '.')); ?>
						</h2>
						<p class="mb-2">
							<span
								class="text-<?= ($pautas['utilizados_diferenca'] > 0) ? ('success') : ('danger'); ?> ms-1 me-1 "><?= (number_format($pautas['utilizados_diferenca'], 0, ',', '.')); ?>
								<i
									class="fas <?= ($pautas['utilizados_diferenca'] > 0) ? ('fa-up-long') : (($pautas['utilizados_diferenca'] < 0) ? ('fa-down-long') : ('fa-minus')); ?> fa-xs"></i></span>
							vs último mês
						</p>
					</div>
				</div>
				<div class="col-md-6 col-xl-3 metricas-total-item">
					<div class="card card-body bg-secondary bg-opacity-10 p-3 h-100">
						<h6>Artigos totais</h6>
						<h2 class="fs-2 text-secondary mb-1">
							<?= (($artigos['total'] < 10) ? ('0') : ('')) . (number_format($artigos['total'], 0, ',', '.')); ?>
						</h2>
						<p class="mb-2">Total acumulado</p>
					</div>
				</div>
				<div class="col-md-6 col-xl-3 metricas-total-item">
					<div class="card card-body bg-success bg-opacity-10 p-3 h-100">
						<h6>Artigos publicados (total)</h6>
						<h2 class="fs-2 text-success mb-1">
							<?= (($artigos['publicados_total'] < 10) ? ('0') : ('')) . (number_format($artigos['publicados_total'], 0, ',', '.')); ?>
						</h2>
						<p class="mb-2">Total acumulado</p>
					</div>
				</div>
				<div class="col-md-6 col-xl-3 metricas-total-item">
					<div class="card card-body bg-info bg-opacity-10 p-3 h-100">
						<h6>Pautas utilizadas (total)</h6>
						<h2 class="fs-2 text-info mb-1">
							<?= (($pautas['utilizados_total'] < 10) ? ('0') : ('')) . (number_format($pautas['utilizados_total'], 0, ',', '.')); ?>
						</h2>
						<p class="mb-2">Total acumulado</p>
					</div>
				</div>
				<div class="col-md-6 col-xl-3 metricas-total-item">
					<div class="card card-body bg-danger bg-opacity-10 p-3 h-100">
						<h6>Artigos descartados (total)</h6>
						<h2 class="fs-2 text-danger mb-1">
							<?= (($artigos['descartados_total'] < 10) ? ('0') : ('')) . (number_format($artigos['descartados_total'], 0, ',', '.')); ?>
						</h2>
						<p class="mb-2">Total acumulado</p>
					</div>
				</div>
				<div class="col-md-6 col-xl-3 metricas-total-item">
					<div class="card card-body bg-dark bg-opacity-10 p-3 h-100">
						<h6>Publicados x Descartados</h6>
						<h2 class="fs-5 text-dark mb-1">
							<?= number_format($artigos['publicados_percentual'], 1, ',', '.'); ?>% publicados
						</h2>
						<p class="mb-0 small text-muted">
							<?= number_format($artigos['descartados_percentual'], 1, ',', '.'); ?>% descartados
						</p>
					</div>
				</div>
			</div>
		</div>

		<div class="col-12 col-lg-6 order-1">
			<div class="card border h-100">
				<div class="card-header border-bottom d-flex justify-content-between align-items-center p-3">
					<h5 class="card-header-title mb-0">Sobre o colaborador</h5>
				</div>
				<div class="card-body">
					<div class="d-sm-flex justify-content-sm-between align-items-center mb-4">
						<div class="d-flex align-items-center">
							<?php if ($colaboradores['avatar'] !== NULL && $colaboradores['avatar'] != ''): ?>
								<div class="avatar">
									<img class="avatar-img rounded-circle" style="width: 3rem;"
										src="<?= $colaboradores['avatar']; ?>" alt="">
								</div>
							<?php endif; ?>
							<!-- Info -->
							<div class="ms-3">
								<div class="d-flex align-items-center gap-2">
									<h5 class="mb-0"><?= esc($colaboradores['apelido']); ?></h5>
									<span class="badge <?= ($colaboradores['confirmado_data'] !== NULL) ? 'bg-success-subtle text-success-emphasis border border-success-subtle' : 'bg-warning-subtle text-warning-emphasis border border-warning-subtle'; ?> status-email-badge">
										<?= ($colaboradores['confirmado_data'] !== NULL) ? 'E-mail confirmado' : 'E-mail não confirmado'; ?>
									</span>
								</div>
								<p class="mb-0 small"><?= esc($colaboradores['email']); ?></p>
								<button type="button" class="btn btn-link text-danger p-0 small email-confirmar <?= ($colaboradores['confirmado_data'] !== NULL) ? ('d-none') : (''); ?>">Confirmar e-mail</button>
							</div>
						</div>
						<!-- <div class="d-flex mt-2 mt-sm-0">
							<h6 class="bg-danger py-2 px-3 text-white rounded">14K Follow</h6>
							<h6 class="bg-info py-2 px-3 text-white rounded ms-2">856 Posts</h6>
						</div> -->
					</div>

					<div class="row gy-3">
						<div class="col-md-12">
							<ul class="list-group list-group-borderless">
								<li class="list-group-item">
									<div class="d-flex align-items-center gap-2 mb-1">
										<span class="small text-muted">Carteira</span>
										<span class="badge bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle">
											<?= esc($colaboradores['carteira']); ?>
										</span>
										<button type="button" class="btn btn-link p-0 small copiar-carteira" data-carteira="<?= esc($colaboradores['carteira'], 'attr'); ?>">Copiar</button>
									</div>
								</li>
								<li class="list-group-item">
									<div class="d-flex align-items-center gap-2 mb-1">
										<span class="small text-muted">Colaborador desde</span>
										<span class="badge bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle">
											<?= ($colaboradores['criado'] !== NULL) ? Time::createFromFormat('Y-m-d H:i:s', $colaboradores['criado'])->toLocalizedString('dd MMMM yyyy') : (''); ?>
										</span>
									</div>
								</li>
								<li class="list-group-item status-acao-item">
									<div class="row g-2">
										<div class="col-12 col-md-6">
											<div class="d-flex align-items-center gap-2 mb-1">
												<span class="small text-muted">Status de bloqueio</span>
												<span class="badge <?= ($colaboradores['bloqueado'] !== 'N') ? 'bg-danger-subtle text-danger-emphasis border border-danger-subtle' : 'bg-success-subtle text-success-emphasis border border-success-subtle'; ?> status-bloqueio-badge">
													<?= ($colaboradores['bloqueado'] !== 'N') ? 'Usuário inativo' : 'Usuário ativo'; ?>
												</span>
											</div>
											<div class="mt-1">
												<button type="button" class="btn btn-link text-danger p-0 bloqueado-bloquear <?= ($colaboradores['bloqueado'] !== 'N') ? ('d-none') : (''); ?>">Bloquear colaborador</button>
												<button type="button" class="btn btn-link text-danger p-0 bloqueado-desbloquear <?= ($colaboradores['bloqueado'] !== 'N') ? ('') : ('d-none'); ?>">Desbloquear colaborador</button>
											</div>
										</div>
										<div class="col-12 col-md-6">
											<div class="d-flex align-items-center gap-2 mb-1">
												<span class="small text-muted">Status de shadowban</span>
												<span class="badge <?= ($colaboradores['shadowban'] == 'S') ? 'bg-warning-subtle text-warning-emphasis border border-warning-subtle' : 'bg-success-subtle text-success-emphasis border border-success-subtle'; ?> status-shadowban-badge">
													<?= ($colaboradores['shadowban'] == 'S') ? 'Habilitado' : 'Desabilitado'; ?>
												</span>
											</div>
											<div class="mt-1">
												<button type="button" class="btn btn-link text-danger p-0 shadowban-habilitar <?= ($colaboradores['shadowban'] != 'N') ? ('d-none') : (''); ?>">Habilitar shadowban</button>
												<button type="button" class="btn btn-link text-danger p-0 shadowban-desabilitar <?= ($colaboradores['shadowban'] != 'S') ? ('d-none') : (''); ?>">Desabilitar shadowban</button>
											</div>
										</div>
									</div>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-12 col-lg-6 order-3">
			<!-- Popular blog START -->
			<div class="card border h-100">
				<!-- Card header -->
				<div class="card-header border-bottom p-3">
					<h5 class="card-header-title mb-0">Últimos artigos escritos deste mês</h5>
				</div>

				<!-- Card body START -->
				<div class="card-body p-3">

					<div class="row">
						<?php if (!empty($artigos['lista']) && $artigos['lista'] !== NULL): ?>
							<?php foreach ($artigos['lista'] as $chave => $artigo): ?>
								<?php if ($chave < 4): ?>
									<?php $artigo['class-img'] = 'd-none'; ?>
									<?php $artigo['class-div'] = 'col-12'; ?>
									<?php $artigo['abrir_nova_aba'] = true; ?>
									<?= view_cell('\App\Libraries\Cards::cardsHorizontais', $artigo); ?>
								<?php endif; ?>
							<?php endforeach; ?>
						<?php else: ?>
							<div class="text-center">Artigos não encontrados.</div>
						<?php endif; ?>

					</div>
				</div>
			</div>
			<!-- Popular blog END -->
		</div>

		<div class="col-12 col-lg-6 order-3">
			<!-- Popular blog START -->
			<div class="card border h-100">
				<!-- Card header -->
				<div class="card-header border-bottom p-3">
					<h5 class="card-header-title mb-0">Últimas pautas cadastradas deste mês</h5>
				</div>

				<!-- Card body START -->
				<div class="card-body p-3">

					<div class="row">
						<?php if (!empty($pautas['lista']) && $pautas['lista'] !== NULL): ?>
							<?php foreach ($pautas['lista'] as $chave => $pauta): ?>
								<?php if ($chave < 4): ?>
									<?php $pauta['class-img'] = 'd-none'; ?>
									<?php $pauta['class-div'] = 'col-12'; ?>
									<?php $pauta['abrir_nova_aba'] = true; ?>
									<?= view_cell('\App\Libraries\Cards::cardsHorizontais', $pauta); ?>
								<?php endif; ?>
							<?php endforeach; ?>
						<?php else: ?>
							<div class="text-center">Pautas não encontradas.</div>
						<?php endif; ?>

					</div>
				</div>
			</div>
			<!-- Popular blog END -->
		</div>

		<div class="col-12 col-lg-6 order-1">
			<div class="card border h-100">
				<div class="card-body">
					<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
						<div>
							<h5 class="mb-0">Atribuições</h5>
							<p class="small text-muted mb-0">Selecione e salve as permissões deste colaborador</p>
						</div>
						<span class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle" id="atribuicoes-selecionadas-badge">0 selecionadas</span>
					</div>
					<div class="row g-2 mb-3">
						<div class="col-12 d-flex align-items-center">
							<small class="text-muted" id="atribuicoes-resumo-diff">Nenhuma alteração pendente.</small>
						</div>
					</div>
					<form class="row" novalidate="yes" method="post" id="atribuicoes">
						<?php foreach ($atribuicoes as $atribuicao): ?>
							<div class="col-12 col-sm-6 col-lg-3 atribuicao-item">
								<div class="form-check form-switch form-check-md mb-3">
									<input class="form-check-input" type="checkbox" id="<?= $atribuicao['id']; ?>"
										name="atribuicoes[<?= $atribuicao['id']; ?>]" value="<?= $atribuicao['id']; ?>"
										<?= in_array($atribuicao['id'], $colaboradores_atribuicoes) ? ('checked') : (''); ?>>
									<label class="form-check-label" for="<?= $atribuicao['id']; ?>">
										<small class="text-muted"><?= esc($atribuicao['nome']); ?></small>
									</label>
								</div>
							</div>
						<?php endforeach; ?>

						<div class="d-sm-flex justify-content-end">
							<button type="button" class="btn btn-sm btn-primary me-2 mb-0 salvar-atribuicoes">Salvar
								atribuições</button>
						</div>
					</form>
				</div>
			</div>


		</div>

		<div class="col-lg-12 order-4">
			<!-- Chart START -->
			<div class="card border h-100">
				<!-- Card header -->
				<div class="card-header p-3 border-bottom">
					<h5 class="card-header-title mb-0">Artigos publicados nos últimos 12 meses</h5>
				</div>
				<!-- Card body -->
				<div class="card-body">
					<div id="chart"></div>
				</div>
			</div>
			<!-- Chart END -->
		</div>

		<div class="col-lg-12 order-5">
			<div class="card border h-100">
				<div class="card-body">
					<div class="row gy-3">
						<div class="col-12">
							<div class="accordion" id="accordion-historico-colaborador">
								<div class="accordion-item">
									<h2 class="accordion-header" id="heading-historico-colaborador">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
											data-bs-target="#collapse-historico-colaborador" aria-expanded="false"
											aria-controls="collapse-historico-colaborador">
											Histórico do colaborador
										</button>
									</h2>
									<div id="collapse-historico-colaborador" class="accordion-collapse collapse"
										aria-labelledby="heading-historico-colaborador" data-bs-parent="#accordion-historico-colaborador">
										<div class="accordion-body">
											<div class="historicos-list col-12 text-center"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>

<div class="modal fade" id="modal-confirmar-acao-permissoes" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Confirmar ação</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
			</div>
			<div class="modal-body">
				<p class="mb-0" id="modal-confirmar-acao-permissoes-texto"></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-primary btn-sm" id="modal-confirmar-acao-permissoes-confirmar">Confirmar</button>
			</div>
		</div>
	</div>
</div>

<script>
	const colaboradorIdPermissoes = <?= (int) $colaboradores['id'] ?>;
	let confirmacaoAcaoPendente = null;
	let historicoJaCarregado = false;

	function abrirModalConfirmacao(texto, onConfirm) {
		$('#modal-confirmar-acao-permissoes-texto').text(texto);
		confirmacaoAcaoPendente = onConfirm;
		const modalEl = document.getElementById('modal-confirmar-acao-permissoes');
		if (modalEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
			bootstrap.Modal.getOrCreateInstance(modalEl).show();
			return;
		}
		if (window.confirm(texto)) {
			onConfirm();
		}
	}

	function enviarAcaoPermissoes(form, callbacks = {}) {
		$.ajax({
			url: "<?php echo base_url('colaboradores/admin/permissoes'); ?>",
			method: "POST",
			data: form,
			processData: false,
			contentType: false,
			cache: false,
			dataType: "json",
			beforeSend: function () {
				$('#modal-loading').show();
				if (typeof callbacks.beforeSend === 'function') {
					callbacks.beforeSend();
				}
			},
			complete: function () {
				$('#modal-loading').hide();
				if (typeof callbacks.complete === 'function') {
					callbacks.complete();
				}
			},
			success: function (retorno) {
				if (retorno.status == true) {
					popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
				} else {
					popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
				}
				if (typeof callbacks.success === 'function') {
					callbacks.success(retorno);
				}
			},
			error: function () {
				popMessage('ATENÇÃO', 'Nao foi possivel concluir a solicitacao.', TOAST_STATUS.DANGER);
			}
		});
	}

	function coletarAtribuicoesMarcadas() {
		return $('#atribuicoes input[type="checkbox"]:checked').map(function () {
			return String($(this).val());
		}).get().sort();
	}

	function atualizarResumoAtribuicoes() {
		const selecionadas = coletarAtribuicoesMarcadas().length;
		$('#atribuicoes-selecionadas-badge').text(selecionadas + (selecionadas === 1 ? ' selecionada' : ' selecionadas'));
	}

	function carregarHistoricoColaborador() {
		if (historicoJaCarregado) {
			return;
		}
		$.ajax({
			url: "<?php echo base_url('colaboradores/admin/historico'); ?>",
			type: 'get',
			dataType: 'html',
			data: { apelido: '<?= $colaboradores['id']; ?>' },
			beforeSend: function () {
				$('#modal-loading').show();
				$('.historicos-list').html('<div class="small text-muted">Carregando historico...</div>');
			},
			complete: function () { $('#modal-loading').hide(); },
			success: function (data) {
				historicoJaCarregado = true;
				$('.historicos-list').html(data);
			},
			error: function () {
				$('.historicos-list').html('<div class="alert alert-warning mb-0">Nao foi possivel carregar o historico.</div>');
			}
		});
	}

	$(document).ready(function () {
		$(function () {
			$('.btn-tooltip').tooltip();
		});
		atualizarResumoAtribuicoes();
	});

	const atribuicoesIniciais = coletarAtribuicoesMarcadas();

	$('#atribuicoes input[type="checkbox"]').on('change', function () {
		atualizarResumoAtribuicoes();
	});

	$('.salvar-atribuicoes').on('click', function () {
		const atuais = coletarAtribuicoesMarcadas();
		const adicionadas = atuais.filter(function (id) { return atribuicoesIniciais.indexOf(id) === -1; });
		const removidas = atribuicoesIniciais.filter(function (id) { return atuais.indexOf(id) === -1; });

		const nomesAdicionadas = adicionadas.map(function (id) {
			return $('label[for="' + id + '"]').text().trim();
		}).filter(Boolean);
		const nomesRemovidas = removidas.map(function (id) {
			return $('label[for="' + id + '"]').text().trim();
		}).filter(Boolean);

		let resumoDiff = 'Nenhuma alteração pendente.';
		if (nomesAdicionadas.length || nomesRemovidas.length) {
			const partes = [];
			if (nomesAdicionadas.length) {
				partes.push('Adicionar: ' + nomesAdicionadas.join(', '));
			}
			if (nomesRemovidas.length) {
				partes.push('Remover: ' + nomesRemovidas.join(', '));
			}
			resumoDiff = partes.join(' | ');
		}
		$('#atribuicoes-resumo-diff').text(resumoDiff);

		abrirModalConfirmacao('Confirmar atualização das atribuições deste colaborador?', function () {
			const form = new FormData(atribuicoes);
			form.append('colaborador_id', colaboradorIdPermissoes);
			enviarAcaoPermissoes(form, {
				beforeSend: function () {
					$('.salvar-atribuicoes').prop('disabled', true);
				},
				complete: function () {
					$('.salvar-atribuicoes').prop('disabled', false);
				}
			});
		});
	});

	$('.bloqueado-bloquear').on('click', function () {
		abrirModalConfirmacao('Tem certeza que deseja bloquear este colaborador?', function () {
			const form = new FormData();
			form.append('bloqueado', 'true');
			form.append('colaborador_id', colaboradorIdPermissoes);
			enviarAcaoPermissoes(form, {
				beforeSend: function () { $('.bloqueado-bloquear, .bloqueado-desbloquear').prop('disabled', true); },
				complete: function () { $('.bloqueado-bloquear, .bloqueado-desbloquear').prop('disabled', false); },
				success: function (retorno) {
					if (retorno.status) {
						$('.bloqueado-desbloquear').removeClass('d-none');
						$('.bloqueado-bloquear').addClass('d-none');
						$('.status-bloqueio-badge')
							.removeClass('bg-success-subtle text-success-emphasis border-success-subtle')
							.addClass('bg-danger-subtle text-danger-emphasis border-danger-subtle')
							.text('Usuário inativo');
					}
				}
			});
		});
	});

	$('.bloqueado-desbloquear').on('click', function () {
		abrirModalConfirmacao('Tem certeza que deseja desbloquear este colaborador?', function () {
			const form = new FormData();
			form.append('bloqueado', 'false');
			form.append('colaborador_id', colaboradorIdPermissoes);
			enviarAcaoPermissoes(form, {
				beforeSend: function () { $('.bloqueado-bloquear, .bloqueado-desbloquear').prop('disabled', true); },
				complete: function () { $('.bloqueado-bloquear, .bloqueado-desbloquear').prop('disabled', false); },
				success: function (retorno) {
					if (retorno.status) {
						$('.bloqueado-desbloquear').addClass('d-none');
						$('.bloqueado-bloquear').removeClass('d-none');
						$('.status-bloqueio-badge')
							.removeClass('bg-danger-subtle text-danger-emphasis border-danger-subtle')
							.addClass('bg-success-subtle text-success-emphasis border-success-subtle')
							.text('Usuário ativo');
					}
				}
			});
		});
	});

	$('.shadowban-habilitar').on('click', function () {
		abrirModalConfirmacao('Tem certeza que deseja habilitar shadowban para este colaborador?', function () {
			const form = new FormData();
			form.append('shadowban', 'S');
			form.append('colaborador_id', colaboradorIdPermissoes);
			enviarAcaoPermissoes(form, {
				beforeSend: function () { $('.shadowban-habilitar, .shadowban-desabilitar').prop('disabled', true); },
				complete: function () { $('.shadowban-habilitar, .shadowban-desabilitar').prop('disabled', false); },
				success: function (retorno) {
					if (retorno.status) {
						$('.shadowban-desabilitar').removeClass('d-none');
						$('.shadowban-habilitar').addClass('d-none');
						$('.status-shadowban-badge')
							.removeClass('bg-success-subtle text-success-emphasis border-success-subtle')
							.addClass('bg-warning-subtle text-warning-emphasis border-warning-subtle')
							.text('Habilitado');
					}
				}
			});
		});
	});

	$('.shadowban-desabilitar').on('click', function () {
		abrirModalConfirmacao('Tem certeza que deseja desabilitar shadowban para este colaborador?', function () {
			const form = new FormData();
			form.append('shadowban', 'N');
			form.append('colaborador_id', colaboradorIdPermissoes);
			enviarAcaoPermissoes(form, {
				beforeSend: function () { $('.shadowban-habilitar, .shadowban-desabilitar').prop('disabled', true); },
				complete: function () { $('.shadowban-habilitar, .shadowban-desabilitar').prop('disabled', false); },
				success: function (retorno) {
					if (retorno.status) {
						$('.shadowban-desabilitar').addClass('d-none');
						$('.shadowban-habilitar').removeClass('d-none');
						$('.status-shadowban-badge')
							.removeClass('bg-warning-subtle text-warning-emphasis border-warning-subtle')
							.addClass('bg-success-subtle text-success-emphasis border-success-subtle')
							.text('Desabilitado');
					}
				}
			});
		});
	});

	$('.email-confirmar').on('click', function () {
		abrirModalConfirmacao('Tem certeza que deseja confirmar manualmente o e-mail deste colaborador?', function () {
			const form = new FormData();
			form.append('confirmar_email', 'S');
			form.append('colaborador_id', colaboradorIdPermissoes);
			enviarAcaoPermissoes(form, {
				beforeSend: function () { $('.email-confirmar').prop('disabled', true); },
				complete: function () { $('.email-confirmar').prop('disabled', false); },
				success: function (retorno) {
					if (retorno.status) {
						$('.email-confirmar').addClass('d-none');
						$('.status-email-badge')
							.removeClass('bg-warning-subtle text-warning-emphasis border-warning-subtle')
							.addClass('bg-success-subtle text-success-emphasis border-success-subtle')
							.text('Confirmado');
					}
				}
			});
		});
	});

	$('.copiar-carteira').on('click', function () {
		const carteira = ($(this).attr('data-carteira') || '').toString();
		if (!carteira) {
			popMessage('ATENÇÃO', 'Carteira não informada.', TOAST_STATUS.DANGER);
			return;
		}
		if (navigator.clipboard && navigator.clipboard.writeText) {
			navigator.clipboard.writeText(carteira).then(function () {
				popMessage('Sucesso!', 'Carteira copiada.', TOAST_STATUS.SUCCESS);
			}).catch(function () {
				popMessage('ATENÇÃO', 'Não foi possível copiar a carteira.', TOAST_STATUS.DANGER);
			});
			return;
		}
		const tempInput = document.createElement('input');
		tempInput.value = carteira;
		document.body.appendChild(tempInput);
		tempInput.select();
		try {
			document.execCommand('copy');
			popMessage('Sucesso!', 'Carteira copiada.', TOAST_STATUS.SUCCESS);
		} catch (e) {
			popMessage('ATENÇÃO', 'Não foi possível copiar a carteira.', TOAST_STATUS.DANGER);
		}
		document.body.removeChild(tempInput);
	});

	$('#collapse-historico-colaborador').on('shown.bs.collapse', function () {
		carregarHistoricoColaborador();
	});

	$('#modal-confirmar-acao-permissoes-confirmar').on('click', function () {
		if (typeof confirmacaoAcaoPendente === 'function') {
			confirmacaoAcaoPendente();
		}
		confirmacaoAcaoPendente = null;
		const modalEl = document.getElementById('modal-confirmar-acao-permissoes');
		if (modalEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
			bootstrap.Modal.getOrCreateInstance(modalEl).hide();
		}
	});

	$('#modal-confirmar-acao-permissoes').on('hidden.bs.modal', function () {
		confirmacaoAcaoPendente = null;
	});
</script>

<?php if (isset($graficos['base']) && !empty($graficos['base'])): ?>
	<script>
		var options = {
			chart: {
				type: 'bar',
				height: '350',
			},
			plotOptions: {
				bar: {
					borderRadius: 10,
					dataLabels: {
						position: 'top',
					},
				}
			},
			dataLabels: {
				enabled: true,
				formatter: function (val) {
					return val;
				},
				offsetY: -20,
				style: {
					fontSize: '14px',
					colors: ["#304758"]
				}
			},
			series: [{
				name: 'Artigos escritos',
				data: [
					<?php foreach ($graficos['base'] as $chave => $i): ?>
																				<?= '"' . $i . '",'; ?>
												<?php endforeach; ?>
				]
			}],
			xaxis: {
				categories: [
					<?php foreach ($graficos['base'] as $chave => $i): ?>
																				<?= '"' . $chave . '",'; ?>
												<?php endforeach; ?>
				]
			}
		}

		var chart = new ApexCharts(document.querySelector("#chart"), options);

		chart.render();
	</script>
<?php endif; ?>

<?= $this->endSection(); ?>