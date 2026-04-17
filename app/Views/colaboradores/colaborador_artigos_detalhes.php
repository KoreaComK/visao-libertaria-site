<?php use CodeIgniter\I18n\Time; ?>

<?= $this->extend('layouts/colaboradores'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid py-2">
	<div class="container">
		<div class="mb-3">
			<?php if ($artigo['fase_producao_id'] == '3'): ?>
				<?= $config['artigo_regras_narrar']; ?>
			<?php endif; ?>
			<?php if ($artigo['fase_producao_id'] == '4'): ?>
				<?= $config['artigo_regras_produzir']; ?>
			<?php endif; ?>
		</div>

		<div class="row g-3 justify-content-center">
			<div class="col-lg-8 col-sm-12">
			<h1 class="display-5 mb-3"><?= esc($artigo['titulo']); ?></h1>
			<div class="position-relative mb-2">
				<div class="py-2">
					<!-- <div class="mb-3">
							<?php //foreach($artigo['categorias'] as $categoria): ?>
								<span class="badge vl-bg-c m-1 p-1">
									<a href="<? //base_url() . 'site/artigos/'.$categoria['id']; ?>"><? //$categoria['nome']; ?></a>
								</span>
							<?php //endforeach; ?>
						</div> -->
					<div>
						<div><?= $artigo['texto_producao'] ?? ''; ?></div>
						<h4 class="mb-3">Referências:</h4>
						<p style="line-break:anywhere;"><?= nl2br(esc($artigo['referencias'] ?? '')); ?></p>
					</div>
				</div>
			</div>

			</div>
			<div class="col-lg-4 col-sm-12">
			<?php if ($historico !== NULL && !empty($historico)): ?>
				<div class="col-12 mb-3 mt-3">
					<!-- Chart START -->
					<div class="card border">
						<div class="">
							<div class="accordion" id="accordionHistorico">
								<div class="accordion-item border-0">
									<h2 class="accordion-header">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
											data-bs-target="#historicoList" aria-expanded="true"
											aria-controls="historicoList">
											Histórico do artigo:
										</button>
									</h2>
									<div id="historicoList" class="accordion-collapse collapse"
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
				</div>
			<?php endif; ?>

			<?php if (isset($artigo['id']) && $artigo['id'] !== null): ?>
				<div class="col-12 mb-3 mt-3">
					<div class="card border">
						<div class="">
							<div class="accordion" id="accordionHistoricoArtigo">
								<div class="accordion-item border-0">
									<h2 class="accordion-header">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
											data-bs-target="#comentarios" aria-expanded="true" aria-controls="comentarios">
											Comentários do artigo:
										</button>
									</h2>
									<div id="comentarios" class="accordion-collapse collapse"
										data-bs-parent="#accordionHistoricoArtigo">
										<div class="accordion-body">
											<div class="row">
												<div class="col-12 text-center">
													<button class="btn btn-primary mb-3 col-md-6 col-lg-7"
														id="btn-comentarios" type="button">Atualizar
														Comentários</button>
												</div>
												<div class="col-12 d-flex justify-content-center">

													<div class="col-12 div-comentarios">
														<div class="col-12">
															<div class="mb-3">
																<input type="hidden" id="id_comentario"
																	name="id_comentario" />
																<textarea id="comentario" name="comentario"
																	class="form-control" rows="5"
																	placeholder="Digite seu comentário aqui"></textarea>
															</div>
															<div class="mb-3 text-center">
																<button class="btn btn-primary mb-3 col-md-6 col-lg-7"
																	id="enviar-comentario" type="button">Enviar
																	comentário</button>
															</div>
														</div>
														<div class="card m-3 div-list-comentarios"></div>
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

			<?php if ($pode_narrar): ?>
				<div class="col-12">
					<!-- Chart START -->
					<div class="card border">
						<!-- Card body -->
						<div class="card-body">
							<!-- Form START -->
							<form class="w-100" novalidate="yes" method="post" id="artigo_form"
								enctype='multipart/form-data'>
								<div class="mb-3">
									<label for="audio">Arquivo de áudio</label>
									<div class="custom-file">
										<input type="file" class="form-control" id="audio" name="audio" required
											aria-describedby="audio" accept=".mp3">
										<small class="">O arquivo precisa ser do formato .mp3</small>
									</div>
								</div>
								<div
									class="d-block mb-2 text-left <?= ($artigo['arquivo_audio'] == NULL) ? ('d-none') : (''); ?> player">
									<audio controls class="w-100 rounded-3 bg-primary audioplayer">
										<source
											src="<?= ($artigo['arquivo_audio'] != NULL) ? (esc($artigo['arquivo_audio'], 'attr')) : (''); ?>"
											type="audio/mp3" class="source-player">
									</audio>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="card border mt-3">
					<div class="card-body">
						<h5 class="card-title">Submeter para produção</h5>
						<div class="d-grid">
							<button type="button" class="btn btn-primary" data-bs-toggle="modal"
								data-bs-target="#modalSubmeter">Enviar para produção</button>
						</div>
					</div>
				</div>
			<?php endif; ?>

			<?php if ($pode_produzir): ?>
				<div class="col-12">
					<!-- Chart START -->
					<div class="card border">
						<!-- Card body -->
						<div class="card-body">
							<!-- Form START -->
							<audio controls class="w-100 rounded-3 bg-primary">
								<source src="<?= esc($artigo['arquivo_audio'], 'attr'); ?>" type="audio/mpeg">
							</audio>
							<form class="w-100" novalidate="yes" method="post" id="artigo_form">
								<div class="mb-3">
									<label for="username">Link do Vídeo no YouTube</label>
									<div class="input-group">
										<span class="input-group-text"><i class="fas fa-link"></i></span>
										<input type="text" class="form-control" id="video_link" name="video_link"
											placeholder="Link do Vídeo no YouTube" value="<?= esc($artigo['link_produzido'], 'attr'); ?>"
											required>
									</div>
								</div>
								<div class="mb-3">
									<label for="username">Link do Shorts no YouTube</label>
									<div class="input-group">
										<span class="input-group-text"><i class="fas fa-link"></i></span>
										<input type="text" class="form-control" id="shorts_link"
											value="<?= esc($artigo['link_shorts'], 'attr'); ?>" name="shorts_link"
											placeholder="Link do Shorts no YouTube" required>
									</div>
								</div>
								<div class="d-flex justify-content-center">
									<button class="btn btn-primary btn-lg btn-block mb-3" id="enviar_artigo"
										type="button">Salvar produção</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="card border mt-3">
					<div class="card-body">
						<h5 class="card-title">Submeter para publicação</h5>
						<div class="d-grid">
							<button type="button" class="btn btn-primary" data-bs-toggle="modal"
								data-bs-target="#modalSubmeter">Enviar para publicação</button>
						</div>
					</div>
				</div>

			<?php endif; ?>

			<?php if ($pode_publicar): ?>
				<div class="col-12">
					<!-- Chart START -->
					<div class="card border">
						<!-- Card body -->
						<div class="card-body">
							<div class="d-grid gap-2 mb-3">
								<a href="<?= esc($artigo['link_produzido'], 'attr'); ?>" target="_blank"
									class="btn btn-secondary btn-lg">Vídeo do Youtube</a>
								<a href="<?= esc($artigo['link_shorts'], 'attr'); ?>" target="_blank"
									class="btn btn-secondary btn-lg">Vídeo do Shorts</a>
								<a href="<?= site_url('colaboradores/artigos/cadastrar/') . $artigo['id']; ?>"
									target="_blank" class="btn btn-danger btn-lg">Editar artigo</a>
							</div>
							<form class="needs-validation w-100" id="artigo_form" novalidate="yes" method="post">
								<div class="mb-3">
									<label for="tags_video_youtube">Tags do Vídeo no YouTube</label>
									<div class="input-group">
										<input type="text" class="form-control" id="tags_video_youtube"
											name="tags_video_youtube" placeholder="Tags do Vídeo" required>
									</div>
									<small class="form-text text-muted">Coloque # na frente da tag e separe as tags com espaço.</small>
								</div>

								<div class="d-flex justify-content-center mb-3">
									<button class="btn btn-primary mt-2" id="btn-tags" type="button">Gerar
										descrição do vídeo</button>
								</div>

								<div class="mb-3 div-descricao collapse">
									<div class="input-group">
										<textarea class="form-control text-descricao" style="height:100px;"></textarea>
									</div>
								</div>

								<div class="mb-3">
									<label for="username">Link do Vídeo no YouTube (Visão Libertária)</label>
									<div class="input-group">
										<span class="input-group-text"><i class="fas fa-link"></i></span>
										<input type="text" class="form-control" id="link_video_youtube"
											name="link_video_youtube" value="<?= esc($artigo['link_video_youtube'], 'attr'); ?>"
											placeholder="Link do Vídeo no Canal do Visão Libertária" required>
									</div>
								</div>
								<div class="d-flex justify-content-center">
									<button class="btn btn-primary btn-lg btn-block mb-3" id="enviar_artigo"
										type="button">Salvar publicação</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="card border mt-3">
					<div class="card-body">
						<h5 class="card-title">Submeter para pagamento</h5>
						<div class="d-grid">
							<button type="button" class="btn btn-primary" data-bs-toggle="modal"
								data-bs-target="#modalSubmeter">Enviar para pagamento</button>
						</div>
					</div>
				</div>
			<?php endif; ?>

			<?php if ($pode_reverter_ou_descartar): ?>
				<div class="card border mt-3">
					<div class="card-body">
						<h5 class="card-title mb-3">Ações avançadas</h5>
						<div class="d-grid gap-2">
							<button type="button" class="btn btn-warning" data-bs-toggle="modal"
								data-bs-target="#modalReverterArtigo">Reverter artigo</button>
							<button type="button" class="btn btn-danger" data-bs-toggle="modal"
								data-bs-target="#modalDescartarArtigo">Descartar artigo</button>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<button type="button" id="btn-topo" class="btn btn-primary position-fixed d-none"
	style="right: 20px; bottom: 20px; z-index: 1050; width: 44px; height: 44px; border-radius: 50%; padding: 0; font-size: 1.2rem; line-height: 1;"
	aria-label="Voltar ao topo" title="Voltar ao topo">
	<i class="fas fa-chevron-up" aria-hidden="true"></i>
</button>

<div class="modal fade" id="modalSubmeter" tabindex="-1" aria-labelledby="modalSubmeterLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalSubmeterLabel">Confirmar submissão</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
			</div>
			<div class="modal-body">
				<p class="mb-2">Ao submeter, confirmo que:</p>
				<?php if ($pode_narrar): ?>
					<div class="form-check"><input class="form-check-input confirm-submeter-check" type="checkbox" id="submeter1"><label class="form-check-label" for="submeter1">O áudio está com boa qualidade, sem ruído de fundo e com bom volume.</label></div>
					<div class="form-check"><input class="form-check-input confirm-submeter-check" type="checkbox" id="submeter2"><label class="form-check-label" for="submeter2">O áudio não está estourado e foi editado conforme o projeto.</label></div>
					<div class="form-check"><input class="form-check-input confirm-submeter-check" type="checkbox" id="submeter3"><label class="form-check-label" for="submeter3">A dicção do narrador está boa e não há erros na narração.</label></div>
				<?php elseif ($pode_produzir): ?>
					<div class="form-check"><input class="form-check-input confirm-submeter-check" type="checkbox" id="submeter1"><label class="form-check-label" for="submeter1">O vídeo foi enviado ao YouTube como não listado.</label></div>
					<div class="form-check"><input class="form-check-input confirm-submeter-check" type="checkbox" id="submeter2"><label class="form-check-label" for="submeter2">Não há aviso de direitos autorais e a qualidade está adequada.</label></div>
					<div class="form-check"><input class="form-check-input confirm-submeter-check" type="checkbox" id="submeter3"><label class="form-check-label" for="submeter3">O shorts está no formato correto e possui menos de 60 segundos.</label></div>
				<?php elseif ($pode_publicar): ?>
					<div class="form-check"><input class="form-check-input confirm-submeter-check" type="checkbox" id="submeter1"><label class="form-check-label" for="submeter1">O vídeo foi publicado com a descrição correta.</label></div>
					<div class="form-check"><input class="form-check-input confirm-submeter-check" type="checkbox" id="submeter2"><label class="form-check-label" for="submeter2">A thumb foi produzida e a imagem atualizada no site.</label></div>
					<div class="form-check"><input class="form-check-input confirm-submeter-check" type="checkbox" id="submeter3"><label class="form-check-label" for="submeter3">As tags de tema foram adicionadas corretamente.</label></div>
				<?php endif; ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-primary" id="btn-confirmar-submeter" disabled>Confirmar envio</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modalReverterArtigo" tabindex="-1" aria-labelledby="modalReverterArtigoLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalReverterArtigoLabel">Confirmar reversão</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
			</div>
			<div class="modal-body">
				<div class="form-check"><input class="form-check-input confirm-reverter-check" type="checkbox" id="reverter1"><label class="form-check-label" for="reverter1">O artigo será revertido para a etapa anterior para ajustes.</label></div>
				<div class="form-check"><input class="form-check-input confirm-reverter-check" type="checkbox" id="reverter2"><label class="form-check-label" for="reverter2">Deixei um comentário com o motivo da reversão.</label></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-warning" id="btn-confirmar-reverter" disabled>Reverter artigo</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modalDescartarArtigo" tabindex="-1" aria-labelledby="modalDescartarArtigoLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalDescartarArtigoLabel">Confirmar descarte</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
			</div>
			<div class="modal-body">
				<div class="form-check"><input class="form-check-input confirm-descartar-check" type="checkbox" id="descarte1"><label class="form-check-label" for="descarte1">O texto será descartado e não seguirá a trilha de produção.</label></div>
				<div class="form-check"><input class="form-check-input confirm-descartar-check" type="checkbox" id="descarte2"><label class="form-check-label" for="descarte2">Deixei um comentário com o motivo do descarte.</label></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-danger" id="btn-confirmar-descartar" disabled>Descartar artigo</button>
			</div>
		</div>
	</div>
</div>

<script>
	function tratarErroAjax(xhr, status, error) {
		let mensagem = 'Não foi possível concluir a operação. Tente novamente.';
		if (xhr && xhr.responseJSON && xhr.responseJSON.mensagem) {
			mensagem = xhr.responseJSON.mensagem;
		} else if (error) {
			mensagem = `Erro de comunicação: ${error}`;
		}
		popMessage('ATENÇÃO', mensagem, TOAST_STATUS.DANGER);
	}

	function toggleBotaoTopo() {
		if (window.scrollY > 300) {
			$('#btn-topo').removeClass('d-none');
		} else {
			$('#btn-topo').addClass('d-none');
		}
	}

	$(window).on('scroll', toggleBotaoTopo);
	toggleBotaoTopo();

	$('#btn-topo').on('click', function () {
		window.scrollTo({ top: 0, behavior: 'smooth' });
	});

	function toggleBotaoConfirmacao(selectorChecks, selectorBotao) {
		const habilitar = $(selectorChecks).length > 0 && $(selectorChecks).toArray().every(function (el) { return el.checked; });
		$(selectorBotao).prop('disabled', !habilitar);
	}

	$(document).on('change', '.confirm-submeter-check', function () {
		toggleBotaoConfirmacao('.confirm-submeter-check', '#btn-confirmar-submeter');
	});
	$(document).on('change', '.confirm-reverter-check', function () {
		toggleBotaoConfirmacao('.confirm-reverter-check', '#btn-confirmar-reverter');
	});
	$(document).on('change', '.confirm-descartar-check', function () {
		toggleBotaoConfirmacao('.confirm-descartar-check', '#btn-confirmar-descartar');
	});

	<?php if ($pode_narrar): ?>
		audio.onchange = evt => {
			const [file] = audio.files;
			if (file) {
				form = new FormData(artigo_form);
				$.ajax({
					url: "<?= site_url('colaboradores/artigos/salvarAudio/') . $artigo['id']; ?>",
					method: "POST",
					data: form,
					processData: false,
					contentType: false,
					cache: false,
					dataType: "json",
					beforeSend: function () { $('#modal-loading').show(); },
					complete: function () { $('#modal-loading').hide(); },
					success: function (retorno) {
						if (retorno.status) {
							popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
							$('.player').removeClass('d-none');
							$('.source-player').attr('src', retorno.parametros.audio);
							var audio = $(".audioplayer")[0];
							audio.pause();
							audio.load();
							audio.play();
						} else {
							popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
						}
					},
					error: tratarErroAjax
				});
			}
		}
	<?php endif; ?>

	<?php if ($pode_produzir): ?>
		$('#enviar_artigo').on('click', function () {
			form = new FormData(artigo_form);
			$.ajax({
				url: "<?= site_url('colaboradores/artigos/produzir') . (($artigo['id'] == NULL) ? ('') : ('/' . $artigo['id'])); ?>",
				method: "POST",
				data: form,
				processData: false,
				contentType: false,
				cache: false,
				dataType: "json",
				beforeSend: function () { $('#modal-loading').show(); },
				complete: function () { $('#modal-loading').hide() },
				success: function (retorno) {
					if (retorno.status) {
						popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
					} else {
						popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
					}
				},
				error: tratarErroAjax
			});
		})
	<?php endif; ?>

	<?php if ($pode_publicar): ?>
		$("#btn-tags").on("click", function () {
			setTags();
		});

		function setTags() {
			form = new FormData();
			form.append('tags', $('#tags_video_youtube').val());
			$.ajax({
				url: "<?php echo base_url('colaboradores/artigos/geraDescricaoVideo/' . $artigo['id']); ?>",
				method: "POST",
				data: form,
				processData: false,
				contentType: false,
				cache: false,
				dataType: "json",
				beforeSend: function () { $('#modal-loading').show(); },
				complete: function () { $('#modal-loading').hide() },
				success: function (retorno) {
					if (retorno.status == true) {
						$('.text-descricao').html(retorno.descricao);
						$('.div-descricao').show();
						$('.text-descricao').select();
						document.execCommand('copy');
					} else {
						popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
					}
				},
				error: tratarErroAjax
			});
		}

		$('#enviar_artigo').on('click', function () {
			form = new FormData(artigo_form);
			$.ajax({
				url: "<?= site_url('colaboradores/artigos/publicar') . (($artigo['id'] == NULL) ? ('') : ('/' . $artigo['id'])); ?>",
				method: "POST",
				data: form,
				processData: false,
				contentType: false,
				cache: false,
				dataType: "json",
				beforeSend: function () { $('#modal-loading').show(); },
				complete: function () { $('#modal-loading').hide() },
				success: function (retorno) {
					if (retorno.status) {
						popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
					} else {
						popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
					}
				},
				error: tratarErroAjax
			});
		})
	<?php endif; ?>

	<?php if ($pode_reverter_ou_descartar): ?>
		$('#btn-confirmar-descartar').on('click', function () {
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
						$('#btn-confirmar-descartar').prop('disabled', true);

						setTimeout(function () {
							window.location.href = "<?= site_url('colaboradores/artigos/artigosColaborar/'); ?>";
						}, 1500);

					} else {
						popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
					}
				},
				error: tratarErroAjax
			});
		});

		$('#btn-confirmar-reverter').on('click', function () {
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
						$('#btn-confirmar-reverter').prop('disabled', true);

						setTimeout(function () {
							window.location.href = "<?= site_url('colaboradores/artigos/artigosColaborar/'); ?>";
						}, 1500);

					} else {
						popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
					}
				},
				error: tratarErroAjax
			});
		});


	<?php endif; ?>


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
				const temComentarios = $('.div-list-comentarios')
					.find('p[class^="comentario-"], p[class*=" comentario-"]').length > 0;
				const comentariosEl = document.getElementById('comentarios');
				if (comentariosEl && window.bootstrap && bootstrap.Collapse) {
					const collapseComentarios = bootstrap.Collapse.getOrCreateInstance(comentariosEl, { toggle: false });
					if (temComentarios) {
						collapseComentarios.show();
					} else {
						collapseComentarios.hide();
					}
				}
			},
			error: tratarErroAjax
		});
	}

	$("#enviar-comentario").on("click", function () {
		var textoComentario = ($('#comentario').val() || '').trim();
		if (textoComentario === '') {
			popMessage('ATENÇÃO', 'É necessário preencher o comentário antes de enviar.', TOAST_STATUS.DANGER);
			return;
		}
		form = new FormData();
		form.append('comentario', $('#comentario').val());
		if ($('#id_comentario').val() == '') {
			form.append('metodo', 'inserir');
		} else {
			form.append('metodo', 'alterar');
			form.append('id_comentario', $('#id_comentario').val());
		}

		$.ajax({
			url: "<?php echo base_url('colaboradores/artigos/comentarios/' . $artigo['id']); ?>",
			method: "POST",
			data: form,
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
			},
			error: tratarErroAjax
		});
	});

	function excluirComentario(id_comentario) {
		form = new FormData();
		form.append('id_comentario', id_comentario);
		form.append('metodo', 'excluir');

		$.ajax({
			url: "<?php echo base_url('colaboradores/artigos/comentarios/' . $artigo['id']); ?>",
			method: "POST",
			data: form,
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
			},
			error: tratarErroAjax
		});
	}

	$('#btn-confirmar-submeter').on('click', function () {
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
					$('#btn-confirmar-submeter').prop('disabled', true);

					setTimeout(function () {
						window.location.href = "<?= site_url('colaboradores/artigos/artigosColaborar/'); ?>";
					}, 1500);

				} else {
					popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
				}
			},
			error: tratarErroAjax
		});
	});
</script>
<?= $this->endSection(); ?>