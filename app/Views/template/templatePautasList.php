<?php

use CodeIgniter\I18n\Time;

?>
<?php if ($pautasList['pautas'] !== NULL && !empty($pautasList['pautas'])): ?>
	<?php foreach ($pautasList['pautas'] as $pauta): ?>
		<div class="media text-muted pt-3 border-bottom row " id="pauta_<?= $pauta['id']; ?>">
			<div class="col-12 row">
				<div class="col-2">
					<image class="mr-2 rounded img-thumbnail float-left" for="btn-check-outlined" style="max-width:inherit;"
						src="<?= $pauta['imagem']; ?>" />
				</div>
				<div class="col-10">
					<p class="media-body pb-3 mb-0 lh-125  border-gray">
						<?php if ($pauta['nome_pauta_fechada'] != NULL): ?>
							<strong class="text-danger d-block">
								Pauta fechada - <?= $pauta['nome_pauta_fechada']; ?>
							</strong>
						<?php endif; ?>
						<strong class="d-block">
							<?php if ($pauta['pauta_antiga'] == 'S'): ?>
								<i class="bi bi-exclamation-circle-fill text-danger" style="font-size: 18px;"></i>
							<?php endif; ?>
							<?= Time::createFromFormat('Y-m-d H:i:s', $pauta['criado'])->toLocalizedString('dd MMM yyyy'); ?> -
							<?= $pauta['titulo']; ?>
						</strong>
						<?= $pauta['texto']; ?><br/>
						<small class="badge bg-primary m-1 p-1">Sugerido:
							<?= $pauta['apelido']; ?>
						</small>
					<div class="d-flex">
						<a data-bs-toggle="modal" data-bs-target="#modalComentariosPauta" class="btn btn-outline-success m-1"
						data-bs-texto="<?= $pauta['texto']; ?>" data-bs-pautas-id="<?= $pauta['id']; ?>" data-bs-imagem="<?= $pauta['imagem']; ?>"
							href="<?= site_url('colaboradores/pautas/detalhe/' . $pauta['id']); ?>"
							target="_blank"><?= ($pauta['qtde_comentarios'] > 0) ? ($pauta['qtde_comentarios']) : ('Nenhum'); ?><?= ($pauta['qtde_comentarios'] > 1) ? (' comentários') : (' comentário'); ?></a>
						<a class="btn btn-outline-info m-1" href="<?= $pauta['link']; ?>" target="_blank">Ler notícia
							original</a>
					</div>
				</div>
			</div>
			<?php if ($pauta['nome_pauta_fechada'] == NULL): ?>
				<div class="col-12 row justify-content-between mb-3">
					<small class="col-2 mt-3 text-center">
						<button type="button" data-information="<?= $pauta['id']; ?>"
							class="btn btn-danger btn-sm descartar">Descartar</button>
					</small>
					<small class="col-6 col-md-4 mt-3 text-center">
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
						<div class="text-center <?= ($pauta['reservado'] == null) ? ('collapse') : (''); ?>" id="div_tag_<?= $pauta['id']; ?>">
							<label class="badge bg-primary badge-<?= $pauta['id']; ?>">
								<?= $pauta['tag_fechamento']; ?>
							</label>
						</div>
					</small>
				</div>
			<?php endif; ?>

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
				beforeSend: function () { $('#modal-loading').show(); },
				complete: function () { $('#modal-loading').hide() },
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
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide() },
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
				beforeSend: function () { $('#modal-loading').show(); },
				complete: function () { $('#modal-loading').hide() },
				success: function (retorno) {

					$('.badge-' + id_pauta).html(retorno.mensagem);
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
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide() },
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