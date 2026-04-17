<?php

use CodeIgniter\I18n\Time;

?>
<?= $this->extend('layouts/colaboradores'); ?>

<?= $this->section('content'); ?>

<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<style>
	#editor .ql-container {
		min-height: 320px;
		font-size: 0.875rem;
	}

	#editor .ql-editor {
		min-height: 320px;
		cursor: text;
		font-size: 0.875rem;
		line-height: 1.5;
	}

	@media (min-width: 992px) {
		.painel-lateral-sticky {
			position: sticky;
			top: 1rem;
		}
	}

	/* Corpo do histórico: conteúdo em <div> (HTML do Quill tem <p> — não pode ficar dentro de <p>) */
	.modal-artigo-corpo-historico p {
		margin-bottom: 0.75rem;
	}

	.modal-artigo-corpo-historico p:last-child {
		margin-bottom: 0;
	}

	/* Comentários: mesma escala que form-control-sm / dashboard */
	#accordionHistoricoArtigo .accordion-button {
		font-size: 0.875rem;
	}

	#accordionHistoricoArtigo .accordion-body {
		font-size: 0.875rem;
	}

	.div-list-comentarios .card-body,
	.div-list-comentarios .card-text {
		font-size: 0.875rem;
	}

	.div-list-comentarios .badge {
		font-size: 0.7rem;
		font-weight: 500;
	}

</style>

<div class="container-fluid py-3">
	<div class="container">

		<section class="mb-4" aria-labelledby="heading-regras-artigo">
			<h2 id="heading-regras-artigo" class="h3 text-body mb-1">Regras e orientações</h2>
			<p class="text-muted small mb-0">Leia antes de salvar ou enviar o texto</p>
			<div class="card border rounded-3 shadow-sm mt-2">
				<div class="card-body p-2 p-sm-3">
					<div class="mb-0">
						<?php if ($artigo['fase_producao_id'] == '1'): ?>
							<?= $config['artigo_regras_escrever']; ?>
						<?php endif; ?>
						<?php if ($artigo['fase_producao_id'] == '2'): ?>
							<?= $config['artigo_regras_revisar']; ?>
						<?php endif; ?>
						<?php if ($artigo['fase_producao_id'] == '3'): ?>
							<?= $config['artigo_regras_narrar']; ?>
						<?php endif; ?>
						<?php if ($artigo['fase_producao_id'] == '4'): ?>
							<?= $config['artigo_regras_produzir']; ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</section>

		<?php
		$mostrarPainelLateral = (
			(!$cadastro && isset($artigo['fase_producao_id']) && ($artigo['fase_producao_id'] == '1' || $artigo['fase_producao_id'] == '2'))
			|| !empty($historico ?? [])
			|| !empty($historicoTexto ?? [])
			|| (isset($artigo['id']) && $artigo['id'] !== null)
		);
		?>
		<div class="row g-3 align-items-start<?= $mostrarPainelLateral ? '' : ' justify-content-center' ?>">
			<div class="col-12 col-lg-8">
			<div class="card border rounded-3 shadow-sm">
				<div class="card-body p-3">
					<form class="w-100" novalidate="yes" method="post" id="artigo_form" enctype='multipart/form-data'>
						<div class="row">
							<div class="col-12">
								<div class="mb-3">
									<label class="form-label small text-muted mb-1" for="tipo_artigo">Tipo de artigo</label>
									<div class="input-group input-group-sm">
										<select class="form-select form-select-sm" name="tipo_artigo" id="tipo_artigo">
											<option value="" <?= (isset($artigo) && isset($artigo['tipo_artigo']) && $artigo['tipo_artigo'] == "") ? ('selected="true"') : (''); ?>>Escolha o
												tipo
												do
												artigo
											</option>
											<option value="T" <?= (isset($artigo) && isset($artigo['tipo_artigo']) && $artigo['tipo_artigo'] == "T") ? ('selected="true"') : (''); ?>>Teórico
											</option>
											<option value="N" <?= (isset($artigo) && isset($artigo['tipo_artigo']) && $artigo['tipo_artigo'] == "N") ? ('selected="true"') : (''); ?>>Notícia
											</option>
										</select>
									</div>
								</div>
							</div>

							<div class="col-12">
								<?php if (!empty($pauta)): ?>
									<div class="mb-3">
										<div class="form-label small text-muted mb-1">
											<input type="text" class="form-control d-none" id="link" name="link"
												value="<?= (isset($pauta['link'])) ? (esc($pauta['link'])) : (''); ?>">
											<a class="btn btn-sm btn-primary" href="<?= esc($pauta['link']); ?>"
												target="_blank" rel="noopener noreferrer">Acessar a notícia</a>
											<?= esc($pauta['titulo']); ?>
										</div>
										<small class="d-block text-danger aviso-pauta"></small>
									</div>
								<?php else: ?>
									<div class="mb-3">
										<label class="form-label small text-muted mb-1" for="link">Link da Notícia</label>
										<div class="input-group input-group-sm">
											<div class="input-group-text"><i class="fas fa-link"></i></div>
											<input type="text" class="form-control form-control-sm" id="link"
												placeholder="Link da notícia para pauta" name="link"
												value="<?= (isset($artigo['link'])) ? (esc($artigo['link'])) : (''); ?>">
										</div>
										<small class="d-block text-danger aviso-pauta"></small>
									</div>
								<?php endif; ?>
							</div>

							<!-- <div class="mb-3">
								<label for="imagem">Categorias</label>
									<div class="d-flex flex-wrap m-n1">
										<?php //foreach ($categorias as $categoria) : ?>
											<div class="btn-group-toggle p-1" data-toggle="buttons">
												<label class="btn btn-secondary vl-bg-c">
													<input id="categoria_<? //$categoria['id']; ?>" value="<? //$categoria['id']; ?>" name="categorias[<? //$categoria['id']; ?>]" type="checkbox" <? //in_array($categoria['id'], $categorias_artigo) ? ('checked') : (''); ?>> <? //$categoria['nome']; ?>
												</label>
											</div>
										<?php //endforeach; ?>
									</div>
								</div> -->

							<div class="col-12">
								<div class="mb-3">
									<label class="form-label small text-muted mb-1" for="titulo">Título</label>
									<input type="text" class="form-control form-control-sm" id="titulo" name="titulo"
										placeholder="Título do artigo" maxlength="100"
										value="<?= esc($artigo['titulo']); ?>">
									<small>O título deve ser chamativo. Deixe as palavras-chave em maiúsculo</small>
								</div>
							</div>
							<div class="col-12">
								<div class="mb-3">
									<label class="form-label small text-muted mb-1" for="gancho">Gancho </label>
									<textarea class="form-control form-control-sm" rows="3" placeholder="Texto curto antes da vinheta"
										id="gancho" maxlength="600"
										name="gancho"><?= esc($artigo['gancho']); ?></textarea>
									<small>Um parágrafo chamativo que cative o telespectador. Máximo 600 caracteres.</small>
								</div>
							</div>

							<div class="col-md-12">
								<div class="mb-3">
									<label class="form-label small text-muted mb-1" for="texto">Corpo do artigo</label>
									<div class="rounded-3 border" id="editor">
									</div>
									<textarea id="texto" name="texto" class="d-none"><?= esc($artigo['texto']); ?></textarea>
									<div class="col-md-12 d-flex justify-content-between">
										<small class="ps-1">
											<span class="">Artigo deve ter entre
												<?= esc($config['artigo_tamanho_minimo']); ?> e
												<?= esc($config['artigo_tamanho_maximo']); ?> palavras.</span>
										</small>
										<small class="pe-1"> <span class="pull-right label label-default"
												id="count_message"></span></small>
									</div>
								</div>
							</div>

							<div class="col-12">
								<div class="mb-3">
									<label class="form-label small text-muted mb-1" for="referencias">Referências </label>
									<textarea class="form-control form-control-sm" rows="3"
										placeholder="Referências para embasar seu texto" id="referencias"
										name="referencias"><?= esc($artigo['referencias']); ?></textarea>
									<small>Todos os links utilizados para dar embasamento para escrever o artigo, menos
										a pauta.</small>
								</div>
							</div>

							<div class="d-flex justify-content-center">
								<button class="btn btn-primary btn-sm w-100 mb-3" id="enviar_artigo"
									type="button">Salvar artigo</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<?php if ($mostrarPainelLateral): ?>
		<div class="col-12 col-lg-4">
			<div class="painel-lateral-sticky">
				<?php if (!$cadastro && $artigo['fase_producao_id'] == '1'): ?>
					<div class="card border rounded-3 shadow-sm mb-3">
						<div class="card-body">
							<h6 class="card-title fw-semibold mb-2">Submeter para revisão</h6>
							<p class="card-text small text-muted mb-2">Ao submeter para revisão aceito os seguintes termos:</p>
							<div class="form-check form-switch">
								<input class="form-check-input aceite-submeter-fase1" type="checkbox" id="aceito-fase1-1">
								<label class="form-check-label small" for="aceito-fase1-1">
									Aceito o texto ser alterado parcial ou completamente para atender o
									padrão
									do Visão
									Libertária.
								</label>
							</div>
							<div class="form-check form-switch">
								<input class="form-check-input aceite-submeter-fase1" type="checkbox" id="aceito-fase1-2">
								<label class="form-check-label small" for="aceito-fase1-2">
									Entendo que não poderei mais descartar o texto após enviá-lo para a
									revisão.
								</label>
							</div>
							<div class="form-check form-switch">
								<input class="form-check-input aceite-submeter-fase1" type="checkbox" id="aceito-fase1-3">
								<label class="form-check-label small" for="aceito-fase1-3">
									Caso o texto esteja muito fora do padrão do projeto ele poderá ser
									descartado a
									qualquer
									momento.
								</label>
							</div>
							<div class="form-check form-switch">
								<input class="form-check-input aceite-submeter-fase1" type="checkbox" id="aceito-fase1-4">
								<label class="form-check-label small" for="aceito-fase1-4">
									Verifiquei <a class="btn-link listagem-artigos-produzindo" data-bs-toggle="modal"
										data-bs-target="#modalListagem">NESTA
										LISTAGEM</a> os artigos que estão sendo
									produzidos
									e este assunto
									não foi encontrado
								</label>
							</div>
							<div class="text-end">
								<button type="button" disabled="" class="btn btn-primary btn-sm mt-2 submeter-revisao">Enviar
									para
									revisão</button>
							</div>
						</div>
					</div>
				<?php endif; ?>
				<?php if (!$cadastro && $artigo['fase_producao_id'] == '2'): ?>
					<div class="card border rounded-3 shadow-sm mb-3">
						<div class="card-body">
							<h6 class="card-title fw-semibold mb-2">Submeter para narração</h6>
							<p class="card-text small text-muted mb-2">Ao submeter para narração aceito os seguintes termos:</p>
							<div class="form-check form-switch">
								<input class="form-check-input aceite-submeter-fase2" type="checkbox" id="aceito-fase2-1">
								<label class="form-check-label small" for="aceito-fase2-1">
									Foi revisado com atenção, tendo portanto uma visão libertária nítida,
									não se
									mostrando
									apoiadora de nenhum
									político ou do estado, sendo totalmente aderente as ideias do projeto.
								</label>
							</div>
							<div class="form-check form-switch">
								<input class="form-check-input aceite-submeter-fase2" type="checkbox" id="aceito-fase2-2">
								<label class="form-check-label small" for="aceito-fase2-2">
									Garanto que o texto não possui erros grosseiros de português, está bem
									escrito e com
									boa
									fluência para narração.
								</label>
							</div>
							<div class="form-check form-switch">
								<input class="form-check-input aceite-submeter-fase2" type="checkbox" id="aceito-fase2-3">
								<label class="form-check-label small" for="aceito-fase2-3">
									Usei a ferramenta <a class="btn-link" href="https://www.duplichecker.com/"
										target="_blank" rel="noopener noreferrer">Dupli
										Checker</a> e <a class="btn-link" href="https://www.zerogpt.com/"
										target="_blank" rel="noopener noreferrer">ZeroGPT</a>
									e em conjunto com a revisão não foi encontrado nenhum indício de plágio
									ou
									que o
									texto
									seja de IA.
								</label>
							</div>
							<div class="text-end">
								<button type="button" disabled="" class="btn btn-success btn-sm mt-2 submeter-revisao">Enviar
									para
									narração</button>
							</div>
						</div>
					</div>

					<div class="card border rounded-3 shadow-sm mb-3">
						<div class="card-body">
							<h6 class="card-title fw-semibold mb-2">Reverter artigo</h6>
							<p class="card-text small text-muted mb-2">Ao reverter o artigo confirmo os seguintes termos:</p>
							<div class="form-check form-switch">
								<input class="form-check-input aceite-reverter" type="checkbox" id="reverter-fase2-1">
								<label class="form-check-label small" for="reverter-fase2-1">
									O artigo será revertido para a etapa anterior para ajustes.
								</label>
							</div>
							<div class="form-check form-switch">
								<input class="form-check-input aceite-reverter" type="checkbox" id="reverter-fase2-2">
								<label class="form-check-label small" for="reverter-fase2-2">
									Deixei um comentário no artigo informando o motivo da reversão para o
									escritor.
								</label>
							</div>
							<div class="text-center">
								<button type="button" disabled="" data-historico-texto-id=""
									class="btn btn-warning btn-sm mt-2 reverter-artigo">Reverter
									artigo</button>
							</div>
						</div>
					</div>

					<div class="card border rounded-3 shadow-sm mb-3">
						<div class="card-body">
							<h6 class="card-title fw-semibold mb-2">Descartar artigo</h6>
							<p class="card-text small text-muted mb-2">Ao descartar o artigo confirmo os seguintes termos:</p>
							<div class="form-check form-switch">
								<input class="form-check-input aceite-descarte" type="checkbox" id="descarte-fase2-1">
								<label class="form-check-label small" for="descarte-fase2-1">
									O texto será descartado e não irá mais seguir a trilha de produção.
								</label>
							</div>
							<div class="form-check form-switch">
								<input class="form-check-input aceite-descarte" type="checkbox" id="descarte-fase2-2">
								<label class="form-check-label small" for="descarte-fase2-2">
									Deixei um comentário no artigo informando o motivo do descarte para o
									escritor.
								</label>
							</div>
							<div class="text-start">
								<button type="button" disabled="" class="btn btn-danger btn-sm mt-2 descartar-artigo">Descartar
									artigo</button>
							</div>
						</div>
					</div>
				<?php endif; ?>
				<?php if ($historico !== NULL && !empty($historico)): ?>
					<div class="card border rounded-3 shadow-sm mb-3">
						<div class="">
							<div class="accordion" id="accordionHistorico">
								<div class="accordion-item border-0">
									<h2 class="accordion-header">
										<button class="accordion-button" type="button" data-bs-toggle="collapse"
											data-bs-target="#historicoList" aria-expanded="true"
											aria-controls="historicoList">
											Histórico do artigo:
										</button>
									</h2>
									<div id="historicoList" class="accordion-collapse collapse show"
										data-bs-parent="#accordionHistorico">
										<div class="accordion-body">
											<ul class="list-group fw-light lista-historico">
												<?php foreach ($historico as $h): ?>
													<li class="list-group-item p-1 border-0">
														<small>
															<?= esc($h['apelido']); ?>
															<?= esc($h['acao']); ?>
															<span class="badge badge-pill badge-secondary fw-light">
																<?= Time::createFromFormat('Y-m-d H:i:s', $h['criado'])->toLocalizedString('dd MMMM yyyy HH:mm:ss'); ?>
															</span>
														</small>
													</li>
												<?php endforeach; ?>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>

				<?php if ($historicoTexto !== NULL && !empty($historicoTexto)): ?>
					<div class="card border rounded-3 shadow-sm mb-3">
						<div class="">
							<div class="accordion" id="accordionHistoricoTexto">
								<div class="accordion-item border-0">
									<h2 class="accordion-header">
										<button class="accordion-button" type="button" data-bs-toggle="collapse"
											data-bs-target="#historicoArtigoList" aria-expanded="true"
											aria-controls="historicoArtigoList">
											Histórico do texto:
										</button>
									</h2>
									<div id="historicoArtigoList" class="accordion-collapse collapse show"
										data-bs-parent="#accordionHistoricoTexto">
										<div class="accordion-body">
											<ul class="list-group fw-light lista-historico-artigo">
												<?php foreach ($historicoTexto as $h): ?>
													<li class="list-group-item p-1 border-0">
														<small><button type="button" class="btn btn-link btn-texto-historico p-0 border-0 align-baseline"
																data-historico-texto-id="<?= esc($h['id']); ?>">
																Ver texto de
																<?= Time::createFromFormat('Y-m-d H:i:s', $h['criado'])->toLocalizedString('dd MMMM yyyy HH:mm:ss'); ?>
															</button></small>
													</li>
												<?php endforeach; ?>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>

				<?php if (isset($artigo['id']) && $artigo['id'] !== null): ?>
					<div class="card border rounded-3 shadow-sm mb-3">
						<div class="">
							<div class="accordion" id="accordionHistoricoArtigo">
								<div class="accordion-item border-0">
									<h2 class="accordion-header">
										<button class="accordion-button" type="button" data-bs-toggle="collapse"
											data-bs-target="#comentarios" aria-expanded="true" aria-controls="comentarios">
											Comentários do artigo:
										</button>
									</h2>
									<div id="comentarios" class="accordion-collapse collapse show"
										data-bs-parent="#accordionHistoricoArtigo">
										<div class="accordion-body pt-2 px-2 px-sm-3 pb-3">
											<div class="row g-2">
												<div class="col-12">
													<button class="btn btn-primary btn-sm w-100 mb-2"
														id="btn-comentarios" type="button">Atualizar
														comentários</button>
												</div>
												<div class="col-12">
													<div class="div-comentarios">
														<div class="mb-2">
															<input type="hidden" id="id_comentario"
																name="id_comentario" />
															<label class="form-label small text-muted mb-1"
																for="comentario">Novo comentário</label>
															<textarea id="comentario" name="comentario"
																class="form-control form-control-sm" rows="4"
																placeholder="Digite seu comentário aqui"></textarea>
														</div>
														<div class="mb-2">
															<button class="btn btn-primary btn-sm w-100"
																id="enviar-comentario" type="button">Enviar
																comentário</button>
														</div>
														<div
															class="card border rounded-3 shadow-sm mt-2 mb-0 div-list-comentarios">
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
				<?php endif; ?>
			</div>
		</div>
		<?php endif; ?>
		</div>
	</div>
</div>


<div class="modal fade" id="modalListagem" tabindex="-1" role="dialog" aria-labelledby="modalListagemLabel"
	aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalListagemLabel">Artigos em produção</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
			</div>
			<div class="modal-body corpo-listar">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary text-left" data-bs-dismiss="modal">Fechar</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalVerTextoHistoricoLabel" aria-hidden="true"
	id="modalVerTextoHistorico">
	<div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalVerTextoHistoricoLabel">Histórico do artigo</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
			</div>
			<div class="modal-body">
				<div class="col-lg-12">
					<h1 class="display-2" id="modal-artigo-titulo"></h1>
					<p class="lead" id="modal-artigo-gancho"></p>
					<div class="position-relative mb-3">
						<div class="pt-3 pb-3">
							<div>
								<div id="modal-artigo-texto" class="modal-artigo-corpo-historico"></div>
								<h4 class="mb-3">Referências:</h4>
								<div id="modal-artigo-referencias" class="modal-artigo-corpo-historico"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer d-flex justify-content-between">
				<button type="button" class="me-auto btn btn-outline-danger btn-reverter">Reverter
					artigo</button>
				<button type="button" class="btn btn-default" data-bs-dismiss="modal"
					id="modal-btn-close">Fechar</button>
			</div>
		</div>
	</div>
</div>

<?php if (!$cadastro && $artigo['fase_producao_id'] == '1'): ?>
	<div class="modal align-middle" tabindex="-1" id="modalAvisoCadastro">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title">ATENÇÃO!</h3>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
				</div>
				<div class="modal-body">
					<p>Seu artigo está salvo, mas não foi submetido para revisão.</p>
					<p>Pra submetê-lo, aceite os termos na parte inferior da tela e clique em ENVIAR PARA REVISÃO.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		const avisoCadastroEl = document.getElementById('modalAvisoCadastro');
		if (avisoCadastroEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
			const myModal = bootstrap.Modal.getOrCreateInstance(avisoCadastroEl);
			myModal.show();
		}
	</script>

<?php endif; ?>

<script type="text/javascript">
	function contapalavras() {
		let texto = $("#texto").val().replaceAll('\n', " ");
		texto = texto.replace(/[0-9]/gi, "");
		const matches = texto.split(" ");
		const number = matches.filter(function (word) {
			return word.length > 0;
		}).length;
		const s = number > 1 ? 's' : '';
		$('#count_message').html(number + " palavra" + s)
	}

	function verificaPautaEscrita() {
		const formData = new FormData(artigo_form);
		$.ajax({
			url: "<?= site_url('colaboradores/artigos/verificaPautaEscrita' . (($artigo['id'] == NULL) ? ('') : ('/' . $artigo['id']))); ?>",
			method: "POST",
			data: formData,
			processData: false,
			contentType: false,
			cache: false,
			dataType: "json",
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide() },
			success: function (retorno) {
				if (retorno.status === false) {
					$('.aviso-pauta').html(retorno.mensagem);
				} else {
					$('.aviso-pauta').html('');
				}
			}
		});
	}

	const options = {
		modules: {
			toolbar: null,
		},
		placeholder: 'Escreva seu texto aqui.',
		theme: 'snow'
	};
	const quill = new Quill('#editor', options);
	quill.on('text-change', () => {
		$('#texto').val(quill.getText(0, quill.getLength()));
		contapalavras();
	});
	<?php if ($artigo['texto'] !== null): ?>
		quill.setText(<?= json_encode(preg_replace('/\s\s+/', "\n\n", htmlspecialchars_decode($artigo['texto']))); ?>);
	<?php endif; ?>

	$('#count_message').html('0 palavra');
	$(document).ready(function () {
		contapalavras();
		verificaPautaEscrita();
	});

	$('#modalVerTextoHistorico').on('hidden.bs.modal', function () {
		const tituloInput = document.getElementById('titulo');
		if (tituloInput) {
			tituloInput.focus();
		}
	});

	$('#link').on('change', function () {
		verificaPautaEscrita();
	});

	const salvarOuRevisarUrl = <?= json_encode(
		($artigo['fase_producao_id'] == '1')
			? site_url('colaboradores/artigos/salvar') . (($artigo['id'] == NULL) ? '' : ('/' . $artigo['id']))
			: (($artigo['fase_producao_id'] == '2')
				? site_url('colaboradores/artigos/revisar') . (($artigo['id'] == NULL) ? '' : ('/' . $artigo['id']))
				: null)
	); ?>;

	$('#enviar_artigo').on('click', function () {
		if (!salvarOuRevisarUrl) {
			popMessage('ATENÇÃO', 'Não foi possível identificar a ação de salvamento para esta fase.', TOAST_STATUS.DANGER);
			return;
		}
		const formData = new FormData(artigo_form);
		$.ajax({
			url: salvarOuRevisarUrl,
			method: "POST",
			data: formData,
			processData: false,
			contentType: false,
			cache: false,
			dataType: "json",
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide() },
			success: function (retorno) {
				if (retorno.status) {
					popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);

					<?php if ($cadastro): ?>
						setTimeout(function () {
							window.location.href = "<?= site_url('colaboradores/artigos/cadastrar/'); ?>" + retorno.parametros['artigoId'];
						}, 2000);
					<?php else: ?>
						atualizaHistorico();
						atualizaArtigoHistorico();
					<?php endif; ?>

				} else {
					popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
				}
			}
		});
	})

	<?php if (!$cadastro): ?>

		<?php if ($artigo['fase_producao_id'] == '1'): ?>

			$('.aceite-submeter-fase1').on('change', function () {
				submeterRevisao();
			});

			function submeterRevisao() {
				if ($('.aceite-submeter-fase1:checked').length === $('.aceite-submeter-fase1').length) {
					$('.submeter-revisao').removeAttr('disabled');
				} else {
					$('.submeter-revisao').attr('disabled', '');
				}
			}

		<?php elseif ($artigo['fase_producao_id'] == '2'): ?>
			$('.aceite-submeter-fase2').on('change', function () {
				submeterRevisao();
			});

			function submeterRevisao() {
				if ($('.aceite-submeter-fase2:checked').length === $('.aceite-submeter-fase2').length) {
					$('.submeter-revisao').removeAttr('disabled');
				} else {
					$('.submeter-revisao').attr('disabled', '');
				}
			}

			$('.aceite-descarte').on('change', function () {
				descartarArtigo();
			});

			function descartarArtigo() {
				if ($('.aceite-descarte:checked').length === $('.aceite-descarte').length) {
					$('.descartar-artigo').removeAttr('disabled');
				} else {
					$('.descartar-artigo').attr('disabled', '');
				}
			}

			$('.descartar-artigo').on('click', function () {
				$.ajax({
					url: "<?= site_url('colaboradores/artigos/descartar/') . $artigo['id']; ?>",
					method: "POST",
					processData: false,
					contentType: false,
					cache: false,
					dataType: "json",
					beforeSend: function () { $('#modal-loading').show(); },
					complete: function () { $('#modal-loading').hide() },
					success: function (retorno) {
						if (retorno.status) {
							popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
							$('.descartar-artigo').attr('disabled', '');

							setTimeout(function () {
								<?php if ($artigo['fase_producao_id'] == '1'): ?>
									window.location.href = "<?= site_url('colaboradores/artigos/meusArtigos/'); ?>";
								<?php elseif ($artigo['fase_producao_id'] == '2'): ?>
									window.location.href = "<?= site_url('colaboradores/artigos/artigosColaborar/'); ?>";
								<?php endif; ?>
							}, 1500);

						} else {
							popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
						}
					}
				});
			});


			$('.aceite-reverter').on('change', function () {
				reverterArtigo();
			});

			function reverterArtigo() {
				if ($('.aceite-reverter:checked').length === $('.aceite-reverter').length) {
					$('.reverter-artigo').removeAttr('disabled');
				} else {
					$('.reverter-artigo').attr('disabled', '');
				}
			}

			$('.reverter-artigo').on('click', function () {
				$.ajax({
					url: "<?= site_url('colaboradores/artigos/reverter/') . $artigo['id']; ?>",
					method: "POST",
					processData: false,
					contentType: false,
					cache: false,
					dataType: "json",
					beforeSend: function () { $('#modal-loading').show(); },
					complete: function () { $('#modal-loading').hide() },
					success: function (retorno) {
						if (retorno.status) {
							popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
							$('.descartar-artigo').attr('disabled', '');

							setTimeout(function () {
								<?php if ($artigo['fase_producao_id'] == '1'): ?>
									window.location.href = "<?= site_url('colaboradores/artigos/meusArtigos/'); ?>";
								<?php elseif ($artigo['fase_producao_id'] == '2'): ?>
									window.location.href = "<?= site_url('colaboradores/artigos/artigosColaborar/'); ?>";
								<?php endif; ?>
							}, 1500);

						} else {
							popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
						}
					}
				});
			});

		<?php endif; ?>

		$('.submeter-revisao').on('click', function () {
			$.ajax({
				url: "<?= site_url('colaboradores/artigos/submeter/') . $artigo['id']; ?>",
				method: "POST",
				processData: false,
				contentType: false,
				cache: false,
				dataType: "json",
				beforeSend: function () { $('#modal-loading').show(); },
				complete: function () { $('#modal-loading').hide() },
				success: function (retorno) {
					if (retorno.status) {
						popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
						$('.submeter-revisao').attr('disabled', '');

						setTimeout(function () {
							<?php if ($artigo['fase_producao_id'] == '1'): ?>
								window.location.href = "<?= site_url('colaboradores/artigos/meusArtigos/'); ?>" + retorno.parametros['artigoId'];
							<?php elseif ($artigo['fase_producao_id'] == '2'): ?>
								window.location.href = "<?= site_url('colaboradores/artigos/artigosColaborar/'); ?>";
							<?php endif; ?>
						}, 1500);

					} else {
						popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
					}
				}
			});
		});


		$('.listagem-artigos-produzindo').on('click', function () {
			$.ajax({
				url: "<?= site_url('colaboradores/artigos/artigosProduzindo'); ?>",
				method: "GET",
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

		function atualizaHistorico() {
			$.ajax({
				url: "<?= site_url('colaboradores/artigos/historicos/') . $artigo['id']; ?>",
				method: "GET",
				processData: false,
				contentType: false,
				cache: false,
				dataType: "html",
				beforeSend: function () { $('#modal-loading').show(); },
				complete: function () { $('#modal-loading').hide() },
				success: function (retorno) {
					$('.lista-historico').html(retorno);
				}
			});
		}

		function atualizaArtigoHistorico() {
			$.ajax({
				url: "<?= site_url('colaboradores/artigos/artigosTextoHistoricosList/') . $artigo['id']; ?>",
				method: "GET",
				processData: false,
				contentType: false,
				cache: false,
				dataType: "html",
				beforeSend: function () { $('#modal-loading').show(); },
				complete: function () { $('#modal-loading').hide() },
				success: function (retorno) {
					$('.lista-historico-artigo').html(retorno);
				}
			});
		}

		$("#btn-comentarios").on("click", function () {
			getComentarios();
		});
		$('#btn-comentarios').trigger('click');

		function getComentarios() {
			$.ajax({
				url: "<?php echo base_url('colaboradores/artigos/comentarios/' . $artigo['id']); ?>",
				method: "GET",
				dataType: "html",
				beforeSend: function () { $('#modal-loading').show(); },
				complete: function () { $('#modal-loading').hide() },
				success: function (retorno) {
					$('.div-list-comentarios').html(retorno);
				}
			});
		}

		$("#enviar-comentario").on("click", function () {
			var textoComentario = ($('#comentario').val() || '').trim();
			if (textoComentario === '') {
				popMessage('ATENÇÃO', 'É necessário preencher o comentário antes de enviar.', TOAST_STATUS.DANGER);
				return;
			}
			const formData = new FormData();
			formData.append('comentario', $('#comentario').val());
			if ($('#id_comentario').val() == '') {
				formData.append('metodo', 'inserir');
			} else {
				formData.append('metodo', 'alterar');
				formData.append('id_comentario', $('#id_comentario').val());
			}

			$.ajax({
				url: "<?php echo base_url('colaboradores/artigos/comentarios/' . $artigo['id']); ?>",
				method: "POST",
				data: formData,
				processData: false,
				contentType: false,
				cache: false,
				dataType: "json",
				beforeSend: function () { $('#modal-loading').show(); },
				complete: function () { $('#modal-loading').hide() },
				success: function (retorno) {

					if (retorno.status) {
						popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
						getComentarios()
						$('#comentario').val('');
						$('#id_comentario').val('');
					} else {
						popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
					}
				}
			});
		});

		function excluirComentario(id_comentario) {
			const formData = new FormData();
			formData.append('id_comentario', id_comentario);
			formData.append('metodo', 'excluir');

			$.ajax({
				url: "<?php echo base_url('colaboradores/artigos/comentarios/' . $artigo['id']); ?>",
				method: "POST",
				data: formData,
				processData: false,
				contentType: false,
				cache: false,
				dataType: "json",
				beforeSend: function () { $('#modal-loading').show(); },
				complete: function () { $('#modal-loading').hide() },
				success: function (retorno) {
					if (retorno.status) {
						popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
						if ($('#id_comentario').val() === id_comentario) {
							$('#id_comentario').val('');
							$('#comentario').val('');
						}
						getComentarios()
					} else {
						popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
					}
				}
			});
		}

		$(document).on('click', '.btn-texto-historico', function () {
			mostraHistoricoTexto(this);
		});

		function escapeHtmlHistorico(s) {
			const d = document.createElement('div');
			d.textContent = s;
			return d.innerHTML;
		}

		function htmlCorpoHistoricoArtigo(texto) {
			if (texto == null || texto === '') {
				return '';
			}
			const t = String(texto);
			if (/<[a-z][\s\S]*>/i.test(t)) {
				return t;
			}
			return '<p class="mb-0 text-break" style="white-space: pre-wrap;">' + escapeHtmlHistorico(t) + '</p>';
		}

		function htmlReferenciasHistorico(ref) {
			if (ref == null || ref === '') {
				return '';
			}
			const t = String(ref);
			if (/<[a-z][\s\S]*>/i.test(t)) {
				return t;
			}
			return '<p class="mb-0 text-break" style="white-space: pre-wrap;">' + escapeHtmlHistorico(t) + '</p>';
		}

		function mostraHistoricoTexto(e) {
			const formData = new FormData();
			$.ajax({
				url: "<?php echo base_url('colaboradores/artigos/artigosTextoHistorico/'); ?>" + e.dataset.historicoTextoId,
				method: "POST",
				data: formData,
				processData: false,
				contentType: false,
				cache: false,
				dataType: "json",
				beforeSend: function () { $('#modal-loading').show(); },
				complete: function () { $('#modal-loading').hide() },
				success: function (retorno) {

					if (retorno.status) {
						const parametros = retorno.parametros || null;
						if (!parametros) {
							popMessage('ATENÇÃO', 'Não foi possível carregar o texto histórico deste item.', TOAST_STATUS.DANGER);
							return;
						}
						$('#modal-artigo-titulo').html(parametros.titulo || '');
						$('#modal-artigo-gancho').html(parametros.gancho || '');
						$('#modal-artigo-texto').html(htmlCorpoHistoricoArtigo(parametros.texto));
						$('#modal-artigo-referencias').html(htmlReferenciasHistorico(parametros.referencias));
						$('.btn-reverter').attr('data-historico-texto-id', e.dataset.historicoTextoId);
						const modalHistoricoEl = document.getElementById('modalVerTextoHistorico');
						if (modalHistoricoEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
							bootstrap.Modal.getOrCreateInstance(modalHistoricoEl).show();
						}
					} else {
						popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
					}
				}
			});
		}

		$(".btn-reverter").on("click", function (e) {
			const formData = new FormData();
			$.ajax({
				url: "<?php echo base_url('colaboradores/artigos/artigosTextoHistorico/'); ?>" + e.currentTarget.dataset.historicoTextoId,
				method: "POST",
				data: formData,
				processData: false,
				contentType: false,
				cache: false,
				dataType: "json",
				beforeSend: function () { $('#modal-loading').show(); },
				complete: function () { $('#modal-loading').hide() },
				success: function (retorno) {
					if (retorno.status) {
						const parametros = retorno.parametros || null;
						if (!parametros) {
							popMessage('ATENÇÃO', 'Não foi possível reverter para o texto histórico selecionado.', TOAST_STATUS.DANGER);
							return;
						}
						$('#titulo').val(parametros.titulo || '');
						$('#gancho').val(parametros.gancho || '');
						$('#referencias').html(parametros.referencias || '');
						quill.setText(parametros.texto || '');
					} else {
						popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
					}
				}
			});
			$('#modal-btn-close').trigger("click");
		});

	<?php endif; ?>

</script>


<?= $this->endSection(); ?>