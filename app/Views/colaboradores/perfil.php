<?php
use CodeIgniter\I18n\Time;
?>
<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>
<?php helper('month_helper'); ?>

<div class="container mb-3">
	<div class="main-body">
		<div class="mensagem p-3 mb-2 rounded text-white text-center collapse col-12"></div>
		<div class="row">
			<div class="col-lg-4">
				<div class="card mb-3">
					<div class="card-body">
						<div class="d-flex flex-column align-items-center text-center">
							<img src="<?= ($colaboradores['avatar']!=NULL)?($colaboradores['avatar']):(site_url('public/assets/avatar-default.png')); ?>" id="avatar_perfil"
								class="rounded-circle p-1 bg-primary vl-bg-c" width="110" height="110">
							<div class="mt-3">
								<h4 class="apelido_colaborador">
									<?= $colaboradores['apelido'] ?>
								</h4>
								<p class="text-muted font-size-sm">Colaborador desde
									<?= date_format(new DateTime($colaboradores['criado']), 'd') . ' ' . month_helper(date_format(new DateTime($colaboradores['criado']), 'F'), 3) . '. ' . date_format(new DateTime($colaboradores['criado']), 'Y'); ?>
								</p>
								<p class="text-secondary mb-1">
									<?php foreach ($atribuicoes as $atribuicao): ?>
										<label class="badge badge-<?= $atribuicao['cor']; ?>"><?= $atribuicao['nome']; ?></label>
									<?php endforeach; ?>
								</p>
								<button class="btn btn-primary" data-toggle="modal" data-target="#modal-perfil">Editar
									Perfil</button>
								<button class="btn btn-outline-primary" data-toggle="modal"
									data-target="#modal-senha">Trocar senha</button>
							</div>
						</div>
						<hr class="my-4">
						<ul class="list-group list-group-flush">
							<li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
								<h6 class="mb-0"><a href="<?= site_url('colaboradores/artigos/index/'.$colaboradores['id']); ?>">Listar artigos do
									colaborador</a></h6>
							</li>
						</ul>
						<ul class="list-group list-group-flush">
							<li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
								<h6 class="mb-0"><a href="#modal-pautas" data-toggle="modal" data-target="#modal-pautas">Listar pautas 
									usadas em vídeos</a></h6>
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
							<h6 class="text-muted mt-1 mb-2 fw-normal"><a href="#" data-toggle="modal"
								data-target="#modal-pagamentos">Ver Todos os Pagamentos</a></h6>
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
							<h6 class="text-muted mt-1 mb-2 fw-normal"><a href="#" data-toggle="modal"
									data-target="#modal-colaboracoes">Ver listagem</a></h6>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 mb-3">
						<div class="card">
							<div class="card-body">
								<h5 class="d-flex align-items-center mb-3">Informações úteis:</h5>
								<p>Leia nossas diretrizes para aceitar artigos no Visão Libertária, <a href="#">clicando
										aqui</a>.</p>
								<p>Veja as diretrizes e cuidados para ser um revisor, <a href="#">clicando
										aqui</a>.</p>
								<p>Saiba as configurações e definições para enviar seu arquivo de áudio, <a
										href="#">clicando
										aqui</a>.</p>
								<p>Encontre todos os parâmetros e insumos para produzir os vídeos do canal, <a
										href="#">clicando aqui</a>.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-12 font-weight-light text-right"><small><a class="text-danger" data-toggle="modal"
						data-target="#modal-excluir" href="">Excluir minha conta</a></small></div>
		</div>
	</div>
</div>

<div class="modal fade bd-example-modal-lg" id="modal-perfil" tabindex="-1" role="dialog"
	aria-labelledby="modal-perfilLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-perfilLabel">Dados do Perfil</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form class="needs-validation m-3" method="post" id="colaboradores_perfil"
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
									<div class="input-group-prepend">
									<div class="input-group-text">@</div>
									</div>
									<input type="text" class="form-control" id="twitter" placeholder="Digite seu @ para usar o AncapsuBot"
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
									<div class="custom-file">
										<input type="file" class="custom-file-input" id="avatar" name="avatar"
											onchange="onFileUpload(this);" aria-describedby="avatar" accept=".png">
										<label class="custom-file-label" for="avatar">Escolha o avatar</label>
									</div>
								</div>
							</div>
							<hr class="mb-4">
							<button class="btn btn-primary btn-block" type="submit">Salvar dados do Perfil</button>
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
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form class="needs-validation m-3" method="post" id="colaboradores_senha">
					<div class="mensagem-senha p-3 mb-2 rounded text-white text-center collapse col-12"></div>
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
							<hr class="mb-4">
							<button class="btn btn-primary btn-block" type="submit">Trocar senha</button>
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
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
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
											<label class="badge badge-info">Escritor</label>
										<?php endif; ?>
										<?php if ($artigo['revisado'] == $colaboradores['id']): ?>
											<?php $total += $artigo['pontos_revisor']; ?>
											<label class="badge badge-info">Revisor</label>
										<?php endif; ?>
										<?php if ($artigo['narrado'] == $colaboradores['id']): ?>
											<?php $total += $artigo['pontos_narrador']; ?>
											<label class="badge badge-info">Narrador</label>
										<?php endif; ?>
										<?php if ($artigo['produzido'] == $colaboradores['id']): ?>
											<?php $total += $artigo['pontos_produtor']; ?>
											<label class="badge badge-info">Produtor</label>
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

<div class="modal fade bd-example-modal-lg" id="modal-pagamentos" tabindex="-1" role="dialog"
	aria-labelledby="modal-perfilLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-perfilLabel">Pagamentos pelas Contribuições</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
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
								<th scope="row"><?= Time::createFromFormat('Y-m-d H:i:s', $pagamento['criado'])->toLocalizedString('dd MMMM yyyy'); ?></td>
								<td>
									<a href="https://mempool.space/pt/tx/<?=$pagamento['hash_transacao'];?>" target="_blank">
										<?=substr($pagamento['hash_transacao'],0,5);?>...<?=substr($pagamento['hash_transacao'],-5,5);?>
									</a>
								</td>
								<td><?=number_format($pagamento['pontuacao_total'], 0, ',', '.');?></td>
								<td><?=number_format(($pagamento['quantidade_bitcoin']*100000000)/$pagamento['pontuacao_total'], 0, ',', '.');?> sats</td>
							</tr>
							<?php endforeach; ?>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="modal fade bd-example-modal-lg" id="modal-pautas" tabindex="-1" role="dialog"
	aria-labelledby="modal-perfilLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modal-perfilLabel">Pautas utilizadas nos vídeos</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<table class="table table-striped">
					<thead>
						<tr>
							<th scope="col">Data do Fechamento</th>
							<th scope="col">Título</th>
						</tr>
					</thead>
					<tbody>
						<?php if (empty($lista_pautas)): ?>
							<td colspan="4" class="text-center">Nenhuma pauta foi utilizada para vídeos até o momento</td>
						<?php else: ?>
							<?php foreach ($lista_pautas as $indice => $pautas): ?>
							<tr>
								<th scope="row"><?= Time::createFromFormat('Y-m-d H:i:s', $pautas['reservado'])->toLocalizedString('dd MMMM yyyy'); ?></td>
								<td><a href="<?= site_url('colaboradores/pautas/detalhe/'.$pautas['id']); ?>"><?= $pautas['titulo']; ?></a></td>
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
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p>Os pagamentos deste mês não serão enviados, pois todos os seus dados (com exceção do seu e-mail) seram deletados da nossa base de
					dados.</p>
				<p>Você não conseguirá mais acessar sua conta. Essa ação não pode ser desfeita.</p>
				<p>Ao clicar em "Excluir minha conta", será enviado um e-mail de confirmação de exclusão.</p>
				<p>Lembre-se: <strong>Essa ação não pode ser desfeita</strong>.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
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
				beforeSend: function() { $('#modal-loading').modal('show'); },
				complete: function() { $('#modal-loading').modal('hide'); },
				success: function (retorno) {
					if (retorno.status == true) {
						$('.mensagem').addClass('bg-success');
						$('.mensagem').removeClass('bg-danger');
						$('.apelido_colaborador').html($('#apelido').val())
					} else {
						$('.mensagem').removeClass('bg-success');
						$('.mensagem').addClass('bg-danger');
					}
					$('.mensagem').addClass(retorno.classe);
					$('.mensagem').html(retorno.mensagem);
					$('.mensagem').show();
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
				beforeSend: function() { $('#modal-loading').modal('show'); },
				complete: function() { $('#modal-loading').modal('hide'); },
				success: function (retorno) {
					if (retorno.status == true) {
						$('.mensagem').addClass('bg-success');
						$('.mensagem').removeClass('bg-danger');
						$('#modal-senha').modal('toggle');
						$('.mensagem').addClass(retorno.classe);
						$('.mensagem').html(retorno.mensagem);
						$('.mensagem').show();
					} else {
						$('.mensagem-senha').removeClass('bg-success');
						$('.mensagem-senha').addClass('bg-danger');
						$('.mensagem-senha').html(retorno.mensagem);
						$('.mensagem-senha').show();
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
				beforeSend: function() { $('#modal-loading').modal('show'); },
				complete: function() { $('#modal-loading').modal('hide'); },
				success: function (retorno) {
					if (retorno.status == true) {
						$('.mensagem').addClass('bg-success');
						$('.mensagem').removeClass('bg-danger');
					} else {
						$('.mensagem').removeClass('bg-success');
						$('.mensagem').addClass('bg-danger');
					}
					$('.mensagem').html(retorno.mensagem);
					$('.mensagem').show();
					$('#modal-excluir').modal('toggle');
				}
			});
		});
	});

</script>

<?= $this->endSection(); ?>