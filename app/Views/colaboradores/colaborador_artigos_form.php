<?php

use CodeIgniter\I18n\Time;

?>
<?= $this->extend('layouts/colaboradores'); ?>

<?= $this->section('content'); ?>

<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">

<style>
	/* Set default font-family */
	#editor {
		font-family: 'roboto';
		font-size: 16px;
		height: 250px;
	}
</style>

<div class="container w-auto">
	<div class="row py-4">
		<div class="col-12">
			<h1 class="mb-0 h2"><?= $titulo; ?></h1>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<div class="mb-5">
				<?php if ($fase_producao == '1'): ?>
					<?= $config['artigo_regras_escrever']; ?>
				<?php endif; ?>
				<?php if ($fase_producao == '2'): ?>
					<?= $config['artigo_regras_revisar']; ?>
				<?php endif; ?>
				<?php if ($fase_producao == '3'): ?>
					<?= $config['artigo_regras_narrar']; ?>
				<?php endif; ?>
				<?php if ($fase_producao == '4'): ?>
					<?= $config['artigo_regras_produzir']; ?>
				<?php endif; ?>
			</div>
		</div>
		<div class="col-12">
			<!-- Chart START -->
			<div class="card border">
				<!-- Card body -->
				<div class="card-body">
					<!-- Form START -->
					<form class="w-100" novalidate="yes" method="post" id="artigo_form" enctype='multipart/form-data'>
						<!-- Main form -->
						<div class="row">
							<div class="col-12">
								<div class="mb-3">
									<label class="form-label" for="tipo_artigo">Tipo de artigo</label>
									<div class="input-group">
										<select class="form-control" name="tipo_artigo" id="tipo_artigo">
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
										<label class="form-label">
											<input type="text" class="form-control d-none" id="link" name="link"
												value="<?= (isset($pauta['link'])) ? ($pauta['link']) : (''); ?>">
											<a class="btn btn-sm btn-primary" href="<?= $pauta['link']; ?>"
												target="_blank">Acessar a notícia</a>
											<?= $pauta['titulo']; ?>
										</label>
										<small class="d-block text-danger aviso-pauta"></small>
									</div>
								<?php else: ?>
									<div class="mb-3">
										<label class="form-label" for="link">Link da Notícia</label>
										<div class="input-group">
											<div class="input-group-text"><i class="fas fa-link"></i></div>
											<input type="text" class="form-control" id="link"
												placeholder="Link da notícia para pauta" name="link"
												value="<?= (isset($artigo['link'])) ? ($artigo['link']) : (''); ?>">
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
								<!-- Post name -->
								<div class="mb-3">
									<label class="form-label" for="titulo">Título</label>
									<input type="text" class="form-control" id="titulo" name="titulo"
										placeholder="Título do artigo"
										value="<?= str_replace('"', "'", $artigo['titulo']); ?>">
									<small>O título deve ser chamativo. Deixe as palavras-chave em maiúsculo</small>
								</div>
							</div>
							<!-- Short description -->
							<div class="col-12">
								<div class="mb-3" for="gancho">
									<label class="form-label">Gancho </label>
									<textarea class="form-control" rows="3" placeholder="Texto curto antes da vinheta"
										id="gancho"
										name="gancho"><?= str_replace('"', "'", $artigo['gancho']); ?></textarea>
									<small>Máximo 255 caracteres.</small>
								</div>
							</div>

							<!-- Main toolbar -->
							<div class="col-md-12">
								<!-- Subject -->
								<div class="mb-3">
									<label class="form-label" for="texto">Corpo do artigo</label>
									<div class="rounded-3" id="editor">
									</div>
									<textarea id="texto_original" name="texto_original"
										class="d-none"><?= $artigo['texto_original']; ?></textarea>
									<div class="col-md-12 d-flex justify-content-between">
										<small class="ps-1">
											<span class="">Artigo deve ter entre
												<?= $config['artigo_tamanho_minimo']; ?> e
												<?= $config['artigo_tamanho_maximo']; ?> palavras.</span>
										</small>
										<small class="pe-1"> <span class="pull-right label label-default"
												id="count_message"></span></small>
									</div>
								</div>
							</div>

							<?php if (!$cadastro): ?>
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
							<?php endif; ?>

							<!-- Short description -->
							<div class="col-12">
								<div class="mb-3" for="referencias">
									<label class="form-label">Referências </label>
									<textarea class="form-control" rows="3"
										placeholder="Referências para embasar seu texto" id="referencias"
										name="referencias"><?= str_replace('"', "'", $artigo['referencias']); ?></textarea>
									<small>Todos os links utilizados para dar embasamento para escrever o artigo, menos
										a pauta.</small>
								</div>
							</div>

							<!--
						<div class="col-lg-7">
							
							<div class="mb-3">
								<label class="form-label">Tags</label>
								<textarea class="form-control" rows="1" placeholder="business, sports ..."
									data-np-intersection-state="visible"></textarea>
								<small>Maximum of 14 keywords. Keywords should all be in lowercase and separated
									by commas. e.g. javascript, react, marketing.</small>
							</div>
						</div>
						<div class="col-lg-5">
							
							<div class="mb-3">
								<label class="form-label">Category</label>
								<select class="form-select" aria-label="Default select example"
									data-np-intersection-state="visible">
									<option selected="">Lifestyle</option>
									<option value="1">Technology</option>
									<option value="2">Travel</option>
									<option value="3">Business</option>
									<option value="4">Sports</option>
									<option value="5">Marketing</option>
								</select>
							</div>
						</div>
						<div class="col-12">
							<div class="form-check mb-3">
								<input class="form-check-input" type="checkbox" value="" id="postCheck">
								<label class="form-check-label" for="postCheck">
									Make this post featured?
								</label>
							</div>
						</div> -->

							<div class="d-flex justify-content-center">
								<button class="btn btn-primary btn-lg btn-block mb-3" id="enviar_artigo"
									type="button">Salvar artigo</button>
							</div>
						</div>
					</form>
				</div>
			</div>
			<?php if (!$cadastro && $artigo['fase_producao_id'] == '1'): ?>
				<div class="card border mt-4">
					<div class="card-body">
						<h5 class="card-title">Submeter para revisão</h5>
						<p class="card-text">Ao submeter para revisão aceito os seguintes termos:</p>
						<div class="form-check form-switch">
							<input class="form-check-input" type="checkbox" id="aceito1">
							<label class="form-check-label" for="aceito1">
								Aceito o texto ser alterado parcial ou completamente para atender o padrão do Visão
								Libertária.
							</label>
						</div>
						<div class="form-check form-switch">
							<input class="form-check-input" type="checkbox" id="aceito2">
							<label class="form-check-label" for="aceito2">
								Entendo que não poderei mais descartar o texto após enviá-lo para a revisão.
							</label>
						</div>
						<div class="form-check form-switch">
							<input class="form-check-input" type="checkbox" id="aceito3">
							<label class="form-check-label" for="aceito3">
								Caso o texto esteja muito fora do padrão do projeto ele poderá ser descartado a qualquer
								momento.
							</label>
						</div>
						<div class="text-end">
							<button type="button" disabled="" class="btn btn-primary mt-2 submeter-revisao">Enviar para
								revisão</button>
						</div>
					</div>
				</div>
			<?php endif; ?>
			<?php if (!$cadastro && $artigo['fase_producao_id'] == '2'): ?>
				<div class="card border mt-4">
					<div class="card-body">
						<h5 class="card-title">Submeter para narração</h5>
						<p class="card-text">Ao submeter para narração aceito os seguintes termos:</p>
						<div class="form-check form-switch">
							<input class="form-check-input" type="checkbox" id="aceito1">
							<label class="form-check-label" for="aceito1">
								Foi revisado com atenção, tendo portanto uma visão libertária nítida, não se mostrando
								apoiadora de nenhum
								político ou do estado, sendo totalmente aderente as ideias do projeto.
							</label>
						</div>
						<div class="form-check form-switch">
							<input class="form-check-input" type="checkbox" id="aceito2">
							<label class="form-check-label" for="aceito2">
								Garanto que o texto não possui erros grosseiros de português, está bem escrito e com boa
								fluência para narração.
							</label>
						</div>
						<div class="form-check form-switch">
							<input class="form-check-input" type="checkbox" id="aceito3">
							<label class="form-check-label" for="aceito3">
								Usei a ferramenta <a class="btn-link" href="https://www.duplichecker.com/"
									target="_blank">Dupli Checker</a> e <a class="btn-link" href="https://www.zerogpt.com/"
									target="_blank">ZeroGPT</a>
								e em conjunto com a revisão não foram encontrados nenhum indício de plágio ou que o texto
								seja de IA.
							</label>
						</div>
						<div class="text-end">
							<button type="button" disabled="" class="btn btn-primary mt-2 submeter-revisao">Enviar para
								narração</button>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<script type="text/javascript">
	function contapalavras() {
		var texto = $("#texto_original").val().replaceAll('\n', " ");
		texto = texto.replace(/[0-9]/gi, "");
		var matches = texto.split(" ");
		number = matches.filter(function (word) {
			return word.length > 0;
		}).length;
		var s = "";
		if (number > 1) {
			s = 's'
		} else {
			s = '';
		}
		$('#count_message').html(number + " palavra" + s)
	}

	function verificaPautaEscrita() {
		form = new FormData(artigo_form);
		$.ajax({
			url: "<?= site_url('colaboradores/artigos/verificaPautaEscrita' . (($artigo['id'] == NULL) ? ('') : ('/' . $artigo['id']))); ?>",
			method: "POST",
			data: form,
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

	const Font = Quill.import('formats/font');
	Font.whitelist = ['roboto'];
	Quill.register(Font, true);
	const options = {
		modules: {
			toolbar: null,
		},
		placeholder: 'Escreva seu texto aqui.',
		theme: 'snow'
	};
	const quill = new Quill('#editor', options);
	quill.on('text-change', () => {
		$('#texto_original').html(quill.getText(0, quill.getLength()));
		contapalavras();
	});
	<?php if ($artigo['texto_original'] !== null): ?>
		quill.setContents([
			{ insert: "<?= preg_replace('/\s\s+/', '\n', $artigo['texto_original']); ?>" },
		])
	<?php endif; ?>

	$('#count_message').html('0 palavra');
	$(document).ready(function () {
		contapalavras();
		verificaPautaEscrita();
	})

	$('#link').on('change', function (e) {
		verificaPautaEscrita();
	});

	$('#enviar_artigo').on('click', function () {
		form = new FormData(artigo_form);
		$.ajax({
			<?php if ($artigo['fase_producao_id'] == '1'): ?>
							url: "<?= site_url('colaboradores/artigos/salvar') . (($artigo['id'] == NULL) ? ('') : ('/' . $artigo['id'])); ?>",
			<?php elseif ($artigo['fase_producao_id'] == '2'): ?>
							url: "<?= site_url('colaboradores/artigos/revisar') . (($artigo['id'] == NULL) ? ('') : ('/' . $artigo['id'])); ?>",
			<?php endif; ?>
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

					<?php if ($cadastro): ?>
						setTimeout(function () {
							window.location.href = "<?= site_url('colaboradores/artigos/cadastrar/'); ?>" + retorno.parametros['artigoId'];
						}, 2000);
					<?php endif; ?>

				} else {
					popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
				}
			}
		});
	})

	<?php if (!$cadastro): ?>

		<?php if ($artigo['fase_producao_id'] == '1'): ?>

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

		<?php elseif ($artigo['fase_producao_id'] == '2'): ?>
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

</script>


<?= $this->endSection(); ?>