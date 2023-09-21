<?php

use CodeIgniter\I18n\Time;

?>
<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid py-3">
	<div class="container">
		<div class="my-3 p-3 bg-white rounded box-shadow">
			<div class="media text-muted pt-3 mb-3 pb-3">
				<image class="mr-2 rounded img-thumbnail" for="btn-check-outlined" style="height: auto; max-width:250px;" src="<?= $artigo['imagem']; ?>" />
				<p class="media-body pb-3 mb-0 small lh-125  border-gray">
					<strong class="d-block">
						<?= Time::createFromFormat('Y-m-d H:i:s', $artigo['criado'])->toLocalizedString('dd MMMM yyyy HH:mm:ss'); ?>
						-
						<?= $artigo['titulo']; ?>
					</strong>
					<?= ($artigo['fase_producao_id'] == '1' || $artigo['fase_producao_id'] == '2') ? (substr($artigo['texto_original'], 0, 350)) : (substr($artigo['texto_revisado'], 0, 350)); ?>...
					<a href="<?= site_url('colaboradores/artigos/detalhe/' . $artigo['id']); ?>" target="_blank">Ler
						artigo completo.</a>
					<br />
				</p>
			</div>
		</div>

		<?php if (isset($artigo['id']) && $artigo['id'] !== null) : ?>
			<div class="row">
				<div class="col-12 text-center">
					<button class="btn btn-primary mb-3 col-md-3 mr-3 ml-3" id="btn-comentarios" type="button">Mostrar
						Comentários</button>
				</div>
				<div class="mensagem-comentario p-3 mb-2 rounded text-white text-center col-12" style="display: none;"></div>
				<div class="mensagem-salvar p-3 mb-2 rounded text-white text-center col-12" style="display: none;"></div>
				<div class="col-12 d-flex justify-content-center">

					<div class="col-8 collapse div-comentarios">
						<div class="card m-3 div-list-comentarios"></div>
						<div class="col-12">
							<div class="mb-3">
								<input type="hidden" id="id_comentario" name="id_comentario" />
								<textarea id="comentario" name="comentario" class="form-control" rows="5" placeholder="Digite seu comentário aqui"></textarea>
							</div>
							<div class="mb-3 text-center">
								<button class="btn btn-primary mb-3 col-md-3 mr-3 ml-3" id="enviar-comentario" type="button">Enviar comentário</button>
							</div>
						</div>
					</div>
				</diV>
			</div>
		<?php endif; ?>

		<?php if ($historico !== NULL && !empty($historico)) : ?>
			<div class="mb-5 text-left col-md-6">
				<h6>Histórico do artigo:</h6>
				<ul class="list-group">
					<?php foreach ($historico as $h) : ?>
						<li class="list-group-item p-1 border-0">
							<small><small>
									<?= $h['apelido']; ?>
									<?= $h['acao']; ?>
									<span class="badge badge-pill badge-secondary">
										<?= Time::createFromFormat('Y-m-d H:i:s', $h['criado'])->toLocalizedString('dd MMMM yyyy HH:mm:ss'); ?>
									</span>
								</small></small>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>

		<div class="mensagem p-3 mb-2 rounded text-white text-center <?= (!isset($retorno)) ? ('collapse') : (''); ?> <?= (isset($retorno) && $retorno['status'] === true) ? ('bg-success') : ('bg-danger'); ?> col-12">
			<?= (isset($retorno)) ? ($retorno['mensagem']) : (''); ?></div>

		<?php if ($fase_producao == '5') : ?>
			<div class="d-block mb-5 text-left">
				<form class="needs-validation w-100" id="form-publicacao" novalidate="yes" method="post">
					<div class="mb-3">
						<label for="username">Tags do Vídeo no YouTube <span class="text-muted">(colocar # na frente da tag, e separá-las com espaço)</span></label>
						<div class="input-group">
							<input type="text" class="form-control" id="tags_video_youtube" name="tags_video_youtube" placeholder="Tags do Vídeo" required>
						</div>
					</div>

					<div class="col-12 text-center">
						<button class="btn btn-primary mb-3 col-md-3 mr-3 ml-3" id="btn-tags" type="button">Gerar descrição do vídeo</button>
					</div>

					<div class="mb-3 div-descricao collapse">
						<div class="input-group">
							<textarea class="form-control text-descricao" rows="10"></textarea>
						</div>
					</div>

					<div class="mb-3">
						<label for="link_video_youtube">Link do Vídeo no YouTube (Visão Libertária)</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-link-45deg" viewBox="0 0 16 16">
										<path d="M4.715 6.542 3.343 7.914a3 3 0 1 0 4.243 4.243l1.828-1.829A3 3 0 0 0 8.586 5.5L8 6.086a1.002 1.002 0 0 0-.154.199 2 2 0 0 1 .861 3.337L6.88 11.45a2 2 0 1 1-2.83-2.83l.793-.792a4.018 4.018 0 0 1-.128-1.287z" />
										<path d="M6.586 4.672A3 3 0 0 0 7.414 9.5l.775-.776a2 2 0 0 1-.896-3.346L9.12 3.55a2 2 0 1 1 2.83 2.83l-.793.792c.112.42.155.855.128 1.287l1.372-1.372a3 3 0 1 0-4.243-4.243L6.586 4.672z" />
									</svg></span>
							</div>
							<input type="text" class="form-control" id="link_video_youtube" name="link_video_youtube" placeholder="Link do Vídeo no Canal do Visão Libertária" required>
						</div>
					</div>
					<div class="row">
						<div class="d-flex col-12 justify-content-around">
							<button class="btn btn-danger mb-3 col-md-4 reverter" data-toggle="modal" data-target="#modalConfirmacao" type="button">Produção Ruim. Pedir
								por nova produção.</button>
							<button class="btn btn-danger mb-3 col-md-3 descartar" data-toggle="modal" data-target="#modalConfirmacao" type="button">
								Descartar artigo</button>
							<button class="btn btn-success mb-3 col-md-4 continuar" data-toggle="modal" data-target="#modalConfirmacao" type="button">Incluir Publicação.
								Solicitar pagamento.</button>
						</div>
					</div>
				</form>
			</div>
		<?php endif; ?>
	</div>
</div>

<div class="modal fade" id="modalConfirmacao" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Atenção</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p id="modal-texto"></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary text-left" data-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-primary confirmacao-acao">Confirmar</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(".marcar").on("click", function() {
		$("#modal-texto").html('Você tem certeza que quer marcar este artigo?<br/> Ao marcá-lo, os demais textos marcados por você, serão liberados.');
		$('.confirmacao-acao').removeClass('confirmacao-descartar');
		$('.confirmacao-acao').removeClass('confirmacao-reverter');
		$('.confirmacao-acao').addClass('confirmacao-continuar-marcar');
	});

	$(".revisar").on("click", function() {
		window.location.href = "<?= site_url('colaboradores/artigos/revisar/' . $artigo['id']); ?>"
	});

	$(".reverter").on("click", function() {
		$("#modal-texto").html('Você tem certeza que deseja <bold>reverter</bold> o texto para a fase anterior?');
		$('.confirmacao-acao').removeClass('confirmacao-continuar');
		$('.confirmacao-acao').removeClass('confirmacao-descartar');
		$('.confirmacao-acao').addClass('confirmacao-reverter');
	});

	$(".continuar").on("click", function() {
		$("#modal-texto").html('Você tem certeza que deseja passar o texto para a <bold>próxima</bold> fase de produção?');
		$('.confirmacao-acao').removeClass('confirmacao-descartar');
		$('.confirmacao-acao').removeClass('confirmacao-reverter');
		$('.confirmacao-acao').addClass('confirmacao-continuar');
	});

	$(".descartar").on("click", function() {
		$("#modal-texto").html('Você tem certeza que deseja <bold>descartar</bold> o texto?');
		$('.confirmacao-acao').removeClass('confirmacao-continuar');
		$('.confirmacao-acao').removeClass('confirmacao-reverter');
		$('.confirmacao-acao').addClass('confirmacao-descartar');
	});

	$(".confirmacao-acao").on("click", function() {
		if ($('.confirmacao-acao').hasClass('confirmacao-descartar')) {
			window.location.href = location.href + '?descartar=true';
		}
		if ($('.confirmacao-acao').hasClass('confirmacao-continuar-marcar')) {
			marcarArtigo();
		}
		if ($('.confirmacao-acao').hasClass('confirmacao-continuar')) {
			$('#form-publicacao').submit();
		}

		if ($('.confirmacao-acao').hasClass('confirmacao-reverter')) {
			window.location.href = location.href + '?anterior=true';
		}
	});



	<?php if (isset($artigo['id']) && $artigo['id'] !== null) : ?>
		$("#btn-comentarios").on("click", function() {
			$(".div-comentarios").toggle();
			getComentarios();
		});

		$("#btn-tags").on("click", function() {
			setTags();
		});

		function getComentarios() {
			$.ajax({
				url: "<?php echo base_url('colaboradores/artigos/comentarios/' . $artigo['id']); ?>",
				method: "GET",
				dataType: "html",
				beforeSend: function() { $('#modal-loading').modal('show'); },
				complete: function() { $('#modal-loading').modal('hide'); },
				success: function(retorno) {
					$('.div-list-comentarios').html(retorno);
				}
			});
		}

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
				beforeSend: function() { $('#modal-loading').modal('show'); },
				complete: function() { $('#modal-loading').modal('hide'); },
				success: function(retorno) {
					if(retorno.status == true) {
						$('.text-descricao').html(retorno.descricao);
						$('.div-descricao').show();
						$('.text-descricao').select();
						document.execCommand('copy');
					} else {
						$('.mensagem').show();
						$('.mensagem').html(retorno.mensagem);
						$('.mensagem').addClass('bg-danger');
					}
				}
			});
		}


		$("#enviar-comentario").on("click", function() {
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
				beforeSend: function() { $('#modal-loading').modal('show'); },
				complete: function() { $('#modal-loading').modal('hide'); },
				success: function(retorno) {
					if (retorno.status) {
						getComentarios()
						$('.mensagem-comentario').hide();
						$('#comentario').val('');
						$('#id_comentario').val('');
						$('.mensagem-comentario').removeClass('bg-danger');
					} else {
						$('.mensagem-comentario').show();
						$('.mensagem-comentario').html(retorno.mensagem);
						$('.mensagem-comentario').addClass('bg-danger');
					}
				}
			});
		});

		function marcarArtigo() {
			form = new FormData();

			$.ajax({
				url: "<?php echo base_url('colaboradores/artigos/marcar/' . $artigo['id']); ?>",
				method: "POST",
				data: form,
				processData: false,
				contentType: false,
				cache: false,
				dataType: "json",
				beforeSend: function() { $('#modal-loading').modal('show'); },
				complete: function() { $('#modal-loading').modal('hide'); },
				success: function(retorno) {
					if (retorno.status) {
						window.location.reload();
					} else {
						console.log(retorno);
						$('.mensagem-salvar').show();
						$('.mensagem-salvar').html(retorno.mensagem);
						$('.mensagem-salvar').addClass('bg-danger');
						$('#modalConfirmacao').modal('toggle');
					}
				}
			});
		}

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
				beforeSend: function() { $('#modal-loading').modal('show'); },
				complete: function() { $('#modal-loading').modal('hide'); },
				success: function(retorno) {
					if (retorno.status) {
						getComentarios()
						$('mensagem-comentario').hide();
					} else {
						$('mensagem-comentario').show();
						$('mensagem-comentario').html(status.mensagem);
						$('.mensagem-comentario').addClass('bg-danger');
					}
				}
			});
		}
	<?php endif; ?>
</script>

<?= $this->endSection(); ?>