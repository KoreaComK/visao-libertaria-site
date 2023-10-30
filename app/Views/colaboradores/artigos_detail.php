<?php

use CodeIgniter\I18n\Time;

?>
<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid py-3">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="position-relative mb-3">
					<div class="overlay position-relative p-3">
						<h3 class="mb-5">
							<?= $artigo['titulo']; ?>
						</h3>
						<img class="img-fluid w-100 mb-4" src="<?= $artigo['imagem']; ?>" style="object-fit: cover;">

						<p>
							<?= $artigo['gancho']; ?>
						</p>

						<p>Este é o visão Libertária. Sua fonte de informações descentralizadas e distribuídas.</p>

						<p>Este artigo foi
							<?= ($artigo['colaboradores']['sugerido'] !== null) ? ('sugerido por ' . $artigo['colaboradores']['sugerido']) : (''); ?>
							<?= ($artigo['colaboradores']['escrito'] !== null) ? ('escrito por ' . $artigo['colaboradores']['escrito']) : (''); ?>
							<?= ($artigo['colaboradores']['revisado'] !== null) ? ('revisado por ' . $artigo['colaboradores']['revisado']) : (''); ?>
							<?= ($artigo['colaboradores']['narrado'] !== null) ? ('narrado por ' . $artigo['colaboradores']['narrado']) : (''); ?>
							<?= ($artigo['colaboradores']['produzido'] !== null) ? ('produzido por ' . $artigo['colaboradores']['produzido']) : (''); ?>.
						</p>
						<p>
							<?= str_replace("\n", "<br/>", ($artigo['texto_revisado'] !== NULL) ? ($artigo['texto_revisado']) : ($artigo['texto_original'])); ?>
						</p>
						<p>Obrigado por sua audiência. Se você gostou do vídeo, compartilhe em suas redes sociais. Caso
							deseje ser avisado de outros vídeos, clique em se inscrever e depois no botão da campainha.
							Até a próxima.</p>
						<p>
							<b>Referências:</b><br/>
							<?= str_replace("\n", "<br/>", $artigo['referencias']); ?>
						</p>
					</div>
				</div>
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

					<div class="col-12 collapse div-comentarios">
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

		<?php if ($artigo['marcado_colaboradores_id'] != $_SESSION['colaboradores']['id'] && !in_array($fase_producao, array(5, 1))) : ?>
			<div class="d-block mb-5 mt-5 text-left">
				<form class="needs-validation w-100" id="form-marcar" novalidate="yes" method="post" enctype="multipart/form-data">
					<div class="row">
						<div class="d-flex col-12 justify-content-around">
							<button class="btn btn-success mb-3 col-md-4 marcar" data-toggle="modal" data-target="#modalConfirmacao" type="button">Marcar este artigo</button>
						</div>
					</div>
				</form>
			</div>
		<?php endif; ?>

		<?php if ($fase_producao == '2' && $artigo['marcado_colaboradores_id'] == $_SESSION['colaboradores']['id']) : ?>
			<div class="d-block mb-5 text-left">
				<form class="needs-validation w-100" id="form-revisar" novalidate="yes" method="post" enctype="multipart/form-data">
					<div class="row">
						<div class="d-flex col-12 justify-content-around">
							<button class="btn btn-danger mb-3 col-md-4 reverter" data-toggle="modal" data-target="#modalConfirmacao" type="button">Artigo mal escrito. Reverter para
								escrita.</button>
							<button class="btn btn-danger mb-3 col-md-3 descartar" data-toggle="modal" data-target="#modalConfirmacao" type="button">
								Descartar artigo</button>
							<button class="btn btn-success mb-3 col-md-4 revisar" type="button">Revisar artigo</button>
						</div>
					</div>
				</form>
			</div>
		<?php endif; ?>

		<?php if ($fase_producao == '3' && $artigo['marcado_colaboradores_id'] == $_SESSION['colaboradores']['id']) : ?>
			<div class="d-block mb-5 text-left">
				<form class="needs-validation w-100" id="form-narracao" novalidate="yes" method="post" enctype="multipart/form-data">
					<div class="mb-3">
						<label for="imagem">Arquivo de áudio</label>
						<div class="custom-file">
							<input type="hidden" name="blah" />
							<input type="file" class="custom-file-input" id="audio" name="audio" required aria-describedby="audio" accept=".mp3">
							<label class="custom-file-label" for="audio">Escolha o arquivo de
								narração...</label>
						</div>
					</div>
					<div class="row">
						<div class="d-flex col-12 justify-content-around">
							<button class="btn btn-danger mb-3 col-md-4 reverter" data-toggle="modal" data-target="#modalConfirmacao" type="button">Revisão Ruim. Pedir
								por nova revisão.</button>
							<button class="btn btn-danger mb-3 col-md-3 descartar" data-toggle="modal" data-target="#modalConfirmacao" type="button">
								Descartar artigo</button>
							<button class="btn btn-success mb-3 col-md-4 continuar" data-toggle="modal" data-target="#modalConfirmacao" type="button">Incluir narração.
								Solicitar produção.</button>
						</div>
					</div>
				</form>
			</div>
		<?php endif; ?>

		<?php if ($fase_producao == '4' && $artigo['marcado_colaboradores_id'] == $_SESSION['colaboradores']['id']) : ?>
			<div class="d-block mb-5 text-left">
				<audio controls autoplay>
					<source src="<?= $artigo['arquivo_audio']; ?>" type="audio/mpeg">
					Your browser does not support the audio element.
				</audio>
				<form class="needs-validation w-100" id="form-producao" novalidate="yes" method="post">
					<div class="mb-3">
						<label for="username">Link do Vídeo no YouTube</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-link-45deg" viewBox="0 0 16 16">
										<path d="M4.715 6.542 3.343 7.914a3 3 0 1 0 4.243 4.243l1.828-1.829A3 3 0 0 0 8.586 5.5L8 6.086a1.002 1.002 0 0 0-.154.199 2 2 0 0 1 .861 3.337L6.88 11.45a2 2 0 1 1-2.83-2.83l.793-.792a4.018 4.018 0 0 1-.128-1.287z" />
										<path d="M6.586 4.672A3 3 0 0 0 7.414 9.5l.775-.776a2 2 0 0 1-.896-3.346L9.12 3.55a2 2 0 1 1 2.83 2.83l-.793.792c.112.42.155.855.128 1.287l1.372-1.372a3 3 0 1 0-4.243-4.243L6.586 4.672z" />
									</svg></span>
							</div>
							<input type="text" class="form-control" id="video_link" name="video_link" placeholder="Link do Vídeo no YouTube" required>
						</div>
					</div>
					<div class="mb-3">
						<label for="username">Link do Shorts no YouTube</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-link-45deg" viewBox="0 0 16 16">
										<path d="M4.715 6.542 3.343 7.914a3 3 0 1 0 4.243 4.243l1.828-1.829A3 3 0 0 0 8.586 5.5L8 6.086a1.002 1.002 0 0 0-.154.199 2 2 0 0 1 .861 3.337L6.88 11.45a2 2 0 1 1-2.83-2.83l.793-.792a4.018 4.018 0 0 1-.128-1.287z" />
										<path d="M6.586 4.672A3 3 0 0 0 7.414 9.5l.775-.776a2 2 0 0 1-.896-3.346L9.12 3.55a2 2 0 1 1 2.83 2.83l-.793.792c.112.42.155.855.128 1.287l1.372-1.372a3 3 0 1 0-4.243-4.243L6.586 4.672z" />
									</svg></span>
							</div>
							<input type="text" class="form-control" id="shorts_link" name="shorts_link" placeholder="Link do Shorts no YouTube">
						</div>
					</div>
					<div class="row">
						<div class="d-flex col-12 justify-content-around">
							<button class="btn btn-danger mb-3 col-md-4 reverter" data-toggle="modal" data-target="#modalConfirmacao" type="button">Narração Ruim. Pedir
								por nova narração.</button>
							<button class="btn btn-danger mb-3 col-md-3 descartar" data-toggle="modal" data-target="#modalConfirmacao" type="button">
								Descartar artigo</button>
							<button class="btn btn-success mb-3 col-md-4 continuar" data-toggle="modal" data-target="#modalConfirmacao" type="button">Incluir Produção.
								Solicitar publicação.</button>
						</div>
					</div>
				</form>
			</div>
		<?php endif; ?>

		<?php if ($fase_producao == '5') : ?>
			<div class="d-block mb-5 text-left">
				<form class="needs-validation w-100" id="form-publicacao" novalidate="yes" method="post">
					<div class="mb-3">
						<label for="username">Link do Vídeo no YouTube (Visão Libertária)</label>
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
			<?php if ($fase_producao == '2') : ?>
				window.location.href = "<?= site_url('colaboradores/artigos/revisar/' . $artigo['id']); ?>"
			<?php elseif ($fase_producao == '3') : ?>
				$('#form-narracao').submit();
			<?php elseif ($fase_producao == '4') : ?>
				$('#form-producao').submit();
			<?php elseif ($fase_producao == '5') : ?>
				$('#form-publicacao').submit();
			<?php endif; ?>
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
		$('#btn-comentarios').trigger('click');

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
