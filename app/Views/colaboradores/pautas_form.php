<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>

<div class="container text-center w-auto">
	<div class="bg-light py-2 px-4 mb-3">
		<h3 class="m-0"><?= $titulo; ?></h3>
	</div>
	<div class="mensagem p-3 mb-2 rounded text-white text-center <?=(!isset($erros))?('collapse'):('bg-danger');?> col-12"><?=(!isset($erros))?('collapse'):($erros['mensagem']); ?></div>
	<div class="d-flex justify-content-center mb-5 text-left">
		<form class="needs-validation col-12 col-md-6" novalidate="yes" method="post" id="pautas_form">

			<div class="mb-3">
				<label for="username">Link da Notícia</label> <?php if (isset($post)): ?><a href="<?= $post['link']; ?>" class="col-md-12 text-muted" target="_blank">Ler notícia original.</a><?php endif; ?>
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
								fill="currentColor" class="bi bi-link-45deg" viewBox="0 0 16 16">
								<path
									d="M4.715 6.542 3.343 7.914a3 3 0 1 0 4.243 4.243l1.828-1.829A3 3 0 0 0 8.586 5.5L8 6.086a1.002 1.002 0 0 0-.154.199 2 2 0 0 1 .861 3.337L6.88 11.45a2 2 0 1 1-2.83-2.83l.793-.792a4.018 4.018 0 0 1-.128-1.287z" />
								<path
									d="M6.586 4.672A3 3 0 0 0 7.414 9.5l.775-.776a2 2 0 0 1-.896-3.346L9.12 3.55a2 2 0 1 1 2.83 2.83l-.793.792c.112.42.155.855.128 1.287l1.372-1.372a3 3 0 1 0-4.243-4.243L6.586 4.672z" />
							</svg></span>
					</div>
					<input type="text" class="form-control" id="link" placeholder="Link da notícia para pauta"
						name="link" onblur="getInformationLink(this.value)" required value="<?=(isset($post))?($post['link']):(''); ?>" <?= (isset($readOnly))?('disabled'):('');?>>
				</div>
			</div>

			<div class="mb-3">
				<label for="titulo">Título</label>
				<input type="text" class="form-control" id="titulo" name="titulo" placeholder="Título da pauta" required value="<?=(isset($post))?($post['titulo']):(''); ?>" <?= (isset($readOnly))?('disabled'):('');?>>
			</div>

			<div class="mb-3">
				<label for="address">Texto <span class="text-muted">Máx. 100 palavras. Mín. 10 palavras.</span> (<span class="pull-right label label-default text-muted" id="count_message"></span>)</label>
				<textarea class="form-control" name="texto" id="texto" required <?= (isset($readOnly))?('disabled'):('');?>><?=(isset($post))?($post['texto']):(''); ?></textarea>
			</div>

			<div class="mb-3">
				<label for="address">Link da Imagem</label>
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
								fill="currentColor" class="bi bi-link-45deg" viewBox="0 0 16 16">
								<path
									d="M4.715 6.542 3.343 7.914a3 3 0 1 0 4.243 4.243l1.828-1.829A3 3 0 0 0 8.586 5.5L8 6.086a1.002 1.002 0 0 0-.154.199 2 2 0 0 1 .861 3.337L6.88 11.45a2 2 0 1 1-2.83-2.83l.793-.792a4.018 4.018 0 0 1-.128-1.287z" />
								<path
									d="M6.586 4.672A3 3 0 0 0 7.414 9.5l.775-.776a2 2 0 0 1-.896-3.346L9.12 3.55a2 2 0 1 1 2.83 2.83l-.793.792c.112.42.155.855.128 1.287l1.372-1.372a3 3 0 1 0-4.243-4.243L6.586 4.672z" />
							</svg></span>
					</div>
					<input type="text" class="form-control" id="imagem" name="imagem"
						placeholder="Link da imagem da notícia" required value="<?=(isset($post))?($post['imagem']):(''); ?>" <?= (isset($readOnly))?('disabled'):('');?>>
				</div>
			</div>

			<div class="d-flex justify-content-center preview_imagem_div mb-3 collapse">
				<image class="img-thumbnail" src="" data-toggle="tooltip" data-placement="top" id="preview_imagem"
					title="Preview da Imagem da Pauta" style="max-height: 200px;" />
			</div>
			<?php if(!isset($readOnly)) : ?>
				<button class="btn btn-primary btn-lg btn-block mb-3 enviar_pauta" type="submit">Sugerir pauta</button>
			<?php else: ?>
				<a class="btn btn-primary btn-lg btn-block mb-3 enviar_pauta" href="<?= site_url('colaboradores/pautas'); ?>">Voltar</a>
			<?php endif; ?>
		</form>
	</div>
</div>

<script>

	$(function () {
		$('[data-toggle="tooltip"]').tooltip()
		$('#preview_imagem').attr('src', $('#imagem').val());
	})

	$('#texto').keyup(contapalavras);

	<?php if(!isset($readOnly)) : ?>

	function getInformationLink(link) {
		$('#pautas_form').trigger("reset");
		$('#link').val(link);

		form = new FormData();
		form.append('link_pauta', link);

		if(link == '') {
			return false;
		}

		$.ajax({
			url: "<?= (isset($post['id']))?(site_url('colaboradores/pautas/cadastrar/'.$post['id'])):(site_url('colaboradores/pautas/cadastrar')); ; ?>",
			method: "POST",
			data: form,
			processData: false,
			contentType: false,
			cache: false,
			dataType: "json",
			beforeSend: function() { $('#modal-loading').modal('show'); },
			complete: function() { $('#modal-loading').modal('hide'); },
			success: function (retorno) {
				if (retorno.status) {
					$('#titulo').val(retorno.titulo);
					$('#texto').val(retorno.texto);
					$('#imagem').val(retorno.imagem);
					$('#preview_imagem').attr('src', retorno.imagem);
					$('.preview_imagem_div').show();
					contapalavras()
				} else {
					$('.mensagem').removeClass('bg-success');
					$('.mensagem').addClass('bg-danger');
					$(".enviar_pauta").prop('disabled', true);
					$('#preview_imagem').attr('src', '');
				}
				$('.mensagem').addClass(retorno.classe);
				$('.mensagem').html(retorno.mensagem);
				$('.mensagem').show();
				$('#modal-perfil').modal('toggle');
			}
		});
	}

	$('#imagem').change(function () {
		$('#preview_imagem').attr('src', $('#imagem').val());
	});

	<?php endif; ?>

	$(document).ready(function() {
		contapalavras();
	})

	function contapalavras() {
		var texto = $("#texto").val().replaceAll('\n', " ");
		var matches = texto.split(" ");
		number = matches.filter(function(word) {
			return word.length > 0;
		}).length;
		var s = "";
		if (number > 1) {
			s = 's'
		} else {
			s = '';
		}
		if (number > 100 || number < 10) {
			$(".enviar_pauta").prop('disabled', true);
		} else {
			$(".enviar_pauta").prop('disabled', false);
		}
		$('#count_message').html(number + " palavra" + s)
	}
</script>

<?= $this->endSection(); ?>