<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>

<div class="container w-auto">
	<div class="bg-light py-2 px-4 mb-3">
		<h3 class="m-0">
			<?= $titulo; ?>
		</h3>
	</div>
	<div class="mensagem p-3 mb-2 rounded text-white text-center collapse col-12"></div>
	<h5>Pesquisa de pautas</h5>
	<form class="w-100" method="get" id="pesquisa-permissoes">
		<div class="form-row">
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

	<div class="d-flex mt-3 justify-content-center">
		<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-fechar">Fechar
			Pauta</button>
	</div>

	<div class="mb-3 mt-3 pautas-list"></div>
</div>


<div class="modal fade" id="modal-fechar" tabindex="-1" role="dialog" aria-labelledby="FecharPauta" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="FecharPauta">Fechar pauta</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p>Digite o título desta pauta?</p>
				<small> Se não for informado nenhum título, ele será a data de hoje.</small>
				<input type="text" id="titulo_fechamento_pauta" class="form-control " placeholder="Título do Fechamento"
					aria-label="Título do Fechamento">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-primary btn-fechar">Fechar Pauta</button>
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
			beforeSend: function () { $('#modal-loading').modal('show'); },
			complete: function () { $('#modal-loading').modal('hide'); },
			success: function (retorno) {
				if (retorno.status == true) {
					$('.mensagem').addClass('bg-success');
					$('.mensagem').removeClass('bg-danger');
					setTimeout(function () {
						location.reload();
					}, 3000);
				} else {
					$('.mensagem').removeClass('bg-success');
					$('.mensagem').addClass('bg-danger');
				}
				$('.mensagem').html(retorno.mensagem);
				$('.mensagem').show();
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
			beforeSend: function () { $('#modal-loading').modal('show'); },
			complete: function () { $('#modal-loading').modal('hide'); },
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
</script>

<?= $this->endSection(); ?>