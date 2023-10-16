<?php

use CodeIgniter\I18n\Time;

?>
<?php helper('data') ?>
<?php if ($pautasList['pautas'] !== NULL && !empty($pautasList['pautas'])): ?>
	<?php foreach ($pautasList['pautas'] as $pauta): ?>
		<div class="media text-muted pt-3 border-bottom row " id="pauta_<?= $pauta['id']; ?>">
			<div class="col-12">
				<image class="mr-2 rounded img-thumbnail float-left" for="btn-check-outlined"
					style="max-height: 120px; max-width:250px;" src="<?= $pauta['imagem']; ?>" />
				<p class="media-body pb-3 mb-0 small lh-125  border-gray">
					<strong class="d-block">
						<?= Time::createFromFormat('Y-m-d H:i:s', $pauta['criado'])->toLocalizedString('dd MMMM yyyy'); ?> - 
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
							<input type="text" id="tema_<?= $pauta['id']; ?>" class="form-control " placeholder="Tema da Pauta"
								aria-label="Tema da Pauta" aria-describedby="button-addon2">
							<div class="input-group-append">
								<button class="btn btn-outline-primary btn-salvar" type="button"
									data-information="<?= $pauta['id']; ?>">Salvar Tema</button>
							</div>
						</div>
					</div>
					<button type="button" data-information="<?= $pauta['id']; ?>"
						class="btn btn-warning btn-sm btn-cancelar btn-cancelar-<?= $pauta['id']; ?> <?= ($pauta['reservado'] == null) ? ('collapse') : (''); ?>">Cancelar
						Reserva</button>
					<div class="<?= ($pauta['reservado'] == null) ? ('collapse') : (''); ?>" id="div_tag_<?= $pauta['id']; ?>">
						<label class="badge badge-primary badge-<?= $pauta['id']; ?>">
							<?= $pauta['tag_fechamento']; ?>
						</label>
					</div>
				</small>
			</div>

			</p>
		</div>
	<?php endforeach; ?>
<?php endif; ?>


<div class="d-block mt-3">
	<?php if ($pautasList['pager']): ?>
		<?= $pautasList['pager']->simpleLinks('pautas', 'default_template') ?>
	<?php endif; ?>
</div>

<script>
	$(document).ready(function () {
		$('.page-link ').on('click', function (e) {
			e.preventDefault();
			$.ajax({
				url: e.target.href,
				type: 'get',
				dataType: 'html',
				beforeSend: function () { $('#modal-loading').modal('show'); },
				complete: function () { $('#modal-loading').modal('hide'); },
				success: function (data) {
					$('.pautas-list').html(data);
				}
			});
		});
	});

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
			beforeSend: function() { $('#modal-loading').modal('show'); },
			complete: function() { $('#modal-loading').modal('hide'); },
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
				beforeSend: function() { $('#modal-loading').modal('show'); },
				complete: function() { $('#modal-loading').modal('hide'); },
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
			beforeSend: function() { $('#modal-loading').modal('show'); },
			complete: function() { $('#modal-loading').modal('hide'); },
			success: function (retorno) {
				$('.badge-' + id_pauta).html('');
				$('#div_tag_' + id_pauta).collapse();
				$('#div_reserva_' + id_pauta).hide();
				$('#btn-reservar-' + id_pauta).toggle();
				$('.btn-cancelar-' + id_pauta).hide();
			}
		});
	});

</script>