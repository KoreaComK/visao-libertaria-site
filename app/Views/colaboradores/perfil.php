<?php
use CodeIgniter\I18n\Time;

?>
<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>
<?php helper('month_helper'); ?>

<div class="container mb-3">
	<div class="main-body">
		<div class="row">
			<div class="col-lg-4">
				<div class="card mb-3">
					<div class="card-body">
						<div class="d-flex flex-column align-items-center text-center">
							<img src="<?= ($colaboradores['avatar'] != NULL) ? ($colaboradores['avatar']) : (site_url('public/assets/avatar-default.png')); ?>"
								id="avatar_perfil" class="rounded-circle p-1 bg-primary vl-bg-c" width="110"
								height="110">
							<div class="mt-3">
								<h4 class="apelido_colaborador">
									<?= $colaboradores['apelido'] ?>
								</h4>
								<p class="text-muted font-size-sm">Colaborador desde
									<?= date_format(new DateTime($colaboradores['criado']), 'd') . ' ' . month_helper(date_format(new DateTime($colaboradores['criado']), 'F'), 3) . '. ' . date_format(new DateTime($colaboradores['criado']), 'Y'); ?>
								</p>
								<p class="text-secondary mb-1">
									<?php foreach ($atribuicoes as $atribuicao): ?>
										<label
											class="badge bg-<?= $atribuicao['cor']; ?>"><?= $atribuicao['nome']; ?></label>
									<?php endforeach; ?>
								</p>
								<button class="btn btn-primary" data-bs-toggle="modal"
									data-bs-target="#modal-perfil">Editar
									Perfil</button>
								<button class="btn btn-outline-primary" data-bs-toggle="modal"
									data-bs-target="#modal-senha">Trocar senha</button>
							</div>
						</div>
						<hr class="my-4">
						<p class="fs-4">Páginas públicas</p>
						<ul class="list-group list-group-flush">
							<li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
								<h6 class="mb-0"><a
										href="<?= site_url('site/escritor/' . urlencode($colaboradores['apelido'])); ?>">Listar
										artigos publicados</a></h6>
							</li>
						</ul>
						<ul class="list-group list-group-flush">
							<li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
								<h6 class="mb-0"><a href="<?= site_url('site/colaborador/' . urlencode($colaboradores['apelido'])); ?>">Listar pautas
										utilizadas</a></h6>
							</li>
						</ul>
					</div>
				</div>
				<div class="card mb-3">
					<div class="card-body text-center">
						<div class="border-light">
							<h4 class="text-normal mt-1 mb-2 fw-normal">Histórico total</h4>
						</div>
						<div class="row">
							<div class="col-12 border-end border-light">
								<h6 class="text-muted mt-1 mb-2 fw-normal">Pontuações</h6>
								<h3 class="mb-0 fw-bold">
									<?= number_format($contribuicoes_total, 0, ',', '.'); ?>
								</h3>
							</div>
						</div>
						<div class="border-light">
							<h6 class="text-muted mt-1 mb-2 fw-normal"><a href="#" data-bs-toggle="modal"
									data-bs-target="#modal-pagamentos">Ver Todos os Pagamentos</a></h6>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-8">
				<div class="card mb-3">
					<div class="card-body text-center">
						<div class="border-light">
							<h4 class="text-normal mt-1 mb-2 fw-normal">Contribuições Aprovadas e Pendentes</h4>
						</div>
						<div class="row">
							<div class="col-6 border-end border-light">
								<h6 class="text-muted mt-1 mb-2 fw-normal">Colaborações</h6>
								<h2 class="mb-0 fw-bold">
									<?= number_format($contribuicoes_mensal['colaboracoes'], 0, ',', '.'); ?>
								</h2>
							</div>
							<div class="col-6 border-end border-light">
								<h6 class="text-muted mt-1 mb-2 fw-normal">Pontuações</h6>
								<h2 class="mb-0 fw-bold">
									<?= number_format($contribuicoes_mensal['pontos'], 0, ',', '.'); ?>
								</h2>
							</div>
						</div>
						<div class="border-light">
							<h6 class="text-muted mt-1 mb-2 fw-normal"><a href="#" data-bs-toggle="modal"
									data-bs-target="#modal-colaboracoes">Ver listagem</a></h6>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 mb-3">
						<div class="card">
							<div class="card-body">
								<h4 class="d-flex align-items-center mb-3">Informações úteis:</h5>
									<div class="row">
										<div class="col-md-6 col-lg-6 col-12">
											<div class="card p-3 mb-3">
												<div class="d-flex justify-content-between">
													<h5> <span>Limite Diário de Pautas</span> </h5>
												</div>
												<div class="mt-2">
													<?php if ($limites['limite_pautas_diario_usadas'] >= $limites['limite_pautas_diario']): ?>
														<div class="mt-1 mb-1">
															Limites serão renovados em
															<?= Time::createFromFormat('Y-m-d H:i:s', $limites['limite_pautas_diario_permitido'])->toLocalizedString("dd MMMM yyyy"); ?>
														</div>
													<?php endif; ?>
													<div class="progress">
														<div class="progress-bar" role="progressbar"
															style="width: <?= number_format(($limites['limite_pautas_diario_usadas'] / $limites['limite_pautas_diario']) * 100, 0, ',', '.'); ?>%"
															aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
														</div>
													</div>
													<div class="mt-3">
														<span class="text1">
															<?= ($limites['limite_pautas_diario_usadas'] < 10) ? ('0') : (''); ?><?= $limites['limite_pautas_diario_usadas']; ?>
															envio<?= ($limites['limite_pautas_semanal_usadas'] > 1) ? ('s') : (''); ?>
															<span class="text2">de
																<?= ($limites['limite_pautas_diario'] < 10) ? ('0') : (''); ?><?= $limites['limite_pautas_diario']; ?>
																pautas.</span></span>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-6 col-lg-6 col-12">
											<div class="card p-3 mb-3">
												<div class="d-flex justify-content-between">
													<h5> <span>Limite Semanal de Pautas</span> </h5>
												</div>
												<div class="mt-2">
													<?php if ($limites['limite_pautas_semanal_usadas'] >= $limites['limite_pautas_semanal']): ?>
														<div class="mt-1 mb-1">
															Limites serão renovados em
															<?= Time::createFromFormat('Y-m-d H:i:s', $limites['limite_pautas_semanal_permitido'])->toLocalizedString("dd MMMM yyyy"); ?>
														</div>
													<?php endif; ?>
													<div class="progress">
														<div class="progress-bar" role="progressbar"
															style="width: <?= number_format(($limites['limite_pautas_semanal_usadas'] / $limites['limite_pautas_semanal']) * 100, 0, ',', '.'); ?>%"
															aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
														</div>
													</div>
													<div class="mt-3">
														<span class="text1">
															<?= ($limites['limite_pautas_semanal_usadas'] < 10) ? ('0') : (''); ?><?= $limites['limite_pautas_semanal_usadas']; ?>
															envio<?= ($limites['limite_pautas_semanal_usadas'] > 1) ? ('s') : (''); ?>
															<span class="text2">de
																<?= ($limites['limite_pautas_semanal'] < 10) ? ('0') : (''); ?><?= $limites['limite_pautas_semanal']; ?>
																pautas.</span></span>
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
			<div class="col-lg-12 font-weight-light text-end"><small><a class="text-danger" data-bs-toggle="modal"
						data-bs-target="#modal-excluir" href="">Excluir minha conta</a></small></div>
		</div>
	</div>
</div>

<div class="modal fade bd-example-modal-lg" id="modal-perfil" tabindex="-1" role="dialog"
	aria-labelledby="modal-perfilLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-perfilLabel">Dados do Perfil</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form class="needs-validation m-2" method="post" id="colaboradores_perfil"
					enctype="multipart/form-data">
					<div class="card mb-3">
						<div class="card-body">
							<div class="mb-3">
								<label for="firstName">Nome Público</label>
								<input type="text" class="form-control" id="apelido" placeholder="Digite seu apelido"
									value="<?= $colaboradores['apelido']; ?>" name="apelido" required>
							</div>

							<div class="mb-3">
								<label for="twitter">Usuário no X (antigo Twitter)</label>
								<div class="input-group mb-2">
									<span class="input-group-text" id="basic-addon1">@</span>
									<input type="text" class="form-control" id="twitter"
										placeholder="Digite seu @ para usar o AncapsuBot"
										value="<?= $colaboradores['twitter']; ?>" name="twitter">
								</div>
							</div>

							<div class="mb-3">
								<label for="username">Carteira Bitcoin</label>
								<div class="input-group">
									<input type="text" class="form-control" id="carteira" name="carteira"
										placeholder="Digite sua carteira bitcoin"
										value="<?= $colaboradores['carteira']; ?>">
								</div>
							</div>

							<div class="mb-3">
								<label for="imagem">Alterar Avatar</label>
								<div class="input-group mb-3">
									<input type="file" class="form-control" id="avatar" name="avatar"
										onchange="onFileUpload(this);" aria-label="Avatar" accept=".png" />
								</div>
							</div>
							<div class="d-grid gap-2">
								<button class="btn btn-primary btn-block" type="submit">Salvar dados do Perfil</button>
							</div>

						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-senha" tabindex="-1" role="dialog" aria-labelledby="modal-perfilLabel"
	aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-perfilLabel">Alterar Senha</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form class="needs-validation m-2" method="post" id="colaboradores_senha">
					<div class="card mb-3">
						<div class="card-body">
							<div class="row mb-3">
								<div class="col-sm-4 align-self-center ">
									<h6 class="mb-0">Senha Atual</h6>
								</div>
								<div class="col-sm-8 text-secondary">
									<input type="password" name="senha_antiga" placeholder="Digite sua senha antiga"
										class="form-control">
								</div>
							</div>
							<div class="row mb-3">
								<div class="col-sm-4 align-self-center ">
									<h6 class="mb-0">Nova Senha</h6>
								</div>
								<div class="col-sm-8 text-secondary">
									<input type="password" class="form-control" name="senha_nova"
										placeholder="Digite sua nova senha">
								</div>
							</div>
							<div class="row mb-3">
								<div class="col-sm-4 align-self-center ">
									<h6 class="mb-0">Repetir Senha</h6>
								</div>
								<div class="col-sm-8 text-secondary">
									<input type="password" class="form-control" name="senha_nova_confirmacao"
										placeholder="Digite sua nova senha">
								</div>
							</div>
							<div class="d-grid gap-2">
								<button class="btn btn-primary btn-block" type="submit">Trocar senha</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade bd-example-modal-lg" id="modal-colaboracoes" tabindex="-1" role="dialog"
	aria-labelledby="modal-perfilLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-perfilLabel">Colaborações deste mês</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<table class="table table-striped">
					<thead>
						<tr>
							<th scope="col">#</th>
							<th scope="col">Título</th>
							<th scope="col">Atribuição</th>
							<th scope="col">Pontos</th>
						</tr>
					</thead>
					<tbody>
						<?php if (empty($lista_artigos_mes)): ?>
							<td colspan="4" class="text-center">Não há colaborações pendentes até o momento</td>
						<?php else: ?>
							<?php foreach ($lista_artigos_mes as $chave => $artigo): ?>
								<?php $total = 0; ?>
								<tr>
									<th scope="row">
										<?= $chave + 1 ?>
									</th>
									<td><a
											href="<?= site_url('/site/artigo/' . $artigo['url_friendly']); ?>"><?= $artigo['titulo']; ?></a>
									</td>
									<td>
										<?php if ($artigo['escrito'] == $colaboradores['id']): ?>
											<?php $total += $artigo['pontos_escritor']; ?>
											<label class="badge bg-info m-1">Escritor</label>
										<?php endif; ?>
										<?php if ($artigo['revisado'] == $colaboradores['id']): ?>
											<?php $total += $artigo['pontos_revisor']; ?>
											<label class="badge bg-info m-1">Revisor</label>
										<?php endif; ?>
										<?php if ($artigo['narrado'] == $colaboradores['id']): ?>
											<?php $total += $artigo['pontos_narrador']; ?>
											<label class="badge bg-info m-1">Narrador</label>
										<?php endif; ?>
										<?php if ($artigo['produzido'] == $colaboradores['id']): ?>
											<?php $total += $artigo['pontos_produtor']; ?>
											<label class="badge bg-info m-1">Produtor</label>
										<?php endif; ?>
									</td>
									<td>
										<?= number_format($total, 0, ',', '.'); ?>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="modal fade bd-example-modal-lg" id="modal-colaboracoes-fechadas" tabindex="-1" role="dialog"
	aria-labelledby="modal-perfilLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-perfilLabel">Colaborações já pagas</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<table class="table table-striped">
					<thead>
						<tr>
							<th scope="col">#</th>
							<th scope="col">Título</th>
							<th scope="col">Atribuição</th>
							<th scope="col">Pontos</th>
						</tr>
					</thead>
					<tbody id="tbody-modal-colaboracoes-fechadas">
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>


<div class="modal fade bd-example-modal-lg" id="modal-pagamentos" tabindex="-1" role="dialog"
	aria-labelledby="modal-perfilLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-perfilLabel">Pagamentos pelas Contribuições</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<table class="table table-striped">
					<thead>
						<tr>
							<th scope="col">Data Pagamento</th>
							<th scope="col">Hash da Transação</th>
							<th scope="col">Pontos totais</th>
							<th scope="col">Sats/Pontos</th>
						</tr>
					</thead>
					<tbody>
						<?php if (empty($lista_pagamentos)): ?>
							<td colspan="4" class="text-center">Não há pagamentos feitos até o momento</td>
						<?php else: ?>
							<?php foreach ($lista_pagamentos as $indice => $pagamento): ?>
								<tr>
									<th scope="row">
										<?= Time::createFromFormat('Y-m-d H:i:s', $pagamento['criado'])->toLocalizedString('dd MMMM yyyy'); ?>
										</td>
									<td>
										<a href="https://mempool.space/pt/tx/<?= $pagamento['hash_transacao']; ?>"
											target="_blank">
											<?= substr($pagamento['hash_transacao'], 0, 5); ?>...<?= substr($pagamento['hash_transacao'], -5, 5); ?>
										</a>
									</td>
									<td><a href="#" class="listar-colaboracoes-fechadas" id="<?= $pagamento['id']; ?>"
											data-bs-toggle="modal" data-bs-target="#modal-colaboracoes-fechadas"
											onclick="javascript:$('#modal-pagamentos').modal('toggle');"><?= number_format($pagamento['pontuacao_total'], 0, ',', '.'); ?></a>
									</td>
									<td><?= number_format(($pagamento['quantidade_bitcoin'] * 100000000) / $pagamento['pontuacao_total'], 0, ',', '.'); ?>
										sats</td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-excluir" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
	aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Tem certeza disso?</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<p>Os pagamentos deste mês não serão enviados, pois todos os seus dados (com exceção do seu e-mail)
					seram deletados da nossa base de
					dados.</p>
				<p>Você não conseguirá mais acessar sua conta. Essa ação não pode ser desfeita.</p>
				<p>Ao clicar em "Excluir minha conta", será enviado um e-mail de confirmação de exclusão.</p>
				<p>Lembre-se: <strong>Essa ação não pode ser desfeita</strong>.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-primary excluir">Excluir minha conta</button>
			</div>
		</div>
	</div>
</div>

<script>
	$(function () {
		$('[data-toggle="tooltip"]').tooltip()
	})

	function onFileUpload(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				$('#avatar_perfil').attr('src', e.target.result)
				$('#avatar_menu').attr('src', e.target.result)
			};
			reader.readAsDataURL(input.files[0]);
		}
	}

	$('.listar-colaboracoes-fechadas').on('click', function (e) {
		$.ajax({
			url: "<?php echo base_url('colaboradores/perfil/fechadas/'); ?>" + e.currentTarget.id,
			method: "POST",
			dataType: "html",
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide() },
			success: function (retorno) {
				$('#tbody-modal-colaboracoes-fechadas').html(retorno);
			}
		});
	});

	$(document).ready(function () {
		$('#colaboradores_perfil').on('submit', function (e) {
			e.preventDefault();
			$.ajax({
				url: "<?php echo base_url('colaboradores/perfil'); ?>",
				method: "POST",
				data: new FormData(this),
				processData: false,
				contentType: false,
				cache: false,
				dataType: "json",
				beforeSend: function () { $('#modal-loading').show(); },
				complete: function () { $('#modal-loading').hide() },
				success: function (retorno) {
					if (retorno.status == true) {
						popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
						$('.apelido_colaborador').html($('#apelido').val())
					} else {
						popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
					}
					$('#modal-perfil').modal('toggle');
				}
			});
		});

		$('#colaboradores_senha').on('submit', function (e) {
			e.preventDefault();
			$.ajax({
				url: "<?php echo base_url('colaboradores/perfil'); ?>",
				method: "POST",
				data: new FormData(this),
				processData: false,
				contentType: false,
				cache: false,
				dataType: "json",
				beforeSend: function () { $('#modal-loading').show(); },
				complete: function () { $('#modal-loading').hide() },
				success: function (retorno) {
					if (retorno.status == true) {
						$('#modal-senha').modal('toggle');
						popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
					} else {
						popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
					}
				}
			});
		});

		$('.excluir').on('click', function (e) {
			e.preventDefault();
			$.ajax({
				url: "<?php echo base_url('site/excluir'); ?>",
				method: "POST",
				dataType: "json",
				beforeSend: function () { $('#modal-loading').show(); },
				complete: function () { $('#modal-loading').hide() },
				success: function (retorno) {
					if (retorno.status == true) {
						popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
					} else {
						popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
					}
					$('#modal-excluir').modal('toggle');
				}
			});
		});
	});

</script>

<?= $this->endSection(); ?>