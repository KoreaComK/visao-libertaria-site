<?= $this->extend('layouts/administradores'); ?>

<?= $this->section('content'); ?>

<div class="container w-auto">
	<div class="bg-light py-2 px-4 mb-3">
		<h3 class="m-0">
			<?= $titulo; ?>
		</h3>
	</div>
	<div class="ms-2 me-2">
		<h5>Pesquisa de pautas</h5>
		<form method="get" id="pesquisa-permissoes">
			<div class="row">
				<div class="col-md-9">
					<div class="control-group">
						<input type="text" class="form-control form-control-sm" id="pesquisa"
							placeholder="Pesquise pelas pautas" />
					</div>
				</div>
				<div class="col-md-3">
					<div class="control-group">
						<button class="btn btn-primary btn-sm btn-block btn-submeter" type="button">Enviar</button>
					</div>
				</div>
			</div>
		</form>
	</div>

	<div class="d-flex mt-3 justify-content-center">
		<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-fechar">Fechar
			Pauta</button>
	</div>

	<div class="mb-3 mt-3 pautas-list"></div>
</div>


<div class="modal fade" id="modal-fechar" tabindex="-1" role="dialog" aria-labelledby="FecharPauta" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="FecharPauta">Fechar pauta</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<p>Digite o título desta pauta?</p>
				<small> Se não for informado nenhum título, ele será a data de hoje.</small>
				<input type="text" id="titulo_fechamento_pauta" class="form-control " placeholder="Título do Fechamento"
					aria-label="Título do Fechamento">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-primary btn-fechar">Fechar Pauta</button>
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


	$('.btn-fechar').on('click', function (e) {
		var id_pauta = e.target.getAttribute('data-information');

		form = new FormData();
		form.append('titulo', $('#titulo_fechamento_pauta').val());
		form.append('metodo', 'fechar');

		$.ajax({
			url: "<?php echo base_url('colaboradores/pautas/fechar'); ?>",
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
					popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
					setTimeout(function () {
						location.reload();
					}, 3000);
				} else {
					popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
				}
				$('#modal-fechar').modal('toggle');
			}
		});
	});

	$('.btn-submeter').on('click', function (e) {
		e.preventDefault();
		$.ajax({
			url: "<?php echo base_url('colaboradores/pautas/pautasList'); ?>",
			type: 'get',
			dataType: 'html',
			data: {
				pesquisa: $('#pesquisa').val()
			},
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide() },
			success: function (data) {
				$('.pautas-list').html(data);
			}
		});
	});

	$("form").on("submit", function (e) {
		e.preventDefault();
	});

	$(document).ready(function () {
		$(".btn-submeter").click();
	});

	//Comentários

	$("#btn-comentarios").on("click", function () {
		getComentarios();
	});

	function getComentarios() {
		$.ajax({
			url: "<?php echo base_url('colaboradores/pautas/comentarios/'); ?>"+$('#idPauta').val(),
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
			url: "<?php echo base_url('colaboradores/pautas/comentarios/'); ?>"+$('#idPauta').val(),
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
			url: "<?php echo base_url('colaboradores/pautas/comentarios/'); ?>"+$('#idPauta').val(),
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