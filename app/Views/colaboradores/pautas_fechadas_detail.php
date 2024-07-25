<?php
use CodeIgniter\I18n\Time;

?>
<?= $this->extend('layouts/administradores'); ?>

<?= $this->section('content'); ?>

<div class="container w-auto">
	<div class="row pb-4 mt-3">
		<div class="col-12">
			<!-- Title -->
			<h1 class="mb-0 h2"><?= $titulo; ?></h1>
		</div>
	</div>
	<div class="my-3 p-3 rounded box-shadow">
		<?php $tag = NULL; ?>
		<?php foreach ($pautasList as $tag => $pautas): ?>
			<div class="card mb-3">
				<div class="card-body">
					<span class="btn btn-primary mb-3"><?= $tag; ?></span>
					<p class="card-text"><small class="text-muted">
							<bold>Quem sugeriu as pautas:
						</small></p>
					<p class="card-title">
						<?php foreach ($pautas['colaboradores'] as $pauta): ?>
							<?= $pauta; ?><br />
						<?php endforeach; ?>
					</p>
					<p class="card-text"><small class="text-muted">Notícias selecionadas para a pauta</small></p>
					<p class="card-text">
						<?php foreach ($pautas['pautas'] as $pauta): ?>
							<?php if ($pauta['pauta_antiga'] == 'S'): ?>
								<i class="bi bi-exclamation-circle-fill text-danger" style="font-size: 18px;"></i>
							<?php endif; ?>
							<?= Time::createFromFormat('Y-m-d H:i:s', $pauta['criado'])->toLocalizedString('dd MMMM yyyy'); ?>
							- <?= $pauta['titulo']; ?>
							<a href="<?= site_url('colaboradores/pautas/detalhe/' . $pauta['id']); ?>" data-bs-toggle="modal"
								data-bs-target="#modalComentariosPauta" class="btn btn-outline-success m-1"
								data-bs-texto="<?= $pauta['texto']; ?>" data-bs-pautas-id="<?= $pauta['id']; ?>"
								data-bs-imagem="<?= $pauta['imagem']; ?>" target="_blank">
								<?= ($pauta['qtde_comentarios'] > 0) ? ($pauta['qtde_comentarios']) : ('Nenhum'); ?>
								<?= ($pauta['qtde_comentarios'] > 1) ? (' comentários') : (' comentário'); ?></a>
							<a class="btn btn-outline-info m-1" href="<?= $pauta['link']; ?>" target="_blank">Ir para a
								notícia</a><br />
						<?php endforeach; ?>
					</p>
				</div>
			</div>
		<?php endforeach; ?>
		<div class="d-block mt-3">
			<a href="<?= site_url('colaboradores/pautas/fechadas'); ?>">Voltar</a></small>
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

	const modalComentarios = document.getElementById('modalComentariosPauta');
	modalComentarios.addEventListener('show.bs.modal', event => {
		const button = event.relatedTarget;

		$('.modalImagem').attr('src', button.getAttribute('data-bs-imagem'));
		$('.modalTexto').html(button.getAttribute('data-bs-texto'));
		$('.modalTitulo').html(button.getAttribute('data-bs-titulo'));
		$('#idPauta').val(button.getAttribute('data-bs-pautas-id'));
		getComentarios();
	});
</script>

<?= $this->endSection(); ?>