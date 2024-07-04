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
				<h1 class="mb-0 h2">Configurações das Regras</h1>
			</div>
		</div>
		<div class="g-4 row">

			<div class="col-lg-12">
				<div class="card border">

					<div class="card-body">
						<h5 class="mb-3">Montar texto para narração</h5>
						<form class="col-12" novalidate="yes" method="post" id="narracao_form">
							<div class="col-md-12">
								<!-- Subject -->
								<div class="mb-3">
									<label class="form-label" for="texto">Corpo do texto para narração</label>
									<div class="rounded-3" id="editor_artigo_visualizacao_narracao">
									</div>
									<textarea id="artigo_visualizacao_narracao" name="artigo_visualizacao_narracao"
										class="d-none"><?= (isset($dados['artigo_visualizacao_narracao'])) ? ($dados['artigo_visualizacao_narracao']) : (''); ?></textarea>
									<span class="text-muted">Tags disponíveis: {gancho}, {texto}, {colaboradores}</span>
								</div>
							</div>
							<div class="d-sm-flex justify-content-end">
								<button type="button"
									class="btn btn-sm btn-primary me-2 mb-0 salvar-texto-narracao">Salvar layout de
									narração</button>
							</div>
						</form>
					</div>
				</div>
			</div>

			<div class="col-lg-12">
				<div class="card border">

					<div class="card-body">
						<h5 class="mb-3">Descrição do padrão do Youtube na publicação</h5>
						<form class="col-12" novalidate="yes" method="post" id="regras_youtube_form">
							<div class="col-md-12">
								<!-- Subject -->
								<div class="mb-3">
									<label class="form-label" for="texto">Corpo do texto para narração</label>
									<div class="rounded-3" id="editor_descricao_padrao_youtube">
									</div>
									<textarea id="descricao_padrao_youtube" name="descricao_padrao_youtube"
										class="d-none"><?= (isset($dados['descricao_padrao_youtube'])) ? ($dados['descricao_padrao_youtube']) : (''); ?></textarea>
									<span class="text-muted">Tags disponíveis: {referencias}, {tags}</span>
								</div>
							</div>
							<div class="d-sm-flex justify-content-end">
								<button type="button"
									class="btn btn-sm btn-primary me-2 mb-0 salvar-texto-descricao">Salvar layout de
									narração</button>
							</div>
						</form>
					</div>
				</div>
			</div>

			<div class="col-lg-12">
				<div class="card border">

					<div class="card-body">
						<h5 class="mb-3">Regras para o escritor</h5>
						<form class="col-12" novalidate="yes" method="post" id="regras_escritor_form">
							<div class="col-md-12">
								<!-- Subject -->
								<div class="mb-3">
									<label class="form-label" for="texto">Escreva as regras para o escritor</label>
									<div class="rounded-3" id="editor_artigo_regras_escrever">
									</div>
									<textarea id="artigo_regras_escrever" name="artigo_regras_escrever"
										class="d-none"><?= (isset($dados['artigo_regras_escrever'])) ? ($dados['artigo_regras_escrever']) : (''); ?></textarea>
								</div>
							</div>
							<div class="d-sm-flex justify-content-end">
								<button type="button"
									class="btn btn-sm btn-primary me-2 mb-0 salvar-regras-escritor">Salvar regras para
									escritor</button>
							</div>
						</form>
					</div>
				</div>
			</div>

			<div class="col-lg-12">
				<div class="card border">

					<div class="card-body">
						<h5 class="mb-3">Regras para o revisor</h5>
						<form class="col-12" novalidate="yes" method="post" id="regras_revisor_form">
							<div class="col-md-12">
								<!-- Subject -->
								<div class="mb-3">
									<label class="form-label" for="texto">Escreva as regras para o revisor</label>
									<div class="rounded-3" id="editor_artigo_regras_revisar">
									</div>
									<textarea id="artigo_regras_revisar" name="artigo_regras_revisar"
										class="d-none"><?= (isset($dados['artigo_regras_revisar'])) ? ($dados['artigo_regras_revisar']) : (''); ?></textarea>
								</div>
							</div>
							<div class="d-sm-flex justify-content-end">
								<button type="button"
									class="btn btn-sm btn-primary me-2 mb-0 salvar-regras-revisor">Salvar regras para
									revisor</button>
							</div>
						</form>
					</div>
				</div>
			</div>

			<div class="col-lg-12">
				<div class="card border">

					<div class="card-body">
						<h5 class="mb-3">Regras para o narrador</h5>
						<form class="col-12" novalidate="yes" method="post" id="regras_narrador_form">
							<div class="col-md-12">
								<!-- Subject -->
								<div class="mb-3">
									<label class="form-label" for="texto">Escreva as regras para o narrador</label>
									<div class="rounded-3" id="editor_artigo_regras_narrar">
									</div>
									<textarea id="artigo_regras_narrar" name="artigo_regras_narrar"
										class="d-none"><?= (isset($dados['artigo_regras_narrar'])) ? ($dados['artigo_regras_narrar']) : (''); ?></textarea>
								</div>
							</div>
							<div class="d-sm-flex justify-content-end">
								<button type="button"
									class="btn btn-sm btn-primary me-2 mb-0 salvar-regras-narrador">Salvar regras para
									narrador</button>
							</div>
						</form>
					</div>
				</div>
			</div>

			<div class="col-lg-12">
				<div class="card border">

					<div class="card-body">
						<h5 class="mb-3">Regras para o produtor</h5>
						<form class="col-12" novalidate="yes" method="post" id="regras_produtor_form">
							<div class="col-md-12">
								<!-- Subject -->
								<div class="mb-3">
									<label class="form-label" for="texto">Escreva as regras para o produtor</label>
									<div class="rounded-3" id="editor_artigo_regras_produzir">
									</div>
									<textarea id="artigo_regras_produzir" name="artigo_regras_produzir"
										class="d-none"><?= (isset($dados['artigo_regras_produzir'])) ? ($dados['artigo_regras_produzir']) : (''); ?></textarea>
								</div>
							</div>
							<div class="d-sm-flex justify-content-end">
								<button type="button"
									class="btn btn-sm btn-primary me-2 mb-0 salvar-regras-produtor">Salvar regras para
									produtor</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<script type="text/javascript">

	$(".salvar-texto-narracao").on("click", function () {
		$('#artigo_visualizacao_narracao').html(quill_artigo_visualizacao_narracao.root.innerHTML);
		form = new FormData(narracao_form);
		submit(form);
	});

	$(".salvar-regras-escritor").on("click", function () {
		$('#artigo_regras_escrever').html(quill_artigo_regras_escrever.root.innerHTML);
		form = new FormData(regras_escritor_form);
		submit(form);
	});

	$(".salvar-regras-revisor").on("click", function () {
		$('#artigo_regras_revisar').html(quill_artigo_regras_revisar.root.innerHTML);
		form = new FormData(regras_revisor_form);
		submit(form);
	});

	$(".salvar-regras-narrador").on("click", function () {
		$('#artigo_regras_narrar').html(quill_artigo_regras_narrar.root.innerHTML);
		form = new FormData(regras_narrador_form);
		submit(form);
	});

	$(".salvar-regras-produtor").on("click", function () {
		$('#artigo_regras_produzir').html(quill_artigo_regras_produzir.root.innerHTML);
		form = new FormData(regras_produtor_form);
		submit(form);
	});

	$(".salvar-texto-descricao").on("click", function () {
		$('#descricao_padrao_youtube').html(quill_descricao_padrao_youtube.root.innerHTML);
		form = new FormData(regras_youtube_form);
		submit(form);
	});

	function submit(form) {
		$.ajax({
			url: "<?php echo base_url('colaboradores/admin/administracao'); ?>",
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
	}

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
		placeholder: 'Escreva seu texto aqui.',
		modules: {
			toolbar: toolbarOptions
		},
		theme: 'snow',
	};
	const quill_artigo_visualizacao_narracao = new Quill('#editor_artigo_visualizacao_narracao', options);
	<?php if (isset($dados['artigo_visualizacao_narracao'])): ?>
		value = '<?= preg_replace('/\s\s+/', '\n', $dados['artigo_visualizacao_narracao']); ?>';
		delta = quill_artigo_visualizacao_narracao.clipboard.dangerouslyPasteHTML(value);
	<?php endif; ?>

	const quill_artigo_regras_escrever = new Quill('#editor_artigo_regras_escrever', options);
	<?php if (isset($dados['artigo_regras_escrever'])): ?>
		value = '<?= $dados['artigo_regras_escrever'] ?>';
		delta = quill_artigo_regras_escrever.clipboard.dangerouslyPasteHTML(value);
	<?php endif; ?>

	const quill_artigo_regras_revisar = new Quill('#editor_artigo_regras_revisar', options);
	<?php if (isset($dados['artigo_regras_revisar'])): ?>
		value = '<?= preg_replace('/\s\s+/', '\n', $dados['artigo_regras_revisar']); ?>';;
		delta = quill_artigo_regras_revisar.clipboard.dangerouslyPasteHTML(value);
	<?php endif; ?>

	const quill_artigo_regras_narrar = new Quill('#editor_artigo_regras_narrar', options);
	<?php if (isset($dados['artigo_regras_narrar'])): ?>
		value = '<?= preg_replace('/\s\s+/', '\n', $dados['artigo_regras_narrar']); ?>';;
		delta = quill_artigo_regras_narrar.clipboard.dangerouslyPasteHTML(value);
	<?php endif; ?>

	const quill_artigo_regras_produzir = new Quill('#editor_artigo_regras_produzir', options);
	<?php if (isset($dados['artigo_regras_produzir'])): ?>
		value = '<?= preg_replace('/\s\s+/', '\n', $dados['artigo_regras_produzir']); ?>';;
		delta = quill_artigo_regras_produzir.clipboard.dangerouslyPasteHTML(value);
	<?php endif; ?>

	const quill_descricao_padrao_youtube = new Quill('#editor_descricao_padrao_youtube', options);
	<?php if (isset($dados['descricao_padrao_youtube'])): ?>
		value = '<?= preg_replace('/\s\s+/', '\n', $dados['descricao_padrao_youtube']); ?>';;
		delta = quill_descricao_padrao_youtube.clipboard.dangerouslyPasteHTML(value);
	<?php endif; ?>

</script>


<?= $this->endSection(); ?>