<div?php use CodeIgniter\I18n\Time; ?>

	<?= $this->extend('layouts/colaboradores'); ?>

	<?= $this->section('content'); ?>

	<div class="container-fluid py-3">
		<div class="container d-flex justify-content-center">
			<div class="col-lg-8">
				<h1 class="display-2 text-black"><?= $artigo['titulo']; ?></h1>
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

				<?php if ($artigo['fase_producao_id'] == '3' && $artigo['marcado_colaboradores_id'] == $_SESSION['colaboradores']['id']): ?>
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

				<?php if ($artigo['fase_producao_id'] == '4' && $artigo['marcado_colaboradores_id'] == $_SESSION['colaboradores']['id']): ?>
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
												placeholder="Link do Vídeo no YouTube" value="<?= $artigo['link_produzido']; ?>" required>
										</div>
									</div>
									<div class="mb-3">
										<label for="username">Link do Shorts no YouTube</label>
										<div class="input-group">
											<span class="input-group-text"><i class="fas fa-link"></i></span>
											<input type="text" class="form-control" id="shorts_link" value="<?= $artigo['link_shorts']; ?>"  name="shorts_link"
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

				<?php if ($artigo['fase_producao_id'] == '5'): ?>
					<div class="d-block mb-5 text-left">
						<form class="needs-validation w-100" id="form-publicacao" novalidate="yes" method="post">
							<div class="mb-3">
								<label for="username">Link do Vídeo no YouTube (Visão Libertária)</label>
								<div class="input-group">
									<span class="input-group-text"><i class="fas fa-link"></i></span>
									<input type="text" class="form-control" id="link_video_youtube"
										name="link_video_youtube" placeholder="Link do Vídeo no Canal do Visão Libertária"
										required>
								</div>
							</div>
							<div class="row">
								<div class="d-flex col-12 justify-content-around">
									<button class="btn btn-danger mb-3 col-md-4 reverter" data-toggle="modal"
										data-target="#modalConfirmacao" type="button">Produção Ruim. Pedir
										por nova produção.</button>
									<button class="btn btn-danger mb-3 col-md-3 descartar" data-toggle="modal"
										data-target="#modalConfirmacao" type="button">
										Descartar artigo</button>
									<button class="btn btn-success mb-3 col-md-4 continuar" data-toggle="modal"
										data-target="#modalConfirmacao" type="button">Incluir Publicação.
										Solicitar pagamento.</button>
								</div>
							</div>
						</form>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<script>

		<?php if ($artigo['fase_producao_id'] == '3'): ?>

			$('#aceito1').on('change', function (e) {
				submeterRevisao();
			});
			$('#aceito2').on('change', function (e) {
				submeterRevisao();
			});
			$('#aceito3').on('change', function (e) {
				submeterRevisao();
			});

			function submeterRevisao() {
				if ($('#aceito1').is(':checked') && $('#aceito2').is(':checked') && $('#aceito3').is(':checked')) {
					$('.submeter-revisao').removeAttr('disabled');
				} else {
					$('.submeter-revisao').attr('disabled', '');
				}
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

			$('#aceito1').on('change', function (e) {
				submeterRevisao();
			});
			$('#aceito2').on('change', function (e) {
				submeterRevisao();
			});
			$('#aceito3').on('change', function (e) {
				submeterRevisao();
			});

			function submeterRevisao() {
				if ($('#aceito1').is(':checked') && $('#aceito2').is(':checked') && $('#aceito3').is(':checked')) {
					$('.submeter-revisao').removeAttr('disabled');
				} else {
					$('.submeter-revisao').attr('disabled', '');
				}
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
	</script>
	<?= $this->endSection(); ?>