<?php

use CodeIgniter\I18n\Time;

?>
<?php foreach ($pautasList['pautas'] as $pauta): ?>

	<div class="col-lg-4 mb-4">
		<div class="card">
			<img src="<?= $pauta['imagem']; ?>" alt="" class="card-img-top">
			<div class="card-body">
				<h5 class="card-title fw-bold">
					<?php if ($pauta['pauta_antiga'] == 'S'): ?>
						<i class="bi bi-exclamation-circle-fill text-danger" style="font-size: 18px;"></i>
					<?php endif; ?>
					<?= $pauta['titulo']; ?>
				</h5>
				<div>
					<div class="text-end">
						<small><?= Time::createFromFormat('Y-m-d H:i:s', $pauta['criado'])->toLocalizedString('dd MMMM yyyy'); ?></small>
					</div>
					<div class="text-start">
						<small class="badge bg-primary m-1 p-1">Sugerido:
							<?= $pauta['apelido']; ?>
						</small>
					</div>
					<p class="card-text"><?= $pauta['texto']; ?></p>
					<a href="<?= $pauta['link']; ?>" target="_blank" class="btn btn-outline-success btn-sm mb-1">Ler
						Notícia</a>
					<a href="" data-bs-titulo="<?= $pauta['titulo']; ?>" data-bs-texto="<?= $pauta['texto']; ?>" data-bs-pautas-id="<?= $pauta['id']; ?>" data-bs-imagem="<?= $pauta['imagem']; ?>" class="btn btn-outline-info btn-sm mb-1"
						data-bs-toggle="modal" data-bs-target="#modalComentariosPauta">Comentários</a>
					<a href="<?= site_url('colaboradores/artigos/cadastrar?pauta=' . $pauta['id']); ?>"
						class="btn btn-outline-primary btn-sm mb-1">Escrever artigo</a>
					<?php if ($pauta['colaboradores_id'] == $_SESSION['colaboradores']['id']): ?>
						<a href="<?= site_url('colaboradores/pautas/cadastrar/' . $pauta['id']); ?>"
							data-bs-pautas-id="<?= $pauta['id']; ?>" data-bs-toggle="modal" data-bs-target="#modalSugerirPauta"
							data-bs-titulo-modal="Alterar a pauta" class="btn btn-warning btn-sm mb-1">Editar</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
<?php endforeach; ?>

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