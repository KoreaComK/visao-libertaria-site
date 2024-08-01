<?php use CodeIgniter\I18n\Time; ?>

<?= $this->extend('layouts/colaboradores'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid py-3">
	<div class="container d-flex justify-content-center">
		<div class="col-lg-8">
			<h1 class="display-2"><?= $artigo['titulo']; ?></h1>
			<div class="position-relative mb-3">
				<div class="pt-3 pb-3">
					<!-- <div class="mb-3">
							<?php //foreach($artigo['categorias'] as $categoria): ?>
								<span class="badge vl-bg-c m-1 p-1">
									<a href="<? //base_url() . 'site/artigos/'.$categoria['id']; ?>"><? //$categoria['nome']; ?></a>
								</span>
							<?php //endforeach; ?>
						</div> -->
					<div>
						<div><?= str_replace("\n", '<br/>', $artigo['texto_producao']); ?></div>
						<h4 class="mb-3">Referências:</h4>
						<p><?= str_replace("\n", '<br/>', $artigo['referencias']); ?></p>
					</div>
				</div>
			</div>

			<?php if ($historico !== NULL && !empty($historico)): ?>
				<div class="col-12 mb-3">
					<!-- Chart START -->
					<div class="card border">
						<div class="card-body mb-5">
							<h6>Histórico do artigo:</h6>
							<ul class="list-group fw-light">
								<?php foreach ($historico as $h): ?>
									<li class="list-group-item p-1 border-0">
										<small>
											<?= $h['apelido']; ?>
											<?= $h['acao']; ?>
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
			<?php endif; ?>

			<?php if (isset($artigo['id']) && $artigo['id'] !== null): ?>
				<div class="col-12 mb-3 mt-3">
					<!-- Chart START -->
					<div class="card border">
						<div class="card-body">
							<div class="row">
								<div class="col-12 text-center">
									<button class="btn btn-primary mb-3 col-md-3 mr-3 ml-3" id="btn-comentarios"
										type="button">Atualizar
										Comentários</button>
								</div>
								<div class="col-12 d-flex justify-content-center">

									<div class="col-12 div-comentarios">
										<div class="col-12">
											<div class="mb-3">
												<input type="hidden" id="id_comentario" name="id_comentario" />
												<textarea id="comentario" name="comentario" class="form-control" rows="5"
													placeholder="Digite seu comentário aqui"></textarea>
											</div>
											<div class="mb-3 text-center">
												<button class="btn btn-primary mb-3 col-md-3 mr-3 ml-3"
													id="enviar-comentario" type="button">Enviar comentário</button>
											</div>
										</div>
										<div class="card m-3 div-list-comentarios"></div>
									</div>
								</diV>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>

			<?php if ($permitido && $artigo['fase_producao_id'] == '3' && $artigo['marcado_colaboradores_id'] == $_SESSION['colaboradores']['id']): ?>
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
											src="<?= ($artigo['arquivo_audio'] != NULL) ? ($artigo['arquivo_audio']) : (''); ?>"
											type="audio/mp3" class="source-player">
									</audio>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="card border mt-4">
					<div class="card-body">
						<h5 class="card-title">Submeter para produção</h5>
						<p class="card-text">Ao submeter para produção confirmo que:</p>
						<div class="form-check form-switch">
							<input class="form-check-input" type="checkbox" id="aceito1">
							<label class="form-check-label" for="aceito1">
								O áudio está com uma boa qualidade, está sem ruído de fundo e com um bom volume.
							</label>
						</div>
						<div class="form-check form-switch">
							<input class="form-check-input" type="checkbox" id="aceito2">
							<label class="form-check-label" for="aceito2">
								O áudio não está estourado, e foi editado para atender a qualidade do projeto.
							</label>
						</div>
						<div class="form-check form-switch">
							<input class="form-check-input" type="checkbox" id="aceito3">
							<label class="form-check-label" for="aceito3">
								A dicção do narrador está boa, e não há erros na narração
							</label>
						</div>
						<div class="text-end">
							<button type="button" disabled="" class="btn btn-primary mt-2 submeter-revisao">Enviar para
								produção</button>
						</div>
					</div>
				</div>
			<?php endif; ?>

			<?php if ($permitido && $artigo['fase_producao_id'] == '4' && $artigo['marcado_colaboradores_id'] == $_SESSION['colaboradores']['id']): ?>
				<div class="col-12">
					<!-- Chart START -->
					<div class="card border">
						<!-- Card body -->
						<div class="card-body">
							<!-- Form START -->
							<audio controls class="w-100 rounded-3 bg-primary">
								<source src="<?= $artigo['arquivo_audio']; ?>" type="audio/mpeg">
							</audio>
							<form class="w-100" novalidate="yes" method="post" id="artigo_form">
								<div class="mb-3">
									<label for="username">Link do Vídeo no YouTube</label>
									<div class="input-group">
										<span class="input-group-text"><i class="fas fa-link"></i></span>
										<input type="text" class="form-control" id="video_link" name="video_link"
											placeholder="Link do Vídeo no YouTube" value="<?= $artigo['link_produzido']; ?>"
											required>
									</div>
								</div>
								<div class="mb-3">
									<label for="username">Link do Shorts no YouTube</label>
									<div class="input-group">
										<span class="input-group-text"><i class="fas fa-link"></i></span>
										<input type="text" class="form-control" id="shorts_link"
											value="<?= $artigo['link_shorts']; ?>" name="shorts_link"
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
				<div class="card border mt-4">
					<div class="card-body">
						<h5 class="card-title">Submeter para publicação</h5>
						<p class="card-text">Ao submeter para publicação confirmo que:</p>
						<div class="form-check form-switch">
							<input class="form-check-input" type="checkbox" id="aceito1">
							<label class="form-check-label" for="aceito1">
								O vídeo foi upado no YouTube e está como não listado.
							</label>
						</div>
						<div class="form-check form-switch">
							<input class="form-check-input" type="checkbox" id="aceito2">
							<label class="form-check-label" for="aceito2">
								Não possui nenhum tipo de aviso de direito autoral, a qualidade está em
								alta
								definição e o áudio está com um bom volume.
							</label>
						</div>
						<div class="form-check form-switch">
							<input class="form-check-input" type="checkbox" id="aceito3">
							<label class="form-check-label" for="aceito3">
								O shorts está com o tamanho correto para ser visto no celular e
								apresenta menos
								de 60 segundos de duração.
							</label>
						</div>
						<div class="text-end">
							<button type="button" disabled="" class="btn btn-primary mt-2 submeter-revisao">Enviar para
								produção</button>
						</div>
					</div>
				</div>

			<?php endif; ?>

			<?php if ($permitido && $artigo['fase_producao_id'] == '5'): ?>
				<div class="col-12">
					<!-- Chart START -->
					<div class="card border">
						<!-- Card body -->
						<div class="card-body">
							<div class="row">
								<div class="col-4">
									<div class="d-flex justify-content-center">
										<a href="<?= $artigo['link_produzido']; ?>" target="_blank"
											class="btn btn-secondary btn-lg btn-block mb-3">Vídeo do Youtube</a>
									</div>
								</div>
								<div class="col-4">
									<div class="d-flex justify-content-center">
										<a href="<?= $artigo['link_shorts']; ?>" target="_blank"
											class="btn btn-secondary btn-lg btn-block mb-3">Vídeo do Shorts</a>
									</div>
								</div>
								<div class="col-4">
									<div class="d-flex justify-content-center">
										<a href="<?= site_url('colaboradores/artigos/cadastrar/') . $artigo['id']; ?>" target="_blank"
											class="btn btn-danger btn-lg btn-block mb-3">Editar artigo</a>
									</div>
								</div>
							</div>
							<form class="needs-validation w-100" id="artigo_form" novalidate="yes" method="post">
								<div class="mb-3">
									<label for="username">Tags do Vídeo no YouTube <span class="text-muted">(colocar #
											na frente
											da tag, e separá-las com espaço)</span></label>
									<div class="input-group">
										<input type="text" class="form-control" id="tags_video_youtube"
											name="tags_video_youtube" placeholder="Tags do Vídeo" required>
									</div>
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
											name="link_video_youtube" value="<?= $artigo['link_video_youtube']; ?>"
											placeholder="Link do Vídeo no Canal do Visão Libertária" required>
									</div>
								</div>
								<div class="col-12 mt-4">
									<div class="mb-3">
										<!-- Image -->
										<div class="row align-items-center mb-2">
											<div class="col-4 col-md-2">
												<div class="position-relative">
													<img class="img-fluid" id="preview" src="<?= $artigo['imagem']; ?>" />
												</div>
											</div>
											<div class="col-sm-8 col-md-10 position-relative">
												<h6 class="my-2">Imagem de capa</h6>
												<label class="w-100" style="cursor:pointer;">
													<span>
														<input class="form-control stretched-link" type="file" name="imagem"
															id="imagem" accept="image/gif, image/jpeg, image/png">
													</span>
												</label>
												<p class="small mb-0 mt-2"><b>Aviso:</b> Apenas JPG, JPEG e PNG. Sugerimos
													tamanhos de
													1.280 x 720. Tamanhos diferentes da proporção 16:9 serão cortadas.</p>
											</div>
										</div>
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
				<div class="card border mt-4">
					<div class="card-body">
						<h5 class="card-title">Submeter para pagamento</h5>
						<p class="card-text">Ao submeter para pagamento confirmo que:</p>
						<div class="form-check form-switch">
							<input class="form-check-input" type="checkbox" id="aceito1">
							<label class="form-check-label" for="aceito1">
								O vídeo foi upado no YouTube e está com a descrição correta.
							</label>
						</div>
						<div class="form-check form-switch">
							<input class="form-check-input" type="checkbox" id="aceito2">
							<label class="form-check-label" for="aceito2">
								Foi feito a thumb do vídeo e a imagem foi atualizada no site.
							</label>
						</div>
						<div class="form-check form-switch">
							<input class="form-check-input" type="checkbox" id="aceito3">
							<label class="form-check-label" for="aceito3">
								Foram colocadas tags sobre temas que são abordados no vídeo.
							</label>
						</div>
						<div class="text-end">
							<button type="button" disabled="" class="btn btn-primary mt-2 submeter-revisao">Enviar para
								pagamento</button>
						</div>
					</div>
				</div>
			<?php endif; ?>

			<?php if ($permitido && $artigo['marcado_colaboradores_id'] == $_SESSION['colaboradores']['id']): ?>


				<div class="card border mt-4">
					<div class="card-body">
						<h5 class="card-title">Reverter artigo</h5>
						<p class="card-text">Ao reverter o artigo confirmo os seguintes termos:</p>
						<div class="form-check form-switch">
							<input class="form-check-input" type="checkbox" id="reverter1">
							<label class="form-check-label" for="reverter1">
								O artigo será revertido para a etapa anterior para ajustes.
							</label>
						</div>
						<div class="form-check form-switch">
							<input class="form-check-input" type="checkbox" id="reverter2">
							<label class="form-check-label" for="reverter2">
								Deixei um comentário no artigo informando o motivo da reversão para o escritor.
							</label>
						</div>
						<div class="text-center">
							<button type="button" disabled="" class="btn btn-warning mt-2 reverter-artigo">Reverter
								artigo</button>
						</div>
					</div>
				</div>

				<div class="card border mt-4">
					<div class="card-body">
						<h5 class="card-title">Descartar artigo</h5>
						<p class="card-text">Ao descartar o artigo confirmo os seguintes termos:</p>
						<div class="form-check form-switch">
							<input class="form-check-input" type="checkbox" id="descarte1">
							<label class="form-check-label" for="descarte1">
								O texto será descartado e não irá mais seguir a trilha de produção.
							</label>
						</div>
						<div class="form-check form-switch">
							<input class="form-check-input" type="checkbox" id="descarte2">
							<label class="form-check-label" for="descarte2">
								Deixei um comentário no artigo informando o motivo do descarte para o escritor.
							</label>
						</div>
						<div class="text-start">
							<button type="button" disabled="" class="btn btn-danger mt-2 descartar-artigo">Descartar
								artigo</button>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
<script>

	$('#aceito1').on('change', function (e) {
		submeterRevisao();
	});
	$('#aceito2').on('change', function (e) {
		submeterRevisao();
	});
	$('#aceito3').on('change', function (e) {
		submeterRevisao();
	});

	<?php if ($artigo['fase_producao_id'] == '3'): ?>

		function submeterRevisao() {
			if ($('#aceito1').is(':checked') && $('#aceito2').is(':checked') && $('#aceito3').is(':checked')) {
				$('.submeter-revisao').removeAttr('disabled');
			} else {
				$('.submeter-revisao').attr('disabled', '');
			}
		}

		audio.onchange = evt => {
			const [file] = audio.files
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
					}
				});
			}
		}

	<?php endif; ?>

	<?php if ($artigo['fase_producao_id'] == '4'): ?>

		function submeterRevisao() {
			if ($('#aceito1').is(':checked') && $('#aceito2').is(':checked') && $('#aceito3').is(':checked')) {
				$('.submeter-revisao').removeAttr('disabled');
			} else {
				$('.submeter-revisao').attr('disabled', '');
			}
		}

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
				}
			});
		})
	<?php endif; ?>

	<?php if ($artigo['fase_producao_id'] == '5'): ?>

		function submeterRevisao() {
			if ($('#aceito1').is(':checked') && $('#aceito2').is(':checked') && $('#aceito3').is(':checked')) {
				$('.submeter-revisao').removeAttr('disabled');
			} else {
				$('.submeter-revisao').attr('disabled', '');
			}
		}

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
				}
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
				}
			});
		})

		imagem.onchange = evt => {
			const [file] = imagem.files
			if (file) {
				preview.src = URL.createObjectURL(file);
				form = new FormData(artigo_form);
				$.ajax({
					url: "<?= site_url('colaboradores/artigos/salvarImagem/') . $artigo['id']; ?>",
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
						} else {
							popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
						}
					}
				});
			}
		}
	<?php endif; ?>

	<?php if ($artigo['marcado_colaboradores_id'] == $_SESSION['colaboradores']['id']): ?>

		$('#descarte1').on('change', function (e) {
			descartarArtigo();
		});
		$('#descarte2').on('change', function (e) {
			descartarArtigo();
		});

		function descartarArtigo() {
			if ($('#descarte1').is(':checked') && $('#descarte2').is(':checked')) {
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
							window.location.href = "<?= site_url('colaboradores/artigos/artigosColaborar/'); ?>";
						}, 1500);

					} else {
						popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
					}
				}
			});
		});

		$('#reverter1').on('change', function (e) {
			reverterArtigo();
		});
		$('#reverter2').on('change', function (e) {
			reverterArtigo();
		});

		function reverterArtigo() {
			if ($('#reverter1').is(':checked') && $('#reverter2').is(':checked')) {
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
							window.location.href = "<?= site_url('colaboradores/artigos/artigosColaborar/'); ?>";
						}, 1500);

					} else {
						popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
					}
				}
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
			}
		});
	}

	$("#enviar-comentario").on("click", function () {
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
			}
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
					getComentarios()
				} else {
					popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
				}
			}
		});
	}

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
						window.location.href = "<?= site_url('colaboradores/artigos/artigosColaborar/'); ?>";
					}, 1500);

				} else {
					popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
				}
			}
		});
	});
</script>
<?= $this->endSection(); ?>