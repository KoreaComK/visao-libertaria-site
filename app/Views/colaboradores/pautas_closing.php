<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>

<div class="container w-auto">
	<div class="bg-light py-2 px-4 mb-3">
		<h3 class="m-0">
			<?= $titulo; ?>
		</h3>
	</div>
	<div class="mensagem p-3 mb-2 rounded text-white text-center collapse col-12"></div>
	<div class="d-flex mt-3 justify-content-center">
		<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-fechar">Fechar
			Pauta</button>
	</div>
	<div class="my-3 p-3 bg-white rounded box-shadow">

		<?php foreach ($pautasList['pautas'] as $pauta): ?>

			<div class="media text-muted pt-3 border-bottom row " id="pauta_<?= $pauta['id']; ?>">
				<div class="col-12">
					<image class="mr-2 rounded img-thumbnail float-left" for="btn-check-outlined"
						style="max-height: 120px; max-width:250px;" src="<?= $pauta['imagem']; ?>" />
					<p class="media-body pb-3 mb-0 small lh-125  border-gray">
						<strong class="d-block">
							<?= $pauta['titulo']; ?>
						</strong>
						<?= $pauta['texto']; ?><br />
						<a href="<?= $pauta['link']; ?>" target="_blank">Ler notícia original.</a><br />
						<small class="badge badge-primary m-1 p-1">Sugerido:
							<?= $pauta['apelido']; ?>
						</small>
				</div>
				<div class="col-12 row text-center mb-3">
					<small class="col-9 mt-3">
						<button type="button" data-information="<?= $pauta['id']; ?>"
							class="btn btn-danger btn-sm descartar">Descartar</button>
					</small>
					<small class="col-3 mt-3">
						<button type="button" data-information="<?= $pauta['id']; ?>"
							class="btn btn-success btn-sm reservar <?= ($pauta['reservado'] != null) ? ('collapse') : (''); ?>"
							id="btn-reservar-<?= $pauta['id']; ?>">Reservar</button>
						<div class="collapse" id="div_reserva_<?= $pauta['id']; ?>">
							<div class="input-group input-group-sm mb-3">
								<input type="text" id="tema_<?= $pauta['id']; ?>" class="form-control "
									placeholder="Tema da Pauta" aria-label="Tema da Pauta" aria-describedby="button-addon2">
								<div class="input-group-append">
									<button class="btn btn-outline-primary btn-salvar" type="button"
										data-information="<?= $pauta['id']; ?>">Salvar Tema</button>
								</div>
							</div>
						</div>
						<button type="button" data-information="<?= $pauta['id']; ?>"
							class="btn btn-warning btn-sm btn-cancelar btn-cancelar-<?= $pauta['id']; ?> <?= ($pauta['reservado'] == null) ? ('collapse') : (''); ?>">Cancelar
							Reserva</button>
						<div class="<?= ($pauta['reservado'] == null) ? ('collapse') : (''); ?>"
							id="div_tag_<?= $pauta['id']; ?>">
							<label class="badge badge-primary badge-<?= $pauta['id']; ?>"><?= $pauta['tag_fechamento']; ?></label>
						</div>
					</small>
				</div>

				</p>
			</div>

		<?php endforeach; ?>
		<div class="d-block mt-3">
			<?php if ($pautasList['pager']): ?>
				<?= $pautasList['pager']->simpleLinks('pautas', 'default_template') ?>
			<?php endif; ?>
		</div>
	</div>
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

	$('.descartar').on('click', function (e) {
		var id_pauta = e.target.getAttribute('data-information');

		form = new FormData();
		form.append('metodo', 'descartar');
		form.append('id', id_pauta);

		$.ajax({
			url: "<?php echo base_url('colaboradores/pautas/fechar'); ?>",
			method: "POST",
			data: form,
			processData: false,
			contentType: false,
			cache: false,
			dataType: "json",
			success: function (retorno) {
				if (retorno.status) {
					$('#pauta_' + id_pauta).toggle();
				}
			}
		});
	});

	$('.reservar').on('click', function (e) {
		var id_pauta = e.target.getAttribute('data-information');
		e.target.style.display = 'none';

		$('#div_reserva_' + id_pauta).show();
	});

	$('.btn-salvar').on('click', function (e) {
		var id_pauta = e.target.getAttribute('data-information');

		var nome_tag = $('#tema_' + id_pauta).val();

		if (nome_tag.trim() != '') {

			form = new FormData();
			form.append('metodo', 'reservar');
			form.append('id', id_pauta);
			form.append('tag', nome_tag);

			$.ajax({
				url: "<?php echo base_url('colaboradores/pautas/fechar'); ?>",
				method: "POST",
				data: form,
				processData: false,
				contentType: false,
				cache: false,
				dataType: "json",
				success: function (retorno) {

					$('.badge-' + id_pauta).html(nome_tag);
					$('#div_tag_' + id_pauta).collapse();
					$('#div_reserva_' + id_pauta).hide();
					$('.btn-cancelar-' + id_pauta).show();
				}
			});
		}
	});

	$('.btn-cancelar').on('click', function (e) {
		var id_pauta = e.target.getAttribute('data-information');


		form = new FormData();
		form.append('metodo', 'cancelar');
		form.append('id', id_pauta);

		$.ajax({
			url: "<?php echo base_url('colaboradores/pautas/fechar'); ?>",
			method: "POST",
			data: form,
			processData: false,
			contentType: false,
			cache: false,
			dataType: "json",
			success: function (retorno) {
				$('.badge-' + id_pauta).html('');
				$('#div_tag_' + id_pauta).collapse();
				$('#div_reserva_' + id_pauta).hide();
				$('#btn-reservar-' + id_pauta).toggle();
				$('.btn-cancelar-' + id_pauta).hide();
			}
		});
	});

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
</script>

<?= $this->endSection(); ?>