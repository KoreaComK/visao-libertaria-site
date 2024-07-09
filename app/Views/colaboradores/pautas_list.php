<?php
use CodeIgniter\I18n\Time;

?>

<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>


<script src="https://cdn.jsdelivr.net/npm/masonry-layout@4.2.2/dist/masonry.pkgd.min.js"
	integrity="sha384-GNFwBvfVxBkLMJpYMOABq3c+d3KnQxudP/mGPkzpZSTYykLBNsZEnG2D9G/X/+7D" crossorigin="anonymous"
	async></script>

<script src="https://unpkg.com/infinite-scroll@4/dist/infinite-scroll.pkgd.min.js"></script>

<style>
	.pautas-list .card-img-top {
		height: 13rem;
	}

	.page-load-status {
		display: none;
	}
</style>

<div class="container w-auto">
	<section class="pt-4 pb-4">
		<div class="container">
			<div class="row">
				<div class="col-12 p-0">
					<div class="bg-dark p-4 text-center rounded-4">
						<h1 class="text-white"> Pautas e Notícias importantes dos últimos dias </h1>
						<nav class="d-flex justify-content-center" aria-label="breadcrumb">
							<ol class="breadcrumb breadcrumb-dark m-0">
								<li class="breadcrumb-item "><a href="<?= site_url(); ?>" class="text-white"><i
											class="bi bi-house me-1"></i>
										Home</a></li>
								<li class="breadcrumb-item active text-secondary">
									Pautas e Notícias importantes dos últimos dias
								</li>
							</ol>
						</nav>
					</div>
				</div>
			</div>
		</div>
	</section>
	<div class="mb-4 text-center">
		<button type="button" class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#modalSugerirPauta"
			data-bs-titulo-modal="Cadastre uma pauta">
			Sugerir pauta
		</button>
	</div>
	<div class="pautas-list row">
		<?php foreach ($pautasList['pautas'] as $pauta): ?>

			<div class="card col-lg-3 mb-4 shadow-0 p-1">
				<img src="<?= $pauta['imagem']; ?>" alt="" class="card-img-top rounded-6 object-fit-cover">
				<div class="card-body p-2">
					<h5 class="card-title fw-bold">
						<?php if ($pauta['pauta_antiga'] == 'S'): ?>
							<i class="bi bi-exclamation-circle-fill text-danger" style="font-size: 18px;"></i>
						<?php endif; ?>
						<?= $pauta['titulo']; ?>
					</h5>
					<div>
						<small>
							<ul class="nav nav-divider">
								<li class="nav-item pointer">
									<div class="d-flex text-muted">
										<span class="">Sugerido por <a href="<?= site_url('site/colaborador/'); ?><?= urlencode($pauta['apelido']); ?>"
												class="text-muted btn-link"><?= $pauta['apelido']; ?></a></span>
									</div>
								</li>
								<li class="nav-item pointer text-muted">
									<?= Time::createFromFormat('Y-m-d H:i:s', $pauta['criado'])->toLocalizedString('dd MMM yyyy'); ?>
								</li>
							</ul>
						</small>
						<p class="card-text"><?= $pauta['texto']; ?></p>
						<a href="<?= $pauta['link']; ?>" target="_blank" class="btn btn-outline-success btn-sm mb-1">Ler
							Notícia</a>
						<a href="" data-bs-titulo="<?= $pauta['titulo']; ?>" data-bs-texto="<?= $pauta['texto']; ?>"
							data-bs-pautas-id="<?= $pauta['id']; ?>" data-bs-imagem="<?= $pauta['imagem']; ?>"
							class="btn btn-outline-info btn-sm mb-1" data-bs-toggle="modal"
							data-bs-target="#modalComentariosPauta">Comentários</a>
						<a href="<?= site_url('colaboradores/artigos/cadastrar?pauta=' . $pauta['id']); ?>"
							class="btn btn-outline-primary btn-sm mb-1">Escrever artigo</a>
						<?php if ($pauta['colaboradores_id'] == $_SESSION['colaboradores']['id']): ?>
							<a href="<?= site_url('colaboradores/pautas/cadastrar/' . $pauta['id']); ?>"
								data-bs-pautas-id="<?= $pauta['id']; ?>" data-bs-toggle="modal"
								data-bs-target="#modalSugerirPauta" data-bs-titulo-modal="Alterar a pauta"
								class="btn btn-warning btn-sm mb-1">Editar</a>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
	<div class="d-none">
		<?php if ($pautasList['pager']): ?>
			<?= $pautasList['pager']->simpleLinks('pautas', 'default_template') ?>
		<?php endif; ?>
	</div>
	<div class="page-load-status">
		<div class="infinite-scroll-request d-flex justify-content-center mt-5 mb-5">
			<div class="spinner-border" role="status">
				<span class="visually-hidden">Loading...</span>
			</div>
		</div>
		<p class="infinite-scroll-last h5">End of content</p>
		<p class="infinite-scroll-error h5">No more pages to load</p>
	</div>

</div>



<div class="modal fade" id="modalSugerirPauta" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title fs-5"></h3>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form method="post" id="pautas_form" name="pautas_form"
					action="<?= site_url('colaboradores/pautas/cadastrar'); ?>">

					<div class="mb-3">
						<label for="username">Link da Notícia</label>
						<div class="input-group">
							<i class="input-group-text bi bi-link-45deg"></i>
							<input type="text" class="form-control" id="link" placeholder="Link da notícia para pauta"
								name="link" onblur="getInformationLink(this.value)" data-bs-target="#modal-loading"
								required>
						</div>
					</div>

					<div class="mb-3">
						<label for="titulo">Título</label>
						<input type="hidden" id="pauta_antiga" name="pauta_antiga" value="N" />
						<input type="hidden" id="id_pauta" name="id_pauta" value="" />
						<input type="text" class="form-control" id="titulo" name="titulo" placeholder="Título da pauta"
							required>
					</div>

					<div class="mb-3">
						<label for="address">Texto <span
								class="text-muted"><?php if (!in_array('7', $_SESSION['colaboradores']['permissoes'])): ?>Máx.
									<?= $config['pauta_tamanho_maximo']; ?> palavras. Mín.
									<?= $config['pauta_tamanho_minimo']; ?> palavras.</span><?php endif; ?> (<span
								class="pull-right label label-default text-muted" id="count_message"></span>)</label>
						<textarea class="form-control" name="texto" id="texto" required></textarea>
					</div>

					<div class="mb-3">
						<label for="address">Link da Imagem</label>
						<div class="input-group">
							<i class="input-group-text bi bi-link-45deg"></i>
							<input type="text" class="form-control" id="imagem" name="imagem"
								placeholder="Link da imagem da notícia" required>
						</div>
					</div>

					<div class="text-center preview_imagem_div mb-3 collapse">
						<image class="img-thumbnail img-preview-modal" src="" data-toggle="tooltip" data-placement="top"
							id="preview_imagem" title="Preview da Imagem da Pauta" style="max-height: 200px;" />
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="me-auto btn btn-outline-danger btn-excluir">Excluir pauta</button>
				<button type="button" class="btn btn-secondary btn-reset" data-bs-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-primary btn-enviar">Enviar</button>
			</div>
		</div>
	</div>
</div>

<div class="modal modal-lg fade" id="modalComentariosPauta" tabindex="-1" aria-labelledby="exampleModalLabel"
	aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title fs-5">Comentários da Pauta</h3>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="card mb-3">
					<img src="" class="card-img-top modalImagem" alt="">
					<div class="card-body">
						<h5 class="card-title modalTitulo"></h5>
						<p class="card-text modalTexto"></p>
					</div>
				</div>

				<div class="row">
					<div class="col-12 text-center">
						<button class="btn btn-primary mt-3 mb-3 col-md-6" id="btn-comentarios" type="button">Atualizar
							Comentários</button>
					</div>
					<div class="col-12 d-flex justify-content-center">

						<div class="col-12 div-comentarios">
							<div class="col-12">
								<div class="mb-3">
									<input type="hidden" id="idPauta" name="idPauta" />
									<input type="hidden" id="id_comentario" name="id_comentario" />
									<textarea id="comentario" name="comentario" class="form-control" rows="5"
										placeholder="Digite seu comentário aqui"></textarea>
								</div>
								<div class="mb-3 text-center">
									<button class="btn btn-primary mt-3 col-md-6" id="enviar-comentario"
										type="button">Enviar comentário</button>
								</div>
							</div>
							<div class="card m-3 div-list-comentarios"></div>
						</div>
					</diV>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-reset" data-bs-dismiss="modal">Fechar</button>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function () {
		var $grid = $('.pautas-list').masonry({
			// Masonry options...
			stagger: 100,
			itemSelector: '.card',
			horizontalOrder: true
		});

		var msnry = $grid.data('masonry');

		$grid.infiniteScroll({
			// Infinite Scroll options...
			path: '.next_page',
			append: '.card',
			history: false,
			outlayer: msnry,
			status: '.page-load-status'
		});
	});
</script>

<script>

	const exampleModal = document.getElementById('modalSugerirPauta');
	exampleModal.addEventListener('show.bs.modal', event => {
		const button = event.relatedTarget;

		const recipient = button.getAttribute('data-bs-pautas-id');
		const titulo = button.getAttribute('data-bs-titulo-modal');

		$('.modal-title').html(titulo);

		if (recipient != null) {
			$('.btn-excluir').show();
			$.ajax({
				url: "<?= site_url('colaboradores/pautas/detalhe/'); ?>" + recipient,
				method: "POST",
				data: '',
				processData: false,
				contentType: false,
				cache: false,
				dataType: "json",
				beforeSend: function () { $('#modal-loading').show(); },
				complete: function () { $('#modal-loading').hide() },
				success: function (retorno) {
					if (retorno.status) {
						$('#id_pauta').val(recipient);
						$('#titulo').val(retorno.titulo);
						$('#link').val(retorno.link);
						$('#link').attr('disabled', true);
						$('#texto').val(retorno.texto);
						$('#imagem').val(retorno.imagem);
						$('#pauta_antiga').val(retorno.pauta_antiga);
						$('#imagem').trigger('change');
						$('#texto').trigger('keyup');
					} else {
						popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
					}
				}
			});
		} else {
			$('#pautas_form').trigger('reset');
			$('.img-preview-modal').attr('src', '');
			$('.btn-excluir').hide();
		}
	});

	const modalComentarios = document.getElementById('modalComentariosPauta');
	modalComentarios.addEventListener('show.bs.modal', event => {
		const button = event.relatedTarget;

		$('.modalImagem').attr('src', button.getAttribute('data-bs-imagem'));
		$('.modalTexto').html(button.getAttribute('data-bs-texto'));
		$('.modalTitulo').html(button.getAttribute('data-bs-titulo'));
		$('#idPauta').val(button.getAttribute('data-bs-pautas-id'));
		getComentarios();
	});


	exampleModal.addEventListener('hide.bs.modal', event => {
		$(".btn-reset").trigger("click");
	});


	$('.btn-excluir').on('click', function () {

		form = new FormData(pautas_form);
		idPauta = $('#id_pauta').val();
		$.ajax({
			url: "<?= site_url('colaboradores/pautas/excluir/'); ?>" + idPauta,
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
					$(".btn-reset").trigger("click");
					setTimeout(function () {
						location.reload();
					}, 2000);
				} else {
					popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
					Toast.create(toast);
				}
			}
		});
	})

	$('#imagem').change(function () {
		$('.preview_imagem_div').show();
		form = new FormData(pautas_form);
		$.ajax({
			url: "<?= site_url('colaboradores/pautas/verificaImagem'); ?>",
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
					$('#preview_imagem').attr('src', $('#imagem').val());
				} else {
					$('#preview_imagem').attr('src', '<?= base_url('public/assets/imagem-default.png'); ?>');
					$('#imagem').val('<?= base_url('public/assets/imagem-default.png'); ?>');
					popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
				}
			}
		});
	});

	$('.btn-reset').on('click', function () {
		$('#link').attr('disabled', false);
		$('#pautas_form').trigger('reset');
		$('.img-preview-modal').attr('src', '');
		$('.preview_imagem_div').hide();
		$('#id_pauta').val('');
	})

	$('#texto').keyup(contapalavras);

	$('.btn-enviar').on('click', function () {
		form = new FormData(pautas_form);
		idPauta = "";
		if ($('#id_pauta').val() != "") {
			idPauta = '/' + $('#id_pauta').val();
		}
		$.ajax({
			url: "<?= site_url('colaboradores/pautas/cadastrar'); ?>" + idPauta,
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
						location.reload();
					}, 2000);
					$(".btn-reset").trigger("click");
				} else {
					popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
				}
			}
		});
	})

	$(document).ready(function () {
		contapalavras();
	})

	function contapalavras() {
		var texto = $("#texto").val().replaceAll('\n', " ");
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

	function getInformationLink(link) {
		$('#pautas_form').trigger("reset");

		link = link.trim();
		link = link.substring(0, 254);
		$('#link').val(link);

		form = new FormData();
		form.append('link_pauta', link);
		if (link == '') { return false; }

		$.ajax({
			url: "<?= site_url('colaboradores/pautas/verificaPautaCadastrada'); ?>",
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
					$('#titulo').val(retorno.titulo);
					$('#imagem').val(retorno.imagem);
					$('#texto').val(retorno.texto);
					$('#preview_imagem').attr('src', retorno.imagem);
					$('.preview_imagem_div').show();
					contapalavras()
					if (retorno.mensagem == null) {
						$('#pauta_antiga').val('N');
					} else {
						$('#pauta_antiga').val('S');
						popMessage('ATENÇÃO!', retorno.mensagem, TOAST_STATUS.INFO);
					}
					if (retorno.imagem == "") {
						$('#imagem').val('<?= base_url('public/assets/imagem-default.png'); ?>');
						$('#preview_imagem').attr('src', '<?= base_url('public/assets/imagem-default.png'); ?>');
					}
				} else {
					$('#preview_imagem').attr('src', '<?= base_url('public/assets/imagem-default.png'); ?>');
					$('#imagem').val('<?= base_url('public/assets/imagem-default.png'); ?>');
					popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
				}
			}
		});
	}


	//Comentários

	$("#btn-comentarios").on("click", function () {
		getComentarios();
	});

	function getComentarios() {
		$.ajax({
			url: "<?php echo base_url('colaboradores/pautas/comentarios/'); ?>" + $('#idPauta').val(),
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
			url: "<?php echo base_url('colaboradores/pautas/comentarios/'); ?>" + $('#idPauta').val(),
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
					getComentarios()
					$('#comentario').val('');
					$('#id_comentario').val('');
					popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
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
			url: "<?php echo base_url('colaboradores/pautas/comentarios/'); ?>" + $('#idPauta').val(),
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
					getComentarios()
					popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
				} else {
					popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
				}
			}
		});
	}

</script>

<?= $this->endSection(); ?>