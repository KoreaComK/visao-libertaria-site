<?= $this->extend('layouts/colaboradores'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid py-3">
	<div class="container">
		<div class="row g-4">
			<div class="col-12">
				<div class="d-flex flex-wrap gap-3">
					<?php if (in_array('3', $permissoes)): ?>
						<div class="flex-fill">
							<input type="radio" class="btn-check radio" name="fase_producao" id="revisar" value="2"
								<?= ($primeira['id'] == '2') ? ('checked') : (''); ?>>
							<label class="btn btn-outline-light w-100" for="revisar">
								<div class="icon-xl fs-1 rounded-3 text-primary text-center">
									<i class="fas fa-pen-to-square"></i>
								</div>
								<span class="mb-0 text-primary">Revisar</span>
							</label>
						</div>
					<?php endif; ?>
					<?php if (in_array('4', $permissoes)): ?>
						<div class="flex-fill">
							<input type="radio" class="btn-check radio" name="fase_producao" id="narrar" value="3"
								<?= ($primeira['id'] == '3') ? ('checked') : (''); ?>>
							<label class="btn btn-outline-light w-100" for="narrar">
								<div class="icon-xl fs-1 rounded-3 text-info text-center">
									<i class="fas fa-microphone"></i>
								</div>
								<span class="mb-0 text-info">Narrar</span>
							</label>
						</div>
					<?php endif; ?>
					<?php if (in_array('5', $permissoes)): ?>
						<div class="flex-fill">
							<input type="radio" class="btn-check radio" name="fase_producao" id="produzir" value="4"
								<?= ($primeira['id'] == '4') ? ('checked') : (''); ?>>
							<label class="btn btn-outline-light w-100" for="produzir">
								<div class="icon-xl fs-1 rounded-3 text-secondary text-center">
									<i class="fas fa-video"></i>
								</div>
								<span class="mb-0 text-secondary">Produzir</span>
							</label>
						</div>
					<?php endif; ?>
					<?php if (in_array('6', $permissoes)): ?>
						<div class="flex-fill">
							<input type="radio" class="btn-check radio" name="fase_producao" id="publicar" value="5"
								<?= ($primeira['id'] == '5') ? ('checked') : (''); ?>>
							<label class="btn btn-outline-light w-100" for="publicar">
								<div class="icon-xl fs-1 rounded-3 text-danger text-center">
									<i class="fab fa-youtube"></i>
								</div>
								<span class="mb-0 text-danger">Publicar</span>
							</label>
						</div>
					<?php endif; ?>
				</div>
			</div>

		</div>

		<!-- Post list table START -->
		<div class="card border bg-transparent rounded-3 mt-4">

			<div class="card-header bg-transparent border-bottom p-3">
				<div class="d-sm-flex justify-content-between align-items-center">
					<h5 class="mb-2 mb-sm-0">Artigos para <span class="fase-producao-nome"
							style="text-transform: lowercase;"></span></h5>
				</div>
			</div>
			<!-- Card body START -->
			<div class="card-body p-3">

				<!-- Search and select START -->
				<div class="row g-3 align-items-center justify-content-between mb-3" data-np-autofill-form-type="other"
					data-np-checked="1" data-np-watching="1">
					<!-- Search -->
					<div class="col-md-8">
						<form class="rounded position-relative">
							<input class="form-control pe-5 bg-transparent" type="search" id="text-pesquisa"
								name="text-pesquisa" placeholder="Pesquisar" aria-label="Pesquisar">
							<button
								class="btn bg-transparent border-0 px-4 py-2 position-absolute top-50 end-0 translate-middle-y btn-pesquisar"
								type="submit"><i class="fas fa-magnifying-glass"></i></button>
						</form>
					</div>

					<!-- Select option -->
					<div class="col-md-3">
						<!-- Short by filter -->

						<select class="form-select z-index-9 bg-transparent select-pesquisa" id="select-tipo"
							name="select-tipo">
							<option value="">Escolha o Tipo</option>
							<option value="T">Teórico</option>
							<option value="N">Notícia</option>
						</select>

					</div>
				</div>
				<!-- Search and select END -->

				<!-- Post list table START -->
				<div class="table-responsive border-0 tabela-publicado">

				</div>
				<!-- Post list table END -->

			</div>
		</div>
		<!-- Post list table END -->
	</div>
	<!-- Counter END -->

</div>
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalPrevia" aria-hidden="true" id="modalPrevia">
	<div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Prévia do artigo</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body"></div>
			<div class="modal-footer d-flex justify-content-between">
				<button type="button" class="btn btn-default" data-bs-dismiss="modal"
					id="modal-btn-close">Fechar</button>
			</div>
		</div>
	</div>
</div>

<script>

	function refreshListPublicado(url, fase_producao) {
		if (url == false) {
			url = '<?php echo base_url('colaboradores/artigos/artigosColaborarList'); ?>';
		}
		if (fase_producao != false) {
			$.ajax({
				url: url,
				type: 'get',
				dataType: 'html',
				data: {
					fase_producao_id: fase_producao,
					texto: $('#text-pesquisa').val(),
					tipo: $('#select-tipo').val()
				},
				beforeSend: function () { $('#modal-loading').show(); },
				complete: function () { $('#modal-loading').hide() },
				success: function (data) {
					$('.tabela-publicado').html(data);
				}
			});
		}
	}
	function showPrevia(artigoId) {
		$.ajax({
			url: "<?php echo base_url('colaboradores/artigos/buscaArtigoJSON/'); ?>" + artigoId,
			type: 'post',
			dataType: 'json',
			data: {
				artigo: artigoId
			},
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide() },
			success: function (dados) {
				if (dados.status) {
					$('#modal-btn-marcar').attr('data-vl-artigo', artigoId);
				} else {
					popMessage('ATENÇÃO', dados.mensagem, TOAST_STATUS.DANGER);
				}
			}
		});

	}

	$("#modal-btn-marcar").on("click", function (e) {
		$('.conteudo-modal').html('Deseja marcar este artigo?');
		artigoId = $(e.currentTarget).attr('data-vl-artigo');
		$("#mi-modal").modal('toggle');
		$("#modal-btn-si").addClass('modal-btn-confirma-marcar');
		document.getElementById('mi-modal').addEventListener('hide.bs.modal', function (event) {
			$("#modal-btn-si").removeClass('modal-btn-confirma-marcar');
		});

	});

	$(document).ready(function () {
		$("#modal-btn-si").on("click", function () {
			if ($('#modal-btn-si').hasClass('modal-btn-confirma-marcar')) {
				$("#mi-modal").modal('toggle');
				$.ajax({
					url: "<?php echo base_url('colaboradores/artigos/marcar/'); ?>" + artigoId,
					type: 'get',
					dataType: 'json',
					data: {
					},
					beforeSend: function () { $('#modal-loading').show(); },
					complete: function () { $('#modal-loading').hide() },
					success: function (retorno) {
						if (retorno.status) {
							popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
							$(".btn-pesquisar").trigger("click");
						} else {
							popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
						}
						artigoId = null;
					}
				});
				return false;
			}
		});

		$("#modal-btn-si").on("click", function () {
			if ($('#modal-btn-si').hasClass('modal-btn-confirma-desmarcar')) {
				$("#mi-modal").modal('toggle');
				$.ajax({
					url: "<?php echo base_url('colaboradores/artigos/desmarcar/'); ?>" + artigoId,
					type: 'get',
					dataType: 'json',
					data: {
					},
					beforeSend: function () { $('#modal-loading').show(); },
					complete: function () { $('#modal-loading').hide() },
					success: function (retorno) {
						if (retorno.status) {
							popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
							$(".btn-pesquisar").trigger("click");
						} else {
							popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
						}
						artigoId = null;
					}
				});
				return false;
			}
		});
	});

	$('.btn-pesquisar').on('click', function (e) {
		refreshListPublicado(false, $("input[name='fase_producao']:checked").val());
	});

	$('.select-pesquisa').on('change', function (e) {
		refreshListPublicado(false, $("input[name='fase_producao']:checked").val());
	});

	$('.radio').on('click', function (e) {
		refreshListPublicado(false, e.currentTarget.value);
	})

	$("form").on("submit", function (e) {
		e.preventDefault();
	});

	$(document).ready(function () {
		refreshListPublicado(false, <?= $primeira['id']; ?>);
	});

</script>

<?= $this->endSection(); ?>