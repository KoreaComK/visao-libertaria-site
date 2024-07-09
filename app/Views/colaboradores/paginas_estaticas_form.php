<?php

use CodeIgniter\I18n\Time;

?>
<?= $this->extend('layouts/administradores'); ?>

<?= $this->section('content'); ?>


<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">

<style>
	/* Set default font-family */
	.ql-editor {
		font-family: 'roboto';
		font-size: 16px;
		height: 250px;
	}

	.ql-toolbar {
		border-radius: var(--mdb-border-radius-lg) !important;
	}
</style>

<section class="py-4">
	<div class="container">
		<div class="row pb-4">
			<div class="col-12">
				<!-- Title -->
				<h1 class="mb-0 h2"><?= $titulo; ?></h1>
			</div>
		</div>
		<div class="g-4 row">

			<div class="col-lg-12">
				<div class="card border">

					<div class="card-body">
						<h5 class="mb-3">Montar texto para narração</h5>
						<form class="col-12" novalidate="yes" method="post" id="estaticas_form">

							<div class="col-12">
								<!-- Post name -->
								<div class="mb-3">
									<label class="form-label" for="titulo">Título</label>
									<input type="text" class="form-control" id="titulo" name="titulo"
										placeholder="Título do artigo"
										value="<?= (isset($estaticas['titulo'])) ? (str_replace('"', "'", $estaticas['titulo'])) : (''); ?>">
								</div>
							</div>

							<div class="col-md-12">
								<!-- Subject -->
								<div class="mb-3">
									<label class="form-label" for="editor_pagina_estatica">Conteúdo</label>
									<div class="rounded-3" id="editor_pagina_estatica">
									</div>
									<textarea id="conteudo" name="conteudo"
										class="d-none"><?= (isset($estaticas['conteudo'])) ? ($estaticas['conteudo']) : (''); ?></textarea>
								</div>
							</div>

							<div class="col-12">
								<div class="mb-3">
									<label class="form-label" for="ativo">Status da página</label>
									<div class="input-group">
										<select class="form-control" name="ativo" id="ativo">
											<option value="A" <?= (isset($estaticas) && isset($estaticas['ativo']) && $estaticas['ativo'] == "A") ? ('selected="true"') : (''); ?>>Ativa
											</option>
											<option value="I" <?= (isset($estaticas) && isset($estaticas['ativo']) && $estaticas['ativo'] == "I") ? ('selected="true"') : (''); ?>>Inativa
											</option>
										</select>
									</div>
								</div>
							</div>


							<div class="col-12">
								<div class="mb-3">
									<label class="form-label" for="localizacao">Localização da página</label>
									<div class="input-group">
										<select class="form-control" name="localizacao" id="localizacao">
											<option value="menu_site" <?= (isset($estaticas) && isset($estaticas['localizacao']) && $estaticas['localizacao'] == "menu_site") ? ('selected="true"') : (''); ?>>
												Menu do site
											</option>
											<option value="menu_colaborador" <?= (isset($estaticas) && isset($estaticas['localizacao']) && $estaticas['localizacao'] == "menu_colaborador") ? ('selected="true"') : (''); ?>>
												Menu do colaborador
											</option>
											<option value="menu_administrador" <?= (isset($estaticas) && isset($estaticas['localizacao']) && $estaticas['localizacao'] == "menu_administrador") ? ('selected="true"') : (''); ?>>
												Menu do administrador
											</option>
											<option value="rodape_site" <?= (isset($estaticas) && isset($estaticas['localizacao']) && $estaticas['localizacao'] == "rodape_site") ? ('selected="true"') : (''); ?>>
												Rodapé do site
											</option>
										</select>
									</div>
								</div>
							</div>

							<div class="col-12">
								<!-- Post name -->
								<div class="mb-3">
									<label class="form-label" for="url_friendly">URL amigável</label>
									<input type="text" class="form-control" id="url_friendly" name="url_friendly"
										placeholder="URL da página"
										value="<?= (isset($estaticas['url_friendly'])) ? (str_replace('"', "'", $estaticas['url_friendly'])) : (''); ?>">
								</div>
							</div>


							<div class="d-sm-flex justify-content-end">
								<button type="button"
									class="btn btn-sm btn-primary me-2 mb-0 salvar-pagina-estatica">Salvar página
									estática</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<script type="text/javascript">

	$(".salvar-pagina-estatica").on("click", function () {
		$('#conteudo').html(quill_pagina_estatica.root.innerHTML);
		form = new FormData(estaticas_form);
		submit(form);
	});

	function submit(form) {
		$.ajax({
			url: "<?php echo base_url('colaboradores/admin/estaticasGravar') . (($estaticas === false || $estaticas['id'] == NULL) ? ('') : ('/' . $estaticas['id'])); ?>",
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
					setTimeout(function () {
						window.location.href = "<?= site_url('colaboradores/admin/estaticas'); ?>";
					}, 1500);
				} else {
					popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
				}
			}
		});
	}

	$("#titulo").on("change", function () {
		form = new FormData(estaticas_form);
		$.ajax({
			url: "<?php echo base_url('colaboradores/admin/geraUrlAmigavel'); ?>",
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
					$('#url_friendly').val(retorno.mensagem);
				}
			}
		});
	});

	const Font = Quill.import('formats/font');
	Font.whitelist = ['roboto'];
	Quill.register(Font, true);
	const toolbarOptions = [
		['bold', 'italic', 'underline', 'strike'],        // toggled buttons
		['link', 'video', 'formula'],

		[{ 'header': 1 }, { 'header': 2 }],               // custom button values
		[{ 'list': 'ordered' }, { 'list': 'bullet' }, { 'list': 'check' }],
		[{ 'script': 'sub' }, { 'script': 'super' }],      // superscript/subscript

		[{ 'header': [1, 2, 3, 4, 5, 6, false] }],

		[{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme

		['clean']                                         // remove formatting button
	];
	const options = {
		placeholder: 'Escreva o conteúdo aqui.',
		modules: {
			toolbar: toolbarOptions
		},
		theme: 'snow',
	};
	const quill_pagina_estatica = new Quill('#editor_pagina_estatica', options);
	<?php if (isset($estaticas['conteudo'])): ?>
		value = '<?= preg_replace('/\s\s+/', '\n', $estaticas['conteudo']); ?>';
		delta = quill_pagina_estatica.clipboard.dangerouslyPasteHTML(value);
	<?php endif; ?>

</script>


<?= $this->endSection(); ?>